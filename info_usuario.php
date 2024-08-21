<?php
// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $id = $_POST['id'];
    $nombre = isset($_POST['nuevo_nombre']) ? $_POST['nuevo_nombre'] : "";
    $apellido = isset($_POST['nuevo_apellido']) ? $_POST['nuevo_apellido'] : "";
    $telefono = isset($_POST['nuevo_telefono']) ? $_POST['nuevo_telefono'] : "";
    $region = isset($_POST['nueva_region']) ? $_POST['nueva_region'] : "";
    $comuna = isset($_POST['nueva_comuna']) ? $_POST['nueva_comuna'] : "";
    $calle = isset($_POST['nueva_calle']) ? $_POST['nueva_calle'] : "";
    $numero = isset($_POST['nuevo_numero']) ? $_POST['nuevo_numero'] : "";
    $info_adicional = isset($_POST['nueva_informacion']) ? $_POST['nueva_informacion'] : "";

    // Conectar a la base de datos
    include 'global/conexion.php';

    // Preparar la consulta SQL con placeholders
    $sql = "UPDATE informacion_usuario 
    SET nombre = ?, apellido = ?, telefono = ?, region = ?, comuna = ?, calle = ?, numero = ?, info_adicional = ?
    WHERE id_usuario = ?";

    // Preparar el statement
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
    die("Error en la preparación del statement: " . $conexion->error);
    }

    // Vincular los parámetros
    $stmt->bind_param("ssssssssi", $nombre, $apellido, $telefono, $region, $comuna, $calle, $numero, $info_adicional, $id);

    // Ejecutar la consulta
    if ($stmt->execute()) {
    echo "Información actualizada correctamente.";
    } else {
    echo "Error al actualizar la información: " . $stmt->error;
    }

    // Cerrar el statement y la conexión
    $stmt->close();
    $conexion->close();
}
?>
