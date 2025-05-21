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
    <?php include "../includes/header.php"; ?>

    <main class="bg-beige pt-8 mx-auto px-4 py-10 text-center">
        <h1 class="text-5xl font-display-CormorantGaramond font-bold text-gray-800 mb-10">Nuestro Equipo</h1>
        <div class="flex justify-center py-10">
            <div class="flex flex-col sm:flex-row items-center gap-8">
                <!-- Miembro 1 -->
                <div class="flex flex-col items-center" data-modal-target="modal1" data-modal-toggle="modal1" type="button">
                    <img src="../assets/img/persona1.jpg" alt="Persona 1"
                        class="w-56 h-56 object-cover rounded-full border-[8px] border-gray-700 hover:scale-105 duration-300">
                    <h2 class="mt-4 text-xl font-semibold text-gray-800">Nombre 1</h2>
                </div>
                <!-- Miembro 2 -->
                <div class="flex flex-col items-center" data-modal-target="modal2" data-modal-toggle="modal2" type="button">
                    <img src="../assets/img/persona2.jpg" alt="Persona 2"
                        class="w-56 h-56 object-cover rounded-full border-[8px] border-gray-700 hover:scale-105 duration-300">
                    <h2 class="mt-4 text-xl font-semibold text-gray-800">Nombre 2</h2>
                </div>
                <!-- Miembro 3 -->
                <div class="flex flex-col items-center" data-modal-target="modal3" data-modal-toggle="modal3" type="button">
                    <img src="../assets/img/persona3.jpg" alt="Persona 3"
                        class="w-56 h-56 object-cover rounded-full border-[8px] border-gray-700 hover:scale-105 duration-300">
                    <h2 class="mt-4 text-xl font-semibold text-gray-800">Nombre 3</h2>
                </div>
                <!-- Miembro 4 -->
                <div class="flex flex-col items-center" data-modal-target="modal4" data-modal-toggle="modal4" type="button">
                    <img src="../assets/img/persona4.jpg" alt="Persona 4"
                        class="w-56 h-56 object-cover rounded-full border-[8px] border-gray-700 hover:scale-105 duration-300">
                    <h2 class="mt-4 text-xl font-semibold text-gray-800">Nombre 4</h2>
                </div>

                <!-- Modal 1 -->
                <div id="modal1" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Nombre 1
                                </h3>
                            </div>
                            <div class="p-4 md:p-5 space-y-4">
                                <img src="../assets/img/persona1.jpg" alt="Persona 1" class="w-full h-auto rounded-lg">
                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    Informacion del miembro 1.
                                </p>
                            </div>
                            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button data-modal-hide="modal1" type="button" class="text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal 2 -->
                <div id="modal2" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Nombre 2
                                </h3>
                            </div>
                            <div class="p-4 md:p-5 space-y-4">
                                <img src="../assets/img/persona2.jpg" alt="Persona 2" class="w-full h-auto rounded-lg">
                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    Informacion del miembro 2.
                                </p>
                            </div>
                            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button data-modal-hide="modal2" type="button" class="text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal 3 -->
                <div id="modal3" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Nombre 3
                                </h3>
                            </div>
                            <div class="p-4 md:p-5 space-y-4">
                                <img src="../assets/img/persona3.jpg" alt="Persona 3" class="w-full h-auto rounded-lg">
                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    Informacion del miembro 3.
                                </p>
                            </div>
                            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button data-modal-hide="modal3" type="button" class="text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal 4 -->
                <div id="modal4" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Nombre 4
                                </h3>
                            </div>
                            <div class="p-4 md:p-5 space-y-4">
                                <img src="../assets/img/persona4.jpg" alt="Persona 4" class="w-full h-auto rounded-lg">
                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    Informacion del miembro 4.
                                </p>
                            </div>
                            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <button data-modal-hide="modal4" type="button" class="text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include "../includes/footer.php"; ?>

    <!-- Scripts al final del body -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>