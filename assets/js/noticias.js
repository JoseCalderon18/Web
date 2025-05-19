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
    $('.eliminar-noticia').on('click', function(e) {
        e.preventDefault();
        
        const id = $(this).data('id');
        const titulo = $(this).data('titulo');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas eliminar la noticia "${titulo}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
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
                                }).then(() => {
                                    location.reload();
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
    $('.ver-noticia').on('click', function(e) {
        e.preventDefault();
        
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
                                    ${noticia.imagen_url ? `<img src="/${noticia.imagen_url}" class="mx-auto mb-4 max-w-full h-auto rounded-lg">` : ''}
                                    <p class="text-sm text-gray-500 mb-4">
                                        <i class="far fa-calendar-alt mr-2"></i>${new Date(noticia.fecha_publicacion).toLocaleDateString()}
                                        ${noticia.autor_nombre ? `<span class="ml-4"><i class="far fa-user mr-2"></i>${noticia.autor_nombre}</span>` : ''}
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
