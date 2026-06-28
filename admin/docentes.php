<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    header('Location: ../login.html');
    exit;
}
require_once '../config/conexion.php';

$docentes = $pdo->query("SELECT d.*, u.email FROM docentes d JOIN usuarios u ON d.id = u.id")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Docentes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <?php include 'menu_admin.php'; ?>
    <div class="container mt-4">
        <h2>Docentes</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalDocente">+ Nuevo Docente</button>
        <table class="table table-bordered">
            <thead><tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Especialidad</th><th>Email</th><th>Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($docentes as $d): ?>
                <tr>
                    <td><?= $d['id'] ?></td>
                    <td><?= $d['nombre'] ?></td>
                    <td><?= $d['apellido'] ?></td>
                    <td><?= $d['especialidad'] ?></td>
                    <td><?= $d['email'] ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning">Editar</button>
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Aquí el modal para agregar docente (similar) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>