document.addEventListener('DOMContentLoaded', function() {
    var botonesItems = document.querySelectorAll('.boton-item');
    botonesItems.forEach(function(boton) {
        boton.addEventListener('click', function(e) {
            e.preventDefault();

            var form = boton.closest('form');
            var cantidadInput = form.querySelector('#cantidad');
            var cantidad = parseInt(cantidadInput.value);
            var cantidadDisponible = parseInt(boton.getAttribute('data-cantidad-disponible'));

            if (cantidad <= 0 || isNaN(cantidad)) {
                alert("La cantidad debe ser un número mayor que 0");
                return;
            }

            if (cantidad > cantidadDisponible) {
                alert("La cantidad seleccionada supera el stock disponible");
                return;
            }

            var id = form.querySelector('#id').value;
            var imagen = form.querySelector('#imagen').value;
            var nombre = form.querySelector('#nombre').value;
            var precio = form.querySelector('#precio').value;

            var carrito = sessionStorage.getItem('CARRITO');
            if (carrito) {
                carrito = JSON.parse(carrito);
                var encontrado = carrito.some(function(item) {
                    return item.id === id;
                });
                if (encontrado) {
                    alert('Este producto ya ha sido seleccionado.');
                    return;
                }
            } else {
                carrito = [];
            }

            var producto = {
                id: id,
                imagen: imagen,
                nombre: nombre,
                precio: precio,
                cantidad: cantidad
            };
            carrito.push(producto);
            sessionStorage.setItem('CARRITO', JSON.stringify(carrito));

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'carrito.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = xhr.responseText;
                    document.getElementById('cantidad-carrito').textContent = response;
                    location.reload(); // Recargar la página después de actualizar el carrito
                }
            };
            var params = 'id=' + id + '&imagen=' + imagen + '&nombre=' + nombre + '&precio=' + precio + '&cantidad=' + cantidad + '&btnAccion=Agregar';
            xhr.send(params);
        });
    });
});
