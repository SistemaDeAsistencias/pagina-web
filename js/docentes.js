// Validar que al menos se seleccione un estado para cada estudiante
document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('form[action="../php/registrarAsistencia.php"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            var selects = form.querySelectorAll('select[name^="estado"]');
            var todosSeleccionados = true;
            selects.forEach(function(select) {
                if (!select.value) {
                    todosSeleccionados = false;
                    select.style.borderColor = 'red';
                } else {
                    select.style.borderColor = '';
                }
            });
            if (!todosSeleccionados) {
                e.preventDefault();
                alert('Seleccione un estado para todos los estudiantes.');
            }
        });
    }
});