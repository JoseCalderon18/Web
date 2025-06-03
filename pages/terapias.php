<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terapias - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet" alt="Estilos de Flowbite">
    <link href="../assets/css/src/output.css" rel="stylesheet" alt="Estilos personalizados">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    
    <main>
        <!-- Primer div -->
        <div class="bg-beige">
            <div class="pt-8 py-10 flex flex-col sm:flex-row justify-center items-center h-full w-3/4 mx-auto text-gray-900 px-4 md:px-8">
                <div class="flex flex-col w-full sm:w-1/2 text-center sm:text-left mb-6 sm:mb-0 aparecer">
                    <h1 class="text-3xl md:text-4xl font-bold font-display-CormorantGaramond mb-4 text-verde">Salas de Terapias Naturales</h1>
                    <p class="text-sm md:text-base mb-2 aparecer-secuencial">En nuestro herbolario, hemos creado un espacio de armonía y bienestar donde cuerpo, mente y espíritu encuentran equilibrio.</p>
                </div>
                <div class="w-full flex justify-center">
                    <img src="../assets/img/terapias/figura.jpg" alt="Imagen de terapias" class="w-full object-cover h-96 sm:h-96 rounded-lg imagen-aparecer">
                </div>
            </div>
        </div>

        <!-- Segundo div -->
        <div>
            <!-- Primer div con fondo gris claro -->
            <div class="bg-gray-800 py-24 w-full">
                <div class="flex flex-col w-3/4 mx-auto aparecer">
                    <!-- Texto superior -->
                    <div class="flex flex-col justify-center items-center text-center w-full mb-16 text-white aparecer-secuencial">
                        <p class="mb-5 text-sm md:text-base lg:text-lg">Contamos con <b>dos salas acogedoras y tranquilas</b>, diseñadas para que puedas
                             recibir terapias naturales de la mano de profesionales especializados.</p>
                        <p class="mb-5 text-sm md:text-base lg:text-lg">Desde <b>masajes, osteopatía, coaching, hasta astrología y otras disciplinas holísticas</b>, 
                            nuestras salas son un refugio donde cada sesión se convierte en un momento de conexión, sanación y transformación.</p>
                    </div>
                    
                    <!-- Imágenes en el centro -->
                    <div class="flex flex-col sm:flex-row justify-center items-center gap-12 my-10">
                        <div class="w-full sm:w-2/5 mb-6 sm:mb-0">
                            <img src="../assets/img/carousel/sala_Masaje.jpeg" alt="Productos naturales" class="object-cover rounded-lg h-80 w-full imagen-aparecer">
                        </div>
                        <div class="w-full sm:w-2/8">
                            <img src="../assets/img/carousel/sala_Masaje1.jpeg" alt="Terapias naturales" class="object-cover rounded-lg h-80 w-full imagen-aparecer">
                        </div>
                    </div>
                    
                    <!-- Texto inferior -->
                    <div class="flex flex-col justify-center items-center text-center w-full text-white my-8 aparecer-secuencial">
                        <p class="text-balance mb-4 text-sm md:text-base lg:text-lg">Tanto si buscas alivio físico, apoyo emocional o un camino hacia el autoconocimiento, aquí
                             encontrarás el espacio perfecto para cuidar de ti de manera integral.</p>
                        <p class="text-balance text-sm md:text-base lg:text-lg">Descubre el poder de las terapias naturales y regálate bienestar. Tu equilibrio empieza aquí.</p>
                    </div>
                </div>
            </div>
            
            <!-- Segundo div con fondo blanco -->
            <div class="bg-white border-y border-gray-200 py-24 w-full text-verde">
                <div class="flex flex-col items-center w-3/4 mx-auto aparecer">
                    <div class="flex flex-col justify-center items-center text-center w-full mb-8">
                        <h1 class="font-display-CormorantGaramond text-4xl font-bold mb-6">"Encuentra la terapia que necesitas"</h1>
                        <p class="mb-3 text-balance">En nuestro herbolario, contamos con <b>tarapeutas especializados</b> en distintas disciplinas naturales, cada
                         una enfocada en ayudarte a recuperar el equilibrio y mejorar tu bienestar. </p>
                        <p class="mb-3 text-balance">Ya sea que busques alivio <b>físico</b>, apoyo <b>emocional</b> o simplemente un momento para <b>reconectar</b> 
                            contigo, aquí encontrarás un espacio donde cuidarte de manera integral. Explora nuestras terapias y elige la que más resuene contigo.</p>
                    </div>
                    <div class="w-full flex justify-center">
                        <img src="../assets/img/terapias/figura2.jpg" alt="Foto de objetos" class="object-contain rounded-lg w-full h-96 imagen-aparecer">
                    </div>
                </div>
            </div>
            <!-- Tercer div con fondo oscuro-->
            <!--Terapias-->
            <div class="bg-gray-800 py-24 w-full">
                <div class="flex flex-col items-center w-3/4 mx-auto">
                    <!-- Título -->
                    <h1 class="text-5xl font-display-CormorantGaramond font-bold text-white text-center mb-5 aparecer">Nuestras Terapias</h1>

                    <p class="text-white text-center text-balance text-sm md:text-base lg:text-lg aparecer-secuencial mb-10">Descubre nuestra selección de terapias holísticas, diseñadas para ayudarte a encontrar el equilibrio físico, mental y emocional. Cada una de ellas ha sido cuidadosamente elegida para ofrecerte una experiencia de bienestar integral.</p>
                    <!-- Contenedor de las terapias -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full mt-10">
                        <!-- Terapia 1 -->
                        <div class="flex flex-col items-center justify-start gap-2 p-3 relative group aparecer-secuencial hover:scale-105 transition-transform duration-500" data-modal-target="Osteopatía" data-modal-toggle="Osteopatía" type="button">
                            <img src="../assets/img/Osteopatía.png" alt="Osteopatía" class="w-56 h-56 object-cover rounded-full border-[8px] border-white">
                            <h2 class="text-xl font-semibold text-white">Osteopatía</h2>
                            <!-- Bordes con animacion-->
                            <div class="absolute top-0 left-0 w-10 h-10 border-t-1 border-l-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-top-left scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                            <div class="absolute bottom-0 right-0 w-10 h-10 border-b-1 border-r-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-bottom-right scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                        </div>

                        <!-- Terapia 2 -->
                        <div class="flex flex-col items-center justify-start gap-2 p-3 relative group aparecer-secuencial hover:scale-105 transition-transform duration-500" data-modal-target="Astrología" data-modal-toggle="Astrología" type="button">
                            <img src="../assets/img/Astrología.png" alt="Astrología" class="w-56 h-56 object-cover rounded-full border-[8px] border-white">
                            <h2 class="text-xl font-semibold text-white">Astrología</h2>
                            <!-- Bordes con animacion-->
                            <div class="absolute top-0 left-0 w-10 h-10 border-t-1 border-l-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-top-left scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                            <div class="absolute bottom-0 right-0 w-10 h-10 border-b-1 border-r-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-bottom-right scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                        </div>

                        <!-- Terapia 3 -->
                        <div class="flex flex-col items-center justify-start gap-2 p-3 relative group aparecer-secuencial hover:scale-105 transition-transform duration-500" data-modal-target="Coaching" data-modal-toggle="Coaching" type="button">
                            <img src="../assets/img/Coaching.png" alt="Coaching" class="w-56 h-56 object-cover rounded-full border-[8px] border-white">
                            <h2 class="text-xl font-semibold text-white">Coaching</h2>
                            <!-- Bordes con animacion-->
                            <div class="absolute top-0 left-0 w-10 h-10 border-t-1 border-l-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-top-left scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                            <div class="absolute bottom-0 right-0 w-10 h-10 border-b-1 border-r-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-bottom-right scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                        </div>

                        <!-- Terapia 4 -->
                        <div class="flex flex-col items-center justify-start gap-2 p-3 relative group aparecer-secuencial hover:scale-105 transition-transform duration-500" data-modal-target="MasajesNaturales" data-modal-toggle="MasajesNaturales" type="button">
                            <img src="../assets/img/Masajes Naturales.png" alt="Masajes Naturales" class="w-56 h-56 object-cover rounded-full border-[8px] border-white">
                            <h2 class="text-xl font-semibold text-white">Masajes Naturales</h2>
                            <!-- Bordes con animacion-->
                            <div class="absolute top-0 left-0 w-10 h-10 border-t-1 border-l-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-top-left scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                            <div class="absolute bottom-0 right-0 w-10 h-10 border-b-1 border-r-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-bottom-right scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                        </div>

                        <!-- Terapia 5 -->
                        <div class="flex flex-col items-center justify-start gap-2 p-3 relative group aparecer-secuencial hover:scale-105 transition-transform duration-500" data-modal-target="Reiki" data-modal-toggle="Reiki" type="button">
                            <img src="../assets/img/Reiki.png" alt="Reiki" class="w-56 h-56 object-cover rounded-full border-[8px] border-white">
                            <h2 class="text-xl font-semibold text-white">Reiki</h2>
                            <!-- Bordes con animacion-->
                            <div class="absolute top-0 left-0 w-10 h-10 border-t-1 border-l-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-top-left scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                            <div class="absolute bottom-0 right-0 w-10 h-10 border-b-1 border-r-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-bottom-right scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                        </div>

                        <!-- Terapia 6 -->
                        <div class="flex flex-col items-center justify-start gap-2 p-3 relative group aparecer-secuencial hover:scale-105 transition-transform duration-500" data-modal-target="TerapiaCraneosacral" data-modal-toggle="TerapiaCraneosacral" type="button">
                            <img src="../assets/img/Terapia Craneosacral.png" alt="Terapia Craneosacral" class="w-56 h-56 object-cover rounded-full border-[8px] border-white">
                            <h2 class="text-xl font-semibold text-white">Terapia Craneosacral</h2>
                            <!-- Bordes con animacion-->
                            <div class="absolute top-0 left-0 w-10 h-10 border-t-1 border-l-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-top-left scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                            <div class="absolute bottom-0 right-0 w-10 h-10 border-b-1 border-r-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-bottom-right scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                        </div>
                    </div>

                    <!-- Modales -->
                    <!-- Modal 1 -->
                    <div id="Osteopatía" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                            <div class="relative bg-white rounded-lg shadow-sm">
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        Osteopatía
                                    </h3>
                                </div>
                                <div class="p-4 md:p-5 space-y-4">
                                    <p class="text-base leading-relaxed text-gray-500">
                                        Terapia manual que trata disfunciones del cuerpo mediante manipulaciones suaves para mejorar la movilidad, aliviar el dolor y restaurar el equilibrio natural del organismo.
                                    </p>
                                </div>
                                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                                    <button data-modal-hide="Osteopatía" type="button" class="text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal 2 -->
                    <div id="Astrología" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                            <div class="relative bg-white rounded-lg shadow-sm">
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        Astrología
                                    </h3>
                                </div>
                                <div class="p-4 md:p-5 space-y-4">
                                    <p class="text-base leading-relaxed text-gray-500">
                                        Herramienta de autoconocimiento basada en la interpretación de los astros y la carta natal, que ayuda a comprender la personalidad, los ciclos vitales y tomar decisiones conscientes.
                                    </p>
                                </div>
                                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                                    <button data-modal-hide="Astrología" type="button" class="text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal 3 -->
                    <div id="Coaching" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                            <div class="relative bg-white rounded-lg shadow-sm">
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        Coaching
                                    </h3>
                                </div>
                                <div class="p-4 md:p-5 space-y-4">
                                    <p class="text-base leading-relaxed text-gray-500">
                                        Proceso de acompañamiento personalizado que impulsa el desarrollo personal y profesional, ayudando a alcanzar metas, superar bloqueos y potenciar habilidades.
                                    </p>
                                </div>
                                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                                    <button data-modal-hide="Coaching" type="button" class="text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal 4 -->
                    <div id="MasajesNaturales" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                            <div class="relative bg-white rounded-lg shadow-sm">
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        Masajes Naturales
                                    </h3>
                                </div>
                                <div class="p-4 md:p-5 space-y-4">
                                    <p class="text-base leading-relaxed text-gray-500">
                                        Técnica terapéutica que utiliza maniobras manuales sobre el cuerpo para relajar los músculos, mejorar la circulación y proporcionar bienestar físico y emocional.
                                    </p>
                                </div>
                                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                                    <button data-modal-hide="MasajesNaturales" type="button" class="text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal 5 -->
                    <div id="Reiki" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                            <div class="relative bg-white rounded-lg shadow-sm">
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        Reiki
                                    </h3>
                                </div>
                                <div class="p-4 md:p-5 space-y-4">
                                    <p class="text-base leading-relaxed text-gray-500">
                                        Terapia energética japonesa que canaliza energía a través de las manos para armonizar cuerpo, mente y espíritu, favoreciendo la relajación y la sanación interior.
                                    </p>
                                </div>
                                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                                    <button data-modal-hide="Reiki" type="button" class="text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal 6 -->
                    <div id="TerapiaCraneosacral" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                            <div class="relative bg-white rounded-lg shadow-sm">
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        Terapia Craneosacral
                                    </h3>
                                </div>
                                <div class="p-4 md:p-5 space-y-4">
                                    <p class="text-base leading-relaxed text-gray-500">
                                        Terapia suave que trabaja el sistema nervioso central mediante toques sutiles en el cráneo, columna y sacro, ayudando a liberar tensiones profundas y mejorar el equilibrio corporal.
                                    </p>
                                </div>
                                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                                    <button data-modal-hide="TerapiaCraneosacral" type="button" class="text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include "../includes/footer.php"; ?>
</body>
    <!-- Scripts al final del body -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/animaciones.js"></script>
</html>