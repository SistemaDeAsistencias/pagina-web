<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    header('Location: ../login.html');
    exit;
}
require_once '../config/conexion.php';

$email = $_POST['email'] ?? '';
$password = md5($_POST['password'] ?? '');
$rol_id = (int)($_POST['rol_id'] ?? 0);

if ($email && $password && $rol_id) {
    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (email, password, rol_id) VALUES (?, ?, ?)");
        $stmt->execute([$email, $password, $rol_id]);
        // Si es administrador o docente, se puede redirigir a completar perfil
        header('Location: ../admin/usuarios.php');
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: ../admin/usuarios.php');
}
?>