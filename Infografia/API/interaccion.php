<?php
session_start();
header('Content-Type: application/json');
require_once('./../database.php');

$db = (new Database())->connect();
$user_id = $_SESSION['user_id'] ?? null;
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if (!$user_id && ($method === 'POST' || $method === 'DELETE')) {
    http_response_code(401); echo json_encode(['error' => 'No logueado.']); exit();
}

try {

    if ($method === 'POST' && $action === 'comment') {
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $db->prepare("CALL sp_crear_comentario(:idPub, :idUser, :com)");
        $stmt->execute([
            ':idPub' => $data['idPublicacion'], 
            ':idUser' => $user_id, 
            ':com' => $data['comentario']
        ]);
        echo json_encode(['message' => 'Comentario guardado.']);
    }

    elseif ($method === 'POST' && $action === 'rate') {
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $db->prepare("CALL sp_calificar_publicacion(:idPub, :idUser, :val)");
        $stmt->execute([
            ':idPub' => $data['idPublicacion'], 
            ':idUser' => $user_id, 
            ':val' => $data['valor']
        ]);
        echo json_encode(['message' => 'Calificación guardada.']);
    }

    elseif ($method === 'DELETE' && $action === 'comment') {
        if (($_SESSION['rol'] ?? '') !== 'Admin') {
            http_response_code(403); echo json_encode(['error' => 'No autorizado.']); exit();
        }
        $stmt = $db->prepare("CALL sp_eliminar_comentario(:id)");
        $stmt->execute([':id' => $_GET['idComentario']]);
        echo json_encode(['message' => 'Eliminado.']);
    }

} catch (PDOException $e) {
    http_response_code(500); echo json_encode(['error' => 'Error DB: ' . $e->getMessage()]);
}
?>