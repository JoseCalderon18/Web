<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Incluir el controlador
require_once '../assets/php/MVC/Controlador/noticias-controlador.php';

// Crear instancia del controlador
$controlador = new NoticiasControlador();

// Verificar si es edición
$es_edicion = isset($_GET['id']) && !empty($_GET['id']);
$noticia = null;
$titulo_pagina = "Añadir Noticia";

// Si es edición, obtener la noticia
if ($es_edicion) {
    $noticia = $controlador->obtenerNoticiaPorId($_GET['id']);
    if (!$noticia) {
        header("Location: noticias.php");
        exit;
    }
    $titulo_pagina = "Editar Noticia";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo_pagina ?> - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
</head>
<body class="bg-beige">
    <!-- Header -->
    <?php require_once '../includes/header.php'; ?>

    <!-- Contenido principal -->
    <main class="py-12 px-4 md:px-24 max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
            <h1 class="text-2xl md:text-3xl font-serif font-bold text-green-800 mb-6 text-center"><?= $titulo_pagina ?></h1>
            
            <form id="newsForm" class="space-y-6" enctype="multipart/form-data">
                <?php if ($es_edicion): ?>
                    <input type="hidden" name="id" value="<?= $noticia['id'] ?>">
                    <?php if (!empty($noticia['imagen_url'])): ?>
                        <input type="hidden" name="imagen_actual" value="<?= $noticia['imagen_url'] ?>">
                    <?php endif; ?>
                <?php endif; ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                        <input type="text" id="titulo" name="titulo" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" 
                               value="<?= $es_edicion ? htmlspecialchars($noticia['titulo']) : '' ?>" 
                               required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="contenido" class="block text-sm font-medium text-gray-700 mb-1">Contenido</label>
                        <textarea id="contenido" name="contenido" rows="8" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" 
                                  required><?= $es_edicion ? htmlspecialchars($noticia['contenido']) : '' ?></textarea>
                    </div>
                    
                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha de publicación</label>
                        <input type="date" id="fecha" name="fecha" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" 
                               value="<?= $es_edicion ? $noticia['fecha_publicacion'] : date('Y-m-d') ?>" 
                               required>
                    </div>
                    
                    <div>
                        <label for="imagen" class="block text-sm font-medium text-gray-700 mb-1">
                            <?= $es_edicion ? 'Imagen (dejar vacío para mantener la actual)' : 'Imagen' ?>
                        </label>
                        <input type="file" id="imagen" name="imagen" accept="image/*" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" 
                               <?= $es_edicion ? '' : 'required' ?>>
                    </div>
                    
                    <div class="md:col-span-2">
                        <?php if ($es_edicion && !empty($noticia['imagen_url'])): ?>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-2">Imagen actual:</p>
                                <img id="preview" src="/<?= htmlspecialchars($noticia['imagen_url']) ?>" 
                                     alt="Vista previa" class="max-h-48 rounded-lg border border-gray-300">
                            </div>
                        <?php else: ?>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-2">Vista previa:</p>
                                <img id="preview" src="" alt="Vista previa" 
                                     class="max-h-48 rounded-lg border border-gray-300 hidden">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="flex justify-center space-x-4 pt-4">
                    <a href="noticias.php" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 transition-colors">
                        <?= $es_edicion ? 'Actualizar' : 'Publicar' ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <?php require_once '../includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/noticias.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
</body>
</html>
