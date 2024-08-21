<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Cargar variables de entorno desde el archivo .env
$dotenv = Dotenv::createImmutable(__DIR__, '/../variables_entorno.env');
$dotenv->load();

// Variables de entorno para la conexión a la base de datos
$db_host = $_ENV['DB_HOST'];
$db_username = $_ENV['DB_USERNAME'];
$db_password = $_ENV['DB_PASSWORD'];
$db_database = $_ENV['DB_DATABASE'];

// Crear conexión
$conexion = mysqli_connect($db_host, $db_username, $db_password, $db_database);

// Verificar la conexión
if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Opcionalmente, puedes configurar el juego de caracteres y otras configuraciones aquí
mysqli_set_charset($conexion, 'utf8');

// Ahora `$conexion` está listo para ser utilizado en tu aplicación

?>
