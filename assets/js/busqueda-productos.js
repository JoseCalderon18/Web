$(document).ready(function() {
    console.log("Script de búsqueda de productos cargado");
    
    const $barraBusqueda = $('#barraBusqueda');
    const $tablaProductos = $('#tablaProductos');
    const $mensajeNoResultados = $('#mensajeNoResultados');
    const $paginacion = $('.flex.justify-center'); // Selector para la paginación
    
    console.log("Barra de búsqueda encontrada:", $barraBusqueda.length);
    console.log("Tabla productos encontrada:", $tablaProductos.length);
    console.log("Mensaje no resultados encontrado:", $mensajeNoResultados.length);
    
    if ($barraBusqueda.length && $tablaProductos.length) {
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
                url: '../assets/php/MVC/Controlador/productos-controlador.php',
                type: 'GET',
                data: {
                    accion: 'buscar',
                    buscar: textoBusqueda
                },
                dataType: 'json',
                success: function(respuesta) {
                    console.log("Respuesta del servidor:", respuesta);
                    
                    if (respuesta.success) {
                        mostrarResultados(respuesta.data);
                        // Ocultar paginación durante la búsqueda
                        $paginacion.hide();
                    } else {
                        console.error("Error en la búsqueda:", respuesta.message);
                    }
                },
                error: function(xhr, estado, error) {
                    console.error("Error en la petición AJAX:", error);
                }
            });
        });
        
        console.log("Eventos de búsqueda configurados correctamente");
    } else {
        console.error("No se encontraron los elementos necesarios para la búsqueda");
    }
    
    // Función para mostrar los resultados de búsqueda
    function mostrarResultados(productos) {
        const $tbody = $tablaProductos.find('tbody');
        $tbody.empty();
        
        if (productos.length === 0) {
            $tbody.html('<tr><td colspan="8" class="px-3 md:px-8 py-5 text-center text-gray-500">No hay productos disponibles</td></tr>');
            $mensajeNoResultados.show();
        } else {
            $mensajeNoResultados.hide();
            
            productos.forEach(function(producto) {
                const fila = `
                    <tr class="border-b">
                        <td class="px-3 md:px-8 py-3 md:py-5 text-center">
                            ${!producto.foto ? `
                                <div class="w-12 h-12 bg-gray-200 rounded-md flex items-center justify-center mx-auto">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            ` : `
                                <img src="../${escaparHtml(producto.foto)}" 
                                     alt="Foto de ${escaparHtml(producto.nombre)}" 
                                     class="w-12 h-12 object-cover rounded-md mx-auto cursor-pointer"
                                     onclick="mostrarGaleria('${escaparHtml(producto.foto)}', '${escaparHtml(producto.nombre)}')">
                            `}
                        </td>
                        <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base text-center">
                            ${escaparHtml(producto.nombre)}
                        </td>
                        <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base">
                            <div class="flex items-center justify-center">
                                <button 
                                    onclick="sumarUnidad(${producto.id})"
                                    class="px-2 py-1 text-xs bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-sm cursor-pointer mr-2"
                                    title="Sumar una unidad">
                                    <i class="fas fa-plus"></i>
                                </button>
                                
                                <span class="mx-2 font-medium">${producto.stock ?? 0}</span>
                                
                                <button 
                                    onclick="restarUnidad(${producto.id}, ${producto.stock})"
                                    class="px-2 py-1 text-xs bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg shadow-sm cursor-pointer ml-2"
                                    ${producto.stock <= 0 ? 'disabled' : ''}
                                    title="Restar una unidad">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </td>
                        <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base text-center">
                            ${escaparHtml(producto.precio)} €
                        </td>
                        <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base text-center">
                            ${escaparHtml(producto.laboratorio ?? 'N/A')}
                        </td>
                        <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base text-center">
                            ${producto.fecha_registro ? new Date(producto.fecha_registro).toLocaleDateString('es-ES') : 'N/A'}
                        </td>
                        <td class="px-3 md:px-8 py-3 md:py-5 text-center">
                            ${producto.comentarios ? `
                                <button 
                                    onclick="mostrarComentarios('${escaparHtml(producto.nombre)}', '${escaparHtml(producto.comentarios)}')"
                                    class="px-3 py-1 text-xs bg-purple-400 hover:bg-purple-500 text-white rounded-lg shadow-sm cursor-pointer">
                                    <i class="fas fa-comment-alt mr-1"></i>Ver comentarios
                                </button>
                            ` : `
                                <span class="text-gray-400">Sin comentarios</span>
                            `}
                        </td>
                        <td class="px-3 md:px-8 py-3 md:py-5 text-center">
                            <?php if (isset($_SESSION["usuario_rol"]) && $_SESSION["usuario_rol"] === "admin"): ?>
                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-5 justify-center">
                                    <button 
                                        onclick="confirmarEliminacion(${producto.id})"
                                        class="px-3 py-2 md:px-4 md:py-2 text-xs md:text-sm bg-red-600 hover:bg-red-800 text-white rounded-lg shadow-sm cursor-pointer">
                                        <i class="fas fa-trash-alt mr-1 md:mr-2"></i>Eliminar
                                    </button>
                                    <a href="productosForm.php?id=${producto.id}"
                                       class="px-3 py-2 md:px-4 md:py-2 text-xs md:text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-sm cursor-pointer text-center">
                                        <i class="fas fa-edit mr-1 md:mr-2"></i>Editar
                                    </a>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                `;
                $tbody.append(fila);
            });
        }
    }
    
    // Función para escapar HTML
    function escaparHtml(texto) {
        if (texto === null || texto === undefined) return '';
        const mapa = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(texto).replace(/[&<>"']/g, function(m) { return mapa[m]; });
    }
}); 