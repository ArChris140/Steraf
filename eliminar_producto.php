<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    include 'global/conexion.php';

    // Obtener la información del producto antes de eliminarlo
    $stmt = $conexion->prepare("SELECT imagen FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imagen);
    $stmt->fetch();
    $stmt->close();

    // Eliminar el producto de la tabla detalle_compras
    $stmt = $conexion->prepare("DELETE FROM detalle_compras WHERE producto_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Eliminar el producto de la tabla productos
    $stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Intentar eliminar la imagen del servidor
        if (file_exists("img/" . $imagen)) {
            unlink("img/" . $imagen);
        }
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    $conexion->close();
}
?>
