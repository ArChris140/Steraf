<?php
include 'global/conexion.php';
session_start();

if(isset($_POST['btnAccion'])) {
    if($_POST['btnAccion'] == 'Agregar') {
        // Lógica para agregar productos al carrito
        $id = $_POST['id'];
        $imagen = $_POST['imagen'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];

        // Verificar si ya existe la variable de sesión 'CARRITO'
        if(isset($_SESSION['CARRITO'])) {
            $carrito = $_SESSION['CARRITO'];
        } else {
            $carrito = array();
        }

        // Verificar si el producto ya está en el carrito
        $producto_existente = false;
        foreach ($carrito as $key => $producto) {
            if($producto['id'] == $id) {
                // Incrementar la cantidad si el producto ya está en el carrito
                $carrito[$key]['cantidad'] += $cantidad;
                $producto_existente = true;
                break;
            }
        }

        // Si el producto no está en el carrito, agregarlo
        if(!$producto_existente) {
            $nuevo_producto = array(
                'id' => $id,
                'imagen' => $imagen,
                'nombre' => $nombre,
                'precio' => $precio,
                'cantidad' => $cantidad
            );
            $carrito[] = $nuevo_producto;
        }

        // Actualizar la variable de sesión 'CARRITO'
        $_SESSION['CARRITO'] = $carrito;

        // Contar la cantidad total de productos en el carrito
        $total_productos = count($carrito);

        // Devolver la cantidad total de productos en el carrito como respuesta AJAX
        echo $total_productos;
    } elseif ($_POST['btnAccion'] == 'Eliminar') {
        // Lógica para eliminar productos del carrito
        $id = $_POST['id'];

        // Verificar si la variable de sesión 'CARRITO' existe
        if(isset($_SESSION['CARRITO'])) {
            $carrito = $_SESSION['CARRITO'];

            // Recorrer el carrito para encontrar y eliminar el producto
            foreach ($carrito as $key => $producto) {
                if($producto['id'] == $id) {
                    unset($carrito[$key]); // Eliminar el producto del carrito
                    break;
                }
            }

            // Actualizar la variable de sesión 'CARRITO'
            $_SESSION['CARRITO'] = array_values($carrito); // Reindexar el array

            // Devolver una respuesta para indicar que el producto ha sido eliminado
            echo "Producto eliminado del carrito.";
        }
    } elseif ($_POST['btnAccion'] == 'Sumar') {
        // Lógica para sumar la cantidad de productos del carrito
        $id = $_POST['id'];
        foreach ($_SESSION['CARRITO'] as $indice => $producto) {
            if ($producto['id'] == $id) {
                $consulta = "SELECT cantidad FROM productos WHERE id = ?";
                $sentencia = $conexion->prepare($consulta);
                $sentencia->bind_param("i", $id);
                $sentencia->execute();
                $resultado = $sentencia->get_result();
                $fila = $resultado->fetch_assoc();
                $cantidad_producto_en_base_datos = $fila['cantidad'];
                if ($producto['cantidad'] >= $cantidad_producto_en_base_datos) {
                    echo "<script>alert('Producto agotado...');</script>";
                } else {
                    $_SESSION['CARRITO'][$indice]['cantidad'] += 1;
                }
            }
        }
    } elseif ($_POST['btnAccion'] == 'Restar') {
        // Lógica para restar la cantidad de productos del carrito
        $id = $_POST['id'];
        foreach($_SESSION['CARRITO'] as $indice => $producto){
            if($producto['id'] == $id){
                // Reducir la cantidad en 1
                $_SESSION['CARRITO'][$indice]['cantidad'] -= 1;
                
                // Verificar si la cantidad es menor o igual a 0
                if($_SESSION['CARRITO'][$indice]['cantidad'] <= 0){
                    // Eliminar el producto del carrito en sessionStorage
                    $eliminar_producto = true;
                    unset($_SESSION['CARRITO'][$indice]);
                }
                break; // Terminar el bucle después de encontrar el producto
            }
        }
        
        // Si se debe eliminar el producto del carrito en sessionStorage
        if($eliminar_producto) {
            echo 'eliminar_producto'; // Esto se utilizará en el lado del cliente para ejecutar la lógica de eliminación
        }
    }
    
}
?>
