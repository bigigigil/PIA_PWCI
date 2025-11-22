<?php
session_start();
header('Content-Type: application/json');
require_once('./../database.php');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autenticado.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$database = new Database();
$db = $database->connect();

if (!$db) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión DB.']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $stmt = $db->prepare("CALL sp_obtener_perfil_usuario(:id)");
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            
            if ($user['foto'] && $user['tipoImagen']) {
                $user['photo'] = 'data:' . $user['tipoImagen'] . ';base64,' . base64_encode($user['foto']);
                unset($user['foto']); 
            }
            unset($user['tipoImagen']);

            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Usuario no encontrado.']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error DB: ' . $e->getMessage()]);
    }
    exit(); 
}

if ($method === 'POST') {

    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $genero = isset($_POST['genero']) ? trim($_POST['genero']) : '';
    $pais = isset($_POST['pais']) ? trim($_POST['pais']) : '';
    $nacionalidad = isset($_POST['nacionalidad']) ? trim($_POST['nacionalidad']) : '';

    if (empty($nombre) || empty($genero) || empty($pais) || empty($nacionalidad)) { 
        http_response_code(400);
        echo json_encode(['error' => 'Todos los campos (Nombre, Género, País, Nacionalidad) son obligatorios.']);
        exit();
    }

    $foto_data = null; 
    $tipo_imagen = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $foto_data = file_get_contents($_FILES['photo']['tmp_name']);
        $tipo_imagen = $_FILES['photo']['type'];
    }

    try {
       
        $stmt = $db->prepare("CALL sp_actualizar_mi_perfil(:id, :nom, :gen, :pais, :nac, :foto, :timg)");
        
        $stmt->bindParam(':id', $user_id);
        $stmt->bindParam(':nom', $nombre);
        $stmt->bindParam(':gen', $genero);
        $stmt->bindParam(':pais', $pais);
        $stmt->bindParam(':nac', $nacionalidad);
       
        if ($foto_data) {
            $stmt->bindParam(':foto', $foto_data, PDO::PARAM_LOB);
            $stmt->bindParam(':timg', $tipo_imagen);
        } else {
            $stmt->bindValue(':foto', null, PDO::PARAM_NULL);
            $stmt->bindValue(':timg', null, PDO::PARAM_NULL);
        }

        $stmt->execute();
        echo json_encode(['message' => 'Perfil actualizado correctamente.']);
        
    } catch (Exception $e) {
        http_response_code(500); 
        echo json_encode(['error' => 'Error al actualizar: ' . $e->getMessage()]);
    }
    exit();
}

http_response_code(405);
echo json_encode(['error' => 'Método no permitido.']);
?>