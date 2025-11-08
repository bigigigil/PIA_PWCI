<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

require_once('./../database.php'); 


$database = new Database();
$db = $database->connect();
if (!$db) {
    error_log("Error de conexión con la base de datos: La clase Database no pudo conectar.");
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión con la base de datos.']);
    exit();
}

$search = $_GET['search'] ?? '';
$categoria = $_GET['categoria'] ?? '';
$mundial = $_GET['mundial'] ?? '';
$orden = $_GET['orden'] ?? 'fecha'; 

$conditions = ["p.estado = 'aprobada'"];
$joins = [];
$groupBy = "";

if (!empty($search)) {
    $conditions[] = "(p.titulo LIKE :search OR u.username LIKE :search OR m.sedePrincipal LIKE :search)";
}
if (!empty($categoria)) {
    $conditions[] = "c.nombre = :categoria";
    if (!in_array("LEFT JOIN Categoria c ON p.idCategoria = c.id", $joins)) $joins[] = "LEFT JOIN Categoria c ON p.idCategoria = c.id";
}
if (!empty($mundial)) {
    $conditions[] = "m.año = :mundial";
}

$whereClause = "WHERE " . implode(' AND ', $conditions);
$joinClause = implode(' ', $joins);

$orderBy = "p.fechaCreacion DESC";
switch ($orden) {
    case 'likes':
        $orderBy = "total_likes DESC, p.fechaCreacion DESC";
        break;
    case 'comentarios':
        $orderBy = "total_comentarios DESC, p.fechaCreacion DESC";
        break;
    case 'vistas':
        $orderBy = "p.vistas DESC, p.fechaCreacion DESC";
        break;
    case 'cronologico_mundial':
        $orderBy = "m.año ASC, p.fechaCreacion DESC";
        break;
    case 'fecha':
    default:
        $orderBy = "p.fechaCreacion DESC";
        break;
}

$query = "SELECT 
            p.idPublicacion, p.titulo, p.contenido, p.tipoContenido, p.fechaCreacion, p.vistas, 
            p.tipoArchivo, p.archivoMultimedia, 
            u.username, u.nombre AS autorNombre, 
            m.nombre AS mundialNombre, m.año AS mundialAnio, m.sedePrincipal,
            (SELECT COUNT(idCalificacion) FROM Calificacion WHERE idPublicacion = p.idPublicacion AND valor = 1) AS total_likes,
            (SELECT COUNT(idComentario) FROM Comentario WHERE idPublicacion = p.idPublicacion) AS total_comentarios
          FROM Publicacion p
          LEFT JOIN Usuario u ON p.idUsuario = u.id
          LEFT JOIN Mundial m ON p.idMundial = m.id
          {$joinClause}
          {$whereClause}
          ORDER BY {$orderBy}";

try {
    $stmt = $db->prepare($query);

    if (!empty($search)) {
        $searchTerm = '%' . $search . '%';
        $stmt->bindParam(':search', $searchTerm);
    }
    if (!empty($categoria)) {
        $stmt->bindParam(':categoria', $categoria);
    }
    if (!empty($mundial)) {
        $stmt->bindParam(':mundial', $mundial);
    }

    $stmt->execute();
    $publicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($publicaciones as &$post) {
    if (!empty($post['archivoMultimedia'])) {
        try {
            $mimeType = $post['tipoArchivo'] ?? 'image/jpeg';

            $blob_content = $post['archivoMultimedia'];

            $post['archivoMultimedia'] = base64_encode($blob_content);
            $post['tipoMime'] = $mimeType;
        } catch (Exception $e) {
            error_log("Fallo al codificar BLOB para publicación #" . $post['idPublicacion'] . ": " . $e->getMessage());
            $post['archivoMultimedia'] = null;
        }
    } else {
        $post['archivoMultimedia'] = null;
    }

    unset($post['tipoArchivo']);
}


    http_response_code(200);
    echo json_encode($publicaciones);

} catch (PDOException $e) {
    http_response_code(500);
    error_log("Error de consulta final en Publicaciones.php: " . $e->getMessage()); 
    echo json_encode(['error' => 'Error de consulta en la base de datos: ' . $e->getMessage()]);
}
?>