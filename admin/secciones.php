<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    header('Location: ../login.html');
    exit;
}
require_once '../config/conexion.php';

$secciones = $pdo->query("SELECT s.*, CONCAT(d.nombre,' ',d.apellido) AS docente_nombre 
                          FROM secciones s 
                          LEFT JOIN docentes d ON s.docente_id = d.id")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Secciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <?php include 'menu_admin.php'; ?>
    <div class="container mt-4">
        <h2>Secciones</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalSeccion">+ Nueva Sección</button>
        <table class="table table-bordered">
            <thead><tr><th>ID</th><th>Nombre</th><th>Año</th><th>Docente</th><th>Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($secciones as $s): ?>
                <tr>
                    <td><?= $s['id'] ?></td>
                    <td><?= $s['nombre'] ?></td>
                    <td><?= $s['anio'] ?></td>
                    <td><?= $s['docente_nombre'] ?? 'Sin asignar' ?></td>
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