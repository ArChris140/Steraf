<script>
// Función para mostrar la sección de perfil
function mostrarPerfil() {
  document.getElementById('perfilDiv').style.display = 'block';
  document.getElementById('cambiarContrasenaDiv').style.display = 'none';
  document.getElementById('editarPerfilDiv').style.display = 'none';
}

// Función para mostrar la sección de cambio de contraseña
function mostrarCambiarContrasena() {
  limpiarFormularioContrasena(); // Restablece los valores del formulario
  document.getElementById('perfilDiv').style.display = 'none';
  document.getElementById('cambiarContrasenaDiv').style.display = 'block';
  document.getElementById('editarPerfilDiv').style.display = 'none';
}

// Función para limpiar los valores del formulario de cambio de contraseña
function limpiarFormularioContrasena() {
  document.getElementById('password').value = '';
  document.getElementById('confirmar_password').value = '';
  document.getElementById('resultado').innerHTML = '';
}

// Función para mostrar la sección de editar perfil
function CambiarPerfil() {
  limpiarFormularioPerfil(); // Restablece los valores del formulario
  document.getElementById('perfilDiv').style.display = 'none';
  document.getElementById('cambiarContrasenaDiv').style.display = 'none';
  document.getElementById('editarPerfilDiv').style.display = 'block';
}

// Función para limpiar los valores del formulario de editar perfil
function limpiarFormularioPerfil() {
  document.getElementById('nuevo_nombre').value = '<?php echo $nombre_usuario; ?>';
  document.getElementById('nuevo_apellido').value = '<?php echo $apellido_usuario; ?>';
  document.getElementById('nuevo_telefono').value = '<?php echo $telefono_usuario; ?>';
  document.getElementById('nueva_region').value = '<?php echo $region_usuario; ?>';
  document.getElementById('nueva_comuna').value = '<?php echo $comuna_usuario; ?>';
  document.getElementById('nueva_calle').value = '<?php echo $calle_usuario; ?>';
  document.getElementById('nuevo_numero').value = '<?php echo $numero_usuario; ?>';
  document.getElementById('nueva_informacion').value = '<?php echo $info_adicional_usuario; ?>';
}

//envio del formulario de contraseña
document.getElementById("formulario-contraseña").onsubmit = function(event) {
  event.preventDefault(); // Prevenir el envío del formulario normal

  var correo = document.getElementById("username").value;
  var password = document.getElementById("password").value;
  var confirmar_password = document.getElementById("confirmar_password").value;

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "actualizar_contrasena.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
              document.getElementById("resultado").innerHTML = xhr.responseText;
          } else {
              alert('Hubo un problema al enviar los datos.');
          }
      }
  };
  xhr.send("username=" + encodeURIComponent(correo) + "&password=" + encodeURIComponent(password) + "&confirmar_password=" + encodeURIComponent(confirmar_password));
};

// Envío del formulario del perfil
document.getElementById("formulario-perfil").onsubmit = function(event) {
  event.preventDefault(); // Prevenir el envío del formulario normal

  var id = document.getElementById("id").value;
  var nombre = document.getElementById("nuevo_nombre").value;
  var apellido = document.getElementById("nuevo_apellido").value;
  var telefono = document.getElementById("nuevo_telefono").value;
  var region = document.getElementById("nueva_region").value;
  var comuna = document.getElementById("nueva_comuna").value;
  var calle = document.getElementById("nueva_calle").value;
  var numero = document.getElementById("nuevo_numero").value;
  var info_adicional = document.getElementById("nueva_informacion").value;

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "info_usuario.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
              
              location.reload();

            } else {
              alert('Hubo un problema al enviar los datos.');
          }
      }
  };
  // Codificar los datos para enviarlos al servidor
  var datos = "id=" + encodeURIComponent(id) +
              "&nuevo_nombre=" + encodeURIComponent(nombre) + 
              "&nuevo_apellido=" + encodeURIComponent(apellido) + 
              "&nuevo_telefono=" + encodeURIComponent(telefono) + 
              "&nueva_region=" + encodeURIComponent(region) + 
              "&nueva_comuna=" + encodeURIComponent(comuna) + 
              "&nueva_calle=" + encodeURIComponent(calle) + 
              "&nuevo_numero=" + encodeURIComponent(numero) + 
              "&nueva_informacion=" + encodeURIComponent(info_adicional);
  xhr.send(datos);
};

// Evento de carga de la ventana para mostrar la sección de perfil por defecto
window.onload = function() {
  mostrarPerfil();
};
</script>