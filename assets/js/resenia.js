document.addEventListener('DOMContentLoaded', function() {
    // Sistema de calificación con estrellas
    const stars = document.querySelectorAll('.rating-stars svg');
    const ratingInput = document.getElementById('rating');
    const ratingValue = document.getElementById('ratingValue');
    
    if (stars.length > 0 && ratingInput && ratingValue) {
        stars.forEach(star => {
            // Evento al hacer clic en una estrella
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                ratingInput.value = rating;
                ratingValue.textContent = rating;
                
                // Actualizar las estrellas
                updateStars(rating);
            });
        });
        
        // Función para actualizar el color de las estrellas
        function updateStars(rating) {
            stars.forEach(s => {
                const starRating = parseFloat(s.getAttribute('data-rating'));
                if (starRating <= rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        }
    }
    
    // Vista previa de imágenes
    const photoUpload = document.getElementById('photo-upload');
    const imagePreview = document.getElementById('image-preview');
    
    if (photoUpload && imagePreview) {
        photoUpload.addEventListener('change', function(e) {
            const files = e.target.files;
            imagePreview.innerHTML = '';
            
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
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                // Verificar tamaño (5MB máximo)
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo demasiado grande',
                        text: 'El tamaño máximo permitido es 5MB por imagen',
                        confirmButtonColor: '#15803d'
                    });
                    this.value = '';
                    imagePreview.innerHTML = '';
                    return;
                }
                
                // Verificar tipo de archivo
                if (!file.type.match('image/jpeg') && !file.type.match('image/png') && !file.type.match('image/jpg')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Formato no válido',
                        text: 'Por favor, selecciona imágenes en formato JPG, JPEG o PNG',
                        confirmButtonColor: '#15803d'
                    });
                    this.value = '';
                    imagePreview.innerHTML = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.className = 'relative group';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'h-auto max-w-full rounded-lg object-cover aspect-square';
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    
                    removeBtn.addEventListener('click', function() {
                        imgContainer.remove();
                    });
                    
                    imgContainer.appendChild(img);
                    imgContainer.appendChild(removeBtn);
                    imagePreview.appendChild(imgContainer);
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Manejar el envío del formulario
    const reviewForm = document.getElementById('reviewForm');
    
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Obtener los valores del formulario
            const rating = document.getElementById('rating').value;
            const comment = document.getElementById('comment').value;
            
            // Validar que se haya seleccionado una calificación
            if (!rating) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Por favor, selecciona una calificación',
                    confirmButtonColor: '#15803d'
                });
                return;
            }
            
            // Validar que se haya escrito un comentario
            if (!comment.trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Por favor, escribe un comentario',
                    confirmButtonColor: '#15803d'
                });
                return;
            }
            
            // Crear FormData para enviar archivos
            const formData = new FormData(this);
            
            // Mostrar indicador de carga
            Swal.fire({
                title: 'Enviando reseña...',
                text: 'Por favor, espera un momento',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Enviar los datos al servidor mediante AJAX
            $.ajax({
                url: '../assets/php/MVC/Controlador/resenias-controlador.php?accion=crearResenia',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Gracias!',
                            text: 'Tu reseña ha sido enviada correctamente',
                            confirmButtonColor: '#15803d'
                        }).then(() => {
                            // Redirigir a la página de blog
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
                error: function(xhr, status, error) {
                    console.error('Error:', error);
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
    
    // Cargar reseñas existentes (si estamos en la página de blog)
    const resenasContainer = document.getElementById('resenas-container');
    
    if (resenasContainer) {
        cargarResenas();
    }
    
    // Función para cargar reseñas
    function cargarResenas() {
        $.ajax({
            url: '../assets/php/MVC/Controlador/resenias-controlador.php?accion=listarResenias',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    mostrarResenas(data.resenas);
                } else {
                    console.error('Error al cargar reseñas:', data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
    
    // Función para mostrar reseñas en la página
    function mostrarResenas(resenas) {
        if (!resenasContainer) return;
        
        resenasContainer.innerHTML = '';
        
        if (resenas.length === 0) {
            resenasContainer.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-gray-500">No hay reseñas disponibles todavía. ¡Sé el primero en compartir tu experiencia!</p>
                </div>
            `;
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
            
            resenasContainer.innerHTML += resenaHTML;
        });
    }
    
    // Función para generar estrellas HTML basadas en la puntuación
    function generarEstrellas(puntuacion) {
        const puntuacionNum = parseFloat(puntuacion);
        let estrellas = '';
        
        for (let i = 1; i <= 5; i++) {
            if (i <= puntuacionNum) {
                // Estrella completa
                estrellas += '<i class="fas fa-star text-yellow-400"></i>';
            } else if (i - 0.5 <= puntuacionNum) {
                // Media estrella
                estrellas += '<i class="fas fa-star-half-alt text-yellow-400"></i>';
            } else {
                // Estrella vacía
                estrellas += '<i class="far fa-star text-yellow-400"></i>';
            }
        }
        
        return estrellas;
    }
    
    // Función para generar galería de imágenes
    function generarGaleriaImagenes(imagenes) {
        if (!imagenes || imagenes.length === 0) return '';
        
        let imagenesHTML = '<div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-4">';
        
        imagenes.forEach(imagen => {
            imagenesHTML += `
                <a href="${imagen}" class="block" data-lightbox="resena-galeria">
                    <img src="${imagen}" alt="Imagen de reseña" class="rounded-lg object-cover w-full h-24 md:h-32">
                </a>
            `;
        });
        
        imagenesHTML += '</div>';
        return imagenesHTML;
    }
    
    // Función para obtener iniciales del nombre
    function obtenerIniciales(nombre) {
        if (!nombre) return '?';
        return nombre.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
    }
    
    // Función para formatear fecha
    function formatearFecha(fecha) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(fecha).toLocaleDateString('es-ES', options);
    }
});
