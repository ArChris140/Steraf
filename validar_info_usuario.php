<?php
// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $id = $_POST['id'];
    $nombre = $_POST['nuevo_nombre'];
    $apellido = isset($_POST['nuevo_apellido']) ? $_POST['nuevo_apellido'] : "";
    $telefono = $_POST['nuevo_telefono'];
    $region = $_POST['nueva_region'];
    $comuna = $_POST['nueva_comuna'];
    $calle = $_POST['nueva_calle'];
    $numero = $_POST['nuevo_numero'];
    $info_adicional = isset($_POST['nueva_informacion']) ? $_POST['nueva_informacion'] : "";

    // Validar campos obligatorios
    $valid = true;

    if (empty($nombre)) {
        echo "<div style = color:red;>El nombre no puede estar vacío</div><br>";
        $valid = false;
    }

    if (strlen($telefono) < 8) {
        echo "<div style = color:red;>El teléfono debe tener como mínimo 8 dígitos</div><br>";
        $valid = false;
    }

    if (empty($region)) {
        echo "<div style = color:red;>La región no puede estar vacía</div><br>";
        $valid = false;
    }

    if (empty($comuna)) {
        echo "<div style = color:red;>La comuna no puede estar vacía</div><br>";
        $valid = false;
    }

    if (empty($calle)) {
        echo "<div style = color:red;>La calle no puede estar vacía</div><br>";
        $valid = false;
    }

    if (empty($numero)) {
        echo "<div style = color:red;>El número de casa/departamento no puede estar vacío</div><br>";
        $valid = false;
    }

    // Si todas las validaciones pasan, ejecutar la consulta
    if ($valid) {
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
}
?>
