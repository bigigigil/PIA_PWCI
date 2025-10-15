<?php

session_start(); // Inicia la sesión para poder acceder a ella
session_unset(); // Libera todas las variables de sesión
session_destroy(); // Destruye toda la información registrada de una sesión

header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'message' => 'Sesión cerrada']);
?>