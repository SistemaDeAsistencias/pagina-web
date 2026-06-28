// Funciones para gestión de asistencia (ej. confirmar eliminación)
function confirmarEliminacion(id) {
    if (confirm('¿Eliminar este registro de asistencia?')) {
        window.location.href = '../php/eliminarAsistencia.php?id=' + id;
    }
}