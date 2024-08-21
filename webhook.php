<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use MercadoPago\SDK;
use MercadoPago\Payment;

$dotenv = Dotenv::createImmutable(__DIR__, 'variables_entorno.env');
$dotenv->load();

// Configurar el token de acceso de MercadoPago
SDK::setAccessToken($_ENV['MERCADO_PAGO_ACCESS_TOKEN']);

// Conexión a la base de datos
require 'global/conexion.php';

// Este es el endpoint que MercadoPago llamará con las notificaciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lee el cuerpo de la notificación
    $json = file_get_contents('php://input');
    error_log("Received webhook payload: " . $json);
    $data = json_decode($json);

    if (isset($data->type) && $data->type == 'payment') {
        $paymentId = $data->data->id;
        error_log("Processing payment ID: $paymentId");

        try {
            // Consultar el estado del pago
            $payment = Payment::find_by_id($paymentId);

            if ($payment) {
                $status = $payment->status;
                $statusDetail = $payment->status_detail;
                $preferenceId = $payment->preference_id;

                // Verificar si 'additional_info' y 'items' existen
                $items = isset($payment->additional_info->items) ? $payment->additional_info->items : [];
                $producto_id = isset($items[0]->id) ? $items[0]->id : null;
                $cantidad_comprada = isset($items[0]->quantity) ? $items[0]->quantity : 0;
                error_log("Product ID: $producto_id, Quantity Purchased: $cantidad_comprada");

                // Aquí puedes manejar el estado del pago (aprobado, rechazado, etc.)
                if ($status == 'approved') {
                    // Pago aprobado
                    error_log("Pago aprobado, ID: $paymentId");

                    // Actualizar la base de datos
                    $stmt = $conexion->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id = ?");
                    $stmt->bind_param("ii", $cantidad_comprada, $producto_id);

                    if ($stmt->execute()) {
                        error_log("Cantidad actualizada correctamente para el producto ID: $producto_id");
                    } else {
                        error_log("Error al actualizar la cantidad del producto ID: $producto_id");
                    }
                } else {
                    // Pago no aprobado
                    error_log("Pago no aprobado, ID: $paymentId, Estado: $status, Detalle: $statusDetail");
                }
            } else {
                error_log("No payment information found for ID: $paymentId");
            }
        } catch (Exception $e) {
            error_log("Error al consultar el estado del pago: " . $e->getMessage());
        }
    } else {
        error_log("Invalid webhook payload type: " . $data->type);
    }
} else {
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
}
?>