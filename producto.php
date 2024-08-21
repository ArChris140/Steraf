<?php
include 'global/conexion.php';
include 'plantillas/cabecera.php';
?>
<link rel="stylesheet" href="catalogo.css">
<?php

if(isset($_GET['id'])) {
    $producto_id = $_GET['id'];
    $consulta = "SELECT * FROM productos WHERE id = ?";
    
    if ($stmt = $conexion->prepare($consulta)) {
        $stmt->bind_param("i", $producto_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $producto = $resultado->fetch_assoc();
        
        $stmt->close();
        
        if($producto) {
            // Mostrar la información del producto
?>
                <div class='producto'>
                    <div class='producto-1'>
                        <div class='producto-1_1'>
                            <img src='img/<?php echo $producto['imagen']; ?>' alt='<?php echo $producto['nombre']; ?>'>
                        </div>
                        <div class='producto-1_1'><br>
                            <h2><?php echo $producto['nombre']; ?></h2>
                            <p><?php echo $producto['descripcion']; ?></p><br>
                            <p>Precio: $<?php echo $producto['precio']; ?></p>
                            <br><br>
                            <form id="formulario-pagar-producto" method="post">
                                <input type="hidden" name="id" id="id" value="<?php echo $producto['id']; ?>">
                                <input type="hidden" name="imagen" id="imagen" value="<?php echo $producto['imagen']; ?>">
                                <input type="hidden" name="nombre" id="nombre" value="<?php echo $producto['nombre']; ?>">
                                <input type="hidden" name="precio" id="precio" value="<?php echo $producto['precio']; ?>">
                                <input type="hidden" name="cantidad_producto" id="cantidad_producto" value="<?php echo $producto['cantidad']; ?>">
                                <p>Cantidad:</p><br>
                                <div id="resultado"></div>
                                <input class="input" type="number" name="cantidad" id="cantidad" value="1" autocomplete="off">
                                <br>
                                <br>
                                <br>
                                <h5>Stock: <?php echo $producto['cantidad']; ?></h5>
                                <br>
                                <button name="btnAccion" value="Agregar" type="submit" class="boton-item btn" data-cantidad-disponible="<?php echo $producto['cantidad']; ?>">Agregar al carrito
                                </button>
                                <br><br>
                                <?php // Verifica si la variable de sesión 'rol' está definida y no está vacía
                                if (isset($_SESSION['rol']) && !empty($_SESSION['rol'])) { 
                                    $usuario_id = $_SESSION['rol']['id']; ?>
                                    <input id="usuario_id" name="usuario_id" type="hidden" value="<?php echo $usuario_id; ?>">
                                    <button id="btn-comprar-ahora" type="submit" class="btn">Comprar ahora
                                    </button>
                            </form>                          
                                <?php } else { ?>
                                    <button class="btn" disabled>Debe iniciar sesión para comprar ahora</button>
                                <?php } ?>
                        </div>
                    </div>
                </div>
<?php
        } else {
            // Si no se encuentra el producto, puedes redirigir al usuario a una página de error o realizar alguna otra acción
            header("Location: catalogo.php");
            exit();
        }
    } else {
        // Si hay un error en la preparación de la consulta
        echo "Error: " . $mysqli->error;
    }

    // Cerrar la conexión
    $conexion->close();
} else {
    // Si no se proporciona un ID de producto válido, puedes redirigir al usuario a una página de error o realizar alguna otra acción
    header("Location: catalogo.php");
    exit();
}
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
// Envío del formulario de comprar ahora
var btnComprarAhora = document.getElementById("btn-comprar-ahora");
if (btnComprarAhora) {
        btnComprarAhora.addEventListener('click', function(event) {
            event.preventDefault(); // Prevenir el envío del formulario normal

            var formularioProducto = document.getElementById("formulario-pagar-producto");
            var formData = new FormData(formularioProducto); // Crear un objeto FormData con los datos del formulario

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "validar_pagar_producto.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var resultado = xhr.responseText;
                        if (resultado.includes("Pagar producto")) {
                            window.location.href = "pagar.php";
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
<script src="js/producto.js"></script>

<?php include 'plantillas/pie.html'; ?>