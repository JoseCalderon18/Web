<div class="bg-beige">
    <!-- separado del header -->
    <div class="bg-beige h-2"></div>
    <!-- Header con datos del usuario -->
    <div class="bg-gray-800 w-full p-4 mb-4 md:mb-8">
        <div class="mx-auto flex flex-col sm:flex-row flex-wrap justify-around items-center text-white gap-3">
            <span class="py-2 text-sm md:text-base">
                <i class="fas fa-user mr-2"></i>
                Usuario: <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
            </span>
            <span class="py-2 text-sm md:text-base">
                <i class="fas fa-clock mr-2"></i>
                Última conexión: <?= date('d/m/Y H:i:s') ?>
            </span>
            <div class="flex gap-4">
                <a href="noticias.php" 
                   class="px-4 py-2 bg-green-700 hover:bg-green-800 rounded-lg transition-colors">
                    <i class="fas fa-blog mr-2"></i>Noticias
                </a>
                <a href="../assets/php/MVC/Controlador/usuarios-controlador.php?accion=cerrarSesion" 
                   class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                    <i class="fas fa-sign-out-alt mr-2"></i>Cerrar sesión
                </a>
            </div>
        </div>
    </div>

    <!-- Título principal -->
    <h1 class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-center text-verde font-display-CormorantGaramond my-6 sm:my-8 md:my-10 lg:my-12 px-4">
        Lista de Productos
    </h1>

    <div class="mx-auto px-6 mb-6 md:mb-10 w-full">
        <!-- Barra de búsqueda y botón de añadir -->
        <div class="flex flex-row text-black">
            <!-- Barra de búsqueda -->
            <div class="me-auto px-2 mb-6 md:mb-10 w-1/2">
                <input type="text" id="barraBusqueda" placeholder="Buscar producto" 
                       class="w-full p-2 border border-black rounded-md">
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
        <div class="mx-auto px-2 mb-12">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border">
                <table class="w-full">
                    <thead class="bg-black text-white">
                        <tr>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-left text-xs md:text-sm">ID</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-left text-xs md:text-sm">FOTO</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-left text-xs md:text-sm">NOMBRE</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-left text-xs md:text-sm">STOCK</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-left text-xs md:text-sm">PRECIO</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-left text-xs md:text-sm">FECHA REGISTRO</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-left text-xs md:text-sm">COMENTARIOS</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-left text-xs md:text-sm">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productos)): ?>
                            <tr>
                                <td colspan="7" class="px-3 md:px-8 py-4 md:py-6 text-center text-gray-500 text-sm md:text-base">
                                    No hay productos registrados
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productos as $producto): ?>
                                <tr class="border-b">
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base"><?= htmlspecialchars($producto["id"]) ?></td>
                                    <td class="px-3 md:px-8 py-3 md:py-5">
                                        <?php if (!empty($producto["foto"])): ?>
                                            <img src="../<?= htmlspecialchars($producto["foto"]) ?>" 
                                                 onclick="mostrarGaleria('<?= htmlspecialchars($producto["foto"]) ?>', '<?= htmlspecialchars($producto["nombre"]) ?>')"
                                                 alt="Foto de <?= htmlspecialchars($producto["nombre"]) ?>"
                                                 class="w-16 h-16 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity">
                                        <?php else: ?>
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base font-medium">
                                        <?= htmlspecialchars($producto["nombre"]) ?>
                                    </td>
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base">
                                        <?= htmlspecialchars($producto["stock"] ?? 0) ?>
                                    </td>
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base">
                                        <?= htmlspecialchars($producto["precio"]) ?> €
                                    </td>
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base">
                                        <?= $producto["fecha_registro"] ? date('d/m/Y', strtotime($producto["fecha_registro"])) : 'N/A' ?>
                                    </td>
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base">
                                        <?php if (!empty($producto["comentarios"])): ?>
                                            <button onclick="mostrarComentarios('<?= htmlspecialchars($producto["nombre"]) ?>', '<?= htmlspecialchars($producto["comentarios"]) ?>')"
                                                    class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-comment-alt mr-1"></i>Ver comentarios
                                            </button>
                                        <?php else: ?>
                                            <span class="text-gray-400">Sin comentarios</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 md:px-8 py-3 md:py-5">
                                        <?php if (isset($_SESSION["usuario_rol"]) && $_SESSION["usuario_rol"] === "admin"): ?>
                                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-5">
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
        </div>
    </div>
</div>
