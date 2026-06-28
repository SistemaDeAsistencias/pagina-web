<?php
session_start();
require_once '../config/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = md5($_POST['password'] ?? '');

    $stmt = $pdo->prepare("SELECT id, rol_id FROM usuarios WHERE email = ? AND password = ? AND activo = 1");
    $stmt->execute([$email, $password]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['rol_id'] = $usuario['rol_id'];
        echo json_encode(['success' => true, 'rol_id' => $usuario['rol_id']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas o usuario inactivo.']);
    }
}
?>