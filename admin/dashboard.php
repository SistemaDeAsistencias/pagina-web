<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    header('Location: ../login.html');
    exit;
}
require_once '../config/conexion.php';

$totalDocentes = $pdo->query("SELECT COUNT(*) FROM docentes")->fetchColumn();
$totalEstudiantes = $pdo->query("SELECT COUNT(*) FROM estudiantes")->fetchColumn();
$totalSecciones = $pdo->query("SELECT COUNT(*) FROM secciones")->fetchColumn();
$totalAsistenciasHoy = $pdo->query("SELECT COUNT(*) FROM asistencias WHERE fecha = CURDATE()")->fetchColumn();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Panel Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <?php include 'menu_admin.php'; ?>
    <div class="container mt-4">
        <h1>Dashboard</h1>
        <div class="row">
            <div class="col-md-3"><div class="card text-white bg-primary"><div class="card-body"><h5>Docentes</h5><h2><?= $totalDocentes ?></h2></div></div></div>
            <div class="col-md-3"><div class="card text-white bg-success"><div class="card-body"><h5>Estudiantes</h5><h2><?= $totalEstudiantes ?></h2></div></div></div>
            <div class="col-md-3"><div class="card text-white bg-warning"><div class="card-body"><h5>Secciones</h5><h2><?= $totalSecciones ?></h2></div></div></div>
            <div class="col-md-3"><div class="card text-white bg-info"><div class="card-body"><h5>Asistencias hoy</h5><h2><?= $totalAsistenciasHoy ?></h2></div></div></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>