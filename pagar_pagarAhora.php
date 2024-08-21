<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Cargar variables de entorno desde el archivo .env
$dotenv = Dotenv::createImmutable(__DIR__, 'variables_entorno.env');
$dotenv->load();

// Verificar si las variables de entorno están configuradas
if (!isset($_ENV['MERCADO_PAGO_ACCESS_TOKEN'], $_ENV['MERCADO_PAGO_PUBLIC_KEY'], $_ENV['SUCCESS_URL'], $_ENV['FAILURE_URL'])) {
    die('Error: Las variables de entorno no están configuradas correctamente.');
}

// Configurar el token de acceso de MercadoPago
MercadoPago\SDK::setAccessToken($_ENV['MERCADO_PAGO_ACCESS_TOKEN']);

try {    
    $producto = $_SESSION['pagar_ahora'];
    $id = $producto['producto_id'];
    $nombre = $producto['nombre'];
    $cantidad = $producto['cantidad'];
    $precio = $producto['precio'];

    require 'global/conexion.php';

    // Consulta para obtener la cantidad disponible del producto
    $stmt = $conexion->prepare("SELECT cantidad FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($cantidadDisponible);
    $stmt->fetch();

    if ($cantidadDisponible === null) {
        throw new Exception("Producto no encontrado.");
    }

    if ($cantidadDisponible < $cantidad) {
        throw new Exception("Producto agotado o cantidad solicitada no disponible.");
    }

    $preference = new MercadoPago\Preference();
    $preference->back_urls = array(
        "success" => $_ENV['SUCCESS_URL'],
        "failure" => $_ENV['FAILURE_URL'],
    );

    $item = new MercadoPago\Item();
    $item->id = $id;
    $item->title = $nombre;
    $item->quantity = $cantidad;
    $item->unit_price = $precio;
    $item->currency_id = 'CLP';
    $preference->items = array($item);

    $preference->auto_return = "approved";
    $preference->binary_mode = true;

    // Genera una clave de idempotencia única
    $idempotencyKey = uniqid('mp_', true);

    // Guarda la preferencia con la cabecera de Idempotency-Key
    $options = array(
        'headers' => array(
            'X-Idempotency-Key' => $idempotencyKey
        )
    );
    $preference->save($options);
} catch (Exception $e) {
    error_log("Error al crear la preferencia: " . $e->getMessage());
    echo "Ocurrió un error al procesar tu solicitud. Por favor, intenta nuevamente.";
    exit;
}
?>
<script src="https://sdk.mercadopago.com/js/v2"></script>                   
<h3><span style="color: red;">Importante: </span>Cuando realice su pago con éxito, espere los 5 segundos o presione la opción "volver al sitio" para poder obtener su boleta de compra y confirmar su pedido.</h3>
<br>
<div id="mensaje" style="display: block;">
    <button class="btn" onclick="mostrarBoton()">Entiendo</button>
</div>
<div id="botonMercadoPago" style="display: none;">
    <script>
    const np = new MercadoPago('<?php echo $_ENV['MERCADO_PAGO_PUBLIC_KEY']; ?>', {
        locale: 'es-CL'
    });
    const checkout = np.checkout({
        preference: {
            id: '<?php echo $preference->id; ?>'
        },
        render: {
            container: '.btnPagar',
            label: 'Pagar',
        }
    });
    </script>
    <a class="btnPagar"></a>
</div>
<script>
function mostrarBoton() {
    document.getElementById("mensaje").style.display = "none";
    document.getElementById("botonMercadoPago").style.display = "block";
}
</script>
