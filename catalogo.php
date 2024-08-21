<?php 
include 'global/conexion.php';
include 'plantillas/cabecera.php';
?>
<link rel="stylesheet" href="catalogo.css">

<section class="contenedor">
    <div class="contenedor-items">
    <?php
        $consulta = "SELECT * FROM productos WHERE disponible = 1 AND cantidad > 0";
        $resultado = $conexion->query($consulta);

        if ($resultado->num_rows > 0) {
            while ($producto = $resultado->fetch_assoc()) {
    ?>
                <div class="item" onclick="redireccionar('producto.php?id=<?php echo $producto['id']; ?>')">
                    <span class="titulo-item"><?php echo $producto['nombre']; ?></span>
                    <img 
                        title="<?php echo $producto['nombre']; ?>"
                        src="img/<?php echo $producto['imagen']; ?>" 
                        alt="<?php echo $producto['nombre']; ?>"
                        class="img-item"
                    >
                    <span class="precio-item">$<?php echo $producto['precio']; ?></span>
                    <form action="" method="post">
                        <input type="hidden" name="id" id="id" value="<?php echo $producto['id']; ?>">
                        <input type="hidden" name="imagen" id="imagen" value="<?php echo $producto['imagen']; ?>">
                        <input type="hidden" name="nombre" id="nombre" value="<?php echo $producto['nombre']; ?>">
                        <input type="hidden" name="precio" id="precio" value="<?php echo $producto['precio']; ?>">
                        <input type="hidden" name="cantidad" id="cantidad" value="1">
                        <button class="boton-item" name="btnAccion" value="Agregar" type="submit" onclick="event.stopPropagation();">Agregar al carrito</button>
                    </form>
                </div>
    <?php
            }
        } else {
            echo "No hay productos disponibles.";
        }
        
    ?>
</div>
</section>
<div class="proximo">
    <div class="linea"></div>
        <h3>Próximamente</h3>
    <div class="linea"></div>
</div>
<section class="contenedor">
    <div class="contenedor-items">
    <?php
        $consulta = "SELECT * FROM productos WHERE disponible = 0 OR cantidad = 0";
        $resultado = $conexion->query($consulta);

        if ($resultado->num_rows > 0) {
            while ($producto = $resultado->fetch_assoc()) {
    ?>
                <div class="item">
                    <span class="titulo-item"><?php echo $producto['nombre']; ?></span>
                    <img 
                        title="<?php echo $producto['nombre']; ?>"
                        src="img/<?php echo $producto['imagen']; ?>" 
                        alt="<?php echo $producto['nombre']; ?>"
                        class="img-item"
                    >
                    <span class="precio-item">$<?php echo $producto['precio']; ?></span>
                    <form action="" method="post">
                        <input type="hidden" name="id" id="id" value="<?php echo $producto['id']; ?>">
                        <input type="hidden" name="imagen" id="imagen" value="<?php echo $producto['imagen']; ?>">
                        <input type="hidden" name="nombre" id="nombre" value="<?php echo $producto['nombre']; ?>">
                        <input type="hidden" name="precio" id="precio" value="<?php echo $producto['precio']; ?>">
                        <input type="hidden" name="cantidad" id="cantidad" value="1">
                        <span class="titulo-item"><h5>Próximamente...</h5></span>
                    </form>
                </div>
    <?php
            }
        } else {
            echo "No hay productos disponibles...";
        }
        $conexion->close();
    ?>
</div>
</section>
<script src="js/catalogo.js"></script>
<script>
    //redirecciona al producto que se esta presionando
    function redireccionar(url) {
        window.location.href = url;
    }

    // Truncar el texto al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        const tituloItems = document.querySelectorAll('.titulo-item');

        tituloItems.forEach(function(titulo) {
            const textoOriginal = titulo.innerText;
            if (textoOriginal.length > 15) {
                const textoTruncado = textoOriginal.substring(0, 15) + '...';
                titulo.innerText = textoTruncado;
            }
        });
    });
</script>
<?php include 'plantillas/pie.html'; ?>