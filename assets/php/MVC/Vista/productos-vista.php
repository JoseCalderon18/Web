<div class="bg-beige">
    <!-- Título y descripción -->
    <div class=" mx-auto px-4 my-8 py-6">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-center text-verde font-display-CormorantGaramond mb-4 py-4">
            Lista de Productos
        </h1>
        <p class="text-gray-700 text-center">
            Administra el inventario del herbolario, gestiona productos, precios, stock y mantén la información actualizada.
        </p>
    </div>

    <div class="mx-auto px-6 mb-6 md:mb-10 w-full">
        <!-- Barra de búsqueda y botón de añadir -->
        <div class="flex flex-row text-black">
            <!-- Barra de búsqueda -->
            <div class="me-auto px-2 mb-6 md:mb-10 w-1/2">
                <div class="relative flex items-center">
                    <input type="text" 
                           id="barraBusqueda" 
                           placeholder="Buscar producto..." 
                           class="w-full pl-4 pr-12 py-3 text-gray-700 bg-white rounded-lg border-3 border-black outline-none"
                           autocomplete="off">
                    <button class="absolute right-0 h-full px-4 text-white bg-green-800 rounded-r-lg hover:bg-green-700 transition-colors flex items-center justify-center">
                        <i class="fas fa-search text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Botón de añadir producto -->
            <div class="ms-auto px-2 mb-6 md:mb-10">
                <?php if (isset($_SESSION["usuario_rol"]) && $_SESSION["usuario_rol"] === "admin") { ?>
                    <div class="flex justify-center sm:justify-end">
                        <a href="productosForm.php" 
                           class="px-2 py-2 md:px-5 md:py-3 text-sm md:text-base bg-green-800 hover:bg-green-800 text-white rounded-lg shadow-md">
                            <i class="fas fa-plus mr-2"></i>Añadir Producto
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="mx-auto px-2 mb-12 py-4">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border">
                <table class="w-full" id="tablaProductos">
                    <thead class="bg-black text-white">
                        <tr>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-center text-xs md:text-sm">FOTO</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-center text-xs md:text-sm">NOMBRE</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-center text-xs md:text-sm">STOCK</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-center text-xs md:text-sm">PRECIO</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-center text-xs md:text-sm">LABORATORIO</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-center text-xs md:text-sm">FECHA REGISTRO</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-center text-xs md:text-sm">COMENTARIOS</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-center text-xs md:text-sm">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productos)): ?>
                            <tr>
                                <td colspan="8" class="px-3 md:px-8 py-5 text-center text-gray-500">
                                    No hay productos disponibles
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productos as $producto): ?>
                                <tr class="border-b">
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-center">
                                        <?php if (!empty($producto["foto"])): ?>
                                            <img src="../<?= htmlspecialchars($producto["foto"]) ?>" 
                                                 alt="Foto de <?= htmlspecialchars($producto["nombre"]) ?>" 
                                                 class="w-12 h-12 object-cover rounded-md mx-auto cursor-pointer"
                                                 onclick="mostrarGaleria('<?= htmlspecialchars($producto["foto"]) ?>', '<?= htmlspecialchars($producto["nombre"]) ?>')">
                                        <?php else: ?>
                                            <div class="w-12 h-12 bg-gray-200 rounded-md flex items-center justify-center mx-auto">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base text-center">
                                        <?= htmlspecialchars($producto["nombre"]) ?>
                                    </td>
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base">
                                        <div class="flex items-center justify-center">
                                            <button 
                                                onclick="sumarUnidad(<?= $producto['id'] ?>)"
                                                class="px-2 py-1 text-xs bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-sm cursor-pointer mr-2"
                                                title="Sumar una unidad">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            
                                            <span class="mx-2 font-medium"><?= htmlspecialchars($producto["stock"] ?? 0) ?></span>
                                            
                                            <button 
                                                onclick="restarUnidad(<?= $producto['id'] ?>, <?= $producto['stock'] ?>)"
                                                class="px-2 py-1 text-xs bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg shadow-sm cursor-pointer ml-2"
                                                <?= ($producto["stock"] <= 0) ? 'disabled' : '' ?>
                                                title="Restar una unidad">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base text-center">
                                        <?= htmlspecialchars($producto["precio"]) ?> €
                                    </td>
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base text-center">
                                        <?= htmlspecialchars($producto["laboratorio"] ?? 'N/A') ?>
                                    </td>
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base text-center">
                                        <?= $producto["fecha_registro"] ? date('d/m/Y', strtotime($producto["fecha_registro"])) : 'N/A' ?>
                                    </td>
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-center">
                                        <?php if (!empty($producto["comentarios"])): ?>
                                            <button 
                                                onclick="mostrarComentarios('<?= htmlspecialchars($producto["nombre"]) ?>', '<?= htmlspecialchars($producto["comentarios"]) ?>')"
                                                class="px-3 py-1 text-xs bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-sm cursor-pointer">
                                                <i class="fas fa-comment-alt mr-1"></i>Ver comentarios
                                            </button>
                                        <?php else: ?>
                                            <span class="text-gray-400">Sin comentarios</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-center">
                                        <?php if (isset($_SESSION["usuario_rol"]) && $_SESSION["usuario_rol"] === "admin"): ?>
                                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-5 justify-center">
                                                <button 
                                                    onclick="confirmarEliminacion(<?= $producto['id'] ?>)"
                                                    class="px-3 py-2 md:px-4 md:py-2 text-xs md:text-sm bg-red-600 hover:bg-red-800 text-white rounded-lg shadow-sm cursor-pointer">
                                                    <i class="fas fa-trash-alt mr-1 md:mr-2"></i>Eliminar
                                                </button>
                                                <a href="productosForm.php?id=<?= $producto['id'] ?>"
                                                   class="px-3 py-2 md:px-4 md:py-2 text-xs md:text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-sm cursor-pointer text-center">
                                                    <i class="fas fa-edit mr-1 md:mr-2"></i>Editar
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Mensaje cuando no hay resultados -->
            <div id="mensajeNoResultados" class="text-center py-4 text-gray-500" style="display: none;">
                No se encontraron resultados para la búsqueda
            </div>
        </div>

        <!-- Paginación -->
        <?php if ($totalPaginas > 0): ?>
            <div class="flex justify-center mt-4 gap-2 pt-8">
                <!-- Primera página -->
                <a href="?pagina=1" 
                   class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 <?= $paginaActual === 1 ? 'opacity-50 cursor-not-allowed' : '' ?>">
                    <i class="fas fa-angle-double-left"></i>
                </a>

                <!-- Números de página -->
                <?php
                $inicio = max(1, $paginaActual - 2);
                $fin = min($totalPaginas, $paginaActual + 2);

                for ($i = $inicio; $i <= $fin; $i++):
                ?>
                    <a href="?pagina=<?= $i ?>" 
                       class="px-3 py-2 rounded-lg <?= $i === $paginaActual ? 'bg-green-800 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <!-- Última página -->
                <a href="?pagina=<?= $totalPaginas ?>" 
                   class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 <?= $paginaActual === $totalPaginas ? 'opacity-50 cursor-not-allowed' : '' ?>">
                    <i class="fas fa-angle-double-right"></i>
                </a>
            </div>

            <!-- Información de paginación -->
            <div class="text-center mt-4 text-gray-600 mb-8 pb-8">
                Mostrando página <?= $paginaActual ?> de <?= $totalPaginas ?> 
                (<?= $total ?> productos en total)
            </div>
        <?php endif; ?>
    </div>
</div>