$(document).ready(function() {
    console.log("Script de búsqueda de usuarios cargado");
    
    const $barraBusqueda = $('#barraBusqueda');
    const $tablaUsuarios = $('#tablaUsuarios');
    const $mensajeNoResultados = $('#mensajeNoResultados');
    const $paginacion = $('.flex.justify-center'); // Selector para la paginación
    
    console.log("Barra de búsqueda encontrada:", $barraBusqueda.length);
    console.log("Tabla usuarios encontrada:", $tablaUsuarios.length);
    console.log("Mensaje no resultados encontrado:", $mensajeNoResultados.length);
    
    if ($barraBusqueda.length && $tablaUsuarios.length) {
        // Evento que se dispara al escribir (tiempo real)
        $barraBusqueda.on('input keyup', function() {
            const textoBusqueda = $(this).val().toLowerCase().trim();
            console.log("Buscando:", textoBusqueda);
            
            if (textoBusqueda === '') {
                // Si no hay búsqueda, recargar la página para mostrar la paginación normal
                location.reload();
                return;
            }
            
            // Hacer petición AJAX para buscar en toda la base de datos
            $.ajax({
                url: '../assets/php/MVC/Controlador/usuarios-controlador.php',
                type: 'GET',
                data: {
                    accion: 'buscar',
                    buscar: textoBusqueda
                },
                dataType: 'json',
                success: function(response) {
                    console.log("Respuesta del servidor:", response);
                    
                    if (response.success) {
                        mostrarResultados(response.data);
                        // Ocultar paginación cuando hay resultados de búsqueda
                        $paginacion.hide();
                    } else {
                        console.error("Error en la búsqueda:", response.message);
                        mostrarResultados([]);
                        $paginacion.hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error AJAX:", error);
                    mostrarResultados([]);
                    $paginacion.hide();
                }
            });
        });
    }
    
    function mostrarResultados(usuarios) {
        const $tbody = $tablaUsuarios.find('tbody');
        $tbody.empty();
        
        if (usuarios.length === 0) {
            $tbody.html('<tr><td colspan="3" class="px-3 md:px-8 py-4 md:py-6 text-center text-gray-500 text-sm md:text-base">No se encontraron usuarios</td></tr>');
            $mensajeNoResultados.show();
        } else {
            $mensajeNoResultados.hide();
            
            usuarios.forEach(function(usuario) {
                const fila = `
                    <tr class="border-b">
                        <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base font-medium">${escapeHtml(usuario.nombre)}</td>
                        <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base">${escapeHtml(usuario.email)}</td>
                        <td class="px-3 md:px-8 py-3 md:py-5">
                            ${usuario.id !== "1" ? `
                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-5">
                                    <button 
                                        data-usuario-id="${usuario.id}"
                                        data-usuario-rol="${usuario.rol}"
                                        data-accion="cambiarRol"
                                        class="btn-cambiar-rol px-3 py-2 md:px-4 md:py-2 text-xs md:text-sm ${usuario.rol === 'admin' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-blue-500 hover:bg-blue-600'} text-white rounded-lg shadow-sm cursor-pointer">
                                        <i class="fas ${usuario.rol === 'admin' ? 'fa-user-minus' : 'fa-user-shield'} mr-1 md:mr-2"></i>
                                        ${usuario.rol === 'admin' ? 'Quitar Admin' : 'Hacer Admin'}
                                    </button>
                                    <button 
                                        data-usuario-id="${usuario.id}"
                                        data-accion="eliminar"
                                        class="btn-eliminar px-3 py-2 md:px-4 md:py-2 text-xs md:text-sm bg-red-600 hover:bg-red-800 text-white rounded-lg shadow-sm cursor-pointer">
                                        <i class="fas fa-trash-alt mr-1 md:mr-2"></i>Eliminar
                                    </button>
                                </div>
                            ` : '<span class="text-gray-500 text-sm">Usuario protegido</span>'}
                        </td>
                    </tr>
                `;
                $tbody.append(fila);
            });
        }
    }
    
    // Event delegation para botones dinámicos - CAMBIAR ROL
    $(document).on('click', '.btn-cambiar-rol', function() {
        const usuarioId = $(this).data('usuario-id');
        const rolActual = $(this).data('usuario-rol');
        const nuevoRol = rolActual === 'admin' ? 'usuario' : 'admin';
        const mensaje = rolActual === 'admin' ? 'quitar permisos de administrador' : 'dar permisos de administrador';

        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas ${mensaje} a este usuario?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, cambiar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `../assets/php/MVC/Controlador/usuarios-controlador.php?accion=cambiarRol&id=${usuarioId}&rol=${nuevoRol}`;
            }
        });
    });
    
    // Event delegation para botones dinámicos - ELIMINAR
    $(document).on('click', '.btn-eliminar', function() {
        const usuarioId = $(this).data('usuario-id');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `../assets/php/MVC/Controlador/usuarios-controlador.php?accion=eliminar&id=${usuarioId}`;
            }
        });
    });
    
    // Función para escapar HTML
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
}); 