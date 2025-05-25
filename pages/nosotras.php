<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nosotras - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <?php include "../includes/header.php"; ?>

    <main>
        <div class="bg-beige pt-8 px-8 py-10 w-full">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mx-auto w-3/4">
                <!-- Columna de texto -->
                <div class="flex flex-col justify-center aparecer">
                    <h1 class="text-3xl font-display-CormorantGaramond font-bold text-verde mb-4">"Porque el bienestar es un viaje, y aquí tienes un lugar donde te acompañamos"</h1>
                    <p class="text-xs md:text-sm text-verde mb-4 aparecer-secuencial">Hola, somos <strong>Tessa y Beatriz</strong>.</p>
                    <p class="text-xs md:text-sm text-verde mb-4 aparecer-secuencial">Os presentamos <strong>Bioespacio Bienestar</strong>, un herbolario diferente que comenzó hace unos años para Tessa como un refugio, un espacio de calma y equilibrio en un momento clave de su vida. Con el tiempo, su camino se cruzó con el de Beatriz, y juntas dieron forma a lo que hoy es mucho más que un herbolario: un espacio dedicado al cuidado personal físico y mental.</p>
                    <p class="text-xs md:text-sm text-verde mb-4 aparecer-secuencial">Buscamos y ponemos a vuestra disposición las mejores terapias naturales, terapeutas y suplementos que podemos encontrar. Con trayectorias complementarias y una visión común, <strong>acompañamos a quienes buscan mejorar su calidad de vida a través de las terapias naturales y el poder de las plantas</strong>.</p>
                </div>
                
                <!-- Columna del carrusel -->
                <div class="bg-white rounded-lg shadow-md h-80 w-full md:w-2/3 mb-8 aparecer">
                    <div id="default-carousel" class="relative w-full h-full" data-carousel="slide">
                        <div class="relative h-full overflow-hidden rounded-lg">
                            <!-- Item 1 -->
                            <div class="hidden duration-700 ease-in-out h-full" data-carousel-item>
                                <img src="../assets/img/carousel/puerta.jpeg" class="absolute block w-full h-full object-cover" alt="Entrada principal">
                            </div>
                            <!-- Item 2 -->
                            <div class="hidden duration-700 ease-in-out h-full" data-carousel-item>
                                <img src="../assets/img/carousel/recepcion.jpeg" class="absolute block w-full h-full object-cover" alt="Recepción">
                            </div>
                            <!-- Item 3 -->
                            <div class="hidden duration-700 ease-in-out h-full" data-carousel-item>
                                <img src="../assets/img/carousel/sala_Masaje.jpeg" class="absolute block w-full h-full object-cover" alt="Sala de masajes">
                            </div>
                            <!-- Item 4 -->
                            <div class="hidden duration-700 ease-in-out h-full" data-carousel-item>
                                <img src="../assets/img/carousel/sala_Masaje1.jpeg" class="absolute block w-full h-full object-cover" alt="Segunda sala de masajes">
                            </div>
                        </div>
                        <!-- Controles -->
                        <button type="button" aria-label="Imagen anterior" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                                <svg class="w-4 h-4 text-white rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                                </svg>
                            </span>
                        </button>
                        <button type="button" aria-label="Imagen siguiente" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                                <svg class="w-4 h-4 text-white rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Misión y Valores -->
        <div class="bg-white py-8 flex flex-col items-center justify-center aparecer-bounce">
            <h1 class="text-5xl font-display-CormorantGaramond font-bold text-gray-800 mb-4">Misión y Valores</h1>
        </div>

        <!-- Sección de imágenes -->
        <div class="bg-white py-8 flex flex-col sm:flex-row items-center justify-center gap-8">
            <img src="../assets/img/mision.png" alt="Imagen 1" class="w-32 sm:w-60 h-auto rounded-lg shadow-lg aparecer-flip">
            <img src="../assets/img/valores.png" alt="Imagen 2" class="w-32 sm:w-60 h-auto rounded-lg shadow-lg aparecer-flip-delay">
        </div>

        <!-- Modal 1 -->
        <div id="modal1" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-xl max-h-full">
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Misión
                        </h3>
                    </div>
                    <div class="p-4 md:p-5 space-y-4 text-center">
                        <img src="../assets/img/mision.png" alt="Misión" class="w-full h-auto rounded-lg">
                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                            Misión de la empresa.
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
            <div class="relative p-4 w-full max-w-xl max-h-full">
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Valores
                        </h3>
                    </div>
                    <div class="p-4 md:p-5 space-y-4 text-center">
                        <img src="../assets/img/valores.png" alt="Valores" class="w-full h-auto rounded-lg">
                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                            Valores de la empresa.
                        </p>
                    </div>
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button data-modal-hide="modal2" type="button" class="text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cerrar</button>
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
    <script src="../assets/js/animaciones.js"></script>
</body>
</html>
