<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
    header('Location: ../login.html');
    exit;
}
require_once '../config/conexion.php';

$stmt = $pdo->prepare("SELECT * FROM estudiantes WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$estudiante = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$estudiante) die("Estudiante no encontrado.");

$mes = $_GET['mes'] ?? date('m');
$anio = $_GET['anio'] ?? date('Y');

$asistencias = $pdo->prepare("SELECT fecha, estado FROM asistencias WHERE estudiante_id = ? AND MONTH(fecha) = ? AND YEAR(fecha) = ?");
$asistencias->execute([$estudiante['id'], $mes, $anio]);
$asistencias = $asistencias->fetchAll();

$presentes = $ausentes = $tardanzas = 0;
foreach ($asistencias as $a) {
    if ($a['estado'] == 'presente') $presentes++;
    elseif ($a['estado'] == 'ausente') $ausentes++;
    elseif ($a['estado'] == 'tardanza') $tardanzas++;
}
$totalDias = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
$porcentaje = $totalDias > 0 ? round(($presentes / $totalDias) * 100, 2) : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mi Asistencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estudiantes.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <span class="navbar-brand">Estudiante - Asistencia</span>
        <ul class="nav">
            <li class="nav-item"><a class="nav-link text-white" href="consulta.php">Consulta</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../logout.html">Cerrar sesión</a></li>
        </ul>
    </nav>
    <div class="container mt-4">
        <h3>Consulta de Asistencia</h3>
        <p>Estudiante: <?= $estudiante['nombre'].' '.$estudiante['apellido'] ?> (<?= $estudiante['codigo'] ?>)</p>
        <form method="GET" class="row g-3 mb-3">
            <div class="col-auto"><input type="number" name="mes" value="<?= $mes ?>" placeholder="Mes (1-12)"></div>
            <div class="col-auto"><input type="number" name="anio" value="<?= $anio ?>" placeholder="Año"></div>
            <div class="col-auto"><button type="submit" class="btn btn-primary">Filtrar</button></div>
        </form>
        <div class="row">
            <div class="col-md-3"><div class="card bg-success text-white"><div class="card-body"><h5>Presentes</h5><h2><?= $presentes ?></h2></div></div></div>
            <div class="col-md-3"><div class="card bg-danger text-white"><div class="card-body"><h5>Ausentes</h5><h2><?= $ausentes ?></h2></div></div></div>
            <div class="col-md-3"><div class="card bg-warning text-white"><div class="card-body"><h5>Tardanzas</h5><h2><?= $tardanzas ?></h2></div></div></div>
            <div class="col-md-3"><div class="card bg-info text-white"><div class="card-body"><h5>% Asistencia</h5><h2><?= $porcentaje ?>%</h2></div></div></div>
        </div>
        <table class="table table-bordered mt-3">
            <thead><tr><th>Fecha</th><th>Estado</th></tr></thead>
            <tbody>
            <?php foreach ($asistencias as $a): ?>
                <tr><td><?= $a['fecha'] ?></td><td><?= ucfirst($a['estado']) ?></td></tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>