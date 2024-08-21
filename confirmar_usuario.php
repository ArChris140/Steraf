<link rel="stylesheet" href="login.css">
<?php
include 'plantillas/cabecera.php';
include 'global/conexion.php';

// Verificar si se ha recibido un token en la URL
if (isset($_GET["token"])) {
    $token = $_GET["token"];

    // Consultar los datos del usuario usando el token recibido
    $sql_consulta_signup = $conexion->prepare("SELECT * FROM signup WHERE token = ?");
    $sql_consulta_signup->bind_param("s", $token);
    $sql_consulta_signup->execute();
    $resultado_consulta_signup = $sql_consulta_signup->get_result();

    if ($resultado_consulta_signup->num_rows > 0) {
        // Obtener los datos del registro de signup
        $registro = $resultado_consulta_signup->fetch_assoc();
        $correo = $registro['correo'];
        $nombre = $registro['nombre'];
        $contraseña = $registro['contraseña'];
        $rol_usuario = $registro['rol_usuario'];

        // Verificar si el usuario ya está registrado en la base de datos
        $sql_verificar_usuario = $conexion->prepare("SELECT * FROM usuarios WHERE username = ?");
        $sql_verificar_usuario->bind_param("s", $correo);
        $sql_verificar_usuario->execute();
        $resultado_verificar_usuario = $sql_verificar_usuario->get_result();

        if ($resultado_verificar_usuario->num_rows > 0) {
            echo "<br><br><div class='welcome'><h3>El usuario ya está registrado</h3></div><br><br>";
        } else {
            // Preparar la consulta SQL para insertar los datos en la tabla "usuarios"
            $sql_insertar_usuario = $conexion->prepare("INSERT INTO usuarios (username, password, rol_id) VALUES (?, ?, ?)");
            $sql_insertar_usuario->bind_param("sss", $correo, $contraseña, $rol_usuario);

            // Ejecutar la consulta
            if ($sql_insertar_usuario->execute() === TRUE) {
                // Ahora, insertar el nombre en la tabla informacion_usuario
                $sql_insertar_nombre = $conexion->prepare("INSERT INTO informacion_usuario (nombre) VALUES (?)");
                $sql_insertar_nombre->bind_param("s", $nombre);

                if ($sql_insertar_nombre->execute() === TRUE) {
                    echo "<br><br><div class='welcome'>Usuario registrado correctamente</h3></div><br><br>";
                    // Eliminar todos los registros de signup con el mismo correo electrónico
                    $sql_eliminar_signup = $conexion->prepare("DELETE FROM signup WHERE correo = ?");
                    $sql_eliminar_signup->bind_param("s", $correo);
                    $sql_eliminar_signup->execute();
                } else {
                    echo "<br><br><div class='welcome'>Error al registrar el usuario: </h3></div><br><br>" . $conexion->error;
                }
            } else {
                echo "<br><br><div class='welcome'>Error al registrar el usuario: </h3></div><br><br>" . $conexion->error;
            }
        }
    } else {
        echo "<br><br><div class='welcome'>Enlace inválido</h3></div><br><br>";
    }
} else {
    // No se ha recibido el token
    header('Location: signup.php');
}

$conexion->close();
?>

<?php include 'plantillas/pie.html'; ?>
