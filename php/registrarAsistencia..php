<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 2) {
    header('Location: ../login.html');
    exit;
}
require_once '../config/conexion.php';

$fecha = $_POST['fecha'] ?? date('Y-m-d');
foreach ($_POST['estado'] as $estudiante_id => $estado) {
    $justificacion = $_POST['justificacion'][$estudiante_id] ?? '';
    $rutaEvidencia = null;
    if (isset($_FILES['evidencia']['name'][$estudiante_id]) && $_FILES['evidencia']['error'][$estudiante_id] === 0) {
        $nombre = time() . '_' . $estudiante_id . '.jpg';
        move_uploaded_file($_FILES['evidencia']['tmp_name'][$estudiante_id], '../uploads/evidencias/' . $nombre);
        $rutaEvidencia = 'uploads/evidencias/' . $nombre;
    }
    $stmt = $pdo->prepare("INSERT INTO asistencias (estudiante_id, fecha, estado, justificacion, evidencia) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$estudiante_id, $fecha, $estado, $justificacion, $rutaEvidencia]);
}
header('Location: ../docente/asistencia.php');
?>