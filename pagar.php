<?php require 'plantillas/cabecera.php'; ?>
<?php
if (!isset($_SESSION['rol'])) {
    header('location: login.php');
    exit();
}
?>
<link rel="stylesheet" href="pagar.css">
<?php
// Verificar si el usuario está autenticado
if (isset($_SESSION['rol']) && isset($_SESSION['rol']['id'])) {
    // Obtener el id del usuario de la variable de sesión
    $usuario_id = $_SESSION['rol']['id'];

    include 'global/conexion.php';

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
    if ($resultado && $resultado->num_rows > 0) {
        // Obtener los datos del usuario
        $usuario = $resultado->fetch_assoc();

        // Ahora puedes acceder a los datos del usuario
        $id_usuario = $usuario['id_usuario'];
        $correo_usuario = $usuario['username'];
        $nombre_usuario = $usuario['nombre'];
        $apellido_usuario = $usuario['apellido'];
        $telefono_usuario = $usuario['telefono'];
        $region_usuario = $usuario['region'];
        $comuna_usuario = $usuario['comuna'];
        $calle_usuario = $usuario['calle'];
        $numero_usuario = $usuario['numero'];
        $info_adicional_usuario = $usuario['info_adicional'];

        // Verificar si alguno de los campos está vacío
        if (empty($nombre_usuario) || empty($telefono_usuario) || empty($region_usuario) || empty($comuna_usuario) || empty($calle_usuario) || empty($numero_usuario)) { ?>
            <div class="container">
                <h3>Complete los datos faltantes para continuar con el proceso de pago</h3>
                <br><br><br>
                <form id="formulario-guardar-info" style="width: 100%;">
                <div id="resultado"></div>
                    <div class="contenedor-padre">
                        <div class="contenedor-input">
                        <label for="nuevo_nombre">Nombre</label><br>
                        <input type="hidden" id="id" name="id" value=<?php echo ("$id_usuario"); ?>>
                        <input class="input" type="text" id="nuevo_nombre" name="nuevo_nombre" value="<?php echo $nombre_usuario; ?>" required>
                        </div>
                        <div class="contenedor-input">
                        <label for="nuevo_apellido">Apellido</label><br>
                        <input class="input" type="text" id="nuevo_apellido" name="nuevo_apellido" value="<?php echo $apellido_usuario; ?>">
                        </div>
                    </div>
                    <br>
                    <label for="nuevo_telefono">Telefono</label><br>
                    <input class="input" type="number" id="nuevo_telefono" name="nuevo_telefono" value="<?php echo $telefono_usuario; ?>" required>
                    <br><br>
                    <div class="contenedor-padre">
                        <div class="contenedor-input">
                        <label for="nueva_region">Region</label><br>
                        <input class="input" type="text" id="nueva_region" name="nueva_region" value="<?php echo $region_usuario; ?>" required>
                        </div>
                        <div class="contenedor-input">
                        <label for="nueva_comuna">Comuna</label><br>
                        <input class="input" type="text" id="nueva_comuna" name="nueva_comuna" value="<?php echo $comuna_usuario; ?>" required>
                        </div>
                    </div>
                    <br>
                    <div class="contenedor-padre">
                        <div class="contenedor-input">
                        <label for="nueva_calle">Calle</label><br>
                        <input class="input" type="text" id="nueva_calle" name="nueva_calle" value="<?php echo $calle_usuario; ?>" required>
                        </div>
                        <div class="contenedor-input">
                        <label for="nuevo_numero">Número de casa/departamento</label><br>
                        <input class="input" type="text" id="nuevo_numero" name="nuevo_numero" value="<?php echo $numero_usuario; ?>" required>
                        </div>
                    </div>
                    <br><br>
                    <label for="nueva_informacion">Informacion adicional</label><br>
                    <input class="input" type="text" id="nueva_informacion" name="nueva_informacion" value="<?php echo $info_adicional_usuario; ?>">
                    <br><br>
                    <div class="contenedor-padre">
                        <div class="contenedor-input">
                        <a href="catalogo.php"><input type="button" class="btn-cancelar" value="Cancelar"></a>
                        </div>
                        <div class="contenedor-input">
                        <input id="btn-guardar-info" type="submit" class="btn" value="Guardar">
                        </div>
                    </div>
                </form>
            </div>
        <?php } elseif (isset($_SESSION['pagar_ahora'])) { 
//-------------------------- Mostrar los datos del producto para pagar ahora ---------------------------
            $producto = $_SESSION['pagar_ahora']; ?>
            <div class="container">
                <h1>Tu pedido</h1><br><br><br>
                <div class="table-responsive">
                    <table>
                        <tbody>
                            <tr>
                                <th width="40%" colspan="2">Descripcion</th>
                                <th width="20%">Cantidad</th>
                                <th width="20%">Precio</th>
                                <th width="20%">Total</th>
                            </tr>
                            <?php $total=0; ?>
                            <tr>
                                <td width="6%"><img 
                                        title="<?php echo $producto['nombre']; ?>"
                                        src="img/<?php echo $producto['imagen']; ?>" 
                                        alt="<?php echo $producto['nombre']; ?>"
                                        width="60px"
                                        height="60px"
                                    ></td>
                                <td width="34%"><?php echo $producto['nombre']; ?></td>
                                <td width="20%"><?php echo $producto['cantidad']; ?></td>
                                <td width="20%"><?php echo $producto['precio']; ?></td>
                                <td width="20%"><?php echo $producto['precio']*$producto['cantidad']; ?></td>
                            </tr>
                            <?php $total = $total + ($producto['precio']*$producto['cantidad']); ?>
                            <tr>
                                <td colspan="4" align="right"><h3>Total:</h3></td>
                                <td><h3>$<?php echo $total; ?></h3></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="seccion">
                    <?php require_once 'pagar_pagarAhora.php'; ?>
                </div>
            </div>          
        <?php } elseif(isset($_SESSION['CARRITO']) && !empty($_SESSION['CARRITO'])) {
//-------------------------------------- Mostrar los productos en el carrito ----------------------------- ?>
            <div class="container">
                <h1>Tu pedido</h1><br><br><br>
                <div class="table-responsive">
                    <table>
                        <tbody>
                            <tr>
                                <th width="40%" colspan="2">Descripcion</th>
                                <th width="20%">Cantidad</th>
                                <th width="20%">Precio</th>
                                <th width="20%">Total</th>
                            </tr>
                            <?php $total=0; ?>
                            <?php foreach($_SESSION['CARRITO'] as $indice=>$producto){ ?>
                            <tr>
                                <td width="6%"><img 
                                        title="<?php echo $producto['nombre']; ?>"
                                        src="img/<?php echo $producto['imagen']; ?>" 
                                        alt="<?php echo $producto['nombre']; ?>"
                                        width="60px"
                                        height="60px"
                                    ></td>
                                <td width="34%"><?php echo $producto['nombre']; ?></td>
                                <td width="20%"><?php echo $producto['cantidad']; ?></td>
                                <td width="20%"><?php echo $producto['precio']; ?></td>
                                <td width="20%"><?php echo $producto['precio']*$producto['cantidad']; ?></td>
                            </tr>
                            <?php $total = $total + ($producto['precio']*$producto['cantidad']); ?>
                            <?php } ?>
                            <tr>
                                <td colspan="4" align="right"><h3>Total:</h3></td>
                                <td><h3>$<?php echo $total; ?></h3></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="seccion">
                    <?php require_once 'pagar_carrito.php'; ?>
                </div>
            </div>
        <?php } else {
            echo "No hay productos";
            header('location: catalogo.php');
        }
    } else {
        echo "No se encontró al usuario";
    }

    $stmt->close();
    $conexion->close();
} else {
    exit("No estás autenticado.");
}
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
// Envío del formulario de comprar ahora
var btnComprarAhora = document.getElementById("btn-guardar-info");
if (btnComprarAhora) {
        btnComprarAhora.addEventListener('click', function(event) {
            event.preventDefault(); // Prevenir el envío del formulario normal

            var formularioProducto = document.getElementById("formulario-guardar-info");
            var formData = new FormData(formularioProducto); // Crear un objeto FormData con los datos del formulario

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "validar_info_usuario.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var resultado = xhr.responseText;
                        if (resultado.includes("Información actualizada correctamente")) {
                            location.reload();
                        } else {
                            document.getElementById("resultado").innerHTML = resultado;
                        }
                    } else {
                        alert('Hubo un problema al enviar los datos.');
                    }
                }
            };
            xhr.send(formData); // Enviar el objeto FormData que contiene los datos del formulario
        });
    }
});
</script>

<?php require 'plantillas/pie.html'; ?>