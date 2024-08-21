<?php
include 'global/conexion.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre_producto = $_POST['nombre_producto'];
    $precio_producto = $_POST['precio_producto'];
    $cantidad_producto = $_POST['cantidad_producto'];
    $descripcion_producto = $_POST['descripcion_producto'];
    $disponibilidad_producto = $_POST['disponibilidad_producto'];

    // Subida de la imagen
    $target_dir = "img/";
    $image_name = basename($_FILES["imagen_producto"]["name"]);
    $target_file = $target_dir . $image_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar si el archivo es una imagen real o una imagen falsa
    $check = getimagesize($_FILES["imagen_producto"]["tmp_name"]);
    if ($check !== false) {
        //echo "El archivo es una imagen - " . $check["mime"] . ".<br>";
        $uploadOk = 1;
    } else {
        echo "El archivo no es una imagen.<br>";
        $uploadOk = 0;
    }

    // Verificar si el archivo ya existe
    if (file_exists($target_file)) {
        echo "Lo siento, el archivo (imagen) ya existe.<br>";
        $uploadOk = 0;
    }

    // Verificar el tamaño del archivo
    if ($_FILES["imagen_producto"]["size"] > 5000000) { // 5MB
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
        if (move_uploaded_file($_FILES["imagen_producto"]["tmp_name"], $target_file)) {
            //echo "El archivo ". htmlspecialchars($image_name) . " ha sido subido correctamente.<br>";

            // Insertar datos en la tabla productos usando prepared statements
            $sql = "INSERT INTO productos (nombre, precio, cantidad, descripcion, disponible, imagen) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sdisss", $nombre_producto, $precio_producto, $cantidad_producto, $descripcion_producto, $disponibilidad_producto, $image_name);

            if ($stmt->execute()) {
                echo "Los datos se han insertado correctamente en la tabla productos.<br>";
            } else {
                echo "Error al insertar datos: " . $stmt->error . "<br>";
            }
            $stmt->close();
        } else {
            echo "Lo siento, hubo un error al subir tu archivo.<br>";
        }
    }

    // Cerrar la conexión
    $conexion->close();
}
?>
