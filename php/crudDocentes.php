<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    header('Location: ../login.html');
    exit;
}
require_once '../config/conexion.php';
// Operaciones CRUD para docentes (listar, agregar, editar, eliminar)
$accion = $_GET['accion'] ?? '';
switch ($accion) {
    case 'eliminar':
        $id = (int)$_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM docentes WHERE id = ?");
        $stmt->execute([$id]);
        break;
}
header('Location: ../admin/docentes.php');
?>