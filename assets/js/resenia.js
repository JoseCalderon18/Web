$(document).ready(function () {
    // Sistema de calificación con estrellas
    const $stars = $('.rating-stars svg');
    const $ratingInput = $('#rating');
    const $ratingValue = $('#ratingValue');

    if ($stars.length && $ratingInput.length && $ratingValue.length) {
        $stars.on('click', function () {
            const rating = $(this).data('rating');
            $ratingInput.val(rating);
            $ratingValue.text(rating);
            updateStars(rating);
        });

        function updateStars(rating) {
            $stars.each(function () {
                const starRating = parseFloat($(this).data('rating'));
                if (starRating <= rating) {
                    $(this).removeClass('text-gray-300').addClass('text-yellow-400');
                } else {
                    $(this).removeClass('text-yellow-400').addClass('text-gray-300');
                }
            });
        }
    }

    // Vista previa de imágenes
    const $photoUpload = $('#photo-upload');
    const $imagePreview = $('#image-preview');

    if ($photoUpload.length && $imagePreview.length) {
        $photoUpload.on('change', function (e) {
            const files = e.target.files;
            $imagePreview.html('');

            if (files.length > 3) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Demasiadas imágenes',
                    text: 'Por favor, selecciona un máximo de 3 imágenes',
                    confirmButtonColor: '#15803d'
                });
                this.value = '';
                return;
            }

            Array.from(files).forEach(file => {
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo demasiado grande',
                        text: 'El tamaño máximo permitido es 5MB por imagen',
                        confirmButtonColor: '#15803d'
                    });
                    this.value = '';
                    $imagePreview.html('');
                    return;
                }

                if (!file.type.match('image/jpeg|image/png|image/jpg')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Formato no válido',
                        text: 'Por favor, selecciona imágenes en formato JPG, JPEG o PNG',
                        confirmButtonColor: '#15803d'
                    });
                    this.value = '';
                    $imagePreview.html('');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    const $imgContainer = $('<div>').addClass('relative group');
                    const $img = $('<img>').attr('src', e.target.result).addClass('h-auto max-w-full rounded-lg object-cover aspect-square');
                    const $removeBtn = $('<button type="button">').addClass('absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity').html('<i class="fas fa-times"></i>');

                    $removeBtn.on('click', function () {
                        $imgContainer.remove();
                    });

                    $imgContainer.append($img).append($removeBtn);
                    $imagePreview.append($imgContainer);
                };
                reader.readAsDataURL(file);
            });
        });
    }

    // Manejo del envío del formulario
    const $reviewForm = $('#reviewForm');

    if ($reviewForm.length) {
        $reviewForm.on('submit', function (e) {
            e.preventDefault();

            const rating = $('#rating').val();
            const comment = $('#comment').val();

            if (!rating) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Por favor, selecciona una calificación',
                    confirmButtonColor: '#15803d'
                });
                return;
            }

            if (!comment.trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Por favor, escribe un comentario',
                    confirmButtonColor: '#15803d'
                });
                return;
            }

            const formData = new FormData(this);

            Swal.fire({
                title: 'Enviando reseña...',
                text: 'Por favor, espera un momento',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '../assets/php/MVC/Controlador/resenias-controlador.php?accion=crearResenia',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Gracias!',
                            text: 'Tu reseña ha sido enviada correctamente',
                            confirmButtonColor: '#15803d'
                        }).then(() => {
                            window.location.href = 'blog.php';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Ha ocurrido un error al enviar tu reseña',
                            confirmButtonColor: '#15803d'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ha ocurrido un error al conectar con el servidor',
                        confirmButtonColor: '#15803d'
                    });
                }
            });
        });
    }

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
