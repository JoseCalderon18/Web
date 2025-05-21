<<<<<<< Updated upstream
$(document).ready(function () {
    // Sistema de calificación con estrellas
    let selectedRating = 0;
    
    $('.rating-stars svg').on('click', function() {
        selectedRating = $(this).data('rating');
        $('#rating').val(selectedRating);
        $('#ratingValue').text(selectedRating.toFixed(1));
        
        // Actualizar estrellas
        $('.rating-stars svg').each(function() {
            const starValue = $(this).data('rating');
            if (starValue <= selectedRating) {
                $(this).removeClass('text-gray-300').addClass('text-yellow-400');
            } else {
                $(this).removeClass('text-yellow-400').addClass('text-gray-300');
            }
        });
    });

    // Vista previa de imágenes
    $('#fotos').on('change', function(e) {
        const files = e.target.files;
        const $preview = $('#image-preview');
        $preview.empty();

        // Procesar cada archivo
        $.each(files, function(i, file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const $container = $('<div>').addClass('relative');
                const $img = $('<img>')
                    .attr('src', e.target.result)
                    .addClass('w-full h-32 object-cover rounded-lg');
                const $removeBtn = $('<button>')
                    .addClass('absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center m-1')
                    .html('×')
                    .on('click', function() {
                        $container.remove();
                    });

                $container.append($img, $removeBtn);
                $preview.append($container);
            };
            reader.readAsDataURL(file);
        });
    });

    // Envío del formulario
    $('#reviewForm').on('submit', function(e) {
        e.preventDefault();

        // Validaciones
        if (!selectedRating) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, selecciona una calificación',
                confirmButtonColor: "#4A6D50"
            });
            return;
        }

        const comentario = $('#comentario').val().trim();
        if (!comentario) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, escribe un comentario',
                confirmButtonColor: "#4A6D50"
            });
            return;
        }

        // Crear FormData y añadir los campos con los nombres correctos
        const formData = new FormData();
        formData.append('rating', selectedRating);
        formData.append('comentario', comentario);
        
        // Añadir las fotos si existen
        const fotos = $('#fotos')[0].files;
        if (fotos.length > 0) {
            for (let i = 0; i < fotos.length; i++) {
                formData.append('fotos[]', fotos[i]);
            }
        }

        // Mostrar loading
        Swal.fire({
            title: 'Enviando reseña...',
            text: 'Por favor espera',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Enviar datos
        $.ajax({
            url: '../assets/php/MVC/Controlador/resenias-controlador.php?accion=crearResenia',
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
                            title: '¡Gracias!',
                            text: 'Tu reseña ha sido enviada correctamente',
                            confirmButtonColor: "#4A6D50"
                        }).then(() => {
                            window.location.href = 'blog.php';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Error al enviar la reseña',
                            confirmButtonColor: "#4A6D50"
                        });
                    }
                } catch (e) {
                    console.error('Error parsing response:', response);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al procesar la respuesta del servidor',
                        confirmButtonColor: "#4A6D50"
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Ajax error:', {xhr, status, error});
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al conectar con el servidor',
                    confirmButtonColor: "#4A6D50"
                });
            }
        });
    });

    // Cargar reseñas existentes
    const $resenasContainer = $('#resenas-container');

    if ($resenasContainer.length) {
        cargarResenas();
    }

    function cargarResenas() {
        $.ajax({
            url: '../assets/php/MVC/Controlador/resenias-controlador.php?accion=listarResenias',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    mostrarResenas(data.resenas);
                } else {
                    console.error('Error al cargar reseñas:', data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function mostrarResenas(resenas) {
        if (!$resenasContainer.length) return;

        $resenasContainer.html('');

        if (!resenas.length) {
            $resenasContainer.html(`
                <div class="text-center py-8">
                    <p class="text-gray-500">No hay reseñas disponibles todavía. ¡Sé el primero en compartir tu experiencia!</p>
                </div>
            `);
            return;
        }

        resenas.forEach(resena => {
            const estrellas = generarEstrellas(resena.puntuacion);
            const imagenes = resena.imagenes ? generarGaleriaImagenes(resena.imagenes) : '';

            const resenaHTML = `
                <div class="bg-white rounded-xl p-6 shadow-md mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-800 font-bold">
                                ${obtenerIniciales(resena.nombre_usuario)}
                            </div>
                            <div class="ml-3">
                                <h3 class="font-medium text-gray-900">${resena.nombre_usuario}</h3>
                                <p class="text-sm text-gray-500">${formatearFecha(resena.fecha_creacion)}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            ${estrellas}
                            <span class="ml-2 text-sm font-medium text-gray-600">${resena.puntuacion}</span>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-4">${resena.comentario}</p>
                    ${imagenes}
                </div>
            `;

            $resenasContainer.append(resenaHTML);
        });
    }

    function generarEstrellas(puntuacion) {
        let estrellas = '';
        const puntuacionNum = parseFloat(puntuacion);
        for (let i = 1; i <= 5; i++) {
            if (i <= puntuacionNum) {
                estrellas += '<i class="fas fa-star text-yellow-400"></i>';
            } else if (i - 0.5 <= puntuacionNum) {
                estrellas += '<i class="fas fa-star-half-alt text-yellow-400"></i>';
            } else {
                estrellas += '<i class="far fa-star text-yellow-400"></i>';
            }
        }
        return estrellas;
    }

    function generarGaleriaImagenes(imagenes) {
        if (!imagenes || !imagenes.length) return '';
        let html = '<div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-4">';
        imagenes.forEach(img => {
            html += `
                <a href="${img}" class="block" data-lightbox="resena-galeria">
                    <img src="${img}" alt="Imagen de reseña" class="rounded-lg object-cover w-full h-24 md:h-32">
                </a>`;
        });
        html += '</div>';
        return html;
    }

    function obtenerIniciales(nombre) {
        if (!nombre) return '?';
        return nombre.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
    }

    function formatearFecha(fecha) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(fecha).toLocaleDateString('es-ES', options);
    }
});
=======
>>>>>>> Stashed changes
