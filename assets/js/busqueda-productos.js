// Espera a que el documento esté listo antes de ejecutar el código
$(document).ready(function() {
    // Referencias a elementos del DOM
    const $barraBusqueda = $('#barraBusqueda');
    const $tablaProductos = $('#tablaProductos');
    const $mensajeNoResultados = $('#mensajeNoResultados');
    const $paginacion = $('.flex.justify-center');
    
    // Verifica que existan los elementos necesarios
    if ($barraBusqueda.length && $tablaProductos.length) {
        // Evento que se dispara al escribir en la barra de búsqueda
        $barraBusqueda.on('input keyup', function() {
            const textoBusqueda = $(this).val().toLowerCase().trim();
            
            // Si no hay texto de búsqueda, recarga la página
            if (textoBusqueda === '') {
                location.reload();
                return;
            }
            
            // Petición AJAX para buscar productos
            $.ajax({
                url: '../assets/php/MVC/Controlador/productos-controlador.php',
                type: 'GET',
                data: {
                    accion: 'buscar',
                    buscar: textoBusqueda
                },
                dataType: 'json',
                success: function(respuesta) {
                    if (respuesta.success) {
                        // Muestra los resultados y oculta paginación
                        mostrarResultados(respuesta.data);
                        $paginacion.hide();
                    } else {
                        // Maneja error en la búsqueda
                        console.error("Error en la búsqueda:", respuesta.message);
                    }
                },
                error: function(xhr, status, error) {
                    // Maneja error en la petición AJAX
                    console.error("Error AJAX:", error);
                }
            });
        });
    }
    
    // Función para mostrar los resultados en la tabla
    function mostrarResultados(productos) {
        const $tbody = $tablaProductos.find('tbody');
        $tbody.empty();
        
        // Si no hay productos, muestra mensaje
        if (productos.length === 0) {
            $tbody.html('<tr><td colspan="8" class="px-3 md:px-8 py-5 text-center text-gray-500">No hay productos disponibles</td></tr>');
            $mensajeNoResultados.show();
        } else {
            // Oculta mensaje y muestra productos
            $mensajeNoResultados.hide();
            
            // Itera sobre cada producto y crea su fila
            productos.forEach(function(producto) {
                const fila = `
                    <tr class="border-b">
                        <td class="px-3 md:px-8 py-3 md:py-5 text-center">
                            ${!producto.foto ? `
                                <!-- Muestra placeholder si no hay foto -->
                                <div class="w-12 h-12 bg-gray-200 rounded-md flex items-center justify-center mx-auto">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            ` : `
                                <!-- Muestra la foto del producto -->
                                <img src="../${escaparHtml(producto.foto)}" 
                                     alt="Foto de ${escaparHtml(producto.nombre)}" 
                                     class="w-12 h-12 object-cover rounded-md mx-auto cursor-pointer"
                                     onclick="mostrarGaleria('${escaparHtml(producto.foto)}', '${escaparHtml(producto.nombre)}')">
                            `}
                        </td>
                        <!-- Nombre del producto -->
                        <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base text-center">
                            ${escaparHtml(producto.nombre)}
                        </td>
                        <!-- Control de stock -->
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
                        <!-- Precio -->
                        <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base text-center">
                            ${escaparHtml(producto.precio)} €
                        </td>
                        <!-- Laboratorio -->
                        <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base text-center">
                            ${escaparHtml(producto.laboratorio ?? 'N/A')}
                        </td>
                        <!-- Fecha de registro -->
                        <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base text-center">
                            ${producto.fecha_registro ? new Date(producto.fecha_registro).toLocaleDateString('es-ES') : 'N/A'}
                        </td>
                        <!-- Comentarios -->
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
                        <!-- Acciones (solo para administradores) -->
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
    
    // Función auxiliar para escapar caracteres HTML especiales
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