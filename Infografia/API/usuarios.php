<?php
header('Content-Type: application/json');
require_once('../database.php'); 

$database = new Database();
$db = $database->connect();
$method = $_SERVER['REQUEST_METHOD'];

// ---------------------------------------------------------
// 1. MÉTODO GET: Listar usuarios o buscar uno específico
// ---------------------------------------------------------
if ($method === 'GET') {
    $id = $_GET['id'] ?? null;
    try {
        if ($id) {
            // Obtener un solo usuario para editar
            $stmt = $db->prepare("CALL sp_obtener_usuario_por_id(:id)");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            // Listar todos
            $stmt = $db->prepare("CALL sp_obtener_usuarios_activos()");
            $stmt->execute();
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    } catch (PDOException $e) {
        http_response_code(500); echo json_encode(['error' => $e->getMessage()]);
    }
    exit();
}

// ---------------------------------------------------------
// 2. MÉTODO POST: Registrar nuevo o Actualizar existente
// ---------------------------------------------------------
if ($method === 'POST') {
    $action = $_GET['action'] ?? 'create'; // Por defecto crea (registro)

    // --- CASO A: ACTUALIZAR (Panel de Admin) ---
    if ($action === 'update') {
        $id = $_POST['id'] ?? null;
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $rol = $_POST['rol'] ?? 'Usuario';
        $estatus = $_POST['estatus'] ?? 'Activo';
        $password = $_POST['password'] ?? '';

        try {
            $passHash = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;
            
            $stmt = $db->prepare("CALL sp_editar_usuario_admin(:id, :nom, :email, :rol, :stat, :pass)");
            $stmt->execute([
                ':id' => $id, 
                ':nom' => $nombre, 
                ':email' => $email, 
                ':rol' => $rol, 
                ':stat' => $estatus, 
                ':pass' => $passHash
            ]);
            echo json_encode(['message' => 'Usuario actualizado correctamente.']);
        } catch (PDOException $e) {
            http_response_code(500); echo json_encode(['error' => 'Error al actualizar: ' . $e->getMessage()]);
        }
        exit();
    }

    // --- CASO B: REGISTRO NUEVO (Tu código original validado) ---
    
    // Variables del formulario de registro (nombres de inputs de register.html)
    $nombre = $_POST['fullname'] ?? '';
    $username = $_POST['username'] ?? ''; 
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $fechaNacimiento = $_POST['birthdate'] ?? '';
    $genero = $_POST['gender'] ?? 'O'; 
    $pais = $_POST['country'] ?? 'Desconocido';
    $nacionalidad = $_POST['nationality'] ?? 'Desconocida';

    // Validaciones
    if (empty($nombre) || empty($username) || empty($email) || empty($password) || empty($fechaNacimiento)) {
        http_response_code(400); echo json_encode(['error' => 'Faltan campos obligatorios.']); exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400); echo json_encode(['error' => 'Email inválido.']); exit();
    }
    
    // Validación Edad
    $fechaNac = new DateTime($fechaNacimiento);
    $hoy = new DateTime();
    $edad = $hoy->diff($fechaNac)->y;
    if ($edad < 13) {
        http_response_code(400); echo json_encode(['error' => 'Debes tener al menos 13 años.']); exit();
    }

    // Validación Contraseña
    $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/'; 
    if (!preg_match($regex, $password)) {
        http_response_code(400); echo json_encode(['error' => 'Contraseña débil (mín 8 caracteres, 1 mayúscula, 1 número).']); exit();
    }

    // Procesar Foto
    $foto_data = null; $tipo_imagen = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $foto_data = file_get_contents($_FILES['photo']['tmp_name']);
        $tipo_imagen = $_FILES['photo']['type'];
    }
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Verificar duplicados
        $check = $db->prepare("CALL sp_verificar_usuario_existe(:u, :e)");
        $check->execute([':u' => $username, ':e' => $email]);
        if ($check->rowCount() > 0) {
            http_response_code(409); echo json_encode(['error' => 'El usuario o email ya existe.']); exit();
        }
        $check->closeCursor();

        // Insertar
        $stmt = $db->prepare("CALL sp_registrar_usuario(:nom, :user, :nac, :gen, :pais, :nacio, :email, :pass, :foto, :timg)");
        $stmt->bindParam(':nom', $nombre);
        $stmt->bindParam(':user', $username);
        $stmt->bindParam(':nac', $fechaNacimiento);
        $stmt->bindParam(':gen', $genero);
        $stmt->bindParam(':pais', $pais);
        $stmt->bindParam(':nacio', $nacionalidad);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pass', $hashed_password);
        
        if ($foto_data) {
            $stmt->bindParam(':foto', $foto_data, PDO::PARAM_LOB);
            $stmt->bindParam(':timg', $tipo_imagen);
        } else {
            $stmt->bindValue(':foto', null, PDO::PARAM_NULL);
            $stmt->bindValue(':timg', null, PDO::PARAM_NULL);
        }

        $stmt->execute();
        http_response_code(201);
        echo json_encode(['message' => 'Registro exitoso.']);

    } catch (PDOException $e) {
        http_response_code(500); echo json_encode(['error' => 'Error DB: ' . $e->getMessage()]);
    }
    exit();
}

// ---------------------------------------------------------
// 3. MÉTODO DELETE: Eliminar usuario (Lógico)
// ---------------------------------------------------------
if ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if(!$id) {
        http_response_code(400); echo json_encode(['error' => 'Falta ID']); exit();
    }

    try {
        $stmt = $db->prepare("CALL sp_eliminar_usuario(:id)");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        echo json_encode(['message' => 'Usuario eliminado correctamente.']);
    } catch (PDOException $e) { 
        http_response_code(500); echo json_encode(['error' => $e->getMessage()]); 
    }
}
?>