<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir el controlador para obtener las noticias
require_once '../Controlador/noticias-controlador.php';

// Crear instancia del controlador
$controlador = new NoticiasControlador();

// Obtener las noticias (con límite de 6 por página)
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$resultado_noticias = $controlador->obtenerNoticias(6, ($pagina - 1) * 6);
$noticias = $resultado_noticias['noticias'];
$total_noticias = $resultado_noticias['total'];
$total_paginas = ceil($total_noticias / 6);

?>

<main class="mx-auto py-8 w-3/4 mb-10">
    <!-- Cabecera con título y botones -->
    <div class="flex justify-between items-center mb-8">
        <!-- Título -->
        <div class="text-center flex-grow">
            <h1 class="text-5xl font-bold text-green-800 mb-3 font-display-CormorantGaramond py-6">Noticias y Artículos</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Mantente informado sobre las últimas novedades en agricultura ecológica.
            </p>
        </div>
    </div>

    <!-- Barra de botones -->
    <div class="flex justify-between items-center mb-8 px-4">
        <!-- Botón de añadir noticia (izquierda) -->
        <div>
            <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                <a href="noticiasForm.php" 
                   class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-md">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Añadir Artículo
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Grid de noticias -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6">
        <?php if(empty($noticias)): ?>
            <div class="col-span-full text-center py-8">
                <p class="text-gray-500">No hay noticias disponibles en este momento.</p>
            </div>
        <?php else: ?>
            <?php foreach ($noticias as $noticia): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden h-full aparecer-secuencial">
                    <?php if (!empty($noticia['imagen_url'])): ?>
                        <img src="/<?= htmlspecialchars($noticia['imagen_url']) ?>" 
                             alt="<?= htmlspecialchars($noticia['titulo']) ?>" 
                             class="w-full h-48 object-cover">
                    <?php else: ?>
                        <div class="w-full h-48 bg-green-100 flex items-center justify-center">
                            <i class="fas fa-newspaper text-green-800 text-4xl"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-green-800 mb-2"><?= htmlspecialchars($noticia['titulo']) ?></h3>
                        <p class="text-sm text-gray-500 mb-2">
                            <i class="far fa-calendar-alt mr-2"></i> <?= date('d/m/Y', strtotime($noticia['fecha_publicacion'])) ?>
                        </p>
                        <p class="text-gray-600 mb-4"><?= htmlspecialchars(substr($noticia['contenido'], 0, 150)) ?>...</p>
                        <div class="flex justify-between items-center">
                            <button class="ver-noticia text-green-700 hover:text-green-900" data-id="<?= $noticia['id'] ?>">
                                Leer más <i class="fas fa-arrow-right ml-1"></i>
                            </button>
                            
                            <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                                <div class="flex space-x-2">
                                    <a href="noticiasForm.php?id=<?= $noticia['id'] ?>" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="eliminar-noticia text-red-600 hover:text-red-800" 
                                            data-id="<?= $noticia['id'] ?>" 
                                            data-titulo="<?= htmlspecialchars($noticia['titulo']) ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Paginación -->
    <?php if ($total_paginas > 1): ?>
        <div class="flex justify-center mt-4 gap-2 pt-8">
            <!-- Primera página -->
            <a href="?pagina=1" 
               class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 <?= $pagina === 1 ? 'opacity-50 cursor-not-allowed' : '' ?>">
                <i class="fas fa-angle-double-left"></i>
            </a>

            <!-- Botón Anterior -->
            <a href="?pagina=<?= max(1, $pagina - 1) ?>" 
               class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 <?= $pagina === 1 ? 'opacity-50 cursor-not-allowed' : '' ?>">
                <i class="fas fa-angle-left"></i>
            </a>

            <!-- Números de página -->
            <?php
            $numeroPaginasAMostrar = 5;
            $mitad = floor($numeroPaginasAMostrar / 2);
            
            // Calcular inicio y fin
            if ($total_paginas <= $numeroPaginasAMostrar) {
                $inicio = 1;
                $fin = $total_paginas;
            } else {
                $inicio = $pagina - $mitad;
                $fin = $pagina + $mitad;
                
                if ($inicio < 1) {
                    $inicio = 1;
                    $fin = $numeroPaginasAMostrar;
                }
                
                if ($fin > $total_paginas) {
                    $fin = $total_paginas;
                    $inicio = max(1, $total_paginas - $numeroPaginasAMostrar + 1);
                }
            }

            for ($i = $inicio; $i <= $fin; $i++):
                $esActual = $i == $pagina;
            ?>
                <a href="?pagina=<?= $i ?>" 
                   class="px-3 py-2 rounded-lg <?= $esActual ? 'bg-green-800 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <!-- Botón Siguiente -->
            <a href="?pagina=<?= min($total_paginas, $pagina + 1) ?>" 
               class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 <?= $pagina === $total_paginas ? 'opacity-50 cursor-not-allowed' : '' ?>">
                <i class="fas fa-angle-right"></i>
            </a>

            <!-- Última página -->
            <a href="?pagina=<?= $total_paginas ?>" 
               class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 <?= $pagina === $total_paginas ? 'opacity-50 cursor-not-allowed' : '' ?>">
                <i class="fas fa-angle-double-right"></i>
            </a>
        </div>

        <!-- Información de paginación -->
        <div class="text-center mt-4 text-gray-600 pb-8">
            Mostrando página <?= $pagina ?> de <?= $total_paginas ?> 
            (<?= $total_noticias ?> noticias en total)
        </div>
    <?php endif; ?>
</main>
