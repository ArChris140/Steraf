<?php
include 'global/conexion.php';
include 'plantillas/cabecera.php';

?>
<link rel="stylesheet" href="login.css">
<?php
// Verificar si se ha recibido el token en la sesión
if (isset($_SESSION['token'])) {
    $token = $_SESSION['token'];

    // Consultar la base de datos para verificar si el token es válido y no ha expirado
    $sql_verificar_token = "SELECT correo, fecha_expiracion FROM token_usuarios WHERE token = ?";
    $stmt = $conexion->prepare($sql_verificar_token);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resultado_verificar_token = $stmt->get_result();

    if ($resultado_verificar_token->num_rows > 0) {
        // Obtener la fila de resultados
        $fila = $resultado_verificar_token->fetch_assoc();
        $correo = $fila['correo'];
        $token_expiracion = $fila['fecha_expiracion'];

        // Verificar si el token ha expirado
        date_default_timezone_set('America/Santiago');
        $fecha_actual = date('Y-m-d H:i:s');
        if ($fecha_actual > $token_expiracion) {
            // El token ha expirado
            echo "<br><br><h1 class='welcome'>El enlace ha expirado</h1><br><br>";
        } else {
            // El token es válido y no ha expirado, mostrar el formulario para restablecer la contraseña
?>
            <div class="container">
                <div class="welcome"><h1>Restablece tu contraseña</h1></div>
                <div class="form-login">
                    <form class="form" id="formulario-contraseña"><br><br>
                        <p id="resultado"></p>
                        <input type="hidden" id="username" name="username" value=<?php echo ("$correo"); ?>>
                        <div class="field">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path>
                            </svg>
                            <input placeholder="Contraseña" class="input-field" type="password" id="password" name="password" required>
                        </div>
                        <div class="field">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path>
                            </svg>
                            <input placeholder="Confirmar contraseña" class="input-field" type="password" id="confirmar_password" name="confirmar_password" required>
                        </div>
                        <div class="btn">
                            <button class="button1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Restablecer&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
            <script>
                //envio del formulario
                document.getElementById("formulario-contraseña").onsubmit = function(event) {
                event.preventDefault(); // Prevenir el envío del formulario normal

                var correo = document.getElementById("username").value;
                var password = document.getElementById("password").value;
                var confirmar_password = document.getElementById("confirmar_password").value;

                var xhr = new XMLHttpRequest();
                xhr.open("POST", "actualizar_contrasena.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            document.getElementById("resultado").innerHTML = xhr.responseText;
                        } else {
                            alert('Hubo un problema al enviar los datos.');
                        }
                    }
                };
                xhr.send("username=" + encodeURIComponent(correo) + "&password=" + encodeURIComponent(password) + "&confirmar_password=" + encodeURIComponent(confirmar_password));
                };
            </script>

<?php       }

    } else {
        echo "<br><br><h1 class='welcome'>Enlace no válido</h1><br><br>";
    }
} else {
    header('location: forgot_password.php');
}

include 'plantillas/pie.html';
?>