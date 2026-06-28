<?php
// Funciones auxiliares
function obtenerNombreRol($rol_id) {
    switch ($rol_id) {
        case 1: return 'Administrador';
        case 2: return 'Docente';
        case 3: return 'Estudiante';
        default: return 'Desconocido';
    }
}
?>