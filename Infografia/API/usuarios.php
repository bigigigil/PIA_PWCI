<?php

header('Content-Type: application/json');

require_once('../database.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $database = new Database();
    $db = $database->connect();
    
    if (!$db) {
        http_response_code(500);
        echo json_encode(['error' => 'Error de servidor.']);
        exit();
    }
    
    try {
        $query = "SELECT id, nombre, email, fechaRegistro, estatus, rol FROM Usuario WHERE estatus = 'Activo' ORDER BY fechaRegistro DESC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode($usuarios);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error de consulta: ' . $e->getMessage()]);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); 
    echo json_encode(['error' => 'Método no permitido.']);
    exit();
}

$nombre = $_POST['fullname'] ?? '';
$username = $_POST['username'] ?? ''; 
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$fechaNacimiento = $_POST['birthdate'] ?? '';

$genero = $_POST['gender'] ?? 'O'; 
$paisNacimiento = $_POST['country'] ?? 'Desconocido';
$nacionalidad = $_POST['nationality'] ?? 'Desconocida';

if (empty($nombre) || empty($username) || empty($email) || empty($password) || empty($fechaNacimiento)) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan campos obligatorios.']);
    exit();
}

$regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/'; 
if (!preg_match($regex, $password)) {
    http_response_code(400);
    echo json_encode(['error' => 'La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número.']);
    exit();
}
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$foto_data = null;
$tipo_imagen = null;

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $foto_data = file_get_contents($_FILES['photo']['tmp_name']);
    $tipo_imagen = $_FILES['photo']['type']; 
}

$database = new Database();
$db = $database->connect();

if (!$db) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de servidor: No se pudo conectar a la base de datos.']);
    exit();
}

try {
    
    $stmt_check = $db->prepare("SELECT id FROM Usuario WHERE email = :email OR username = :username");
    $stmt_check->bindParam(':email', $email);
    $stmt_check->bindParam(':username', $username);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        http_response_code(409); 
        echo json_encode(['error' => 'El email o el nombre de usuario ya está registrado.']);
        exit();
    }

    $query = "INSERT INTO Usuario 
              (nombre, username, fechaNacimiento, genero, paisNacimiento, nacionalidad, email, password, rol, foto, tipoImagen) 
              VALUES (:nombre, :username, :fechaNacimiento, :genero, :paisNacimiento, :nacionalidad, :email, :password, 'Usuario', :foto, :tipoImagen)";
    
    $stmt = $db->prepare($query);

    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':fechaNacimiento', $fechaNacimiento);
    $stmt->bindParam(':genero', $genero);
    $stmt->bindParam(':paisNacimiento', $paisNacimiento);
    $stmt->bindParam(':nacionalidad', $nacionalidad);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    if ($foto_data) {
        $stmt->bindParam(':foto', $foto_data, PDO::PARAM_LOB);
        $stmt->bindParam(':tipoImagen', $tipo_imagen);
    } else {
 
        $stmt->bindValue(':foto', null, PDO::PARAM_NULL);
        $stmt->bindValue(':tipoImagen', null, PDO::PARAM_NULL);
    }

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['message' => 'Usuario registrado exitosamente.', 'user_id' => $db->lastInsertId()]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al registrar el usuario.']);
    }

} catch (PDOException $e) {

    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>