<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    header('Location: ../login.html');
    exit;
}
require_once '../config/conexion.php';

$usuarios = $pdo->query("SELECT u.id, u.email, r.nombre AS rol, u.activo 
                         FROM usuarios u JOIN roles r ON u.rol_id = r.id")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <?php include 'menu_admin.php'; ?>
    <div class="container mt-4">
        <h2>Usuarios</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalUsuario">+ Nuevo</button>
        <table class="table table-bordered">
            <thead><tr><th>ID</th><th>Email</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= $u['email'] ?></td>
                    <td><?= $u['rol'] ?></td>
                    <td><?= $u['activo'] ? 'Activo' : 'Inactivo' ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editarUsuario(<?= $u['id'] ?>)">Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarUsuario(<?= $u['id'] ?>)">Eliminar</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Nuevo Usuario -->
    <div class="modal fade" id="modalUsuario" tabindex="-1">
        <div class="modal-dialog"><div class="modal-content">
            <div class="modal-header"><h5>Nuevo Usuario</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form action="../php/crearUsuario.php" method="POST">
                    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                    <div class="mb-3"><label>Contraseña</label><input type="password" name="password" class="form-control" required></div>
                    <div class="mb-3"><label>Rol</label>
                        <select name="rol_id" class="form-control">
                            <option value="1">Administrador</option>
                            <option value="2">Docente</option>
                            <option value="3">Estudiante</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear</button>
                </form>
            </div>
        </div></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/admin.js"></script>
</body>
</html>