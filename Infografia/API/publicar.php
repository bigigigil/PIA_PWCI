<?php

session_start();
header('Content-Type: application/json');

require_once('../database.php'); 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido.']);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado. Debes iniciar sesión para publicar.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$database = new Database();
$db = $database->connect();

if (!$db) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión con la base de datos.']);
    exit();
}

$titulo = $_POST['titulo'] ?? '';
$contenido = $_POST['contenido'] ?? '';
$tipoContenido = $_POST['tipoContenido'] ?? '';
$idMundial = $_POST['idMundial'] ?? null;
$idCategoria = $_POST['idCategoria'] ?? null;
$idSeleccion = $_POST['idSeleccion'] ?? null; // Opcional

if (empty($titulo) || empty($contenido) || empty($tipoContenido) || empty($idMundial) || empty($idCategoria) || empty($_FILES['archivoMultimedia']['tmp_name'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan campos obligatorios o el archivo multimedia es requerido.']);
    exit();
}

$archivoMultimedia = file_get_contents($_FILES['archivoMultimedia']['tmp_name']);
$tipoArchivo = strtolower(pathinfo($_FILES['archivoMultimedia']['name'], PATHINFO_EXTENSION));

if (!in_array($tipoArchivo, ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Tipo de archivo no permitido.']);
    exit();
}

try {
    $query = "INSERT INTO Publicacion 
              (titulo, contenido, tipoContenido, archivoMultimedia, tipoArchivo, idUsuario, idMundial, idCategoria, idSeleccion, estado) 
              VALUES 
              (:titulo, :contenido, :tipoContenido, :archivoMultimedia, :tipoArchivo, :idUsuario, :idMundial, :idCategoria, :idSeleccion, 'pendiente')";
    
    $stmt = $db->prepare($query);

    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':contenido', $contenido);
    $stmt->bindParam(':tipoContenido', $tipoContenido);
    $stmt->bindParam(':archivoMultimedia', $archivoMultimedia, PDO::PARAM_LOB); // Usar PDO::PARAM_LOB para datos LONGBLOB
    $stmt->bindParam(':tipoArchivo', $tipoArchivo);
    $stmt->bindParam(':idUsuario', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':idMundial', $idMundial, PDO::PARAM_INT);
    $stmt->bindParam(':idCategoria', $idCategoria, PDO::PARAM_INT);
    
    if (empty($idSeleccion)) {
        $stmt->bindValue(':idSeleccion', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindParam(':idSeleccion', $idSeleccion, PDO::PARAM_INT);
    }
    
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['message' => 'Publicación enviada para aprobación.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al guardar la publicación.']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    error_log("Error al publicar: " . $e->getMessage());
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>