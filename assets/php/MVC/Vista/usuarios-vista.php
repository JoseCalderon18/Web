<div class="bg-beige">
    <!-- separado del header -->
    <div class="bg-beige h-32"></div>

    <!-- Header con datos del usuario -->
    <div class="bg-gray-800 w-full p-4 mb-4 md:mb-8">
        <div class=" mx-auto flex flex-col sm:flex-row flex-wrap justify-around items-center text-white gap-3">
            <span class="py-2 text-sm md:text-base">
                <i class="fas fa-user mr-2"></i>
                Usuario: <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
            </span>
            <span class="py-2 text-sm md:text-base">
                <i class="fas fa-clock mr-2"></i>
                Última conexión: <?= date('d/m/Y H:i:s') ?>
            </span>
            <div class="flex gap-3 md:gap-5">
                <a href="noticias.php" 
                   class="px-4 py-2 md:px-6 md:py-3 text-sm md:text-base font-medium bg-green-800 hover:bg-green-800 rounded-lg shadow-md">
                    <i class="fas fa-blog mr-2 md:mr-3"></i>Noticias
                </a>
                <a href="../assets/php/MVC/Controlador/usuarios-controlador.php?accion=cerrarSesion" 
                   class="px-5 py-2 md:px-7 md:py-3 text-sm md:text-base font-medium bg-red-600 hover:bg-red-800 rounded-lg shadow-md">
                    <i class="fas fa-sign-out-alt mr-2 md:mr-3"></i>Cerrar sesión
                </a>
            </div>
        </div>
    </div>

    <!-- Título principal -->
    <h1 class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-center text-verde font-display-CormorantGaramond my-6 sm:my-8 md:my-10 lg:my-12 px-4">
        Lista de Usuarios
    </h1>

    <div class="mx-auto px-6 mb-6 md:mb-10 w-full">
        <!-- barra de busqueda de usuarios y boton de añadir usuario -->
        <div class="flex flex-row text-black">
            <!-- barra de busqueda de usuarios -->
            <div class="me-auto px-2 mb-6 md:mb-10 w-1/2">
                <input type="text" id="barraBusqueda" placeholder="Buscar usuario" class="w-full p-2 border border-black rounded-md">
            </div>

            <!-- Botón de añadir usuario -->
            <div class=" ms-auto px-2 mb-6 md:mb-10">
                <?php if (isset($_SESSION["usuario_rol"])) { ?>
                    <div class="flex justify-center sm:justify-end">
                        <a href="../pages/registro.php" 
                        class="px-2 py-2 md:px-5 md:py-3 text-sm md:text-base bg-green-800 hover:bg-green-800 text-white rounded-lg shadow-md">
                            <i class="fas fa-user-plus mr-2"></i>Añadir Usuario
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class=" mx-auto px-2 mb-12 ">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border">
                <table class="w-full">
                    <thead class="bg-black text-white">
                        <tr>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-left text-xs md:text-base font-semibold">ID</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-left text-xs md:text-base font-semibold">NOMBRE</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-left text-xs md:text-base font-semibold">EMAIL</th>
                            <th class="px-3 md:px-8 py-3 md:py-4 text-left text-xs md:text-base font-semibold">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="4" class="px-3 md:px-8 py-4 md:py-6 text-center text-gray-500 text-sm md:text-base">
                                    No hay usuarios registrados
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr class="border-b">
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base"><?= htmlspecialchars($usuario["id"]) ?></td>
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base font-medium"><?= htmlspecialchars($usuario["nombre"]) ?></td>
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base"><?= htmlspecialchars($usuario["email"]) ?></td>
                                    <td class="px-3 md:px-8 py-3 md:py-5">
                                        <?php if (isset($_SESSION["usuario_rol"]) && $usuario["id"] !== "1"): ?>
                                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-5">
                                                <!-- Botón para cambiar rol -->
                                                <button 
                                                    onclick="cambiarRol(<?= $usuario['id'] ?>, '<?= $usuario['rol'] ?>')"
                                                    class="px-3 py-2 md:px-4 md:py-2 text-xs md:text-sm <?= $usuario['rol'] === 'admin' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-blue-500 hover:bg-blue-600' ?> text-white rounded-lg shadow-sm cursor-pointer">
                                                    <i class="fas <?= $usuario['rol'] === 'admin' ? 'fa-user-minus' : 'fa-user-shield' ?> mr-1 md:mr-2"></i>
                                                    <?= $usuario['rol'] === 'admin' ? 'Quitar Admin' : 'Hacer Admin' ?>
                                                </button>

                                                <!-- Botón para eliminar usuario -->
                                                <button 
                                                    onclick="confirmarEliminacion(<?= $usuario['id'] ?>)"
                                                    class="px-3 py-2 md:px-4 md:py-2 text-xs md:text-sm bg-red-600 hover:bg-red-800 text-white rounded-lg shadow-sm cursor-pointer">
                                                    <i class="fas fa-trash-alt mr-1 md:mr-2"></i>Eliminar
                                                </button>
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
