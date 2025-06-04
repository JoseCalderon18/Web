<?php
require_once __DIR__ . '/../Controlador/usuarios-controlador.php';

// Crear instancia del controlador
$controlador = new UsuariosControlador();

// Obtener los datos de usuarios
$datosUsuarios = $controlador->obtenerTodosLosUsuarios();

// Extraer las variables para la vista
$usuarios = $datosUsuarios['usuarios'];
$paginaActual = $datosUsuarios['paginaActual'];
$totalPaginas = $datosUsuarios['totalPaginas'];
$total = $datosUsuarios['total'];
?>

<div class="bg-beige">

    <!-- Título y descripción -->
    <div class="mx-auto px-4 my-8 py-6">
        <h1 class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-center text-verde font-display-CormorantGaramond mb-4 py-4">
            Lista de Usuarios
        </h1>
        <p class="text-gray-700 text-center">
            Gestiona los usuarios del sistema, añade nuevos administradores, modifica roles y mantén el control de accesos.
        </p>
    </div>

    <div class="mx-auto px-6 mb-6 md:mb-10 w-full">
        <!-- barra de busqueda de usuarios y boton de añadir usuario -->
        <div class="flex flex-row text-black">
            <!-- barra de busqueda de usuarios -->
            <div class="me-auto px-2 mb-6 md:mb-10 w-1/2">
                <div class="relative flex items-center">
                    <input type="text" 
                           id="barraBusqueda" 
                           placeholder="Buscar usuario..." 
                           class="w-full pl-4 pr-12 py-3 text-gray-700 bg-white rounded-lg border-3 border-black outline-none"
                           autocomplete="off">
                    <button id="botonBuscar" 
                            class="absolute right-0 h-full px-4 text-white bg-green-800 rounded-r-lg hover:bg-green-700 transition-colors flex items-center justify-center">
                        <i class="fas fa-search text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Botón de añadir usuario -->
            <div class="ms-auto px-2 mb-6 md:mb-10">
                <?php if (isset($_SESSION["usuario_rol"])): ?>
                    <div class="flex justify-center sm:justify-end">
                        <a href="../pages/registro.php?from=admin" 
                           class="px-2 py-2 md:px-5 md:py-3 text-sm md:text-base bg-green-800 hover:bg-green-800 text-white rounded-lg shadow-md">
                            <i class="fas fa-user-plus mr-2"></i>Añadir Usuario
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="mx-auto px-2 mb-12 py-4">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border">
                <table class="w-full" id="tablaUsuarios">
                    <thead class="bg-black text-white">
                        <tr>
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

                <!-- Botón Anterior -->
                <a href="?pagina=<?= max(1, $paginaActual - 1) ?>" 
                   class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 <?= $paginaActual === 1 ? 'opacity-50 cursor-not-allowed' : '' ?>">
                    <i class="fas fa-angle-left"></i>
                </a>

                <!-- Números de página -->
                <?php
                $numeroPaginasAMostrar = 5;
                $mitad = floor($numeroPaginasAMostrar / 2);
                
                // Calcular inicio y fin
                if ($totalPaginas <= $numeroPaginasAMostrar) {
                    $inicio = 1;
                    $fin = $totalPaginas;
                } else {
                    $inicio = $paginaActual - $mitad;
                    $fin = $paginaActual + $mitad;
                    
                    if ($inicio < 1) {
                        $inicio = 1;
                        $fin = $numeroPaginasAMostrar;
                    }
                    
                    if ($fin > $totalPaginas) {
                        $fin = $totalPaginas;
                        $inicio = max(1, $totalPaginas - $numeroPaginasAMostrar + 1);
                    }
                }

                for ($i = $inicio; $i <= $fin; $i++):
                    $esActual = $i == $paginaActual;
                ?>
                    <a href="?pagina=<?= $i ?>" 
                       class="px-3 py-2 rounded-lg <?= $esActual ? 'bg-green-800 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <!-- Botón Siguiente -->
                <a href="?pagina=<?= min($totalPaginas, $paginaActual + 1) ?>" 
                   class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 <?= $paginaActual === $totalPaginas ? 'opacity-50 cursor-not-allowed' : '' ?>">
                    <i class="fas fa-angle-right"></i>
                </a>

                <!-- Última página -->
                <a href="?pagina=<?= $totalPaginas ?>" 
                   class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 <?= $paginaActual === $totalPaginas ? 'opacity-50 cursor-not-allowed' : '' ?>">
                    <i class="fas fa-angle-double-right"></i>
                </a>
            </div>

            <!-- Información de paginación -->
            <div class="text-center mt-4 text-gray-600 pb-8">
                Mostrando página <?= $paginaActual ?> de <?= $totalPaginas ?> 
                (<?= $total ?> usuarios en total)
            </div>
        <?php endif; ?>
    </div>
</div>
