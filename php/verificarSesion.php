<?php
session_start();
header('Content-Type: application/json');

$response = ['loggedIn' => false];
if (isset($_SESSION['usuario_id'])) {
    $response['loggedIn'] = true;
    $response['rol_id'] = $_SESSION['rol_id'];
}
echo json_encode($response);
?>