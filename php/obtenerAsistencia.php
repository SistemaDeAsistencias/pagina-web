<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No autorizado']);
    exit;
}
require_once '../config/conexion.php';

$estudiante_id = $_GET['estudiante_id'] ?? 0;
$mes = $_GET['mes'] ?? date('m');
$anio = $_GET['anio'] ?? date('Y');

if ($_SESSION['rol_id'] == 3) {
    // Estudiante solo ve sus propias asistencias
    $stmt = $pdo->prepare("SELECT id FROM estudiantes WHERE id = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    if (!$stmt->fetch()) {
        echo json_encode(['error' => 'Acceso denegado']);
        exit;
    }
    $estudiante_id = $_SESSION['usuario_id'];
}

if (!$estudiante_id) {
    echo json_encode(['error' => 'Falta ID de estudiante']);
    exit;
}

$stmt = $pdo->prepare("SELECT fecha, estado, justificacion, evidencia 
                       FROM asistencias 
                       WHERE estudiante_id = ? AND MONTH(fecha) = ? AND YEAR(fecha) = ?
                       ORDER BY fecha");
$stmt->execute([$estudiante_id, $mes, $anio]);
$asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($asistencias);
?>