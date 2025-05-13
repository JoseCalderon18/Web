<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir el modelo para obtener las noticias
require_once __DIR__ . '/../Modelo/noticias-modelo.php';

// Crear instancia del modelo
$noticias_modelo = new NoticiasModelo();

// Obtener las noticias (con límite de 20)
$resultado_noticias = $noticias_modelo->obtenerNoticias(20, 0);

// Función para formatear fecha
function formatearFecha($fecha) {
    $timestamp = strtotime($fecha);
    return date('d \d\e F \d\e Y', $timestamp);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lightbox para las imágenes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Contenedor principal -->
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-serif font-bold text-green-800 mb-8 text-center">Últimas Noticias</h2>
        
        <!-- Grid de noticias en 2 columnas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php 
            // Verificar si hay noticias
            if (empty($resultado_noticias) || !isset($resultado_noticias['noticias']) || empty($resultado_noticias['noticias'])) { 
            ?>
                <div class="col-span-2 text-center py-8">
                    <p class="text-gray-500">No hay noticias disponibles en este momento.</p>
                </div>
            <?php 
            } else {
                // Iterar sobre las noticias
                foreach ($resultado_noticias['noticias'] as $noticia) {
                    // Formatear fecha
                    $fecha = formatearFecha($noticia['fecha']);
            ?>
                <!-- Tarjeta de noticia -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="font-medium text-gray-900 text-xl mb-2"><?= htmlspecialchars($noticia['titulo']) ?></h3>
                                <p class="text-sm text-gray-500"><?= $fecha ?></p>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-4"><?= nl2br(htmlspecialchars($noticia['contenido'])) ?></p>
                        
                        <?php 
                        // Mostrar imagen si existe
                        if (!empty($noticia['imagen_url'])) {
                            echo '<div class="mt-4">';
                            echo '<a href="../' . $noticia['imagen_url'] . '" class="block" data-lightbox="noticia-' . $noticia['id'] . '">';
                            echo '<img src="../' . $noticia['imagen_url'] . '" alt="Imagen de noticia" class="rounded-lg object-cover w-full h-48">';
                            echo '</a>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            <?php 
                }
            } 
            ?>
        </div>
        
        <!-- Formulario para añadir noticia (solo para administradores) -->
        <?php if (isset($_SESSION['usuario_id']) && $_SESSION['rol'] === 'admin') { ?>
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md mt-12">
            <h2 class="text-2xl font-serif font-bold text-green-800 mb-6 text-center">Añadir Nueva Noticia</h2>
            
            <form id="form-noticia" action="../Controlador/noticias-controlador.php?accion=crearNoticia" method="post" enctype="multipart/form-data" class="space-y-6">
                <!-- Título -->
                <div>
                    <label for="titulo" class="block mb-2 text-sm font-medium text-gray-900">Título</label>
                    <input type="text" id="titulo" name="titulo" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500" required>
                </div>
                
                <!-- Contenido -->
                <div>
                    <label for="contenido" class="block mb-2 text-sm font-medium text-gray-900">Contenido</label>
                    <textarea id="contenido" name="contenido" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500" required></textarea>
                </div>
                
                <!-- Subir imagen -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Imagen de la noticia</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                    <p class="mt-1 text-sm text-gray-500">Formatos permitidos: JPG, PNG, GIF</p>
                    
                    <!-- Vista previa de imagen -->
                    <div id="image-preview" class="mt-2"></div>
                </div>
                
                <!-- Botón de envío -->
                <button type="submit" class="w-full text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Publicar Noticia</button>
            </form>
        </div>
        <?php } ?>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script src="../js/noticia-vista.js"></script>
</body>
</html>
