$(document).ready(function() {
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
    
    // Eliminar noticia
    $(document).on('click', '.eliminar-noticia', function() {
        const id = $(this).data('id');
        const card = $(this).closest('.bg-white');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4A6D50',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../assets/php/MVC/Controlador/noticias-controlador.php?accion=eliminarNoticia',
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
                                });
                                
                                // Eliminar la tarjeta de la noticia del DOM
                                card.fadeOut(300, function() {
                                    $(this).remove();
                                    
                                    // Si no quedan noticias, mostrar mensaje
                                    if ($('.bg-white').length === 0) {
                                        $('.grid').html('<div class="col-span-full text-center py-8"><p class="text-gray-500">No hay noticias disponibles en este momento.</p></div>');
                                    }
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
    });
    
    // Ver noticia completa
    $(document).on('click', '.ver-noticia', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: '../assets/php/MVC/Controlador/noticias-controlador.php?accion=obtenerNoticia&id=' + id,
            type: 'GET',
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    
                    if (data.success) {
                        const noticia = data.noticia;
                        
                        // Crear modal con los detalles de la noticia
                        Swal.fire({
                            title: noticia.titulo,
                            html: `
                                <div class="text-left">
                                    ${noticia.imagen_url ? `<img src="/${noticia.imagen_url}" class="mx-auto mb-4 max-w-full h-auto rounded-lg">` : 
                                    `<div class="w-full h-48 bg-green-100 flex items-center justify-center mb-4 rounded-lg">
                                        <i class="fas fa-newspaper text-green-800 text-4xl"></i>
                                    </div>`}
                                    <p class="text-sm text-gray-500 mb-4">
                                        <i class="far fa-calendar-alt mr-2"></i>${new Date(noticia.fecha_publicacion).toLocaleDateString()}
                                    </p>
                                    <div class="text-gray-700 whitespace-pre-line">${noticia.contenido}</div>
                                </div>
                            `,
                            width: '600px',
                            confirmButtonColor: '#4A6D50',
                            confirmButtonText: 'Cerrar'
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
});
