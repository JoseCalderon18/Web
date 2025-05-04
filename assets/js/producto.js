console.log('Archivo productos.js cargado - Versión 1.0');

// Verificar que jQuery está disponible
if (typeof jQuery === 'undefined') {
    console.error('jQuery no está cargado');
} else {
    console.log('jQuery está disponible');
}

$(document).ready(function() {
    console.log('DOM cargado en productos.js');
    console.log('Formulario encontrado:', $('#productoForm').length);
    
    // Añadir un manejador de click al botón directamente para debug
    $('button[type="submit"]').on('click', function() {
        console.log('Botón submit clickeado');
    });

    // Inicializar Swiper si hay fotos
    if ($('.swiper-container').length > 0) {
        new Swiper('.swiper-container', {
            slidesPerView: 1,
            spaceBetween: 30,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                }
            }
        });
    }

    // Función para mostrar el link de la foto
    window.mostrarLink = function(rutaFoto, numeroFoto) {
        Swal.fire({
            title: `Foto ${numeroFoto}`,
            html: `
                <div class="mb-4">
                    <img src="../${rutaFoto}" class="max-w-full h-auto rounded-lg mx-auto">
                </div>
                <p class="text-sm text-gray-600">Ruta de la imagen:</p>
                <p class="text-sm font-mono bg-gray-100 p-2 rounded">${rutaFoto}</p>
            `,
            confirmButtonColor: '#4A6D50',
            width: '600px'
        });
    };

    // Función para eliminar foto
    window.eliminarFoto = function(boton, rutaFoto) {
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
                const fotosExistentes = JSON.parse($('#fotos_existentes').val() || '[]');
                const nuevasFotos = fotosExistentes.filter(foto => foto !== rutaFoto);
                $('#fotos_existentes').val(JSON.stringify(nuevasFotos));
                $(boton).closest('.swiper-slide').remove();
                
                // Si no quedan fotos, ocultar el contenedor
                if ($('#preview-grid').children().length === 0) {
                    $('#preview-container').addClass('hidden');
                }
            }
        });
    };

    // Previsualización de imágenes
    function previsualizarImagenes(input) {
        const gridVista = $('#preview-grid');
        gridVista.empty();
        
        if (input.files && input.files.length > 0) {
            Array.from(input.files).forEach((archivo, index) => {
                const lector = new FileReader();
                
                lector.onload = function(e) {
                    const previewDiv = $('<div>').addClass('relative');
                    const img = $('<img>')
                        .attr('src', e.target.result)
                        .attr('alt', 'Vista previa ' + (index + 1))
                        .addClass('w-32 h-32 object-cover rounded-lg');
                    
                    const btnEliminar = $('<button>')
                        .attr('type', 'button')
                        .addClass('absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 m-1 hover:bg-red-600')
                        .html('<i class="fas fa-times"></i>')
                        .on('click', function() {
                            previewDiv.remove();
                            if (gridVista.children().length === 0) {
                                $('#preview-container').addClass('hidden');
                                $('#fotos').val('');
                            }
                        });

                    previewDiv.append(img).append(btnEliminar);
                    gridVista.append(previewDiv);
                }
                
                lector.readAsDataURL(archivo);
            });
            
            $('#preview-container').removeClass('hidden');
        }
    }

    // Asignar el manejador al input de fotos
    $('#fotos').on('change', function() {
        previsualizarImagenes(this);
    });

    // Envío del formulario
    $('#productoForm').submit(function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const nombre = $('#nombre').val().trim();
        const stock = $('#stock').val();
        const precio = $('#precio').val();
        const fecha = $('#fecha_registro').val();
        const fotos = $('#fotos')[0].files;

        // Validaciones
        if (!nombre) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, ingresa el nombre del producto',
                icon: 'error',
                confirmButtonColor: '#4A6D50'
            });
            return false;
        }

        if (!stock || stock < 0) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, ingresa un stock válido',
                icon: 'error',
                confirmButtonColor: '#4A6D50'
            });
            return false;
        }

        if (!precio || precio <= 0) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, ingresa un precio válido',
                icon: 'error',
                confirmButtonColor: '#4A6D50'
            });
            return false;
        }

        if (!fecha) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, selecciona una fecha',
                icon: 'error',
                confirmButtonColor: '#4A6D50'
            });
            return false;
        }

        if (!$('input[name="id"]').length && (!fotos || fotos.length === 0)) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, selecciona al menos una foto',
                icon: 'error',
                confirmButtonColor: '#4A6D50'
            });
            return false;
        }

        // Mostrar loading
        Swal.fire({
            title: 'Enviando...',
            text: 'Por favor espera',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Enviar datos
        const accion = $('input[name="id"]').length ? 'editar' : 'crear';
        
        $.ajax({
            url: '../assets/php/MVC/Controlador/productos-controlador.php?accion=' + accion,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Respuesta del servidor:', response); // Debug
                
                // Verificar si la respuesta es HTML
                if (response.trim().startsWith('<!DOCTYPE html>')) {
                    console.error('El servidor devolvió HTML en lugar de JSON:', response);
                    Swal.fire({
                        title: 'Error',
                        text: 'Error en el servidor. Por favor, contacta al administrador.',
                        icon: 'error',
                        confirmButtonColor: '#4A6D50'
                    });
                    return;
                }

                try {
                    const data = JSON.parse(response);
                    console.log('Datos parseados:', data); // Debug

                    if (data.success) {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: data.message || 'Producto guardado correctamente',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'productos.php';
                        });
                    } else {
                        console.error('Error del servidor:', data.message); // Debug
                        Swal.fire({
                            title: 'Error',
                            text: data.message || 'Error al procesar la solicitud',
                            icon: 'error',
                            confirmButtonColor: '#4A6D50'
                        });
                    }
                } catch (e) {
                    console.error('Error al parsear JSON:', e); // Debug
                    console.error('Respuesta raw:', response); // Debug
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al procesar la respuesta del servidor. Verifica la consola para más detalles.',
                        icon: 'error',
                        confirmButtonColor: '#4A6D50'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                }); // Debug detallado
                Swal.fire({
                    title: 'Error',
                    text: 'Error al enviar la solicitud. Verifica la consola para más detalles.',
                    icon: 'error',
                    confirmButtonColor: '#4A6D50'
                });
            }
        });

        return false;
    });
}); 