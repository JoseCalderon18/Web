function confirmarEliminacion(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarProducto(id);
        }
    });
}

// Función para eliminar producto
function eliminarProducto(id) {
    $.ajax({
        url: '../assets/php/MVC/Controlador/productos-controlador.php?accion=eliminar',
        method: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Producto eliminado correctamente',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Error al eliminar el producto'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error en el servidor'
            });
        }
    });
}

// Función para mostrar la galería de productos
function mostrarGaleria(foto, nombreProducto) {
    if (!foto) {
        Swal.fire({
            title: nombreProducto,
            text: 'No hay imagen disponible para este producto',
            confirmButtonText: 'Cerrar'
        });
        return;
    }

    Swal.fire({
        title: nombreProducto,
        imageUrl: '../' + foto,
        imageAlt: 'Foto de ' + nombreProducto,
        width: '80%',
        imageHeight: '70vh',
        confirmButtonText: 'Cerrar',
        confirmButtonColor: '#166534',
        showCloseButton: true,
        customClass: {
            popup: 'swal-large-image',
            image: 'swal-image-fit'
        }
    });
}

// Función para mostrar comentarios
function mostrarComentarios(nombreProducto, comentarios) {
    Swal.fire({
        title: `Comentarios de ${nombreProducto}`,
        text: comentarios,
        confirmButtonText: 'Cerrar'
    });
}

// Funcionalidad de búsqueda
document.addEventListener('DOMContentLoaded', function() {
    const barraBusqueda = document.getElementById('barraBusqueda');
    if (barraBusqueda) {
        barraBusqueda.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const filas = document.querySelectorAll('tbody tr');
            
            filas.forEach(fila => {
                const nombre = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                if (nombre.includes(searchTerm)) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });
    }
});

// Función para restar una unidad del stock
function restarUnidad(id, stockActual) {
    if (stockActual <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No hay stock disponible para restar'
        });
        return;
    }

    $.ajax({
        url: '../assets/php/MVC/Controlador/productos-controlador.php?accion=restarUnidad',
        method: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: response.message,
                    timer: 1000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la petición:", error);
            console.log("Respuesta del servidor:", xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error en el servidor'
            });
        }
    });
}

// Función para sumar una unidad al stock
function sumarUnidad(id) {
    $.ajax({
        url: '../assets/php/MVC/Controlador/productos-controlador.php?accion=sumarUnidad',
        method: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: response.message,
                    timer: 1000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la petición:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error en el servidor'
            });
        }
    });
}