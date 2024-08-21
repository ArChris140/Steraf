<?php include 'carrito.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="plantillas/cabecera.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <title>STERAF</title>
</head>
<body>
    <header>
        <a href="index.php"><img src="img/steraf.jpeg" alt="" style="height: 60px; width: 60px;"></a>
        <input type="checkbox" id="nav_check" hidden>
        <nav>
            <ul>
                <li>
                    <a href="mostrarCarrito.php"><img src="img/carrito.png" alt="" style="height: 30px; width: 30px;"><span id="cantidad-carrito"><?php echo (empty($_SESSION['CARRITO']))?0:count($_SESSION['CARRITO']); ?></span></a>
                </li>
                <li>
                    <a href="catalogo.php"><h3>Tienda</h3></a>
                </li>
                <li>
                    <a href="servicios.php"><h3>Servicios</h3></a>
                </li>
                <li>
                    <a href="ComoComprar.php"><h3>¿Cómo comprar?</h3></a>
                </li>
                
                <?php
                if (isset($_SESSION['rol']) && $_SESSION['rol']['rol_id'] == 1) { ?>
                    <li>
                        <a href="admin.php"><h3>Admin</h3></a>
                    </li>
                <?php } ?>  
                                               
                <li>
                    <?php if(isset($_SESSION['rol'])): ?>
                        <a href="usuarios.php">
                            <button id="btn_login">
                                <span class="btn_login">
                                    sesión
                                </span>
                            </button>
                        </a>
                    <?php else: ?>
                        <a href="login.php">
                            <button id="btn_login">
                                <span class="btn_login">
                                    sesión
                                </span>
                            </button>
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>
        <label for="nav_check" class="hamburger">
            <div></div>
            <div></div>
            <div></div>
        </label>
    </header>