function eliminarUsuario(id) {
    if (confirm('¿Está seguro de eliminar este usuario?')) {
        window.location.href = '../php/crudUsuarios.php?accion=eliminar&id=' + id;
    }
}

function editarUsuario(id) {
    // Implementar edición vía modal o redirección
    alert('Funcionalidad de edición en desarrollo.');
}