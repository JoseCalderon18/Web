<?php
// Iniciar sesión al principio del archivo
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    // Si no está logueado, redirigir a login
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Reseña - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos mínimos para el sistema de estrellas */
        .rating-stars svg {
            cursor: pointer;
            transition: color 0.2s;
        }
        .rating-stars svg:hover,
        .rating-stars svg:hover ~ svg {
            color: #FFB700 !important;
        }
    </style>
</head>
<body class="bg-beige flex flex-col min-h-screen">
    <!-- Header -->
    <?php require_once '../includes/header.php'; ?>

    <main class="flex-grow py-12 px-4 md:px-24 max-w-7xl mx-auto">
        <!-- Encabezado de sección -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-green-800 mb-4 font-display-CormorantGaramond">Comparte tu experiencia</h1>
            <p class="text-gray-600 max-w-2xl my-5 mx-auto">Tu opinión es muy importante para nosotros. Comparte tu experiencia con BioEspacio y ayuda a otros a conocer nuestros productos y servicios.</p>
        </div>

        <!-- Formulario de reseña -->
        <div class="bg-white rounded-xl my-10 p-6 md:p-8 shadow-lg max-w-2xl mx-auto">
            <form id="reviewForm" class="space-y-6" enctype="multipart/form-data">
                <!-- Nombre del usuario (automático) -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Usuario</label>
                    <input type="text" value="<?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" readonly>
                </div>
                
                <!-- Sistema de calificación con estrellas usando Flowbite -->
                <div class="bg-gray-50 border border-gray-300 rounded-lg p-4">
                    <label class="block mb-4 text-sm font-medium text-gray-900 text-center">¿Cómo calificarías tu experiencia?</label>
                    
                    <div class="rating-stars flex flex-row-reverse justify-center space-x-1 space-x-reverse">
                        <!-- Estrella 5 -->
                        <svg data-rating="5" class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        
                        <!-- Estrella 4.5 -->
                        <svg data-rating="4.5" class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="halfStar45" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="50%" stop-color="currentColor" />
                                    <stop offset="50%" stop-color="transparent" stop-opacity="1" />
                                </linearGradient>
                            </defs>
                            <path fill="url(#halfStar45)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        
                        <!-- Estrella 4 -->
                        <svg data-rating="4" class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        
                        <!-- Estrella 3.5 -->
                        <svg data-rating="3.5" class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="halfStar35" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="50%" stop-color="currentColor" />
                                    <stop offset="50%" stop-color="transparent" stop-opacity="1" />
                                </linearGradient>
                            </defs>
                            <path fill="url(#halfStar35)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        
                        <!-- Estrella 3 -->
                        <svg data-rating="3" class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        
                        <!-- Estrella 2.5 -->
                        <svg data-rating="2.5" class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="halfStar25" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="50%" stop-color="currentColor" />
                                    <stop offset="50%" stop-color="transparent" stop-opacity="1" />
                                </linearGradient>
                            </defs>
                            <path fill="url(#halfStar25)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        
                        <!-- Estrella 2 -->
                        <svg data-rating="2" class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        
                        <!-- Estrella 1.5 -->
                        <svg data-rating="1.5" class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="halfStar15" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="50%" stop-color="currentColor" />
                                    <stop offset="50%" stop-color="transparent" stop-opacity="1" />
                                </linearGradient>
                            </defs>
                            <path fill="url(#halfStar15)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        
                        <!-- Estrella 1 -->
                        <svg data-rating="1" class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        
                        <!-- Estrella 0.5 -->
                        <svg data-rating="0.5" class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="halfStar05" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="50%" stop-color="currentColor" />
                                    <stop offset="50%" stop-color="transparent" stop-opacity="1" />
                                </linearGradient>
                            </defs>
                            <path fill="url(#halfStar05)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                    
                    <input type="hidden" id="rating" name="rating" value="">
                    <div class="text-xl font-bold mt-2 text-center">
                        <span id="ratingValue">0.0</span> / 5.0
                    </div>
                </div>
                
                <!-- Campo de comentario -->
                <div>
                    <label for="comentario" class="block mb-2 text-sm font-medium text-gray-900">Comentarios</label>
                    <textarea id="comentario" name="comentario" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="Comparte tu experiencia con nosotros..."></textarea>
                </div>
                
                <!-- Campo para subir fotos -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Añade fotos (opcional)</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="fotos" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Haz clic para subir</span> o arrastra y suelta</p>
                                <p class="text-xs text-gray-500">PNG, JPG o JPEG (Máx. 5MB)</p>
                            </div>
                            <input id="fotos" name="fotos[]" type="file" class="hidden" multiple accept="image/png, image/jpeg, image/jpg" />
                        </label>
                    </div>
                    <!-- Vista previa de imágenes -->
                    <div id="image-preview" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4"></div>
                </div>
                
                <!-- Botón de enviar -->
                <button type="submit" class="w-full text-white bg-green-700 hover:bg-green-800 font-medium rounded-lg text-sm px-5 py-3 text-center transition-colors duration-300">
                    <i class="fas fa-paper-plane mr-2"></i> Enviar mi reseña
                </button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <?php require_once '../includes/footer.php'; ?>

    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/resenia.js"></script>
</body>
</html>
