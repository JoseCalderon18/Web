<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario es administrador
$es_admin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
?>

<main class="py-12 px-4 md:px-24 max-w-7xl mx-auto">
    <!-- Encabezado de sección -->
    <div class="text-center mb-12">
        <h1 class="text-3xl md:text-4xl font-serif font-bold text-green-800 mb-4">Bienvenidos a nuestro blog</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">Aquí podrás informarte sobre novedades y noticias relacionadas con los cuidados, la salud y el bienestar tanto de tu cuerpo como de tu mente.</p>
    </div>

    <!-- Botones de añadir noticia -->
    <div class="flex justify-center mx-auto py-10">
        <?php if(isset($_SESSION['usuario_id'])): ?>
            <a href="noticiasForm.php" class="bg-green-700 hover:bg-green-800 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md flex items-center">
                <i class="fas fa-plus-circle mr-2"></i> Añadir Noticia
            </a>
        <?php else: ?>
            <a href="login.php" class="bg-green-700 hover:bg-green-800 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md flex items-center">
                <i class="fas fa-sign-in-alt mr-2"></i> Inicia sesión para publicar
            </a>
        <?php endif; ?>
    </div>

    <!-- Grid de noticias -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php if(empty($noticias)): ?>
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg">No hay noticias disponibles en este momento.</p>
            </div>
        <?php else: ?>
            <?php foreach($noticias as $noticia): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                    <?php if(!empty($noticia['imagen_url'])): ?>
                        <div class="h-48 overflow-hidden">
                            <img src="../<?= htmlspecialchars($noticia['imagen_url']) ?>" 
                                alt="<?= htmlspecialchars($noticia['titulo']) ?>" 
                                class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <h2 class="text-xl font-bold text-green-800 mb-3"><?= htmlspecialchars($noticia['titulo']) ?></h2>
                            
                            <?php if($es_admin): ?>
                                <div class="flex space-x-3">
                                    <a href="noticiasForm.php?id=<?= $noticia['id'] ?>" 
                                       class="text-blue-500 hover:text-blue-700 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="text-red-500 hover:text-red-700 transition-colors eliminar-noticia" 
                                       data-id="<?= $noticia['id'] ?>" 
                                       data-titulo="<?= htmlspecialchars($noticia['titulo']) ?>">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <p class="text-sm text-gray-500 mb-4">
                            <i class="far fa-calendar-alt mr-2"></i><?= date('d \d\e F \d\e Y', strtotime($noticia['fecha_publicacion'])) ?>
                            <?php if(!empty($noticia['autor_nombre'])): ?>
                                <span class="ml-4"><i class="far fa-user mr-2"></i><?= htmlspecialchars($noticia['autor_nombre']) ?></span>
                            <?php endif; ?>
                        </p>
                        <div class="text-gray-700 mb-4">
                            <?php 
                            $contenido = $noticia['contenido'];
                            echo (strlen($contenido) > 200) ? htmlspecialchars(substr($contenido, 0, 200)) . '...' : htmlspecialchars($contenido);
                            ?>
                        </div>
                        <a href="#" class="ver-noticia inline-block bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300" data-id="<?= $noticia['id'] ?>">
                            Leer más
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Paginación -->
    <?php if($total_paginas > 1): ?>
    <div class="flex justify-center my-12">
        <nav aria-label="Paginación">
            <ul class="flex items-center -space-x-px h-10 text-base">
                <li>
                    <a href="<?= $pagina > 1 ? '?pagina=' . ($pagina - 1) : '#' ?>" 
                       class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 <?= $pagina <= 1 ? 'opacity-50 cursor-not-allowed' : '' ?>">
                        <span class="sr-only">Anterior</span>
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
                
                <?php for($i = 1; $i <= $total_paginas; $i++): ?>
                <li>
                    <a href="?pagina=<?= $i ?>" 
                       class="flex items-center justify-center px-4 h-10 leading-tight <?= $i == $pagina ? 'text-white bg-green-700 hover:bg-green-800' : 'text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700' ?> border border-gray-300">
                        <?= $i ?>
                    </a>
                </li>
                <?php endfor; ?>
                
                <li>
                    <a href="<?= $pagina < $total_paginas ? '?pagina=' . ($pagina + 1) : '#' ?>" 
                       class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 <?= $pagina >= $total_paginas ? 'opacity-50 cursor-not-allowed' : '' ?>">
                        <span class="sr-only">Siguiente</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</main>
