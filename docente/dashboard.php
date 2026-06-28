<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 2) {
    header('Location: ../login.html');
    exit;
}
require_once '../config/conexion.php';

$stmt = $pdo->prepare("SELECT id, nombre FROM secciones WHERE docente_id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$seccion = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$seccion) die("No tiene sección asignada.");

$estudiantes = $pdo->prepare("SELECT * FROM estudiantes WHERE seccion_id = ?");
$estudiantes->execute([$seccion['id']]);
$estudiantes = $estudiantes->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Panel Docente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/docente.css">
</head>
<body>
    <?php include 'menu_docente.php'; ?>
    <div class="container mt-4">
        <h3>Bienvenido, sección <?= $seccion['nombre'] ?></h3>
        <h5>Lista de estudiantes</h5>
        <table class="table">
            <thead><tr><th>Código</th><th>Nombre</th></tr></thead>
            <tbody>
            <?php foreach ($estudiantes as $e): ?>
                <tr><td><?= $e['codigo'] ?></td><td><?= $e['nombre'].' '.$e['apellido'] ?></td></tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>