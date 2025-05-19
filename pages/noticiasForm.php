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
<body class="bg-beige flex flex-col">
    <!-- Header -->
    <?php require_once '../includes/header.php'; ?>

    <!-- Contenido principal -->
    <main class="flex-grow py-12 px-4 md:px-24">
        <!-- Encabezado de sección -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-green-800 mb-4 font-display-CormorantGaramond">
                <?= $titulo_pagina ?>
            </h1>
            <p class="text-gray-600 max-w-2xl my-5 mx-auto">
                Complete los detalles de la noticia a continuación.
            </p>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-xl my-4 md:my-10 p-4 md:p-8 shadow-lg max-w-2xl mx-auto">
            <form id="newsForm" class="space-y-6" enctype="multipart/form-data">
                <?php if ($es_edicion): ?>
                    <input type="hidden" name="id" value="<?= $noticia['id'] ?>">
                    <?php if (!empty($noticia['imagen_url'])): ?>
                        <input type="hidden" name="imagen_actual" value="<?= $noticia['imagen_url'] ?>">
                    <?php endif; ?>
                <?php endif; ?>
                
                <!-- Título -->
                <div class="mb-4">
                    <label for="titulo" class="block mb-2 text-sm font-medium text-gray-900">Título <span class="text-red-500">*</span></label>
                    <input type="text" id="titulo" name="titulo" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" 
                           value="<?= $es_edicion ? htmlspecialchars($noticia['titulo']) : '' ?>" 
                           required>
                </div>
                
                <!-- Contenido -->
                <div class="mb-4">
                    <label for="contenido" class="block mb-2 text-sm font-medium text-gray-900">Contenido <span class="text-red-500">*</span></label>
                    <textarea id="contenido" name="contenido" rows="6" 
                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" 
                              required><?= $es_edicion ? htmlspecialchars($noticia['contenido']) : '' ?></textarea>
                    <p class="mt-1 text-sm text-gray-500">Describe detalladamente la noticia o artículo.</p>
                </div>
                
                <!-- Fecha de publicación -->
                <div class="mb-4">
                    <label for="fecha" class="block mb-2 text-sm font-medium text-gray-900">Fecha de publicación <span class="text-red-500">*</span></label>
                    <input type="date" id="fecha" name="fecha" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" 
                           value="<?= $es_edicion ? $noticia['fecha_publicacion'] : date('Y-m-d') ?>" 
                           required>
                </div>
                
                <!-- Imagen (opcional) -->
                <div class="mb-4">
                    <label for="imagen" class="block mb-2 text-sm font-medium text-gray-900">
                        Imagen <?= $es_edicion ? '(opcional)' : '(opcional)' ?>
                    </label>
                    <?php if ($es_edicion && !empty($noticia['imagen_url'])): ?>
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Imagen actual:</p>
                            <img id="preview" src="/<?= htmlspecialchars($noticia['imagen_url']) ?>" 
                                 alt="Vista previa" class="w-32 h-32 object-cover rounded-lg">
                            <input type="hidden" name="imagen_actual" value="<?= $noticia['imagen_url'] ?>">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="imagen" name="imagen" accept="image/*" 
                           class="mt-1 block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-green-50 file:text-green-700
                                  hover:file:bg-green-100">
                    <p class="mt-1 text-sm text-gray-500">
                        <?php if ($es_edicion): ?>
                            Sube una nueva imagen solo si deseas cambiar la actual.
                        <?php else: ?>
                            Selecciona una imagen para la noticia. Si no seleccionas ninguna, se usará un color de fondo verde en la tarjeta.
                        <?php endif; ?>
                    </p>
                    
                    <?php if (!$es_edicion || empty($noticia['imagen_url'])): ?>
                        <div class="mt-2">
                            <img id="preview" src="" alt="Vista previa" class="w-32 h-32 object-cover rounded-lg hidden">
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex justify-end space-x-4">
                    <a href="noticias.php" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <?php if ($es_edicion): ?>
                            Guardar cambios
                        <?php else: ?>
                            Publicar noticia
                        <?php endif; ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <?php require_once '../includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="../assets/js/script.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/productos.js"></script>
</body>
</html>
