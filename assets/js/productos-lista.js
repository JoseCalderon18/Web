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
            // Crear y enviar un formulario para eliminar el producto
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../assets/php/MVC/Controlador/productos-controlador.php?accion=eliminar';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id';
            input.value = id;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
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
        width: '60%',
        imageWidth: 'auto',
        imageHeight: '50vh',
        confirmButtonText: 'Cerrar',
        confirmButtonColor: '#166534'
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
    // Verificar que haya stock disponible
    if (stockActual <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No hay stock disponible para restar',
            confirmButtonColor: '#4A6D50'
        });
        return;
    }
    
    Swal.fire({
        title: '¿Restar una unidad?',
        text: "Se reducirá el stock en una unidad",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, restar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar indicador de carga
            Swal.fire({
                title: 'Procesando...',
                text: 'Actualizando stock',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Enviar solicitud para restar unidad
            $.ajax({
                url: '../assets/php/MVC/Controlador/productos-controlador.php?accion=restarUnidad',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: data.message,
                                confirmButtonColor: '#4A6D50'
                            }).then(() => {
                                // Recargar la página para ver los cambios
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message,
                                confirmButtonColor: '#4A6D50'
                            });
                        }
                    } catch (e) {
                        console.error('Error al parsear la respuesta:', e);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al procesar la respuesta del servidor',
                            confirmButtonColor: '#4A6D50'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al conectar con el servidor',
                        confirmButtonColor: '#4A6D50'
                    });
                }
            });
        }
    });
}

// Función para sumar una unidad al stock
function sumarUnidad(id) {
    Swal.fire({
        title: '¿Sumar una unidad?',
        text: "Se aumentará el stock en una unidad",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, sumar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar indicador de carga
            Swal.fire({
                title: 'Procesando...',
                text: 'Actualizando stock',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Enviar solicitud para sumar unidad
            $.ajax({
                url: '../assets/php/MVC/Controlador/productos-controlador.php?accion=sumarUnidad',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: data.message,
                                confirmButtonColor: '#4A6D50'
                            }).then(() => {
                                // Recargar la página para ver los cambios
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message,
                                confirmButtonColor: '#4A6D50'
                            });
                        }
                    } catch (e) {
                        console.error('Error al parsear la respuesta:', e);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al procesar la respuesta del servidor',
                            confirmButtonColor: '#4A6D50'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al conectar con el servidor',
                        confirmButtonColor: '#4A6D50'
                    });
                }
            });
        }
    });
} 