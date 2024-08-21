<?php
include 'global/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_producto = $_POST['id'];
    $nombre_producto = $_POST['nombre_editar_producto'];
    $precio_producto = $_POST['precio_editar_producto'];
    $cantidad_producto = $_POST['cantidad_editar_producto'];
    $descripcion_producto = $_POST['descripcion_editar_producto'];
    $disponibilidad_producto = $_POST['disponibilidad_editar_producto'];

    // Obtener el nombre de la imagen actual
    $sql_select = "SELECT imagen FROM productos WHERE id = ?";
    $stmt_select = $conexion->prepare($sql_select);
    $stmt_select->bind_param("i", $id_producto);
    $stmt_select->execute();
    $stmt_select->bind_result($imagen_actual);
    $stmt_select->fetch();
    $stmt_select->close();

    // Inicializar variables
    $update_image = false;
    $image_name = "";

    // Verificar si se subió una nueva imagen
    if (!empty($_FILES["imagen_editar_producto"]["name"])) {
        $target_dir = "img/";
        $image_name = basename($_FILES["imagen_editar_producto"]["name"]);
        $target_file = $target_dir . $image_name;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verificar si el archivo es una imagen real o una imagen falsa
        $check = getimagesize($_FILES["imagen_editar_producto"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "El archivo no es una imagen.<br>";
            $uploadOk = 0;
        }

        // Verificar el tamaño del archivo
        if ($_FILES["imagen_editar_producto"]["size"] > 5000000) { // 5MB
            echo "Lo siento, tu archivo (imagen) es demasiado grande.<br>";
            $uploadOk = 0;
        }

        // Permitir solo ciertos formatos de archivo
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "webp") {
            echo "Lo siento, solo se permiten archivos (imagenes) JPG, JPEG, PNG y WEBP.<br>";
            $uploadOk = 0;
        }

        // Si todo está bien, intenta subir el archivo
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["imagen_editar_producto"]["tmp_name"], $target_file)) {
                $update_image = true;

                // Eliminar la imagen antigua si la nueva imagen se subió correctamente
                if ($imagen_actual && file_exists($target_dir . $imagen_actual)) {
                    unlink($target_dir . $imagen_actual);
                }
            } else {
                echo "Lo siento, hubo un error al subir tu archivo.<br>";
            }
        }
    }

    // Construir la consulta SQL de actualización
    $sql = "UPDATE productos SET nombre = ?, precio = ?, cantidad = ?, descripcion = ?, disponible = ?";
    if ($update_image) {
        $sql .= ", imagen = ?";
    }
    $sql .= " WHERE id = ?";

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);
    if ($update_image) {
        $stmt->bind_param("sdisssi", $nombre_producto, $precio_producto, $cantidad_producto, $descripcion_producto, $disponibilidad_producto, $image_name, $id_producto);
    } else {
        $stmt->bind_param("sdissi", $nombre_producto, $precio_producto, $cantidad_producto, $descripcion_producto, $disponibilidad_producto, $id_producto);
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Los datos se han actualizado correctamente en la tabla productos.<br>";
    } else {
        echo "Error al actualizar datos: " . $stmt->error . "<br>";
    }

    // Cerrar la conexión
    $stmt->close();
    $conexion->close();
}
?>
