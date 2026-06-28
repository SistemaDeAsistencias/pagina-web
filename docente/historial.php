<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 2) {
    header('Location: ../login.html');
    exit;
}
require_once '../config/conexion.php';

$stmt = $pdo->prepare("SELECT id FROM secciones WHERE docente_id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$seccion_id = $stmt->fetchColumn();

$mes = $_GET['mes'] ?? date('m');
$anio = $_GET['anio'] ?? date('Y');

$historial = $pdo->prepare("SELECT e.nombre, e.apellido, e.codigo, a.fecha, a.estado, a.justificacion 
                            FROM asistencias a 
                            JOIN estudiantes e ON a.estudiante_id = e.id 
                            WHERE e.seccion_id = ? AND MONTH(a.fecha) = ? AND YEAR(a.fecha) = ?
                            ORDER BY e.nombre, a.fecha");
$historial->execute([$seccion_id, $mes, $anio]);
$historial = $historial->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Historial de Asistencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/docente.css">
</head>
<body>
    <?php include 'menu_docente.php'; ?>
    <div class="container mt-4">
        <h3>Historial de asistencias</h3>
        <form method="GET" class="row g-3 mb-3">
            <div class="col-auto"><input type="number" name="mes" value="<?= $mes ?>" placeholder="Mes (1-12)"></div>
            <div class="col-auto"><input type="number" name="anio" value="<?= $anio ?>" placeholder="Año"></div>
            <div class="col-auto"><button type="submit" class="btn btn-primary">Filtrar</button></div>
        </form>
        <table class="table table-bordered">
            <thead><tr><th>Código</th><th>Nombre</th><th>Fecha</th><th>Estado</th><th>Justificación</th></tr></thead>
            <tbody>
            <?php foreach ($historial as $h): ?>
                <tr>
                    <td><?= $h['codigo'] ?></td>
                    <td><?= $h['nombre'].' '.$h['apellido'] ?></td>
                    <td><?= $h['fecha'] ?></td>
                    <td><?= ucfirst($h['estado']) ?></td>
                    <td><?= $h['justificacion'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>