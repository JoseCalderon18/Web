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
        
        // Validar campos
        const titulo = $('#titulo').val().trim();
        const contenido = $('#contenido').val().trim();
        const fecha = $('#fecha').val();
        
        // Verificar si es edición o creación
        const isEditing = $('input[name="id"]').length > 0 && $('input[name="id"]').val() !== '';
        
        // Si es creación, la imagen es obligatoria
        if (!isEditing && (!$('#imagen')[0].files[0])) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, selecciona una imagen para la noticia',
                confirmButtonColor: '#4A6D50'
            });
            return;
        }
        
        if (!titulo || !contenido || !fecha) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, completa todos los campos requeridos',
                confirmButtonColor: '#4A6D50'
            });
            return;
        }
        
        // Crear FormData para enviar archivos
        const formData = new FormData(this);
        
        // Determinar la acción (crear o editar)
        const accion = isEditing ? 'actualizarNoticia' : 'crearNoticia';
        
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Procesando...',
            text: 'Espera un momento mientras procesamos tu solicitud',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Enviar datos al servidor
        $.ajax({
            url: '../assets/php/MVC/Controlador/noticias-controlador.php?accion=' + accion,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    // Intentar extraer solo la parte JSON de la respuesta
                    const jsonStartPos = response.indexOf('{');
                    const jsonEndPos = response.lastIndexOf('}') + 1;
                    
                    if (jsonStartPos >= 0 && jsonEndPos > jsonStartPos) {
                        const jsonStr = response.substring(jsonStartPos, jsonEndPos);
                        const data = JSON.parse(jsonStr);
                        
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
                                text: data.message || 'Error al procesar la solicitud',
                                confirmButtonColor: '#4A6D50'
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
                        text: 'Error al procesar la respuesta del servidor',
                        confirmButtonColor: '#4A6D50'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Ajax error:', {xhr, status, error});
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al conectar con el servidor',
                    confirmButtonColor: '#4A6D50'
                });
            }
        });
    });
    
    // Funcionalidad para la lista de noticias
    setupNoticiasLista();
    
    // Cargar noticias al iniciar la página
    if ($('.noticias-container').length > 0) {
        cargarNoticias();
    }
});

// Configurar funcionalidad para la lista de noticias
function setupNoticiasLista() {
    // Eliminar noticia
    $(document).on('click', '.eliminar-noticia', function(e) {
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
                eliminarNoticia(id);
            }
        });
    });
}

// Función para eliminar noticia
function eliminarNoticia(id) {
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Eliminando...',
        text: 'Espera un momento',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Enviar solicitud al servidor
    $.ajax({
        url: '../assets/php/MVC/Controlador/noticias-controlador.php?accion=eliminarNoticia',
        type: 'POST',
        data: {id: id},
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
                        // Recargar la página para mostrar los cambios
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al eliminar la noticia',
                        confirmButtonColor: '#4A6D50'
                    });
                }
            } catch (e) {
                console.error('Error al procesar la respuesta:', response);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al procesar la respuesta del servidor',
                    confirmButtonColor: '#4A6D50'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Ajax error:', {xhr, status, error});
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al conectar con el servidor',
                confirmButtonColor: '#4A6D50'
            });
        }
    });
}

// Función para cargar noticias
function cargarNoticias(pagina = 1) {
    $.ajax({
        url: '../assets/php/MVC/Controlador/noticias-controlador.php?accion=listarNoticias&pagina=' + pagina,
        type: 'GET',
        success: function(response) {
            try {
                const data = JSON.parse(response);
                if (data.success) {
                    mostrarNoticias(data.noticias);
                    actualizarPaginacion(data.paginas, pagina);
                } else {
                    console.error('Error al cargar noticias:', data.message);
                }
            } catch (e) {
                console.error('Error al procesar la respuesta:', e);
            }
        },
        error: function(xhr, status, error) {
            console.error('Ajax error:', {xhr, status, error});
        }
    });
}

// Función para mostrar noticias en la página
function mostrarNoticias(noticias) {
    const container = $('.noticias-container');
    container.empty();
    
    if (noticias.length === 0) {
        container.html('<p class="text-center text-gray-500 my-8">No hay noticias disponibles.</p>');
        return;
    }
    
    noticias.forEach(noticia => {
        const esAdmin = typeof userRole !== 'undefined' && userRole === 'admin';
        const fecha = new Date(noticia.fecha_publicacion).toLocaleDateString('es-ES', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        
        const card = `
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="md:flex">
                    <div class="md:w-1/3">
                        <img src="../${noticia.imagen_url}" alt="${noticia.titulo}" class="w-full h-48 md:h-full object-cover">
                    </div>
                    <div class="p-6 md:w-2/3">
                        <div class="flex justify-between items-start">
                            <h2 class="text-xl font-bold text-green-800 mb-2">${noticia.titulo}</h2>
                            ${esAdmin ? `
                                <div class="flex space-x-2">
                                    <a href="noticiasForm.php?id=${noticia.id}" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="text-red-500 hover:text-red-700 eliminar-noticia" data-id="${noticia.id}" data-titulo="${noticia.titulo}">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            ` : ''}
                        </div>
                        <p class="text-sm text-gray-500 mb-4">
                            <i class="far fa-calendar-alt mr-2"></i>${fecha}
                            ${noticia.autor_nombre ? `<span class="ml-4"><i class="far fa-user mr-2"></i>${noticia.autor_nombre}</span>` : ''}
                        </p>
                        <div class="text-gray-700 mb-4">
                            ${noticia.contenido.length > 200 ? noticia.contenido.substring(0, 200) + '...' : noticia.contenido}
                        </div>
                        <a href="noticia.php?id=${noticia.id}" class="inline-block bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">
                            Leer más
                        </a>
                    </div>
                </div>
            </div>
        `;
        
        container.append(card);
    });
}

// Función para actualizar la paginación
function actualizarPaginacion(totalPaginas, paginaActual) {
    const paginacion = $('.pagination');
    paginacion.empty();
    
    if (totalPaginas <= 1) {
        return;
    }
    
    // Botón anterior
    paginacion.append(`
        <li>
            <a href="#" class="pagina-link ${paginaActual === 1 ? 'disabled' : ''}" data-pagina="${paginaActual - 1}" aria-label="Anterior">
                <span class="sr-only">Anterior</span>
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>
    `);
    
    // Números de página
    for (let i = 1; i <= totalPaginas; i++) {
        paginacion.append(`
            <li>
                <a href="#" class="pagina-link ${i === paginaActual ? 'active' : ''}" data-pagina="${i}">
                    ${i}
                </a>
            </li>
        `);
    }
    
    // Botón siguiente
    paginacion.append(`
        <li>
            <a href="#" class="pagina-link ${paginaActual === totalPaginas ? 'disabled' : ''}" data-pagina="${paginaActual + 1}" aria-label="Siguiente">
                <span class="sr-only">Siguiente</span>
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
    `);
    
    // Manejar clics en la paginación
    $('.pagina-link').on('click', function(e) {
        e.preventDefault();
        if (!$(this).hasClass('disabled')) {
            const pagina = $(this).data('pagina');
            cargarNoticias(pagina);
        }
    });
}
