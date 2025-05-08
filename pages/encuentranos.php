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

    <main class="bg-beige pt-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-2/3 mx-auto">
            <!-- Columna izquierda: Horario y Carrusel -->
            <div class="grid grid-rows-2 gap-4">
                <!-- Horario -->
                <div class="bg-white p-6 rounded-lg shadow-md h-65 flex flex-col w-full md:w-2/3">
                    <h1 class="text-2xl font-display-CormorantGaramond font-bold text-verde mb-4">Horario de Atención</h1>
                    <div class="space-y-4 flex-grow">
                        <!-- Dirección -->
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-verde">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            <p class="text-xs md:text-sm">P.º de Fuente Lucha, 7, 28100 Alcobendas, Madrid</p>
                        </div>

                        <!-- Horario -->
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-verde mt-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-xs md:text-sm">
                                <p class="font-semibold">Lunes a Viernes:</p>
                                <p class="mb-2">10:00 - 19:00</p>
                                <p class="font-semibold">Sábados, Domingos y Festivos:</p>
                                <p>Cerrado</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carrusel -->
                <div class="bg-white rounded-lg shadow-md h-80 w-full md:w-2/3">
                    <div id="default-carousel" class="relative w-full h-full" data-carousel="slide">
                        <div class="relative h-full overflow-hidden rounded-lg ">
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
                        <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                                <svg class="w-4 h-4 text-white rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                                </svg>
                            </span>
                        </button>
                        <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                                <svg class="w-4 h-4 text-white rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Columna derecha: Formulario -->
            <div class="bg-white p-5 rounded-lg shadow-md h-90">
                <h2 class="text-2xl font-display-CormorantGaramond font-bold text-verde mb-3">Contáctanos</h2>
                <form class="space-y-3 text-center" id="formularioContacto">
                    <input type="text" id="nombre" name="nombre" placeholder="Introduce tu nombre" 
                        class="w-full p-3 text-xs md:text-sm border border-gray-300 rounded-md ">
                    <input type="email" id="email" name="email" placeholder="Introduce tu email" 
                        class="w-full p-3 text-xs md:text-sm border border-gray-300 rounded-md ">
                    <select name="asunto" id="asunto" 
                        class="w-full p-3 text-xs md:text-sm border border-gray-300 rounded-md ">
                        <option value="" disabled selected class="text-xs md:text-sm">Selecciona un asunto</option>
                        <option value="1" class="text-xs md:text-sm">Solicitar Información</option>
                        <option value="2" class="text-xs md:text-sm">Consulta sobre algún producto</option>
                        <option value="3" class="text-xs md:text-sm">Consulta sobre terapias</option>
                        <option value="4" class="text-xs md:text-sm">Otro</option>
                    </select>
                    <textarea name="mensaje" id="mensaje" rows="8" placeholder="Introduce tu mensaje" 
                        class="w-full p-3 text-xs md:text-sm border border-gray-300 rounded-md  flex-grow"></textarea>
                    <div class="flex justify-center">
                        <button type="submit" id="btnEnviar"
                            class="bg-green-700 hover:bg-green-900 h-full text-white font-semibold py-3 px-2 md:py-3 md:px-4 rounded-md w-1/2 text-base md:text-base">
                            Enviar mensaje
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!--Reseñas-->
        <div class="mt-8">
            <h2 class="text-4xl font-display-CormorantGaramond font-bold text-verde mb-3 text-center">Reseñas de nuestros clientes</h2> 
            <!-- Elfsight Google Reviews | Untitled Google Reviews -->
            <script src="https://static.elfsight.com/platform/platform.js" async></script>
            <div class="elfsight-app-fb064c10-161e-495f-b396-f6fefe8abfb3" data-elfsight-app-lazy></div>
        </div>

        <!-- Mapa -->
        <div class="mt-8">
            <div class="w-full h-96">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3031.537791266752!2d-3.6602848000000003!3d40.5517997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd422dd4b4e4d15b%3A0x375649d95b7c987d!2sHerbolario%20Bioespacio%20Bienestar!5e0!3m2!1ses!2ses!4v1746532595845!5m2!1ses!2ses" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </main>

    <br><br>

    <!-- Footer -->
    <?php require_once '../includes/footer.php'; ?>
</body>
<!-- Scripts -->
<script src="../assets/js/script.js"></script>
<script src="../assets/js/formulario.js"></script>
<script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
</html>