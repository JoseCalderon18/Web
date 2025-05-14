$(document).ready(function() {
    console.log('Productos.js inicializado');
    
    // Funcionalidad para la lista de productos
    setupProductosLista();
    
    // Funcionalidad para el formulario de productos (crear/editar)
    setupProductosForm();
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
