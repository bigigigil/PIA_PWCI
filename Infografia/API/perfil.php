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
    echo json_encode(['error' => 'Error de conexión con la base de datos.']);
    exit();
}

try {
    $query = "SELECT nombre, username, email, fechaNacimiento, genero, paisNacimiento, nacionalidad, fechaRegistro, rol, foto, tipoImagen 
              FROM Usuario 
              WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        $perfil_data = [
            'nombre' => $user['nombre'],
            'username' => $user['username'],
            'email' => $user['email'],
            'fechaNacimiento' => $user['fechaNacimiento'],
            'genero' => $user['genero'],
            'paisNacimiento' => $user['paisNacimiento'],
            'nacionalidad' => $user['nacionalidad'],
            'fechaRegistro' => $user['fechaRegistro'],
            'rol' => $user['rol']
        ];
        
        if ($user['foto'] && $user['tipoImagen']) {
            $perfil_data['photo'] = 'data:' . $user['tipoImagen'] . ';base64,' . base64_encode($user['foto']);
        }

        http_response_code(200);
        echo json_encode($perfil_data);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Usuario no encontrado.']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>