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
if (!$seccion) die("Sin sección.");

$anio = $_GET['anio'] ?? date('Y');

$data = $pdo->prepare("SELECT e.codigo, e.nombre, e.apellido, 
                              SUM(a.estado='presente') AS presentes,
                              SUM(a.estado='ausente') AS ausentes,
                              SUM(a.estado='tardanza') AS tardanzas
                       FROM asistencias a JOIN estudiantes e ON a.estudiante_id = e.id
                       WHERE e.seccion_id = ? AND YEAR(a.fecha) = ?
                       GROUP BY e.id");
$data->execute([$seccion['id'], $anio]);
$data = $data->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reportes del Docente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/reportes.css">
</head>
<body>
    <?php include 'menu_docente.php'; ?>
    <div class="container mt-4">
        <h3>Reporte de asistencia - Sección <?= $seccion['nombre'] ?></h3>
        <form method="GET" class="row g-3 mb-3">
            <div class="col-auto"><input type="number" name="anio" value="<?= $anio ?>" placeholder="Año"></div>
            <div class="col-auto"><button type="submit" class="btn btn-primary">Filtrar</button></div>
        </form>

        <div id="reporteTabla">
            <table class="table table-bordered">
                <thead><tr><th>Código</th><th>Nombre</th><th>Presentes</th><th>Ausentes</th><th>Tardanzas</th><th>% Asistencia</th></tr></thead>
                <tbody>
                <?php foreach ($data as $d): 
                    $total = $d['presentes'] + $d['ausentes'] + $d['tardanzas'];
                    $porcentaje = $total > 0 ? round(($d['presentes'] / $total) * 100, 2) : 0;
                ?>
                    <tr>
                        <td><?= $d['codigo'] ?></td>
                        <td><?= $d['nombre'].' '.$d['apellido'] ?></td>
                        <td><?= $d['presentes'] ?></td>
                        <td><?= $d['ausentes'] ?></td>
                        <td><?= $d['tardanzas'] ?></td>
                        <td><?= $porcentaje ?>%</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <button class="btn btn-danger" onclick="exportarPDF()">Exportar PDF</button>
        <a href="../php/exportarCSV.php?anio=<?= $anio ?>&seccion_id=<?= $seccion['id'] ?>" class="btn btn-success">Exportar Excel (CSV)</a>
    </div>

    <script>
    function exportarPDF() {
        var contenido = document.getElementById('reporteTabla').innerHTML;
        var ventana = window.open('', '_blank');
        ventana.document.write('<html><head><title>Reporte PDF</title>');
        ventana.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
        ventana.document.write('<style>body { padding: 20px; } @media print { .no-print { display: none; } }</style>');
        ventana.document.write('</head><body>');
        ventana.document.write('<h3>Reporte de Asistencia - Sección <?= $seccion['nombre'] ?></h3>');
        ventana.document.write(contenido);
        ventana.document.write('</body></html>');
        ventana.document.close();
        ventana.print();
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>