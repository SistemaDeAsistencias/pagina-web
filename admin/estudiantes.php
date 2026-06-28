<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    header('Location: ../login.html');
    exit;
}
require_once '../config/conexion.php';

$estudiantes = $pdo->query("SELECT e.*, s.nombre AS seccion_nombre 
                            FROM estudiantes e 
                            JOIN secciones s ON e.seccion_id = s.id")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Estudiantes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <?php include 'menu_admin.php'; ?>
    <div class="container mt-4">
        <h2>Estudiantes</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalEstudiante">+ Nuevo Estudiante</button>
        <table class="table table-bordered">
            <thead><tr><th>Código</th><th>Nombre</th><th>Apellido</th><th>Sección</th><th>Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($estudiantes as $e): ?>
                <tr>
                    <td><?= $e['codigo'] ?></td>
                    <td><?= $e['nombre'] ?></td>
                    <td><?= $e['apellido'] ?></td>
                    <td><?= $e['seccion_nombre'] ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning">Editar</button>
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>