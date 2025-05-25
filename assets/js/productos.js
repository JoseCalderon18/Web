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
    // Aquí va la configuración de la lista de productos
    console.log('Setup de lista de productos iniciado');
    
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
    // Aquí va la configuración del formulario
    console.log('Setup de formulario de productos iniciado');
    
    // Si existe el formulario de productos
    const form = document.getElementById('productoForm');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }
}

function handleFormSubmit(e) {
    e.preventDefault();
    // Manejo del envío del formulario
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
