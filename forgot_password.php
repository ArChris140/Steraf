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
    // Recoger el correo electrónico ingresado en el formulario
    $correo = $_POST["username"];

    // Convertir el correo electrónico a minúsculas
    $correo = strtolower($correo);

    // Validar el formato del correo electrónico
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "El formato del correo electrónico no es válido.";
    } else {
        // Verificar si el correo está registrado en la base de datos
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 0) {
            $error = "El correo electrónico no está registrado.";
        } else {
            // Insertar un nuevo registro en la tabla token_usuarios
            $token = bin2hex(random_bytes(32));
            date_default_timezone_set('America/Santiago');
            $expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $stmt = $conexion->prepare("INSERT INTO token_usuarios (correo, token, fecha_expiracion) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $correo, $token, $expiracion);

            if ($stmt->execute()) {
                // Borrar el token existente en la sesión
                unset($_SESSION['token']);

                // Guardar el nuevo token en la sesión
                $_SESSION['token'] = $token;

                // Cargar variables de entorno desde el archivo .env
                $dotenv = Dotenv::createImmutable(__DIR__, 'variables_entorno.env');
                $dotenv->load();

                // Validar variables de entorno
                if (!isset($_ENV['SMTP_USERNAME'], $_ENV['SMTP_PASSWORD'], $_ENV['FORGOT_PASSWORD_URL'])) {
                    die('Error: Variables de entorno no están configuradas correctamente.');
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
                    $mail->setFrom($_ENV['SMTP_USERNAME']);
                    $mail->addAddress($correo);

                    // Leer el contenido del archivo CSS
                    $css = file_get_contents('email_styles.css');
                    if ($css === false) {
                        throw new Exception('No se pudo leer el archivo CSS');
                    }

                    // Contenido
                    $mail->isHTML(true);
                    $mail->Subject = 'Restablecer clave';

                    // Obtener la URL de la variable de entorno
                    $resetPasswordUrl = $_ENV['FORGOT_PASSWORD_URL'];

                    // Cuerpo del correo
                    $mail->Body = '<html>
                                <head>
                                <title>Test correo</title>
                                <style>' . $css . '</style>
                                </head>
                                <body>
                                <div class="container">
                                    <h1>STERAF</h1>
                                    <p>Recuperación de contraseña</p>
                                    <p>Hola, has solicitado restablecer tu contraseña. Por favor haz clic en el siguiente enlace para restablecer tu contraseña de usuario.</p>
                                    <p>Si no solicitaste una nueva contraseña, puedes omitir este correo electrónico.</p>
                                    <a id="enlace-pagina" href="' . $resetPasswordUrl . '">ir a la pagina</a>
                                    <div class="linea"></div>
                                    <small>Si tienes alguna pregunta, responde este correo electrónico o contáctanos a través de <a href="mailto:Steraf17@gmail.com">Steraf17@gmail.com</a></small>
                                </div>
                                </body>
                                </html>';

                    $mail->AltBody = "STERAF\n\nRecuperación de contraseña\n\nHola, has solicitado restablecer tu contraseña. Por favor haz clic en el siguiente enlace para restablecer tu contraseña de usuario.\n\nSi no solicitaste una nueva contraseña, puedes omitir este correo electrónico.\n\nEnlace a la página: " . $resetPasswordUrl . "\n\nSi tienes alguna pregunta, responde este correo electrónico o contáctanos a través de Steraf17@gmail.com";
                                    
                    // Enviar correo
                    $mail->send();
                    $bueno = "Se ha enviado un correo electrónico con el enlace de recuperación a $correo";
                } catch (Exception $e) {
                    $error = "El mensaje no se pudo enviar. Error de correo: {$mail->ErrorInfo}";
                }
            } else {
                $error = "Error al insertar el token en la base de datos: " . $stmt->error;
            }
        }
    }
}
?>

<link rel="stylesheet" href="login.css">
<div class="container">
    <div class="welcome"><h1>Recupera tu contraseña</h1></div>
    <div class="form-login">
        <form class="form" method="post">
            <p id="heading-recuperar">Enviaremos un correo para restablecer tu contraseña</p>
            <p id="mensaje-error"> <?php echo ("$error"); ?> </p>
            <p id="mensaje-bueno"> <?php echo ("$bueno"); ?> </p>
            <div class="field">
            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.93 0 1.524.738 1.524 1.907S8.308 9.84 7.371 9.84c-.895 0-1.442-.725-1.442-1.914z"></path>
            </svg>
            <input autocomplete="off" placeholder="Correo" class="input-field" type="email" id="email" name="username" required>
            </div>
            <div class="btn">
                <button class="button1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Recuperar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
            </div>
            <br>
        </form>
    </div>
</div>

<?php include 'plantillas/pie.html'; ?>