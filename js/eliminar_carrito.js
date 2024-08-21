$('.btn-eliminar').click(function(e){
    e.preventDefault();
    var id = $(this).closest('form').find('#id').val();

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

    // Realizar la solicitud AJAX para eliminar el producto del carrito
    $.ajax({
        url: 'carrito.php',
        method: 'POST',
        data: {
            id: id,
            btnAccion: 'Eliminar'
        },
        success: function(response){
            // Recargar la página
            location.reload();
        }
    });
});