<?php include 'plantillas/cabecera.php'; ?>
<?php
include 'global/conexion.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require 'vendor/autoload.php';
$error = "";
$bueno = "";

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombre = $_POST["nombre"];
    $correo = $_POST["username"];
    $contraseña = $_POST["password"];
    $rol_usuario = 2;

    // Convertir el correo electrónico a minúsculas
    $correo = strtolower($correo);

    // Verificar si la contraseña cumple con los requisitos
    if (mb_strlen($contraseña) < 8 || !preg_match("/[a-zA-Z]/", $contraseña) || !preg_match("/[0-9]/", $contraseña)) {
        $error = "La contraseña debe tener al menos 8 caracteres, contener letras y números";
        // No continúes con el proceso de registro
        exit;
    }

    // Validar el formato del correo electrónico
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "El formato del correo electrónico es inválido";
    } else {
        // Verificar si el correo ya está registrado
        $sql_verificar_correo = $conexion->prepare("SELECT * FROM usuarios WHERE username = ?");
        $sql_verificar_correo->bind_param("s", $correo);
        $sql_verificar_correo->execute();
        $resultado_verificar_correo = $sql_verificar_correo->get_result();

        if ($resultado_verificar_correo->num_rows > 0) {
            $error = "El correo electrónico ya está registrado";
        } else {
            // Generar el token
            $token = bin2hex(random_bytes(32));

            // Encriptar la contraseña
            $contraseña_encriptada = password_hash($contraseña, PASSWORD_DEFAULT);

            // Cargar variables de entorno desde el archivo .env
            $dotenv = Dotenv::createImmutable(__DIR__, 'variables_entorno.env');
            $dotenv->load();

            // Validar variables de entorno
            if (!isset($_ENV['SMTP_USERNAME'], $_ENV['SMTP_PASSWORD'], $_ENV['USUARIO_REGISTRO_URL'])) {
                die('Error: Variables de entorno SMTP no están configuradas correctamente.');
            }

            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = $_ENV['SMTP_USERNAME'];
                $mail->Password   = $_ENV['SMTP_PASSWORD'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Destinatarios
                $mail->setFrom($_ENV['SMTP_USERNAME'], 'Mailer');
                $mail->addAddress($correo);

                // Leer el contenido del archivo CSS
                $css = file_get_contents('email_styles.css');
                if ($css === false) {
                    throw new Exception('No se pudo leer el archivo CSS');
                }

                // Contenido
                $mail->isHTML(true);
                $mail->Subject = 'Registro de cuenta';

                // Obtener la URL de la variable de entorno
                $UsuarioRegistroUrl = $_ENV['USUARIO_REGISTRO_URL'];

                // Cuerpo del correo
                $mail->Body = '<html>
                                <head>
                                <title>Test correo</title>
                                <style>' . $css . '</style>
                                </head>
                                <body>
                                <div class="container">
                                    <h1>STERAF</h1>
                                    <p>Creación de perfil</p>
                                    <p>Hola, has solicitado crear una cuenta en nuestro sitio web. Por favor, haz clic en el siguiente enlace para confirmar tu perfil.</p>
                                    <p>Si no solicitaste crear una cuenta, puedes omitir este correo electrónico.</p>
                                    <a id="enlace-pagina" href="' . $UsuarioRegistroUrl . '?token='.$token.'">Ir a la página</a>
                                    <div class="linea"></div>
                                    <small>Si tienes alguna pregunta, responde a este correo electrónico o contáctanos a través de <a href="mailto:Steraf17@gmail.com">Steraf17@gmail.com</a></small>
                                </div>
                                </body>
                                </html>';

                $mail->AltBody = "STERAF\n\nCreación de perfil\n\nHola, has solicitado crear una cuenta en nuestro sitio web. Por favor, haz clic en el siguiente enlace para confirmar tu perfil.\n\nSi no solicitaste crear una cuenta, puedes omitir este correo electrónico.\n\nEnlace a la página: " . $UsuarioRegistroUrl . "?token=" . $token . "\n\nSi tienes alguna pregunta, responde a este correo electrónico o contáctanos a través de Steraf17@gmail.com";
                                
                // Enviar correo
                $mail->send();
                // Consulta preparada para insertar datos en la tabla signup
                $sql = "INSERT INTO signup (token, nombre, correo, contraseña, rol_usuario) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("sssss", $token, $nombre, $correo, $contraseña_encriptada, $rol_usuario);

                // Ejecutar la consulta
                if ($stmt->execute()) {
                    //echo "Datos insertados correctamente.";
                } else {
                    echo "Error al insertar datos: " . $conexion->error;
                }

                $bueno = "Se ha enviado un correo electrónico de confirmación a $correo. Por favor, verifica tu correo electrónico para completar el registro";
            } catch (Exception $e) {
                $error = "El mensaje no se pudo enviar. Error de correo: {$mail->ErrorInfo}";
            }
        }
    }

    // Cerrar la conexión
    $conexion->close();
}
?>

<link rel="stylesheet" href="login.css">
<div class="container">
    <div class="welcome"><h1>Crear una cuenta</h1></div>
        <div class="form-login">
            <form class="form" method="post" onsubmit="return validarContraseña()">
                <br><br>
                <p id="mensaje-bueno"> <?php echo ("$bueno"); ?> </p>
                <p id="mensaje-error"> <?php echo ("$error"); ?> </p>
                <div class="field">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 0a4 4 0 0 1 4 4c0 1.124-.474 2.158-1.23 2.876C11.525 7.919 12 8.898 12 10v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-1c0-1.102.475-2.081 1.23-2.876C4.475 6.158 4 5.124 4 4a4 4 0 0 1 4-4zm3 11a2 2 0 0 0 2-2V9a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1v.005a2 2 0 0 0 2 1.995h.01a2 2 0 0 0 1.987-1.851A3.999 3.999 0 0 0 14 7v1a2 2 0 0 0 2 2z"/>
                    </svg>
                    <input autocomplete="off" placeholder="Nombre" class="input-field" type="text" id="nombre" name="nombre" required>
                </div>
                <div class="field">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.93 0 1.524.738 1.524 1.907S8.308 9.84 7.371 9.84c-.895 0-1.442-.725-1.442-1.914z"></path>
                    </svg>
                    <input autocomplete="off" placeholder="Correo" class="input-field" type="email" id="email" name="username" required>
                </div>
                <div class="field">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path>
                    </svg>
                    <input placeholder="Contraseña" class="input-field" type="password" id="password" name="password" required>
                </div>
                <div class="btn">
                    <button class="button1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Crear cuenta&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                </div>
                <br>
            </form>
        </div>
</div>

<script>
    function validarContraseña() {
        var contraseña = document.getElementById("password").value;
        var hasNumber = /\d/.test(contraseña);
        var hasLetter = /[a-zA-Z]/.test(contraseña);

        if (contraseña.length < 8 || !hasNumber || !hasLetter) {
            document.getElementById("mensaje-error").innerHTML = "La contraseña debe tener al menos 8 caracteres, contener letras y números";
            return false;
        }
        return true;
    }
</script>

<?php include 'plantillas/pie.html'; ?>