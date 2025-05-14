$(document).ready(function () {
    // Vista previa de imágenes
    $('#foto').on('change', function(e) {
        const file = e.target.files[0];
        const $preview = $('#image-preview');
        $preview.empty();

        if (file) {
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
                        $('#foto').val('');
                    });

                $container.append($img, $removeBtn);
                $preview.append($container);
            };
            reader.readAsDataURL(file);
        }
    });

    // Envío del formulario
    $('#noticiaForm').on('submit', function(e) {
        e.preventDefault();

        // Validaciones
        const titulo = $('#titulo').val().trim();
        if (!titulo) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, ingresa un título',
                confirmButtonColor: "#4A6D50"
            });
            return;
        }

        const texto = $('#texto').val().trim();
        if (!texto) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, escribe el contenido de la noticia',
                confirmButtonColor: "#4A6D50"
            });
            return;
        }

        // Crear FormData
        const formData = new FormData();
        formData.append('titulo', titulo);
        formData.append('texto', texto);
        
        // Añadir la foto si existe
        const foto = $('#foto')[0].files[0];
        if (foto) {
            formData.append('foto', foto);
        }

        // Mostrar loading
        Swal.fire({
            title: 'Publicando noticia...',
            text: 'Por favor espera',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Enviar datos
        $.ajax({
            url: '../assets/php/MVC/Controlador/noticias-controlador.php?accion=crearNoticia',
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
                            text: 'La noticia ha sido publicada correctamente',
                            confirmButtonColor: "#4A6D50"
                        }).then(() => {
                            window.location.href = 'noticias.php';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Error al publicar la noticia',
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

    // Cargar noticias existentes
    const $noticiasContainer = $('#noticias-container');

    if ($noticiasContainer.length) {
        cargarNoticias();
    }

    function cargarNoticias() {
        $.ajax({
            url: '../assets/php/MVC/Controlador/noticias-controlador.php?accion=listarNoticias',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    mostrarNoticias(data.noticias);
                } else {
                    console.error('Error al cargar noticias:', data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function mostrarNoticias(noticias) {
        if (!$noticiasContainer.length) return;

        $noticiasContainer.html('');

        if (!noticias.length) {
            $noticiasContainer.html(`
                <div class="text-center py-8">
                    <p class="text-gray-500">No hay noticias disponibles todavía.</p>
                </div>
            `);
            return;
        }

        noticias.forEach(noticia => {
            const noticiaHTML = `
                <div class="bg-white rounded-xl p-6 shadow-md mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">${noticia.titulo}</h2>
                        <p class="text-sm text-gray-500">${formatearFecha(noticia.fecha)}</p>
                    </div>
                    <p class="text-gray-700 mb-4">${noticia.texto}</p>
                    ${noticia.foto ? `<img src="${noticia.foto}" alt="Imagen de noticia" class="rounded-lg w-full h-48 object-cover">` : ''}
                </div>
            `;

            $noticiasContainer.append(noticiaHTML);
        });
    }

    function formatearFecha(fecha) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(fecha).toLocaleDateString('es-ES', options);
    }
});
