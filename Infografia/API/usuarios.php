<?php

header('Content-Type: application/json');
require_once('../Database.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    require_once('../Database.php');
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
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Método no permitido.']);
    exit();
}


$data = json_decode(file_get_contents("php://input"));

if (empty($data->fullname) || empty($data->email) || empty($data->password) || empty($data->birthdate)) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan campos obligatorios.']);
    exit();
}


$nombre = $data->fullname;
$email = $data->email;
$password = $data->password;
$fechaNacimiento = $data->birthdate;

$genero = $data->gender ?? 'O'; 
$paisNacimiento = $data->country ?? 'Desconocido';
$nacionalidad = $data->nationality ?? 'Desconocida';

$regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/'; 

if (!preg_match($regex, $password)) {
    http_response_code(400);
    echo json_encode(['error' => 'La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número.']);
    exit();
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$database = new Database();
$db = $database->connect();

if (!$db) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de servidor: No se pudo conectar a la base de datos.']);
    exit();
}

try {

    $stmt_check = $db->prepare("SELECT id FROM Usuario WHERE email = :email");
    $stmt_check->bindParam(':email', $email);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        http_response_code(409); // Conflicto
        echo json_encode(['error' => 'El email ya está registrado.']);
        exit();
    }

    $query = "INSERT INTO Usuario (nombre, fechaNacimiento, genero, paisNacimiento, nacionalidad, email, password, rol) 
              VALUES (:nombre, :fechaNacimiento, :genero, :paisNacimiento, :nacionalidad, :email, :password, 'Usuario')";
    
    $stmt = $db->prepare($query);

    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':fechaNacimiento', $fechaNacimiento);
    $stmt->bindParam(':genero', $genero);
    $stmt->bindParam(':paisNacimiento', $paisNacimiento);
    $stmt->bindParam(':nacionalidad', $nacionalidad);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

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