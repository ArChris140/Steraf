<?php include 'plantillas/cabecera.php'; ?>
<?php
    include 'global/conexion.php';
    $error = "";
    
    if(isset($_GET['cerrar_sesion'])){
        session_unset();
        session_destroy();
    }
    
    if(isset($_SESSION['rol'])){
        switch($_SESSION['rol']){
            case 1:
                header('location: index.php');
                break;
    
            case 2:
                header('location: index.php');
                break;
            default:
        }
    }
    
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Convertir el correo electrónico a minúsculas
        $username = strtolower($username);
    
        // Preparar la consulta SQL para obtener el usuario por su nombre de usuario
        $sql = "SELECT * FROM usuarios WHERE username = '$username'";
        $resultado = mysqli_query($conexion, $sql);
        
        if(mysqli_num_rows($resultado) > 0) {
            $row = mysqli_fetch_assoc($resultado);
            // Verificar si la contraseña coincide
            if(password_verify($password, $row['password'])) {
                // Almacena tanto el rol_id como la id del usuario en un array asociativo
                $usuario = array(
                    'id' => $row['id'],       // Suponiendo que la id del usuario está en la columna 'id'
                    'rol_id' => $row['rol_id']// Suponiendo que el rol_id está en la columna 'rol_id'
                );
                $_SESSION['rol'] = $usuario;
    
                // Redirige según el rol del usuario
                switch($_SESSION['rol']['rol_id']){
                    case 1:
                        header('location: index.php');
                        break;
    
                    case 2:
                        header('location: index.php');
                        break;
                    default:
                }
            } else {
                $error = "El usuario o contraseña son incorrectos";
            }
        } else {
            $error = "El usuario o contraseña son incorrectos";
        }
    }    
    
?>

<link rel="stylesheet" href="login.css">
<div class="container">
    <div class="welcome"><h1>Bienvenido</h1></div>
    <div class="form-login">
        <form class="form" method="post">
            <p id="heading">Inicia sesión</p>
            <p id="mensaje-error"> <?php echo ("$error"); ?> </p>
            <div class="field">
            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.93 0 1.524.738 1.524 1.907S8.308 9.84 7.371 9.84c-.895 0-1.442-.725-1.442-1.914z"></path>
            </svg>
            <input autocomplete="off" placeholder="Correo" class="input-field" type="email" id="email" name="username" required>
            </div>
            <div class="field">
            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path>
            </svg>
            <input placeholder="Contraseña" class="input-field" type="password" id="password" name="password" required>
            </div>
            <div class="btn">
            <button class="button1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ingresar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
            <a href="signup.php" class="button2">Registrarce</a>
            </div>
            <a href="forgot_password.php" class="button3">Recuperar contraseña</a>
        </form>
    </div>
</div>

<?php include 'plantillas/pie.html'; ?>