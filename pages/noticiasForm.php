<?php
// Iniciar sesión al principio del archivo
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    // Si no está logueado, redirigir a login
    header("Location: login.php");
    exit;
}

// Incluir el controlador de noticias
require_once '../assets/php/MVC/Controlador/noticias-controlador.php';
$controlador = new NoticiasControlador();

// Verificar si es edición o nueva noticia
$esEdicion = isset($_GET['id']) && !empty($_GET['id']);
$noticia = null;

if ($esEdicion) {
    // Verificar si el usuario es administrador para editar
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
        header("Location: noticias.php");
        exit;
    }
    
    // Obtener la noticia para editar
    $noticia = $controlador->obtenerNoticiaPorId($_GET['id']);
    if (!$noticia) {
        header("Location: noticias.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $esEdicion ? 'Editar' : 'Añadir' ?> Noticia - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-beige flex flex-col min-h-screen">
    <!-- Header -->
    <?php require_once '../includes/header.php'; ?>

    <main class="flex-grow py-12 px-4 md:px-24 max-w-7xl mx-auto">
        <!-- Encabezado de sección -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-serif font-bold text-green-800 mb-4">
                <?= $esEdicion ? 'Editar Noticia' : 'Crear Nueva Noticia' ?>
            </h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                <?= $esEdicion ? 'Actualiza la información de tu noticia.' : 'Comparte información relevante con nuestra comunidad.' ?>
            </p>
        </div>

        <!-- Formulario de noticia -->
        <div class="bg-white rounded-lg shadow-md p-6 md:p-8 max-w-3xl mx-auto">
            <form id="newsForm" enctype="multipart/form-data" method="post">
                <?php if ($esEdicion): ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($noticia['id']) ?>">
                <?php endif; ?>
                
                <!-- Título -->
                <div class="mb-6">
                    <label for="titulo" class="block mb-2 text-sm font-medium text-gray-900">Título de la noticia</label>
                    <input type="text" id="titulo" name="titulo" 
                           value="<?= $esEdicion ? htmlspecialchars($noticia['titulo']) : '' ?>"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                </div>
                
                <!-- Fecha de publicación -->
                <div class="mb-6">
                    <label for="fecha" class="block mb-2 text-sm font-medium text-gray-900">Fecha de publicación</label>
                    <input type="date" id="fecha" name="fecha" 
                           value="<?= $esEdicion ? htmlspecialchars($noticia['fecha_publicacion']) : date('Y-m-d') ?>"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                </div>
                
                <!-- Contenido -->
                <div class="mb-6">
                    <label for="contenido" class="block mb-2 text-sm font-medium text-gray-900">Contenido</label>
                    <textarea id="contenido" name="contenido" rows="8" 
                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required><?= $esEdicion ? htmlspecialchars($noticia['contenido']) : '' ?></textarea>
                </div>
                
                <!-- Imagen -->
                <div class="mb-6">
                    <label for="imagen" class="block mb-2 text-sm font-medium text-gray-900">Imagen de la noticia</label>
                    <?php if ($esEdicion && !empty($noticia['imagen_url'])): ?>
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Imagen actual:</p>
                            <img src="../<?= htmlspecialchars($noticia['imagen_url']) ?>" 
                                 alt="Imagen actual de la noticia" 
                                 class="w-64 h-auto object-cover rounded-lg">
                            <input type="hidden" name="imagen_actual" value="<?= htmlspecialchars($noticia['imagen_url']) ?>">
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
                        <?php if ($esEdicion): ?>
                            Sube una nueva imagen solo si deseas cambiar la actual.
                        <?php else: ?>
                            Selecciona una imagen para la noticia.
                        <?php endif; ?>
                    </p>
                    <!-- Vista previa de la imagen -->
                    <div id="image-preview" class="mt-4">
                        <img id="preview" class="hidden max-w-full h-auto rounded-lg" alt="Vista previa">
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex gap-4">
                    <a href="noticias.php" class="w-1/2 text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 font-medium rounded-lg text-sm px-5 py-3 text-center transition-colors duration-300">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </a>
                    <button type="submit" class="w-1/2 text-white bg-green-700 hover:bg-green-800 focus:ring-4 font-medium rounded-lg text-sm px-5 py-3 text-center transition-colors duration-300">
                        <i class="fas fa-paper-plane mr-2"></i> <?= $esEdicion ? 'Actualizar Noticia' : 'Publicar Noticia' ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <?php require_once '../includes/footer.php'; ?>

    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/noticias.js"></script>
</body>
</html>
