<?php
session_start();
header('Content-Type: application/json');
require_once('./../database.php');

$database = new Database();
$db = $database->connect();
if (!$db) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión con la base de datos.']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$user_id = $_SESSION['user_id'] ?? null;
$user_rol = $_SESSION['rol'] ?? 'Invitado';

if (!$user_id && ($method === 'POST' || $method === 'DELETE')) {
    http_response_code(401);
    echo json_encode(['error' => 'Debes iniciar sesión para interactuar.']);
    exit();
}

if ($method === 'POST' && ($_GET['action'] ?? '') === 'comment') {
    $data = json_decode(file_get_contents("php://input"), true);
    $idPublicacion = $data['idPublicacion'] ?? null;
    $comentario = $data['comentario'] ?? null;

    if (!$idPublicacion || empty($comentario)) {
        http_response_code(400);
        echo json_encode(['error' => 'Faltan campos obligatorios para el comentario.']);
        exit();
    }

    try {
        $query = "INSERT INTO Comentario (idPublicacion, idUsuario, comentario) VALUES (:idPublicacion, :idUsuario, :comentario)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':idPublicacion', $idPublicacion);
        $stmt->bindParam(':idUsuario', $user_id);
        $stmt->bindParam(':comentario', $comentario);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(['message' => 'Comentario publicado.']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al publicar comentario: ' . $e->getMessage()]);
    }
}

if ($method === 'POST' && ($_GET['action'] ?? '') === 'rate') {
    $data = json_decode(file_get_contents("php://input"), true);
    $idPublicacion = $data['idPublicacion'] ?? null;
    $valor = $data['valor'] ?? 1; 
    if (!$idPublicacion || !in_array($valor, [1, -1])) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos de calificación inválidos.']);
        exit();
    }
    
    try {
        $query = "INSERT INTO Calificacion (idPublicacion, idUsuario, valor) 
                  VALUES (:idPublicacion, :idUsuario, :valor) 
                  ON DUPLICATE KEY UPDATE valor = VALUES(valor)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':idPublicacion', $idPublicacion);
        $stmt->bindParam(':idUsuario', $user_id);
        $stmt->bindParam(':valor', $valor);
        $stmt->execute();

        http_response_code(201);
        echo json_encode(['message' => 'Calificación guardada.']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al calificar: ' . $e->getMessage()]);
    }
}

if ($method === 'DELETE' && ($_GET['action'] ?? '') === 'comment') {
    $idComentario = $_GET['idComentario'] ?? null;
    
    if ($user_rol !== 'Admin' || !$idComentario) {
        http_response_code(403);
        echo json_encode(['error' => 'Acceso denegado o ID no proporcionado.']);
        exit();
    }

    try {
        $query = "DELETE FROM Comentario WHERE idComentario = :idComentario";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':idComentario', $idComentario);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(['message' => 'Comentario eliminado.']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Comentario no encontrado.']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al eliminar comentario: ' . $e->getMessage()]);
    }
}
?>