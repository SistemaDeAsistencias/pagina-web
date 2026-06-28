<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    header('Location: ../login.html');
    exit;
}
require_once '../config/conexion.php';

$anio = $_GET['anio'] ?? date('Y');
$seccion_id = (int)($_GET['seccion_id'] ?? 0);

$secciones = $pdo->query("SELECT id, nombre FROM secciones")->fetchAll();

$where = "WHERE YEAR(a.fecha) = $anio";
if ($seccion_id > 0) $where .= " AND e.seccion_id = $seccion_id";

$data = $pdo->query("SELECT e.codigo, e.nombre, e.apellido, s.nombre AS seccion,
                            SUM(a.estado='presente') AS presentes,
                            SUM(a.estado='ausente') AS ausentes,
                            SUM(a.estado='tardanza') AS tardanzas
                     FROM asistencias a 
                     JOIN estudiantes e ON a.estudiante_id = e.id 
                     JOIN secciones s ON e.seccion_id = s.id 
                     $where 
                     GROUP BY e.id")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reportes Administrativos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/reportes.css">
</head>
<body>
    <?php include 'menu_admin.php'; ?>
    <div class="container mt-4">
        <h3>Reportes de Asistencia</h3>
        <form method="GET" class="row g-3 mb-3">
            <div class="col-auto"><input type="number" name="anio" value="<?= $anio ?>" placeholder="Año"></div>
            <div class="col-auto">
                <select name="seccion_id" class="form-control">
                    <option value="0">Todas las secciones</option>
                    <?php foreach ($secciones as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $seccion_id == $s['id'] ? 'selected' : '' ?>><?= $s['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-primary">Filtrar</button></div>
        </form>

        <div id="reporteTabla">
            <table class="table table-bordered">
                <thead><tr><th>Código</th><th>Nombre</th><th>Sección</th><th>Presentes</th><th>Ausentes</th><th>Tardanzas</th><th>% Asistencia</th></tr></thead>
                <tbody>
                <?php foreach ($data as $d): 
                    $total = $d['presentes'] + $d['ausentes'] + $d['tardanzas'];
                    $porcentaje = $total > 0 ? round(($d['presentes'] / $total) * 100, 2) : 0;
                ?>
                    <tr>
                        <td><?= $d['codigo'] ?></td>
                        <td><?= $d['nombre'].' '.$d['apellido'] ?></td>
                        <td><?= $d['seccion'] ?></td>
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
        <a href="../php/exportarCSV.php?anio=<?= $anio ?>&seccion_id=<?= $seccion_id ?>" class="btn btn-success">Exportar Excel (CSV)</a>
    </div>

    <script>
    function exportarPDF() {
        var contenido = document.getElementById('reporteTabla').innerHTML;
        var ventana = window.open('', '_blank');
        ventana.document.write('<html><head><title>Reporte PDF</title>');
        ventana.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
        ventana.document.write('<style>body { padding: 20px; } @media print { .no-print { display: none; } }</style>');
        ventana.document.write('</head><body>');
        ventana.document.write('<h3>Reporte de Asistencia</h3>');
        ventana.document.write(contenido);
        ventana.document.write('</body></html>');
        ventana.document.close();
        ventana.print();
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>