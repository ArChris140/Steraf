<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require 'vendor/autoload.php';

// Cargar variables de entorno desde el archivo .env
$dotenv = Dotenv::createImmutable(__DIR__, 'variables_entorno.env');
$dotenv->load();

// Validar variables de entorno
if (!isset($_ENV['SMTP_USERNAME'], $_ENV['SMTP_PASSWORD'])) {
    die('Error: Variables de entorno SMTP no están configuradas correctamente.');
}

include 'global/conexion.php';
    $usuario_id = $_SESSION['rol']['id'];
    // Preparar la consulta SQL con placeholders
    $sql = "SELECT u.*, i.* FROM usuarios u INNER JOIN informacion_usuario i ON u.id = i.id_usuario WHERE u.id = ?";

    // Preparar el statement
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        die("Error en la preparación del statement: " . $conexion->error);
    }

    // Vincular los parámetros
    $stmt->bind_param("i", $usuario_id);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $resultado = $stmt->get_result();

    // Verificar si se encontró al usuario en la base de datos
    if($resultado && mysqli_num_rows($resultado) > 0) {
        // Obtener los datos del usuario
        $usuario = mysqli_fetch_assoc($resultado);
    } else {
        echo "No se encontró al usuario en la base de datos";
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
    $mail->addAddress($usuario['username']);
    $mail->addAddress($_ENV['SMTP_USERNAME']);

    // Leer el contenido del archivo CSS
    $css = file_get_contents('email_styles.css');
    if ($css === false) {
        throw new Exception('No se pudo leer el archivo CSS');
    }

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Tu compra';

    $producto = $_SESSION['pagar_ahora'];
    $total = $producto['precio']*$producto['cantidad'];

    // Cuerpo del correo
    $mail->Body = '<html>
                    <head>
                    <title>Boleta de Compra</title>
                    <style>' . $css . '</style>
                    </head>
                    <body>
                    <div class="container">
                        <h1>STERAF</h1>
                        <h2>Boleta de Compra</h2>
                        <h3>Datos del Comprador</h3>
                        <p><strong>Nombre: </strong>' . htmlspecialchars($usuario['nombre']).' '.htmlspecialchars($usuario['apellido']) . '</p>
                        <p><strong>Correo: </strong>' . htmlspecialchars($usuario['username']) . '</p>
                        <p><strong>Teléfono: </strong>' . htmlspecialchars($usuario['telefono']) . '</p>
                        <p><strong>Dirección: </strong>' . htmlspecialchars($usuario['region']).' - '.htmlspecialchars($usuario['comuna']).' - '.htmlspecialchars($usuario['calle']).' - '.htmlspecialchars($usuario['numero']) . '</p>
                        <p><strong>Informacion Adicional: </strong>' . htmlspecialchars($usuario['info_adicional']) . '</p>
                        <h3>Información de la Transacción</h3>
                        <p><strong>Numero de Pago: </strong>' . htmlspecialchars($payment) . '</p>
                        <p><strong>Fecha y Hora: </strong>' . htmlspecialchars($fecha_actual) . '</p>
                        <p><strong>Tipo de Pago: </strong>' . htmlspecialchars($payment_type) . '</p>
                        <h3>Resumen de Productos</h3>
                        <div class="table-responsive">
                            <table>
                                <tbody>
                                    <tr>
                                        <th width="40%">Nombre</th>
                                        <th width="20%">Cantidad</th>
                                        <th width="20%">Precio</th>
                                        <th width="20%">Total</th>
                                    </tr>
                                    <tr>
                                        <td width="40%">' . htmlspecialchars($producto['nombre']) . '</td>
                                        <td width="20%">' . htmlspecialchars($producto['cantidad']) . '</td>
                                        <td width="20%">' . htmlspecialchars($producto['precio']) . '</td>
                                        <td width="20%">' . htmlspecialchars($producto['precio'] * $producto['cantidad']) . '</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" align="right"><h3>Total:</h3></td>
                                        <td><h3>$' . htmlspecialchars($total) . '</h3></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p>Gracias por tu compra</p>   
                        <a id="enlace-pagina" href="https://steraf.com/index.php">ir a la pagina</a>
                        <div class="linea"></div>
                        <small>Si tienes alguna pregunta, responde este correo electrónico o contáctanos a través de <a href="mailto:Steraf17@gmail.com">Steraf17@gmail.com</a></small>
                    </div>
                    </body>
                    </html>';

    $mail->AltBody = "STERAF
                    Boleta de Compra

                    Datos del Comprador:
                    Nombre: " . htmlspecialchars($usuario['nombre']) . " " . htmlspecialchars($usuario['apellido']) . "
                    Correo: " . htmlspecialchars($usuario['username']) . "
                    Teléfono: " . htmlspecialchars($usuario['telefono']) . "
                    Dirección: " . htmlspecialchars($usuario['region']) . " - " . htmlspecialchars($usuario['comuna']) . " - " . htmlspecialchars($usuario['calle']) . " - " . htmlspecialchars($usuario['numero']) . "
                    Informacion Adicional: " . htmlspecialchars($usuario['info_adicional']) . "

                    Información de la Transacción:
                    Numero de Pago: " . htmlspecialchars($payment) . "
                    Fecha y Hora: " . htmlspecialchars($fecha_actual) . "
                    Tipo de Pago: " . htmlspecialchars($payment_type) . "

                    Resumen de Productos:
                    Nombre          Cantidad      Precio       Total
                    " . htmlspecialchars($producto['nombre']) . "          " . htmlspecialchars($producto['cantidad']) . "     " . htmlspecialchars($producto['precio']) . "    " . htmlspecialchars($producto['precio'] * $producto['cantidad']) . "

                    Total: $" . htmlspecialchars($total) . "

                    Gracias por tu compra

                    Ir a la página: https://steraf.com/index.php

                    ---
                    Si tienes alguna pregunta, responde este correo electrónico o contáctanos a través de Steraf17@gmail.com";
                    
    // Enviar correo
    $mail->send();
    // echo 'El mensaje ha sido enviado';
    unset($_SESSION['pagar_ahora']);
} catch (Exception $e) {
    echo "El mensaje no se pudo enviar. Error de correo: {$mail->ErrorInfo}";
}
?>
