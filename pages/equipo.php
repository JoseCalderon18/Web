<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipo - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
</head>
<body>
    <?php require_once '../includes/header.php'; ?>

    <main class="bg-beige pt-8 container mx-auto px-4 py-10 text-center">
        <h1 class="text-4xl font-bold mb-10">Nuestro Equipo</h1>
        <div class="flex justify-center gap-8">
            <!-- Miembro 1 -->
            <div class="flex flex-col items-center">
                <img src="../assets/img/persona1.jpg" alt="Persona 1"
                    class="w-56 h-56 object-cover rounded-full border-[8px] border-gray-500 hover:scale-105 duration-300"
                    data-modal-target="modal1" data-modal-toggle="modal1">
                <h2 class="mt-4 text-xl font-semibold text-gray-500">Nombre 1</h2>
            </div>
            <!-- Miembro 2 -->
            <div class="flex flex-col items-center">
                <img src="../assets/img/persona2.jpg" alt="Persona 2"
                    class="w-56 h-56 object-cover rounded-full border-[8px] border-gray-500 hover:scale-105 duration-300"
                    data-modal-target="modal2" data-modal-toggle="modal2">
                <h2 class="mt-4 text-xl font-semibold text-gray-500">Nombre 2</h2>
            </div>
            <!-- Miembro 3 -->
            <div class="flex flex-col items-center">
                <img src="../assets/img/persona3.jpg" alt="Persona 3"
                    class="w-56 h-56 object-cover rounded-full border-[8px] border-gray-500 hover:scale-105 duration-300"
                    data-modal-target="modal3" data-modal-toggle="modal3">
                <h2 class="mt-4 text-xl font-semibold text-gray-500">Nombre 3</h2>
            </div>
            <!-- Miembro 4 -->
            <div class="flex flex-col items-center">
                <img src="../assets/img/persona4.jpg" alt="Persona 4"
                    class="w-56 h-56 object-cover rounded-full border-[8px] border-gray-500 hover:scale-105 duration-300"
                    data-modal-target="modal4" data-modal-toggle="modal4">
                <h2 class="mt-4 text-xl font-semibold text-gray-500">Nombre 4</h2>
            </div>
        </div>

        <!-- Modales -->
        <div id="modal1" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="modal1">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Cerrar modal</span>
                    </button>
                    <div class="p-6 text-center">
                        <h3 class="mb-5 text-lg font-normal text-gray-500">Nombre 1</h3>
                        <p class="mb-5">Descripción detallada del miembro del equipo.</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal2" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="modal2">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Cerrar modal</span>
                    </button>
                    <div class="p-6 text-center">
                        <h3 class="mb-5 text-lg font-normal text-gray-500">Nombre 2</h3>
                        <p class="mb-5">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quis nam et optio ratione reprehenderit, eos, sint nemo autem fuga totam asperiores aliquid voluptatum distinctio, voluptate qui! Deleniti exercitationem maxime ut.</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal3" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="modal3">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Cerrar modal</span>
                    </button>
                    <div class="p-6 text-center">
                        <h3 class="mb-5 text-lg font-normal text-gray-500">Nombre 3</h3>
                        <p class="mb-5">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quis nam et optio ratione reprehenderit, eos, sint nemo autem fuga totam asperiores aliquid voluptatum distinctio, voluptate qui! Deleniti exercitationem maxime ut.</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal4" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="modal4">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Cerrar modal</span>
                    </button>
                    <div class="p-6 text-center">
                        <h3 class="mb-5 text-lg font-normal text-gray-500">Nombre 4</h3>
                        <p class="mb-5">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quis nam et optio ratione reprehenderit, eos, sint nemo autem fuga totam asperiores aliquid voluptatum distinctio, voluptate qui! Deleniti exercitationem maxime ut.</p>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </main>

    <?php require_once '../includes/footer.php'; ?>

    <!-- Scripts al final del body -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
