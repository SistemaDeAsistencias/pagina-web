$(document).ready(function() {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        var email = $('#email').val().trim();
        var password = $('#password').val().trim();
        if (!email || !password) {
            mostrarError('Todos los campos son obligatorios.');
            return;
        }
        $.ajax({
            url: 'php/login.php',
            type: 'POST',
            data: { email: email, password: password },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (response.rol_id === 1) window.location.href = 'admin/dashboard.php';
                    else if (response.rol_id === 2) window.location.href = 'docente/dashboard.php';
                    else if (response.rol_id === 3) window.location.href = 'estudiante/consulta.php';
                    else window.location.href = 'index.html';
                } else {
                    mostrarError(response.message || 'Error al iniciar sesión.');
                }
            },
            error: function() {
                mostrarError('Error de conexión con el servidor.');
            }
        });
    });

    function mostrarError(mensaje) {
        $('#mensajeError').removeClass('d-none').text(mensaje);
    }
});