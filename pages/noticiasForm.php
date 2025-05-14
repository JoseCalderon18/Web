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
    <title>Añadir Noticia - BioEspacio</title>
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
            <h1 class="text-3xl md:text-4xl font-bold text-green-800 mb-4 font-display-CormorantGaramond">Añadir Nueva Noticia</h1>
            <p class="text-gray-600 max-w-2xl my-5 mx-auto">Comparte las últimas novedades y actualizaciones de BioEspacio con nuestra comunidad.</p>
        </div>

        <!-- Formulario de noticia -->
        <div class="bg-white rounded-xl my-10 p-6 md:p-8 shadow-lg max-w-2xl mx-auto">
            <form id="newsForm" class="space-y-6" enctype="multipart/form-data">
                <!-- Título de la noticia -->
                <div>
                    <label for="titulo" class="block mb-2 text-sm font-medium text-gray-900">Título de la Noticia</label>
                    <input type="text" id="titulo" name="titulo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="Ingresa el título de la noticia" required>
                </div>

                <!-- Fecha de la noticia -->
                <div>
                    <label for="fecha" class="block mb-2 text-sm font-medium text-gray-900">Fecha de Publicación</label>
                    <input type="date" id="fecha" name="fecha" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                </div>
                
                <!-- Contenido de la noticia -->
                <div>
                    <label for="contenido" class="block mb-2 text-sm font-medium text-gray-900">Contenido</label>
                    <textarea id="contenido" name="contenido" rows="6" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="Escribe el contenido de la noticia..." required></textarea>
                </div>
                
                <!-- Campo para subir imagen principal -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Imagen Principal</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="imagen" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Haz clic para subir</span> o arrastra y suelta</p>
                                <p class="text-xs text-gray-500">PNG, JPG o JPEG</p>
                            </div>
                            <input id="imagen" name="imagen" type="file" class="hidden" accept="image/png, image/jpeg, image/jpg" required />
                        </label>
                    </div>
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
                        <i class="fas fa-paper-plane mr-2"></i> Publicar Noticia
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
