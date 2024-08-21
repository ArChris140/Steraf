<?php
include 'global/conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["username"];
    $password = $_POST["password"];
    $confirmar_password = $_POST["confirmar_password"];

    // Verificar si la contraseña cumple con los requisitos
    if (mb_strlen($password) < 8 || !preg_match("/[a-zA-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
        echo "<p style='color:red;'>La contraseña debe tener al menos 8 caracteres, contener letras y números</p>";
        // No continúes con el proceso de registro
        exit;
    }

    // Verificar si las contraseñas coinciden
    if ($password !== $confirmar_password) {
        echo "<p style='color:red;'>Las contraseñas no coinciden</p>";
        exit;
    }

    // Consultar la fecha de modificación de la contraseña en la base de datos
    $stmt = $conexion->prepare("SELECT fecha_modificacion FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->bind_result($fecha_modificacion);
    $stmt->fetch();
    $stmt->close();

    // Calcular la diferencia en segundos entre la fecha de modificación y la fecha y hora actuales
    $fecha_modificacion_timestamp = strtotime($fecha_modificacion);
    $fecha_actual_timestamp = time();
    $diferencia_segundos = $fecha_actual_timestamp - $fecha_modificacion_timestamp;

    // Verificar si han pasado al menos 24 horas (86400 segundos) desde la última modificación de contraseña
    if ($diferencia_segundos < 86400) {
        echo "<p style='color:red;'>Debes esperar al menos 24 horas antes de poder cambiar la contraseña nuevamente</p>";
        exit;
    }

    // Hash de la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Actualizar la contraseña en la base de datos
    $stmt = $conexion->prepare("UPDATE usuarios SET password = ?, fecha_modificacion = NOW() WHERE username = ?");
    $stmt->bind_param("ss", $hashed_password, $correo);
    if ($stmt->execute()) {
        // Eliminar el token de recuperación de la base de datos
        $stmt_eliminar_token = $conexion->prepare("DELETE FROM token_usuarios WHERE correo = ?");
        $stmt_eliminar_token->bind_param("s", $correo);
        $stmt_eliminar_token->execute();
        $stmt_eliminar_token->close();

        unset($_SESSION['token']);
        echo "<p style='color:green;'>Contraseña modificada con éxito</p>";

    } else {
        header('Location: forgot_password.php');
    }
} else {
    // Redireccionar si se intenta acceder directamente al archivo
    header('Location: forgot_password.php');
}
?>
