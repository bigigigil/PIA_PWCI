<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

require_once('./../database.php'); 

$database = new Database();
$db = $database->connect();

if (!$db) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión con la base de datos.']);
    exit();
}

$search = $_GET['search'] ?? '';
$categoria = $_GET['categoria'] ?? '';
$mundial = $_GET['mundial'] ?? '';
$orden = $_GET['orden'] ?? 'fecha'; 

try {

    $query = "CALL sp_obtener_publicaciones_filtro(:search, :categoria, :mundial, :orden)";
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(':search', $search);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':mundial', $mundial);
    $stmt->bindParam(':orden', $orden);
    
    $stmt->execute();
    $publicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt->closeCursor(); 

    $commentStmt = $db->prepare("CALL sp_obtener_comentarios(:idPublicacion)");

    foreach ($publicaciones as &$post) {
        
        $commentStmt->bindParam(':idPublicacion', $post['idPublicacion'], PDO::PARAM_INT);
        $commentStmt->execute();
        $post['comentarios'] = $commentStmt->fetchAll(PDO::FETCH_ASSOC);
        $commentStmt->closeCursor(); 

        if (!empty($post['archivoMultimedia'])) {
            try {
                $mimeType = $post['tipoArchivo'] ?? 'image/jpeg';
                $blob_content = $post['archivoMultimedia'];
                
                $post['archivoMultimedia'] = base64_encode($blob_content);
                $post['tipoMime'] = $mimeType;
            } catch (Exception $e) {
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
    error_log("Error en API Publicaciones: " . $e->getMessage()); 
    echo json_encode(['error' => 'Error al procesar la solicitud.']);
}
?>