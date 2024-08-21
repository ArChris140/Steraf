$(document).ready(function(){
    $('.boton-item').click(function(e){
        e.preventDefault();
        var id = $(this).closest('form').find('#id').val();
        var imagen = $(this).closest('form').find('#imagen').val();
        var nombre = $(this).closest('form').find('#nombre').val();
        var precio = $(this).closest('form').find('#precio').val();
        var cantidad = $(this).closest('form').find('#cantidad').val();
        
        // Verificar si el producto ya está en el carrito
        var carrito = sessionStorage.getItem('CARRITO');
        if(carrito) {
            carrito = JSON.parse(carrito);
            var encontrado = false;
            for(var i = 0; i < carrito.length; i++) {
                if(carrito[i].id == id) {
                    encontrado = true;
                    break;
                }
            }
            if(encontrado) {
                alert('Este producto ya ha sido seleccionado.');
                return; // Detener la ejecución si el producto ya está en el carrito
            }
        } else {
            carrito = [];
        }
        
        // Agregar el producto al carrito
        var producto = {
            id: id,
            imagen: imagen,
            nombre: nombre,
            precio: precio,
            cantidad: cantidad
        };
        carrito.push(producto);
        sessionStorage.setItem('CARRITO', JSON.stringify(carrito));
        
        // Realizar la solicitud AJAX
        $.ajax({
            url: 'carrito.php',
            method: 'POST',
            data: {
                id: id,
                imagen: imagen,
                nombre: nombre,
                precio: precio,
                cantidad: cantidad,
                btnAccion: 'Agregar'
            },
            success: function(response){
                // Actualizar cantidad del carrito
                $('#cantidad-carrito').text('' + response);
                location.reload();
            }
        });
    });
});
