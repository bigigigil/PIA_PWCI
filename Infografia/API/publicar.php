<?php
session_start();
header('Content-Type: application/json');
require_once('../database.php'); 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); echo json_encode(['error' => 'Método no permitido.']); exit();
}
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); echo json_encode(['error' => 'No autorizado.']); exit();
}

$user_id = $_SESSION['user_id'];
$database = new Database();
$db = $database->connect();

if (!$db) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión con la base de datos.']);
    exit();
}

// Recoger datos
$titulo = $_POST['titulo'] ?? '';
$contenido = $_POST['contenido'] ?? '';
$tipoContenido = $_POST['tipoContenido'] ?? '';
$idMundial = $_POST['idMundial'] ?? null;
$idCategoria = $_POST['idCategoria'] ?? null;
$idSeleccion = $_POST['idSeleccion'] ?? null;

if (empty($titulo) || empty($contenido) || empty($tipoContenido) || empty($idMundial) || empty($idCategoria) || empty($_FILES['archivoMultimedia']['tmp_name'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan campos obligatorios o el archivo multimedia es requerido.']);
    exit();
}

$archivo = file_get_contents($_FILES['archivoMultimedia']['tmp_name']);
$tipoArchivo = strtolower(pathinfo($_FILES['archivoMultimedia']['name'], PATHINFO_EXTENSION));
$archivoMultimedia = file_get_contents($_FILES['archivoMultimedia']['tmp_name']);

if (!in_array($tipoArchivo, ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Tipo de archivo no permitido.']);
    exit();
}
try {
    $stmt = $db->prepare("CALL sp_crear_publicacion(:tit, :cont, :tCont, :arch, :tArch, :uid, :idM, :idC, :idS)");
    
    $stmt->bindParam(':tit', $titulo);
    $stmt->bindParam(':cont', $contenido);
    $stmt->bindParam(':tCont', $tipoContenido);
    $stmt->bindParam(':arch', $archivo, PDO::PARAM_LOB);
    $stmt->bindParam(':tArch', $tipoArchivo);
    $stmt->bindParam(':uid', $user_id);
    $stmt->bindParam(':idM', $idMundial);
    $stmt->bindParam(':idC', $idCategoria);
    $stmt->bindValue(':idS', !empty($idSeleccion) ? $idSeleccion : null, PDO::PARAM_INT); 
    $stmt->execute();
    http_response_code(201);
    echo json_encode(['message' => 'Publicación enviada.']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error DB: ' . $e->getMessage()]);
}
?>
