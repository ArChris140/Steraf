$(document).ready(function(){
    $('.btn-operacion').click(function(e){
        e.preventDefault();
        var id = $(this).closest('form').find('#id').val();
        var accion = $(this).val(); // Obtener el valor del botón (Restar o Sumar)
        
        $.ajax({
            url: 'carrito.php',
            method: 'POST',
            data: {
                id: id,
                btnAccion: accion
            },
            success: function(response){
                if(response.includes('Producto agotado')) {
                    // Si la respuesta incluye 'Producto agotado', mostrar mensaje en el div
                    $('#mensaje').html(response);
                } else if(response === 'eliminar_producto') {
                    // Eliminar el producto del carrito en sessionStorage antes de enviar la solicitud AJAX
                    var carrito = sessionStorage.getItem('CARRITO');
                    if(carrito) {
                        carrito = JSON.parse(carrito);
                        // Filtrar el carrito para eliminar el producto con el ID correspondiente
                        carrito = carrito.filter(function(producto){
                            return producto.id !== id;
                        });
                        // Actualizar sessionStorage con el nuevo carrito
                        sessionStorage.setItem('CARRITO', JSON.stringify(carrito));
                    }
                    location.reload();
                } else {
                    // Si no, recargar la página
                    location.reload();
                }
            }
        });
    });
});
