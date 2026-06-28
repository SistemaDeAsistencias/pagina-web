<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 2) {
    header('Location: ../login.html');
    exit;
}
require_once '../config/conexion.php';

// Obtener la sección del docente
$stmt = $pdo->prepare("SELECT id, nombre FROM secciones WHERE docente_id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$seccion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$seccion) {
    die("<div class='alert alert-danger'>No tiene una sección asignada. Contacte al administrador.</div>");
}

// Obtener estudiantes de esa sección
$estudiantes = $pdo->prepare("SELECT * FROM estudiantes WHERE seccion_id = ? ORDER BY apellido, nombre");
$estudiantes->execute([$seccion['id']]);
$estudiantes = $estudiantes->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes - Docente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/docente.css">
</head>
<body>
    <?php include 'menu_docente.php'; ?>

    <div class="container mt-4">
        <h3>Lista de Estudiantes - Sección <?= htmlspecialchars($seccion['nombre']) ?></h3>
        <p>Cantidad de estudiantes: <?= count($estudiantes) ?></p>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($estudiantes) > 0): ?>
                        <?php foreach ($estudiantes as $e): ?>
                            <tr>
                                <td><?= htmlspecialchars($e['codigo']) ?></td>
                                <td><?= htmlspecialchars($e['nombre']) ?></td>
                                <td><?= htmlspecialchars($e['apellido']) ?></td>
                                <td>
                                    <!-- Puedes agregar más acciones si lo deseas, por ejemplo ver historial individual -->
                                    <a href="historial_individual.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-info">Ver historial</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">No hay estudiantes registrados en esta sección.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>