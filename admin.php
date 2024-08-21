<?php include 'plantillas/cabecera.php'; ?>
<?php
if (!isset($_SESSION['rol']) || $_SESSION['rol']['rol_id'] != 1) {
    header('location: login.php');
    exit();
}
?>
<link rel="stylesheet" href="admin.css">

<div class="container">
    <div class="contenedor-padre">
      <div class="contenedor-input">
        <h2>Tus productos</h2>
      </div>
      <div class="contenedor-input">
        <a href="nuevo_producto.php">
            <button class="Btn-editar" style="float: right;">
                Nuevo
                <svg class="svg" viewBox="0 0 448 512">
                <path d="M432 256c0 13.3-10.7 24-24 24h-144v144c0 13.3-10.7 24-24 24h-48c-13.3 0-24-10.7-24-24V280H40c-13.3 0-24-10.7-24-24v-48c0-13.3 10.7-24 24-24h144V40c0-13.3 10.7-24 24-24h48c13.3 0 24 10.7 24 24v144h144c13.3 0 24 10.7 24 24v48z"/>
                </svg>
            </button>
        </a>
      </div>
    </div><br><br><br>
    <input class="input" id="buscar" placeholder="Buscar por nombre o precio">
    <br>
    <div class="table-responsive">
        <table id="tabla-productos">
            <tr>
                <th width="40%" colspan="2">Producto</th>
                <th width="20%">Cantidad</th>
                <th width="20%">Precio</th>
                <th width="20%" colspan="2">Acción</th>
            </tr>
            <?php
            $select = "SELECT * FROM productos";
            $resultado = mysqli_query($conexion,$select);
            if(mysqli_num_rows($resultado)>0) {
                while($fila=mysqli_fetch_assoc($resultado)){ ?>
                <tr data-id="<?php echo $fila['id']; ?>">
                    <td width="6%"><img src="img/<?php echo $fila['imagen']; ?>" alt="<?php echo $fila['nombre']; ?>" width="60px" height="60px"></td>
                    <td width="34%"><?php echo $fila['nombre']; ?></td>
                    <td width="20%"><?php echo $fila['cantidad']; ?></td>
                    <td width="20%"><?php echo $fila['precio']; ?></td>
                    <td width="10%">
                        <form action="nuevo_producto.php" method="post">
                            <input type="hidden" id="id" name="id" value="<?php echo $fila['id']; ?>">
                            <button class="btn-accion"><img src="img/editar.png" alt="editar" width="23px" height="23px"></button>
                        </form>
                    </td>
                    <td width="10%"><button class="btn-accion eliminar"><img src="img/papelera.png" alt="papelera" width="23px" height="23px"></button></td>
                </tr>
            <?php
                }
            } else {
                echo "No hay resultados";
            }
            ?>
        </table>
    </div>
</div>
<script>
$(document).ready(function() {
            $('.eliminar').on('click', function() {
                let fila = $(this).closest('tr');
                let idProducto = fila.data('id');

                if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                    $.ajax({
                        url: 'eliminar_producto.php',
                        type: 'POST',
                        data: { id: idProducto },
                        success: function(response) {
                            if (response == 'success') {
                                fila.remove();
                            } else {
                                alert('Error al eliminar el producto.');
                            }
                        }
                    });
                }
            });
        });
</script>
<script>
document.getElementById('buscar').addEventListener('input', function() {
    var filter = this.value.toLowerCase();
    var rows = document.querySelectorAll('#tabla-productos tr[data-id]'); // Selecciona todas las filas que tienen el atributo data-id

    rows.forEach(function(row) {
        var nombre = row.cells[1].textContent.toLowerCase(); // Columna del nombre
        var precio = row.cells[3].textContent.toLowerCase(); // Columna del precio

        if (nombre.includes(filter) || precio.includes(filter)) {
            row.style.display = ''; // Muestra la fila
        } else {
            row.style.display = 'none'; // Oculta la fila
        }
    });
});
</script>

<?php include 'plantillas/pie.html'; ?>