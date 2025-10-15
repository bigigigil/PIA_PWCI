<?php
// API/session_status.php

session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    // Si hay una sesión activa, devuelve los datos del usuario
    echo json_encode([
        'loggedIn' => true,
        'username' => $_SESSION['username'],
        'rol' => $_SESSION['rol']
    ]);
} else {
    // Si no, indica que no hay nadie conectado
    echo json_encode(['loggedIn' => false]);
}
?>