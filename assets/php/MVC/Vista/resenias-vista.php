<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir el modelo para obtener las reseñas
require_once __DIR__ . '/../Modelo/resenias-modelo.php';

// Crear instancia del modelo
$resenias_modelo = new ReseniasModelo();

// Obtener las reseñas (con límite de 20)
$resultado_resenias = $resenias_modelo->obtenerResenias(20, 0);

// Obtener el promedio de puntuaciones
$promedio = $resenias_modelo->obtenerPromedioPuntuaciones();

// Función para obtener iniciales
function obtenerIniciales($nombre) {
    if (empty($nombre)) return '?';
    
    $palabras = explode(' ', $nombre);
    $iniciales = '';
    
    foreach ($palabras as $palabra) {
        if (!empty($palabra)) {
            $iniciales .= strtoupper(substr($palabra, 0, 1));
        }
    }
    
    return substr($iniciales, 0, 2);
}

// Función para formatear fecha
function formatearFecha($fecha) {
    $timestamp = strtotime($fecha);
    return date('d \d\e F \d\e Y', $timestamp);
}

// Función para generar estrellas
function generarEstrellas($puntuacion) {
    $puntuacionNum = floatval($puntuacion);
    $estrellas = '';
    
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $puntuacionNum) {
            // Estrella completa
            $estrellas .= '<i class="fas fa-star text-yellow-400"></i>';
        } else if ($i - 0.5 <= $puntuacionNum) {
            // Media estrella
            $estrellas .= '<i class="fas fa-star-half-alt text-yellow-400"></i>';
        } else {
            // Estrella vacía
            $estrellas .= '<i class="far fa-star text-yellow-400"></i>';
        }
    }
    
    return $estrellas;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseñas de Clientes</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome para las estrellas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Lightbox para las imágenes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Contenedor principal -->
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-serif font-bold text-green-800 mb-8 text-center">Opiniones de nuestros clientes</h2>
        
        <!-- Resumen de puntuaciones -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8 flex flex-col md:flex-row items-center justify-between">
            <div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Valoración general</h3>
                <p class="text-gray-500">Basado en <?= $promedio['total'] ?> reseñas</p>
            </div>
            <div class="flex items-center mt-4 md:mt-0">
                <div class="flex mr-2">
                    <?= generarEstrellas($promedio['promedio']) ?>
                </div>
                <span class="text-3xl font-bold text-gray-900"><?= $promedio['promedio'] ?></span>
            </div>
        </div>
        
        <!-- Grid de reseñas en 2 columnas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php 
            // Verificar si hay reseñas
            if (empty($resultado_resenias) || !isset($resultado_resenias['resenias']) || empty($resultado_resenias['resenias'])) { 
            ?>
                <div class="col-span-2 text-center py-8">
                    <p class="text-gray-500">Aún no hay reseñas. ¡Sé el primero en compartir tu experiencia!</p>
                </div>
            <?php 
            } else {
                // Iterar sobre las reseñas
                foreach ($resultado_resenias['resenias'] as $resenia) {
                    // Obtener iniciales para el avatar
                    $iniciales = obtenerIniciales($resenia['nombre_usuario']);
                    
                    // Formatear fecha
                    $fecha = formatearFecha($resenia['fecha']);
                    
                    // Generar estrellas
                    $estrellas = generarEstrellas($resenia['puntuacion']);
            ?>
                <!-- Tarjeta de reseña -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full bg-green-700 flex items-center justify-center text-white font-bold text-lg mr-4">
                                    <?= $iniciales ?>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900"><?= htmlspecialchars($resenia['nombre_usuario']) ?></h3>
                                    <p class="text-sm text-gray-500"><?= $fecha ?></p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <?= $estrellas ?>
                                <span class="ml-2 text-sm font-medium text-gray-600"><?= $resenia['puntuacion'] ?></span>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-4"><?= nl2br(htmlspecialchars($resenia['comentario'])) ?></p>
                        
                        <?php 
                        // Mostrar imágenes si existen
                        if (!empty($resenia['foto_url'])) {
                            $fotos = explode(';', rtrim($resenia['foto_url'], ';'));
                            if (!empty($fotos[0])) {
                                echo '<div class="grid grid-cols-' . min(count($fotos), 3) . ' gap-2 mt-4">';
                                foreach ($fotos as $foto) {
                                    echo '<a href="../' . $foto . '" class="block" data-lightbox="resena-' . $resenia['id'] . '">';
                                    echo '<img src="../' . $foto . '" alt="Imagen de reseña" class="rounded-lg object-cover w-full h-24 md:h-32">';
                                    echo '</a>';
                                }
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php 
                }
            } 
            ?>
        </div>
        
        <!-- Formulario para añadir reseña (solo para usuarios autenticados) -->
        <?php if (isset($_SESSION['usuario_id'])) { ?>
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md mt-12">
            <h2 class="text-2xl font-serif font-bold text-green-800 mb-6 text-center">Comparte tu experiencia</h2>
            
            <form id="form-resenia" action="../Controlador/resenias-controlador.php?accion=crearResenia" method="post" enctype="multipart/form-data" class="space-y-6">
                <!-- Sistema de calificación con estrellas -->
                <div>
                    <label class="block mb-4 text-sm font-medium text-gray-900 text-center">¿Cómo calificarías tu experiencia?</label>
                    <div class="rating-stars flex flex-row-reverse justify-center gap-2">
                        <svg data-rating="5.0" class="w-8 h-8 text-gray-300 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg data-rating="4.5" class="w-8 h-8 text-gray-300 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg data-rating="4.0" class="w-8 h-8 text-gray-300 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg data-rating="3.5" class="w-8 h-8 text-gray-300 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg data-rating="3.0" class="w-8 h-8 text-gray-300 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg data-rating="2.5" class="w-8 h-8 text-gray-300 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg data-rating="2.0" class="w-8 h-8 text-gray-300 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg data-rating="1.5" class="w-8 h-8 text-gray-300 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg data-rating="1.0" class="w-8 h-8 text-gray-300 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <svg data-rating="0.5" class="w-8 h-8 text-gray-300 cursor-pointer" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    <input type="hidden" id="rating" name="rating" value="">
                    <div class="text-xl font-bold mt-2 text-center">
                        <span id="ratingValue">0.0</span> / 5.0
                    </div>
                </div>
                
                <!-- Comentario -->
                <div>
                    <label for="comment" class="block mb-2 text-sm font-medium text-gray-900">Tu comentario</label>
                    <textarea id="comment" name="comment" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500" placeholder="Cuéntanos tu experiencia..."></textarea>
                </div>
                
                <!-- Subir fotos -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Añade fotos (opcional)</label>
                    <input type="file" id="photo-upload" name="photos[]" accept="image/*" multiple class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                    <p class="mt-1 text-sm text-gray-500">Máximo 3 imágenes (JPG, PNG, GIF)</p>
                    
                    <!-- Vista previa de imágenes -->
                    <div id="image-preview" class="grid grid-cols-3 gap-2 mt-2"></div>
                </div>
                
                <!-- Botón de envío -->
                <button type="submit" class="w-full text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Enviar reseña</button>
            </form>
        </div>
        <?php } else { ?>
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md mt-12 text-center">
            <p class="text-gray-700 mb-4">Para dejar una reseña, por favor <a href="login.php" class="text-green-700 hover:underline">inicia sesión</a> o <a href="registro.php" class="text-green-700 hover:underline">regístrate</a>.</p>
        </div>
        <?php } ?>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script src="../js/resenia-vista.js"></script>
</body>
</html>
