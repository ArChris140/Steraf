<?php
include 'plantillas/cabecera.php';
?>
<link rel="stylesheet" href="mostrarCarrito.css">
<div id="mensaje"></div>
<div class="contenedor">
<div class="seccion-1"><h1>Lista del carrito</h1></div>
<?php if(!empty($_SESSION['CARRITO'])) { ?>
<div class="seccion-1">
<div class="table-responsive">
<table>
    <tbody>
        <tr>
            <th width="40%" colspan="2">Descripcion</th>
            <th width="15%" colspan="3">Cantidad</th>
            <th width="20%">Precio</th>
            <th width="20%">Total</th>
            <th width="5%">Eliminar</th>
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
            <td width="5%"><form action="" method="post">
            <input type="hidden" name="id" id="id" value="<?php echo $producto['id']; ?>">
                                                <button
                                                class="btn-operacion"
                                                type="submit"
                                                name="btnAccion"
                                                value="Restar"
                                                ><</button>
                                                </form></td>
            <td width="5%"><?php echo $producto['cantidad']; ?></td>
            <td width="5%"><form action="" method="post">
            <input type="hidden" name="id" id="id" value="<?php echo $producto['id']; ?>">
                                                <button
                                                class="btn-operacion"
                                                type="submit"
                                                name="btnAccion"
                                                value="Sumar"
                                                >></button>
                                                </form></td>
            <td width="20%"><?php echo $producto['precio']; ?></td>
            <td width="20%"><?php echo $producto['precio']*$producto['cantidad']; ?></td>
            <td width="5%">
            <form action="" method="post">
                <input type="hidden" name="id" id="id" value="<?php echo $producto['id']; ?>">
                <button 
                class="btn-eliminar" 
                type="submit"
                name="btnAccion"
                value="Eliminar">
                <img src="img/papelera.png" alt="papelera" width="30px" height="30px">
                </button>
            </form>
            </td>
        </tr>
        <?php $total = $total + ($producto['precio']*$producto['cantidad']); ?>
        <?php } ?>
        <tr>
            <td colspan="7" align="right"><h3>Total:</h3></td>
            <td align="right"><h3>$<?php echo $total; ?></h3></td>
        </tr>
    </tbody>
</table>
</div>
</div>
<?php 
// Verifica si la variable de sesión 'rol' está definida y no está vacía
if (isset($_SESSION['rol']) && !empty($_SESSION['rol'])) { 
    unset($_SESSION['pagar_ahora']); ?>
    <div class="seccion-1">
        <a href="pagar.php">
            <button type="submit" name="btnAccion" value="proceder" class="cssbuttons-io-button">
                Proceder a pagar
                <div class="icon">
                    <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z" fill="currentColor"></path>
                    </svg>
                </div>
            </button>
        </a>
    </div>
<?php } else { ?>
    <div class="seccion-1">
        Debe iniciar sesión para proceder a pagar
    </div>
<?php }
}else{ ?>
    <div class="seccion-1">
        No hay productos en el carrito...
    </div>
<?php } ?>
</div>
<script src="js/eliminar_carrito.js"></script>
<script src="js/cantidad_carrito.js"></script>
<script>
window.onload = function() {
    // Verificar si el formulario fue enviado previamente
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
};
</script>
<?php include 'plantillas/pie.html'; ?>