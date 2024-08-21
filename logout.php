<?php
session_start();

// Verificar si la variable de sesión 'rol' está definida
if(isset($_SESSION['rol'])) {
    // Eliminar solo la variable de sesión 'rol'
    unset($_SESSION['rol']);
    // Redireccionar a la página de inicio
    header('location: index.php');
} else {
    // Si la variable de sesión 'rol' no está definida, simplemente redirigir a la página de inicio
    header('location: index.php');
}
?>
