<?php
session_start();
header('Content-Type: application/json');
require_once('../database.php');

if (!isset($_SESSION['user_id']) || ($_SESSION['rol'] ?? '') !== 'Admin') {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido.']);
    exit();
}

$database = new Database();
$db = $database->connect();

if (!$db) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de base de datos.']);
    exit();
}

try {

    if ($action === 'categoria') {
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';

        if (empty($nombre)) throw new Exception("El nombre es obligatorio.");

        $stmt = $db->prepare("INSERT INTO Categoria (nombre, descripcion, idUsuarioCreador) VALUES (:nombre, :desc, :uid)");
        $stmt->execute([':nombre' => $nombre, ':desc' => $descripcion, ':uid' => $user_id]);
        
        echo json_encode(['message' => 'Categoría creada con éxito.']);

    } elseif ($action === 'mundial') {
        $nombre = $_POST['nombre'] ?? '';
        $anio = $_POST['anio'] ?? '';
        $sede = $_POST['sede'] ?? '';

        if (empty($nombre) || empty($anio) || empty($sede)) throw new Exception("Todos los campos del Mundial son obligatorios.");

        $stmt = $db->prepare("INSERT INTO Mundial (nombre, año, sedePrincipal, idUsuarioCreador) VALUES (:nombre, :anio, :sede, :uid)");
        $stmt->execute([':nombre' => $nombre, ':anio' => $anio, ':sede' => $sede, ':uid' => $user_id]);

        echo json_encode(['message' => 'Mundial registrado con éxito.']);

    } elseif ($action === 'seleccion') {
        $nombre = $_POST['nombre'] ?? '';
        $pais = $_POST['pais'] ?? '';

        if (empty($nombre) || empty($pais)) throw new Exception("Nombre y País son obligatorios.");

        $stmt = $db->prepare("INSERT INTO Seleccion (nombre, pais) VALUES (:nombre, :pais)");
        $stmt->execute([':nombre' => $nombre, ':pais' => $pais]);

        echo json_encode(['message' => 'Selección creada con éxito.']);

    } else {
        throw new Exception("Acción no válida.");
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error SQL: ' . $e->getMessage()]);
}
?>