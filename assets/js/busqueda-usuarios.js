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
                        // Ocultar paginación durante la búsqueda
                        $paginacion.hide();
                    } else {
                        console.error("Error en la búsqueda:", response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la petición AJAX:", error);
                }
            });
        });
        
        console.log("Eventos de búsqueda configurados correctamente");
    } else {
        console.error("No se encontraron los elementos necesarios para la búsqueda");
    }
    
    // Función para mostrar los resultados de búsqueda
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
                                        onclick="cambiarRol(${usuario.id}, '${usuario.rol}')"
                                        class="px-3 py-2 md:px-4 md:py-2 text-xs md:text-sm ${usuario.rol === 'admin' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-blue-500 hover:bg-blue-600'} text-white rounded-lg shadow-sm cursor-pointer">
                                        <i class="fas ${usuario.rol === 'admin' ? 'fa-user-minus' : 'fa-user-shield'} mr-1 md:mr-2"></i>
                                        ${usuario.rol === 'admin' ? 'Quitar Admin' : 'Hacer Admin'}
                                    </button>
                                    <button 
                                        onclick="confirmarEliminacion(${usuario.id})"
                                        class="px-3 py-2 md:px-4 md:py-2 text-xs md:text-sm bg-red-600 hover:bg-red-800 text-white rounded-lg shadow-sm cursor-pointer">
                                        <i class="fas fa-trash-alt mr-1 md:mr-2"></i>Eliminar
                                    </button>
                                </div>
                            ` : ''}
                        </td>
                    </tr>
                `;
                $tbody.append(fila);
            });
        }
    }
    
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