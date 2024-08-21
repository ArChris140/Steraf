<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar datos del formulario
    $producto_id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $imagen = $_POST['imagen'];
    $precio = $_POST['precio'];
    $cantidad_ingresada = $_POST['cantidad'];
    $stock_disponible = $_POST['cantidad_producto'];

    // Validar que la cantidad ingresada no sea mayor al stock disponible ni menor que 0
    if ($cantidad_ingresada > $stock_disponible) {
        echo "La cantidad ingresada es mayor al stock disponible";
    } elseif ($cantidad_ingresada < 1) {
        echo "La cantidad ingresada no puede ser menor que 1";
    } else {
        unset($_SESSION['pagar_ahora']);
        $_SESSION['pagar_ahora'] = [
            'producto_id' => $producto_id,
            'nombre' => $nombre,
            'imagen' => $imagen,
            'precio' => $precio,
            'cantidad' => $cantidad_ingresada
        ];
        echo "Pagar producto";
    }
} else {
    echo "Solicitud no válida";
}
?>
