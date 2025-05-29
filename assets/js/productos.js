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
    if ($('#productoForm').length > 0) {
        console.log('Formulario de productos encontrado');
        
        // Vista previa de imagen
        $('#foto').on('change', function() {
            const file = this.files[0];
            let preview = $('#preview');
            
            // Si no existe el elemento preview, crearlo
            if (preview.length === 0) {
                // Buscar donde insertar la vista previa
                const fotoContainer = $('#foto').closest('.space-y-1, .mb-4, .form-group');
                if (fotoContainer.length > 0) {
                    fotoContainer.append(`
                        <div class="mt-2">
                            <img id="preview" class="hidden w-32 h-32 object-cover rounded-lg border-2 border-gray-300" alt="Vista previa">
                        </div>
                    `);
                    preview = $('#preview');
                }
            }
            
            if (file) {
                // Validar que sea una imagen
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result).removeClass('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    alert('Por favor selecciona un archivo de imagen válido');
                    $(this).val('');
                    preview.addClass('hidden').attr('src', '');
                }
            } else {
                preview.addClass('hidden').attr('src', '');
            }
        });
        
        // Envío del formulario
        $('#productoForm').on('submit', function(e) {
            e.preventDefault();
            console.log('Formulario enviado');
            
            // Crear FormData para enviar archivos
            const formData = new FormData(this);
            
            // Verificar si es edición o creación
            const isEditing = formData.has('id') && formData.get('id') !== '';
            console.log('Es edición:', isEditing);
            
            // Determinar la acción
            const accion = isEditing ? 'editar' : 'crear';
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
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    console.log('Respuesta del servidor:', response);
                    
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.message,
                            confirmButtonColor: '#4A6D50'
                        }).then(() => {
                            window.location.href = 'productos.php';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            confirmButtonColor: '#4A6D50'
                        });
                    }
                },
                error: function(xhr, status, error) {
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
    // Determinar la acción correcta según la operación
    const accion = operacion === 'sumar' ? 'sumarUnidad' : 'restarUnidad';
    
    $.ajax({
        url: '../assets/php/MVC/Controlador/productos-controlador.php?accion=' + accion,
        method: 'POST',
        data: {
            id: productoId
        },
        dataType: 'json',
        success: function(response) {
            console.log('Respuesta del servidor:', response);
            
            if (response.success) {
                // Buscar el botón que contiene el productoId para encontrar la fila
                const botonSumar = document.querySelector(`button[onclick="sumarUnidad(${productoId})"]`);
                if (botonSumar) {
                    // Encontrar el span del stock en la misma fila
                    const stockElement = botonSumar.parentElement.querySelector('span.font-medium');
                    if (stockElement) {
                        const currentStock = parseInt(stockElement.textContent);
                        const newStock = operacion === 'sumar' ? currentStock + 1 : currentStock - 1;
                        stockElement.textContent = newStock;
                        
                        // Actualizar el estado del botón de restar
                        const botonRestar = botonSumar.parentElement.querySelector(`button[onclick*="restarUnidad(${productoId}"]`);
                        if (botonRestar) {
                            if (newStock <= 0) {
                                botonRestar.disabled = true;
                            } else {
                                botonRestar.disabled = false;
                            }
                            // Actualizar el onclick del botón restar con el nuevo stock
                            botonRestar.setAttribute('onclick', `restarUnidad(${productoId}, ${newStock})`);
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
                    text: response.message || 'Error al actualizar el stock'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error AJAX:', error);
            console.error('Estado:', status);
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
    const stockInput = document.getElementById('stock');
    let valor = parseInt(stockInput.value) || 0;
    
    if (operacion === 'sumar') {
        valor++;
    } else if (operacion === 'restar' && valor > 0) {
        valor--;
    }
    
    stockInput.value = valor;
}
