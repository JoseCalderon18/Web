$(document).ready(function() {
    console.log('Productos.js inicializado');
    
    // Funcionalidad para la lista de productos
    configurarListaProductos();
    
    // Funcionalidad para el formulario de productos (crear/editar)
    configurarFormularioProductos();

    // Funcionalidad para el formulario de noticias
    if ($('#newsForm').length > 0) {
        // Vista previa de imagen
        $('#imagen').on('change', function() {
            const archivo = this.files[0];
            const vistaPrevia = $('#preview');
            
            if (archivo) {
                const lector = new FileReader();
                lector.onload = function(e) {
                    vistaPrevia.attr('src', e.target.result).removeClass('hidden');
                }
                lector.readAsDataURL(archivo);
            } else {
                vistaPrevia.addClass('hidden').attr('src', '');
            }
        });
        
        // Envío del formulario de noticia
        $('#newsForm').on('submit', function(e) {
            e.preventDefault();
            
            // Crear FormData para enviar archivos
            const datosFormulario = new FormData(this);
            
            // Verificar si es edición o creación
            const esEdicion = datosFormulario.has('id') && datosFormulario.get('id') !== '';
            
            // URL de la acción
            const url = '../assets/php/MVC/Controlador/noticias-controlador.php?accion=' + 
                        (esEdicion ? 'actualizarNoticia' : 'crearNoticia');
            
            // Mostrar indicador de carga
            Swal.fire({
                title: 'Procesando...',
                text: 'Espera mientras procesamos tu solicitud',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Enviar formulario
            $.ajax({
                url: url,
                type: 'POST',
                data: datosFormulario,
                processData: false,
                contentType: false,
                success: function(respuesta) {
                    try {
                        const datos = JSON.parse(respuesta);
                        
                        if (datos.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: datos.message,
                                confirmButtonColor: '#4A6D50'
                            }).then(() => {
                                window.location.href = 'noticias.php';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: datos.message,
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
        });
    }
});

function configurarListaProductos() {
    // Código para la lista de productos
    console.log('Configurando lista de productos');
    
    // Confirmar eliminación
    $('.eliminar-producto').on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas eliminar el producto "${nombre}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((resultado) => {
            if (resultado.isConfirmed) {
                // Enviar formulario de eliminación
                $(`#eliminar-form-${id}`).submit();
            }
        });
    });
}

function configurarFormularioProductos() {
    if ($('#productoForm').length > 0) {
        console.log('Formulario de productos encontrado');
        
        // Vista previa de imagen
        $('#foto').on('change', function() {
            const archivo = this.files[0];
            let vistaPrevia = $('#preview');
            
            // Si no existe el elemento preview, crearlo
            if (vistaPrevia.length === 0) {
                // Buscar donde insertar la vista previa
                const contenedorFoto = $('#foto').closest('.space-y-1, .mb-4, .form-group');
                if (contenedorFoto.length > 0) {
                    contenedorFoto.append(`
                        <div class="mt-2">
                            <img id="preview" class="hidden w-32 h-32 object-cover rounded-lg border-2 border-gray-300" alt="Vista previa">
                        </div>
                    `);
                    vistaPrevia = $('#preview');
                }
            }
            
            if (archivo) {
                // Validar que sea una imagen
                if (archivo.type.startsWith('image/')) {
                    const lector = new FileReader();
                    lector.onload = function(e) {
                        vistaPrevia.attr('src', e.target.result).removeClass('hidden');
                    }
                    lector.readAsDataURL(archivo);
                } else {
                    alert('Por favor selecciona un archivo de imagen válido');
                    $(this).val('');
                    vistaPrevia.addClass('hidden').attr('src', '');
                }
            } else {
                vistaPrevia.addClass('hidden').attr('src', '');
            }
        });
        
        // Envío del formulario
        $('#productoForm').on('submit', function(e) {
            e.preventDefault();
            console.log('Formulario enviado');
            
            // Crear FormData para enviar archivos
            const datosFormulario = new FormData(this);
            
            // Verificar si es edición o creación
            const esEdicion = datosFormulario.has('id') && datosFormulario.get('id') !== '';
            console.log('Es edición:', esEdicion);
            
            // Determinar la acción
            const accion = esEdicion ? 'editar' : 'crear';
            console.log('Acción:', accion);
            
            // URL de la acción
            const url = '../assets/php/MVC/Controlador/productos-controlador.php?accion=' + accion;
            
            // Mostrar indicador de carga
            Swal.fire({
                title: 'Procesando...',
                text: 'Espera mientras procesamos tu solicitud',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Enviar formulario
            $.ajax({
                url: url,
                type: 'POST',
                data: datosFormulario,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(respuesta) {
                    console.log('Respuesta del servidor:', respuesta);
                    
                    if (respuesta.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: respuesta.message,
                            confirmButtonColor: '#4A6D50'
                        }).then(() => {
                            window.location.href = 'productos.php';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: respuesta.message,
                            confirmButtonColor: '#4A6D50'
                        });
                    }
                },
                error: function(xhr, estado, error) {
                    console.error('Error en AJAX:', error);
                    console.error('Respuesta completa:', xhr.responseText);
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error en la comunicación con el servidor',
                        confirmButtonColor: '#4A6D50'
                    });
                }
            });
        });
    } else {
        console.log('No estamos en la página del formulario de productos');
    }
}

// Definir la función globalmente
function actualizarStock(idProducto, operacion) {
    // Determinar la acción correcta según la operación
    const accion = operacion === 'sumar' ? 'sumarUnidad' : 'restarUnidad';
    
    $.ajax({
        url: '../assets/php/MVC/Controlador/productos-controlador.php?accion=' + accion,
        method: 'POST',
        data: {
            id: idProducto
        },
        dataType: 'json',
        success: function(respuesta) {
            console.log('Respuesta del servidor:', respuesta);
            
            if (respuesta.success) {
                // Buscar el botón que contiene el idProducto para encontrar la fila
                const botonSumar = document.querySelector(`button[onclick="sumarUnidad(${idProducto})"]`);
                if (botonSumar) {
                    // Encontrar el span del stock en la misma fila
                    const elementoStock = botonSumar.parentElement.querySelector('span.font-medium');
                    if (elementoStock) {
                        const stockActual = parseInt(elementoStock.textContent);
                        const nuevoStock = operacion === 'sumar' ? stockActual + 1 : stockActual - 1;
                        elementoStock.textContent = nuevoStock;
                        
                        // Actualizar el estado del botón de restar
                        const botonRestar = botonSumar.parentElement.querySelector(`button[onclick*="restarUnidad(${idProducto}"]`);
                        if (botonRestar) {
                            if (nuevoStock <= 0) {
                                botonRestar.disabled = true;
                            } else {
                                botonRestar.disabled = false;
                            }
                            // Actualizar el onclick del botón restar con el nuevo stock
                            botonRestar.setAttribute('onclick', `restarUnidad(${idProducto}, ${nuevoStock})`);
                        }
                    }
                }
                
                Swal.fire({
                    icon: 'success',
                    title: 'Stock actualizado',
                    showConfirmButton: false,
                    timer: 1000
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: respuesta.message || 'Error al actualizar el stock'
                });
            }
        },
        error: function(xhr, estado, error) {
            console.error('Error AJAX:', error);
            console.error('Estado:', estado);
            console.error('Respuesta XHR completa:', xhr.responseText);
            console.error('Status code:', xhr.status);
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error en la comunicación con el servidor'
            });
        }
    });
}

// Código que se ejecuta cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('JavaScript cargado correctamente');
});

function modificarStock(operacion) {
    const campoStock = document.getElementById('stock');
    let valor = parseInt(campoStock.value) || 0;
    
    if (operacion === 'sumar') {
        valor++;
    } else if (operacion === 'restar' && valor > 0) {
        valor--;
    }
    
    campoStock.value = valor;
}
