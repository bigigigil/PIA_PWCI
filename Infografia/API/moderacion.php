<?php
session_start();
header('Content-Type: application/json');
require_once('../database.php'); 

$database = new Database();
$db = $database->connect();
$user_id = $_SESSION['user_id'] ?? null;
$user_rol = $_SESSION['rol'] ?? 'Usuario';

if ($user_rol !== 'Admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso denegado.']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'GET' && $action === 'pending') {
    try {
        $stmt = $db->prepare("CALL sp_obtener_publicaciones_pendientes()");
        $stmt->execute();
        $publicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($publicaciones);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error DB: ' . $e->getMessage()]);
    }
    exit();
}

// *** LÓGICA CORREGIDA PARA 'VIEW' ***
if ($method === 'GET' && $action === 'view') {
    $idPublicacion = $_GET['id'] ?? null;

    if (!$idPublicacion) {
        http_response_code(400);
        echo json_encode(['error' => 'Falta ID de publicación.']);
        exit();
    }
    try {
        
        $query = "CALL sp_obtener_publicacion_pendiente_por_id(:idPub)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':idPub', $idPublicacion, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC); // Obtener el resultado
        $stmt->closeCursor(); // Limpiar el cursor

        if ($post) {
            // 1. Procesar Multimedia (Base64 encoding)
            if (!empty($post['archivoMultimedia'])) {
                // Codificar el BLOB a Base64
                $post['archivoMultimedia'] = base64_encode($post['archivoMultimedia']);
                // Asignar tipo MIME (tipoArchivo se carga desde el SP)
                $post['tipoMime'] = $post['tipoArchivo'] ?? 'image/jpeg'; 
            }
            unset($post['tipoArchivo']);

            // 2. Enviar respuesta de éxito
            http_response_code(200);
            echo json_encode($post);
        } else {
            // 3. Manejar publicación no encontrada
            http_response_code(404);
            echo json_encode(['error' => 'Publicación pendiente no encontrada o ya moderada.']);
        }

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error DB: ' . $e->getMessage()]);
    }
    exit();
}


if ($method === 'POST' && ($action === 'approve' || $action === 'reject')) {
    $data = json_decode(file_get_contents("php://input"), true);
    $idPublicacion = $data['idPublicacion'] ?? null;
    $motivo = $data['motivo'] ?? null; // Solo para rechazo

    if (!$idPublicacion) {
        http_response_code(400);
        echo json_encode(['error' => 'Falta ID.']);
        exit();
    }
    
    $estado_nuevo = ($action === 'approve') ? 'aprobada' : 'rechazada';
    
    try {
        $stmt = $db->prepare("CALL sp_moderar_publicacion(:idPub, :estado, :idAdmin, :motivo)");
        $stmt->bindParam(':idPub', $idPublicacion);
        $stmt->bindParam(':estado', $estado_nuevo);
        $stmt->bindParam(':idAdmin', $user_id);
        $stmt->bindValue(':motivo', ($action === 'reject' ? $motivo : null), PDO::PARAM_STR);

        $stmt->execute();
        echo json_encode(['message' => 'Publicación marcada como ' . $estado_nuevo]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error DB: ' . $e->getMessage()]);
    }
    exit();
}
?>