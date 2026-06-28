// Funciones para reportes (ya integradas en los archivos PHP)
// Esta función se usa en admin/reportes.php y docente/reportes.php
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