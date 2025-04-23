<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipo - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
</head>
<body>
    <?php require_once '../includes/header.php'; ?>

    <main class="bg-beige pt-8 mx-auto px-4 py-10 text-center">
        <h1 class="text-5xl font-display-CormorantGaramond font-bold text-gray-800 mb-10">Nuestro Equipo</h1>
        <div class="flex justify-center py-10">
            <div class="flex flex-col sm:flex-row items-center gap-8">
                <!-- Miembro 1 -->
                <div class="flex flex-col items-center">
                    <img src="../assets/img/persona1.jpg" alt="Persona 1"
                        class="w-56 h-56 object-cover rounded-full border-[8px] border-gray-700 hover:scale-105 duration-300"
                        data-modal-target="modal1" data-modal-toggle="modal1">
                    <h2 class="mt-4 text-xl font-semibold text-gray-800">Nombre 1</h2>
                </div>
                <!-- Miembro 2 -->
                <div class="flex flex-col items-center">
                    <img src="../assets/img/persona2.jpg" alt="Persona 2"
                        class="w-56 h-56 object-cover rounded-full border-[8px] border-gray-700 hover:scale-105 duration-300"
                        data-modal-target="modal2" data-modal-toggle="modal2">
                    <h2 class="mt-4 text-xl font-semibold text-gray-800">Nombre 2</h2>
                </div>
                <!-- Miembro 3 -->
                <div class="flex flex-col items-center">
                    <img src="../assets/img/persona3.jpg" alt="Persona 3"
                        class="w-56 h-56 object-cover rounded-full border-[8px] border-gray-700 hover:scale-105 duration-300"
                        data-modal-target="modal3" data-modal-toggle="modal3">
                    <h2 class="mt-4 text-xl font-semibold text-gray-800">Nombre 3</h2>
                </div>
                <!-- Miembro 4 -->
                <div class="flex flex-col items-center">
                    <img src="../assets/img/persona4.jpg" alt="Persona 4"
                        class="w-56 h-56 object-cover rounded-full border-[8px] border-gray-700 hover:scale-105 duration-300"
                        data-modal-target="modal4" data-modal-toggle="modal4">
                    <h2 class="mt-4 text-xl font-semibold text-gray-800">Nombre 4</h2>
                </div>
            </div>
        </div>
    </main>


    <?php require_once '../includes/footer.php'; ?>

    <!-- Scripts al final del body -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>