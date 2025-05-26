$(document).ready(function() {
    console.log('Productos.js inicializado');
    
    // Funcionalidad para la lista de productos
    setupProductosLista();
    
    // Funcionalidad para el formulario de productos (crear/editar)
    setupProductosForm();

    // Funcionalidad para el formulario de noticias
    if ($('#newsForm').length > 0) {
        // Vista previa de imagen
        $('#imagen').on('change', function() {
            const file = this.files[0];
            const preview = $('#preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result).removeClass('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                preview.addClass('hidden').attr('src', '');
            }
        });
        
        // Envío del formulario de noticia
        $('#newsForm').on('submit', function(e) {
            e.preventDefault();
            
            // Crear FormData para enviar archivos
            const formData = new FormData(this);
            
            // Verificar si es edición o creación
            const isEditing = formData.has('id') && formData.get('id') !== '';
            
            // URL de la acción
            const url = '../assets/php/MVC/Controlador/noticias-controlador.php?accion=' + 
                        (isEditing ? 'actualizarNoticia' : 'crearNoticia');
            
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
                data: formData,
                processData: false,
                contentType: false,
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
                                window.location.href = 'noticias.php';
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
        });
    }
});

function setupProductosLista() {
    // Código para la lista de productos
    console.log('Configurando lista de productos');
    
    // Mostrar galería de fotos
    $('.ver-foto').on('click', function() {
        const foto = $(this).data('foto');
        const nombre = $(this).data('nombre');
        mostrarGaleria(foto, nombre);
    });
    
    // Mostrar comentarios
    $('.ver-comentarios').on('click', function() {
        const comentarios = $(this).data('comentarios');
        const nombre = $(this).data('nombre');
        mostrarComentarios(nombre, comentarios);
    });
    
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
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar formulario de eliminación
                $(`#eliminar-form-${id}`).submit();
            }
        });
    });
}

function setupProductosForm() {
    // Verificar si estamos en la página del formulario
    const productoForm = $('#productoForm');
    if (productoForm.length === 0) {
        console.log('No estamos en la página del formulario de productos');
        return; // No estamos en la página del formulario
    }
    
    console.log('Formulario de productos encontrado');
    
    // Previsualización de imagen
    function mostrarVistaPrevia(input) {
        const previewContainer = $('#preview-container');
        const previewImg = $('#preview-image');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.attr('src', e.target.result);
                previewContainer.removeClass('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            // Si no hay archivo seleccionado y no estamos en edición, ocultar la previsualización
            if ($('input[name="foto_actual"]').length === 0) {
                previewContainer.addClass('hidden');
            }
        }
    }
    
    // Crear contenedor de previsualización si no existe
    if ($('#preview-container').length === 0) {
        const previewContainer = $('<div id="preview-container" class="mt-4 hidden"></div>');
        previewContainer.append('<h4 class="text-sm font-medium text-gray-900 mb-2">Vista previa de la nueva imagen:</h4>');
        
        const imageContainer = $('<div class="relative inline-block"></div>');
        imageContainer.append('<img id="preview-image" class="w-32 h-32 object-cover rounded-lg">');
        imageContainer.append('<button type="button" id="remove-preview" class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 m-1 hover:bg-red-600"><i class="fas fa-times"></i></button>');
        
        previewContainer.append(imageContainer);
        previewContainer.insertAfter('#foto');
    }
    
    // Eliminar previsualización
    $(document).on('click', '#remove-preview', function() {
        $('#preview-container').addClass('hidden');
        $('#foto').val('');
    });
    
    // Manejar cambio en el input de foto
    $('#foto').on('change', function() {
        mostrarVistaPrevia(this);
    });
    
    // Manejar envío del formulario
    productoForm.on('submit', function(e) {
        e.preventDefault();
        console.log('Formulario enviado');
        
        // Validación básica
        const nombre = $('#nombre').val().trim();
        const stock = $('#stock').val().trim();
        const precio = $('#precio').val().trim();
        
        if (!nombre || !stock || !precio) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor completa todos los campos obligatorios'
            });
            return;
        }
        
        // Crear FormData
        const formData = new FormData(this);
        
        // Determinar acción (crear o editar)
        const isEditing = $('input[name="id"]').length > 0 && $('input[name="id"]').val() !== '';
        const action = isEditing ? 'editar' : 'crear';
        
        console.log('Acción:', action);
        
        // Mostrar loading
        Swal.fire({
            title: 'Procesando...',
            text: 'Por favor espera',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Enviar formulario
        $.ajax({
            url: '../assets/php/MVC/Controlador/productos-controlador.php?accion=' + action,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Respuesta del servidor:', response);
                try {
                    // Intentar extraer solo la parte JSON de la respuesta
                    const jsonStartPos = response.indexOf('{');
                    const jsonEndPos = response.lastIndexOf('}') + 1;
                    
                    if (jsonStartPos >= 0 && jsonEndPos > jsonStartPos) {
                        const jsonStr = response.substring(jsonStartPos, jsonEndPos);
                        const result = JSON.parse(jsonStr);
                        
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: result.message
                            }).then(() => {
                                window.location.href = 'productos.php';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Ocurrió un error al procesar la solicitud'
                            });
                        }
                    } else {
                        throw new Error('No se encontró JSON válido en la respuesta');
                    }
                } catch (e) {
                    console.error('Error al procesar la respuesta:', e);
                    console.error('Respuesta cruda:', response);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la respuesta del servidor'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', error);
                console.error('Estado:', status);
                console.error('Respuesta XHR:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al enviar los datos'
                });
            }
        });
    });
}

// Función para mostrar galería de fotos
function mostrarGaleria(foto, nombre) {
    if (!foto) {
        Swal.fire({
            title: nombre,
            text: 'No hay imagen disponible para este producto',
            confirmButtonText: 'Cerrar'
        });
        return;
    }

    Swal.fire({
        title: nombre,
        imageUrl: '../' + foto,
        imageAlt: 'Foto de ' + nombre,
        width: '250px',
        imageWidth: '200px',
        imageHeight: '200px',
        confirmButtonText: 'Cerrar',
        customClass: {
            image: 'swal-image-small'
        }
    });
}

// Función para mostrar comentarios
function mostrarComentarios(nombre, comentarios) {
    Swal.fire({
        title: `Comentarios de ${nombre}`,
        text: comentarios || 'No hay comentarios para este producto',
        confirmButtonText: 'Cerrar',
        width: '250px'
    });
}

// Función para sumar una unidad al stock
function sumarUnidad(productoId) {
    actualizarStock(productoId, 'sumar');
}

// Función para restar una unidad al stock
function restarUnidad(productoId, stockActual) {
    if (stockActual <= 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Stock insuficiente',
            text: 'No se puede reducir más el stock'
        });
        return;
    }
    actualizarStock(productoId, 'restar');
}

// Definir la función globalmente
function actualizarStock(productoId, operacion) {
    $.ajax({
        url: '../assets/php/MVC/Controlador/productos-controlador.php',
        method: 'POST',
        data: {
            accion: 'actualizarStock',
            id: productoId,
            operacion: operacion
        },
        success: function(response) {
            try {
                const data = JSON.parse(response);
                if (data.success) {
                    const stockElement = document.querySelector(`#stock-${productoId}`);
                    const currentStock = parseInt(stockElement.textContent);
                    stockElement.textContent = operacion === 'sumar' ? currentStock + 1 : currentStock - 1;
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Stock actualizado',
                        showConfirmButton: false,
                        timer: 1000
                    });
                } else {
                    throw new Error(data.message || 'Error al actualizar el stock');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error AJAX:', error);
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
    const stockInput = document.getElementById('stock');
    let valor = parseInt(stockInput.value) || 0;
    
    if (operacion === 'sumar') {
        valor++;
    } else if (operacion === 'restar' && valor > 0) {
        valor--;
    }
    
    stockInput.value = valor;
}
