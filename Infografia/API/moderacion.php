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
    echo json_encode(['error' => 'Acceso denegado. Solo administradores.']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'GET' && $action === 'pending') {
    try {
     
        $query = "SELECT 
                    p.idPublicacion, p.titulo, p.fechaCreacion, p.tipoContenido,
                    u.username AS autor, m.nombre AS mundial, c.nombre AS categoria,
                    p.idMundial, p.idCategoria, p.idUsuario 
                  FROM Publicacion p
                  LEFT JOIN Usuario u ON p.idUsuario = u.id
                  LEFT JOIN Mundial m ON p.idMundial = m.id
                  LEFT JOIN Categoria c ON p.idCategoria = c.id
                  WHERE p.estado = 'pendiente'
                  ORDER BY p.fechaCreacion ASC";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $publicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode($publicaciones);
    } catch (PDOException $e) {
        http_response_code(500);
        error_log("Error al obtener pendientes: " . $e->getMessage());
        echo json_encode(['error' => 'Error de base de datos al cargar pendientes.']);
    }
    exit();
}

if ($method === 'POST' && ($action === 'approve' || $action === 'reject')) {
    $data = json_decode(file_get_contents("php://input"), true);
    $idPublicacion = $data['idPublicacion'] ?? null;
    $motivoRechazo = $data['motivo'] ?? null;

    if (!$idPublicacion) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de publicación faltante.']);
        exit();
    }
    
    $estado_nuevo = ($action === 'approve') ? 'aprobada' : 'rechazada';
    
    try {
        $query = "UPDATE Publicacion SET 
                    estado = :estado, 
                    fechaAprobacion = NOW(), 
                    idUsuarioAprobador = :idUsuarioAprobador,
                    motivoRechazo = :motivoRechazo
                  WHERE idPublicacion = :idPublicacion";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':estado', $estado_nuevo);
        $stmt->bindParam(':idUsuarioAprobador', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':idPublicacion', $idPublicacion, PDO::PARAM_INT);
        
        if ($action === 'reject') {
             $stmt->bindParam(':motivoRechazo', $motivoRechazo);
        } else {
             $stmt->bindValue(':motivoRechazo', null, PDO::PARAM_NULL);
        }

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(['message' => 'Publicación marcada como ' . $estado_nuevo . '.']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Publicación no encontrada o ya procesada.']);
        }

    } catch (PDOException $e) {
        http_response_code(500);
        error_log("Error al moderar publicación: " . $e->getMessage());
        echo json_encode(['error' => 'Error de base de datos durante la moderación.']);
    }
    exit();
}

http_response_code(400);
echo json_encode(['error' => 'Solicitud inválida.']);
?>