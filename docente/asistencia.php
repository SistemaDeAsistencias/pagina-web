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
if (!$seccion_id) die("No tiene sección asignada.");

$estudiantes = $pdo->prepare("SELECT * FROM estudiantes WHERE seccion_id = ?");
$estudiantes->execute([$seccion_id]);
$estudiantes = $estudiantes->fetchAll();

$fecha = date('Y-m-d');
$check = $pdo->prepare("SELECT COUNT(*) FROM asistencias a JOIN estudiantes e ON a.estudiante_id = e.id WHERE e.seccion_id = ? AND a.fecha = ?");
$check->execute([$seccion_id, $fecha]);
$yaRegistrado = $check->fetchColumn() > 0;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registro de Asistencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/docente.css">
</head>
<body>
    <?php include 'menu_docente.php'; ?>
    <div class="container mt-4">
        <h3>Registro del día <?= $fecha ?></h3>
        <?php if ($yaRegistrado): ?>
            <div class="alert alert-warning">Ya se registró asistencia hoy.</div>
        <?php else: ?>
            <form action="../php/registrarAsistencia.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="fecha" value="<?= $fecha ?>">
                <table class="table table-bordered">
                    <thead><tr><th>Código</th><th>Estudiante</th><th>Estado</th><th>Justificación</th><th>Evidencia</th></tr></thead>
                    <tbody>
                    <?php foreach ($estudiantes as $e): ?>
                        <tr>
                            <td><?= $e['codigo'] ?></td>
                            <td><?= $e['nombre'].' '.$e['apellido'] ?></td>
                            <td>
                                <select name="estado[<?= $e['id'] ?>]" class="form-select">
                                    <option value="presente">Presente</option>
                                    <option value="ausente">Ausente</option>
                                    <option value="tardanza">Tardanza</option>
                                </select>
                            </td>
                            <td><input type="text" name="justificacion[<?= $e['id'] ?>]" class="form-control"></td>
                            <td><input type="file" name="evidencia[<?= $e['id'] ?>]" class="form-control"></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Guardar Asistencia</button>
            </form>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>