<?php include 'plantillas/cabecera.php'; ?>
<?php
if (!isset($_SESSION['rol']) || $_SESSION['rol']['rol_id'] != 1) {
    header('location: login.php');
    exit();
}
?>
<link rel="stylesheet" href="admin.css">

<div class="container">
    <?php
    include 'global/conexion.php';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
        $id = $_POST["id"];

        // Consultar los datos del producto en la base de datos
        $sql = "SELECT * FROM productos WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si se encontró el producto
        if ($result->num_rows > 0) {
            $producto = $result->fetch_assoc(); ?>
            <div class="seccion">
                <h1>Editar producto</h1>
            </div>
            <br><br><br>
            <div id="resultado"></div>
            <br><br><br>
            <div class="seccion">
                <form style="width: 100%;" id="formulario-editar-productos" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php echo $producto['id']; ?>">
                    <label for="nombre_editar_producto">Nombre</label><br>
                    <input class="input" type="text" id="nombre_editar_producto" name="nombre_editar_producto" value="<?php echo $producto['nombre']; ?>" required>
                    <br><br>
                    <div class="contenedor-padre">
                        <div class="contenedor-input">
                            <label for="precio_editar_producto">Precio</label><br>
                            <input class="input" type="number" id="precio_editar_producto" name="precio_editar_producto" value="<?php echo $producto['precio']; ?>" required>
                        </div>
                        <div class="contenedor-input">
                            <label for="cantidad_editar_producto">Cantidad</label><br>
                            <input class="input" type="number" id="cantidad_editar_producto" name="cantidad_editar_producto" value="<?php echo $producto['cantidad']; ?>" required>
                        </div>
                    </div>
                    <br>
                    Descripción<br>
                    <textarea class="input" name="descripcion_editar_producto" id="descripcion_editar_producto" style="resize: none; height: 100px;"><?php echo $producto['descripcion']; ?></textarea>
                    <br><br>
                    <label>
                    <input type="radio" name="disponibilidad_editar_producto" value="1" checked required>
                        Disponible
                    </label>
                    <br><br>
                    <label>
                        <input type="radio" name="disponibilidad_editar_producto" value="0" required>
                        Próximamente
                    </label>
                    <br><br>
                    Imagen<br>
                    <label for="imagen_editar_producto" class="custum-file-upload">
                        <img src="img/<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" style="width: 100px;">
                        <input id="imagen_editar_producto" name="imagen_editar_producto" type="file" accept=".jpg, .jpeg, .png, .webp">
                    </label>
                    <br><br>
                    <div class="contenedor-padre">
                        <div class="contenedor-input">
                            <a href="admin.php"><input type="button" class="btn-cancelar" value="Cancelar"></a>
                        </div>
                        <div class="contenedor-input">
                            <input type="submit" class="btn" value="Guardar">
                        </div>
                    </div>
                </form>
            </div>
    <?php
        } else {
            echo "Producto no encontrado.";
            exit();
        }

        // Cerrar la conexión
        $stmt->close();
        $conexion->close();
    } else {
    ?>

    <div class="seccion">
        <h1>Insertar producto</h1>
    </div>
    <br><br><br>
    <div id="resultado"></div>
    <br><br><br>
    <div class="seccion">
        <form style="width: 100%;" id="formulario-insertar-productos" enctype="multipart/form-data">
            <label for="nombre_producto">Nombre</label><br>
            <input class="input" type="text" id="nombre_producto" name="nombre_producto" required>
            <br><br>
            <div class="contenedor-padre">
                <div class="contenedor-input">
                    <label for="precio_producto">Precio</label><br>
                    <input class="input" type="number" id="precio_producto" name="precio_producto" required>
                </div>
                <div class="contenedor-input">
                    <label for="cantidad_producto">Cantidad</label><br>
                    <input class="input" type="number" id="cantidad_producto" name="cantidad_producto" required>
                </div>
            </div>
            <br>
            Descripción<br>
            <textarea class="input" name="descripcion_producto" id="descripcion_producto" style="resize: none; height: 100px;"></textarea>
            <br><br>
            <label>
            <input type="radio" name="disponibilidad_producto" value="1" checked required>
                Disponible
            </label>
            <br><br>
            <label>
                <input type="radio" name="disponibilidad_producto" value="0" required>
                Próximamente
            </label>
            <br><br>
            Imagen<br>
            <label for="imagen_producto" class="custum-file-upload">
                <div class="icon">
                    <svg viewBox="0 0 24 24" fill="" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V9C19 9.55228 19.4477 10 20 10C20.5523 10 21 9.55228 21 9V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM14 15.5C14 14.1193 15.1193 13 16.5 13C17.8807 13 19 14.1193 19 15.5V16V17H20C21.1046 17 22 17.8954 22 19C22 20.1046 21.1046 21 20 21H13C11.8954 21 11 20.1046 11 19C11 17.8954 11.8954 17 13 17H14V16V15.5ZM16.5 11C14.142 11 12.2076 12.8136 12.0156 15.122C10.2825 15.5606 9 17.1305 9 19C9 21.2091 10.7909 23 13 23H20C22.2091 23 24 21.2091 24 19C24 17.1305 22.7175 15.5606 20.9844 15.122C20.7924 12.8136 18.858 11 16.5 11Z" fill=""></path> </g></svg>
                </div>
                <div class="text">
                    <span>Subir imagen</span>
                </div>
                <input id="imagen_producto" name="imagen_producto" type="file" accept=".jpg, .jpeg, .png, .webp" required>
            </label>
            <br><br>
            <div class="contenedor-padre">
                <div class="contenedor-input">
                    <a href="admin.php"><input type="button" class="btn-cancelar" value="Cancelar"></a>
                </div>
                <div class="contenedor-input">
                    <input type="submit" class="btn" value="Guardar">
                </div>
            </div>
        </form>
    </div>
    <?php } ?>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Envío del formulario de editar productos
    var formularioEditar = document.getElementById("formulario-editar-productos");
    if (formularioEditar) {
        formularioEditar.onsubmit = function(event) {
            event.preventDefault(); // Prevenir el envío del formulario normal

            var formData = new FormData(this); // Crear un objeto FormData con los datos del formulario

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "editar_producto.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var resultado = xhr.responseText;
                        if (resultado.includes("Los datos se han actualizado correctamente")) {
                            window.location.href = "admin.php";
                        } else {
                            document.getElementById("resultado").innerHTML = resultado;
                        }
                    } else {
                        alert('Hubo un problema al enviar los datos.');
                    }
                }
            };
            xhr.send(formData); // Enviar el objeto FormData que contiene los datos del formulario y la imagen
        };
    }

    // Envío del formulario de insertar productos
    var formularioInsertar = document.getElementById("formulario-insertar-productos");
    if (formularioInsertar) {
        formularioInsertar.onsubmit = function(event) {
            event.preventDefault(); // Prevenir el envío del formulario normal

            var formData = new FormData(this); // Crear un objeto FormData con los datos del formulario

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "insertar_producto.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var resultado = xhr.responseText;
                        if (resultado.includes("Los datos se han insertado correctamente")) {
                            window.location.href = "admin.php";
                        } else {
                            document.getElementById("resultado").innerHTML = resultado;
                        }
                    } else {
                        alert('Hubo un problema al enviar los datos.');
                    }
                }
            };
            xhr.send(formData); // Enviar el objeto FormData que contiene los datos del formulario y la imagen
        };
    }
});
</script>
<?php include 'plantillas/pie.html'; ?>