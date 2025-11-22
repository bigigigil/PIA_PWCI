<?php
header('Content-Type: application/json; charset=utf-8');
require_once('../database.php');

$database = new Database();
$db = $database->connect();

if (!$db) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión']);
    exit();
}

try {
 
    $queryCat = "SELECT c.nombre, COUNT(p.idPublicacion) as total 
                 FROM Categoria c 
                 LEFT JOIN Publicacion p ON c.id = p.idCategoria AND p.estado = 'aprobada'
                 GROUP BY c.id, c.nombre
                 ORDER BY c.nombre ASC";
    $stmtCat = $db->prepare($queryCat);
    $stmtCat->execute();
    $categorias = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

    $queryMun = "SELECT año, nombre FROM Mundial ORDER BY año DESC";
    $stmtMun = $db->prepare($queryMun);
    $stmtMun->execute();
    $mundiales = $stmtMun->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'categorias' => $categorias,
        'mundiales' => $mundiales
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error SQL: ' . $e->getMessage()]);
}
?>