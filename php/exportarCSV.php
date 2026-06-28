<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.html');
    exit;
}
require_once '../config/conexion.php';

$anio = $_GET['anio'] ?? date('Y');
$seccion_id = (int)($_GET['seccion_id'] ?? 0);

$data = $pdo->prepare("SELECT e.codigo, e.nombre, e.apellido, 
                              SUM(a.estado='presente') AS presentes,
                              SUM(a.estado='ausente') AS ausentes,
                              SUM(a.estado='tardanza') AS tardanzas
                       FROM asistencias a JOIN estudiantes e ON a.estudiante_id = e.id
                       WHERE e.seccion_id = ? AND YEAR(a.fecha) = ?
                       GROUP BY e.id");
$data->execute([$seccion_id, $anio]);
$data = $data->fetchAll();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="reporte_asistencia.csv"');
$output = fopen('php://output', 'w');
fputcsv($output, ['Código', 'Nombre', 'Presentes', 'Ausentes', 'Tardanzas', '% Asistencia']);
foreach ($data as $row) {
    $total = $row['presentes'] + $row['ausentes'] + $row['tardanzas'];
    $porcentaje = $total > 0 ? round(($row['presentes'] / $total) * 100, 2) : 0;
    fputcsv($output, [$row['codigo'], $row['nombre'].' '.$row['apellido'], $row['presentes'], $row['ausentes'], $row['tardanzas'], $porcentaje.'%']);
}
fclose($output);
exit;
?>