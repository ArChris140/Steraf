<?php include 'plantillas/cabecera.php'; ?>
<?php
if (!isset($_SESSION['rol'])) {
    header('location: login.php');
    exit();
}
?>
<?php
// Verificar si el usuario está autenticado
if(isset($_SESSION['rol']) && isset($_SESSION['rol']['id'])) {
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
    if($resultado && mysqli_num_rows($resultado) > 0) {
        // Obtener los datos del usuario
        $usuario = mysqli_fetch_assoc($resultado);

        // Ahora puedes acceder a los datos del usuario, por ejemplo, su correo electrónico
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
    } else {
        echo "No se encontró al usuario en la base de datos";
    }
} else {
    exit();
}
?>

<link rel="stylesheet" href="usuarios.css">

<div class="container">
  <div class="sidebar">
    <div class="user">
      <h1><?php echo $nombre_usuario; ?></h1>
      <p><?php echo $correo_usuario; ?></p>
    </div>
    <input class="btn-radio" type="radio" id="perfilRadio" name="sidebar-option" onclick="mostrarPerfil()" checked>
    <label for="perfilRadio">Perfil</label>
    <br><br>
    <input class="btn-radio" type="radio" id="cambiarContrasenaRadio" name="sidebar-option" onclick="mostrarCambiarContrasena()">
    <label for="cambiarContrasenaRadio">Cambiar contraseña</label>
    <br><br>
    <a href="logout.php">Cerrar sesión</a>
  </div>

  <div class="content">
  <div id="perfilDiv" style="display: none;">
    <!-- Contenido de la sección Perfil -->
    <div class="contenedor-padre">
      <div class="contenedor-input perfil-1">
        <h2>Tu informacion</h2>
      </div>
      <div class="contenedor-input perfil-1">
        <button class="Btn-editar" id="btnEditarPerfil" onclick="CambiarPerfil()">Editar
          <svg class="svg" viewBox="0 0 512 512">
          <path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path></svg>
        </button>
      </div>
    </div>
    <div class="contenedor-padre">
      <div class="contenedor-input perfil-1">
        <div class="etiquetas">Nombre</div>
        <?php echo $nombre_usuario; ?>
      </div>
      <div class="contenedor-input perfil-1">
        <div class="etiquetas">Apellido</div>
        <?php echo $apellido_usuario; ?>
      </div>
    </div>
    <div class="perfil-1">
      <div class="etiquetas">Correo</div>
      <?php echo $correo_usuario; ?>
    </div>
    <div class="perfil-1">
      <div class="etiquetas">Telefono</div>
      <?php echo $telefono_usuario; ?>
    </div>
    <div class="perfil-1">
      <div class="etiquetas">Direccion</div>
      <?php echo $region_usuario." - ".$comuna_usuario." - ".$calle_usuario." - ".$numero_usuario; ?>
    </div>
  </div>

  <div id="cambiarContrasenaDiv" style="display: none;">
    <!-- Contenido de la sección Cambiar contraseña -->
    <h1>Cambiar Contraseña</h1><br>
    <form id="formulario-contraseña">
      <p id="resultado"></p><br>
      <input type="hidden" id="username" name="username" value=<?php echo ("$correo_usuario"); ?>>
        <label for="password">Nueva contraseña</label>
        <br><br>
        <input class="input" type="password" id="password" name="password" required><br><br>
        <label for="confirmar_password">Confirmar contraseña</label>
        <br><br>
        <input class="input" type="password" id="confirmar_password" name="confirmar_password" required><br>
      <br>
      <input class="btn" type="submit" value="Cambiar contraseña">
    </form>
  </div>

  <div id="editarPerfilDiv" style="display: none;">
    <!-- Contenido de la sección Editar perfil -->
    <h1>Editar perfil</h1><br>
    <form id="formulario-perfil">
      <div class="contenedor-padre">
        <div class="contenedor-input">
          <label for="nuevo_nombre">Nombre</label><br>
          <input type="hidden" id="id" name="id" value=<?php echo ("$id_usuario"); ?>>
          <input class="input" type="text" id="nuevo_nombre" name="nuevo_nombre" value="<?php echo $nombre_usuario; ?>">
        </div>
        <div class="contenedor-input">
          <label for="nuevo_apellido">Apellido</label><br>
          <input class="input" type="text" id="nuevo_apellido" name="nuevo_apellido" value="<?php echo $apellido_usuario; ?>">
        </div>
      </div>
      <br>
      <label for="nuevo_telefono">Telefono</label><br>
      <input class="input" type="number" id="nuevo_telefono" name="nuevo_telefono" value="<?php echo $telefono_usuario; ?>">
      <br><br>
      <div class="contenedor-padre">
        <div class="contenedor-input">
          <label for="nueva_region">Region</label><br>
          <input class="input" type="text" id="nueva_region" name="nueva_region" value="<?php echo $region_usuario; ?>">
        </div>
        <div class="contenedor-input">
          <label for="nueva_comuna">Comuna</label><br>
          <input class="input" type="text" id="nueva_comuna" name="nueva_comuna" value="<?php echo $comuna_usuario; ?>">
        </div>
      </div>
      <br>
      <div class="contenedor-padre">
        <div class="contenedor-input">
          <label for="nueva_calle">Calle</label><br>
          <input class="input" type="text" id="nueva_calle" name="nueva_calle" value="<?php echo $calle_usuario; ?>">
        </div>
        <div class="contenedor-input">
          <label for="nuevo_numero">Número de casa/departamento</label><br>
          <input class="input" type="text" id="nuevo_numero" name="nuevo_numero" value="<?php echo $numero_usuario; ?>">
        </div>
      </div>
      <br><br>
      <label for="nueva_informacion">Informacion adicional</label><br>
      <input class="input" type="text" id="nueva_informacion" name="nueva_informacion" value="<?php echo $info_adicional_usuario; ?>">
      <br><br>
      <div class="contenedor-padre">
        <div class="contenedor-input">
          <input type="button" class="btn-cancelar" value="Cancelar" onclick="mostrarPerfil()">
        </div>
        <div class="contenedor-input">
          <input type="submit" class="btn" value="Guardar">
        </div>
      </div>
    </form>
  </div>
</div>
</div>

<?php include 'js/usuarios_js.php'; ?>

<?php include 'plantillas/pie.html'; ?>