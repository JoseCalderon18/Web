<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuentranos - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Header -->
    <?php require_once '../includes/header.php'; ?>

    <main class="bg-beige pt-8 p-6 md:px-24">

        <!-- Botón -->
        <div class="mb-6 px-2">
            <button class="bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
            Añadir Reseña
            </button>
        </div>

        <!-- Contenedor de tarjetas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tarjeta de reseña -->
            <div class="bg-green-100 rounded-lg p-4 shadow">
            <h2 class="font-serif text-lg mb-2">Nombre</h2>
            <p class="text-yellow-400 text-xl mb-2">⭐ ⭐ ⭐ ⭐ ⭐</p>
            <p class="text-sm">Comentario</p>
            </div>

            <div class="bg-green-100 rounded-lg p-4 shadow">
            <h2 class="font-serif text-lg mb-2">Nombre</h2>
            <p class="text-yellow-400 text-xl mb-2">⭐ ⭐ ⭐ ⭐ ⭐</p>
            <p class="text-sm">Comentario</p>
            </div>

            <div class="bg-green-100 rounded-lg p-4 shadow">
            <h2 class="font-serif text-lg mb-2">Nombre</h2>
            <p class="text-yellow-400 text-xl mb-2">⭐ ⭐ ⭐ ⭐ ⭐</p>
            <p class="text-sm">Comentario</p>
            </div>

            <div class="bg-green-100 rounded-lg p-4 shadow">
            <h2 class="font-serif text-lg mb-2">Nombre</h2>
            <p class="text-yellow-400 text-xl mb-2">⭐ ⭐ ⭐ ⭐ ⭐</p>
            <p class="text-sm">Comentario</p>
            </div>

            <div class="bg-green-100 rounded-lg p-4 shadow">
            <h2 class="font-serif text-lg mb-2">Nombre</h2>
            <p class="text-yellow-400 text-xl mb-2">⭐ ⭐ ⭐ ⭐ ⭐</p>
            <p class="text-sm">Comentario</p>
            </div>

            <div class="bg-green-100 rounded-lg p-4 shadow">
            <h2 class="font-serif text-lg mb-2">Nombre</h2>
            <p class="text-yellow-400 text-xl mb-2">⭐ ⭐ ⭐ ⭐ ⭐</p>
            <p class="text-sm">Comentario</p>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <?php require_once '../includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="../assets/js/script.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
</body>
</html>