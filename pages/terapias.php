<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terapias - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet" alt="Estilos de Flowbite">
    <link href="../assets/css/src/output.css" rel="stylesheet" alt="Estilos personalizados">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    
    <main>
        <!-- Primer div -->
        <div class="bg-beige">
            <div class="pt-8 py-10 flex flex-col sm:flex-row justify-center items-center h-full w-3/4 mx-auto text-gray-900 px-4 md:px-8">
                <div class="flex flex-col w-full sm:w-1/2 text-center sm:text-left mb-6 sm:mb-0">
                    <h1 class="text-3xl md:text-4xl font-bold font-display-CormorantGaramond mb-4">Salas de Terapias Naturales</h1>
                    <p class="text-sm md:text-base mb-2">En nuestro herbolario, hemos creado un espacio de armonía y bienestar donde cuerpo, mente y espíritu encuentran equilibrio.</p>
                </div>
                <div class="w-full flex justify-center">
                    <img src="../assets/img/terapias/figura.jpg" alt="Imagen de terapias" class="w-full object-cover h-96 sm:h-96 rounded-lg">
                </div>
            </div>
        </div>
        <!-- Segundo div -->
        <div>
            <!-- Primer div con fondo gris claro -->
            <div class="bg-gray-800 py-24 w-full">
                <div class="flex flex-col w-3/4 mx-auto">
                    <!-- Texto superior -->
                    <div class="flex flex-col justify-center items-center text-center w-full mb-16 text-white">
                        <p class="mb-5">Contamos con <b>dos salas acogedoras y tranquilas</b>, diseñadas para que puedas
                             recibir terapias naturales de la mano de profesionales especializados.</p>
                        <p class="mb-5">Desde <b>masajes, osteopatía, coaching, hasta astrología y otras disciplinas holísticas</b>, 
                            nuestras salas son un refugio donde cada sesión se convierte en un momento de conexión, sanación y transformación. </p>
                    </div>
                    
                    <!-- Imágenes en el centro -->
                    <div class="flex flex-col sm:flex-row justify-center items-center gap-12 my-10">
                        <div class="w-full sm:w-2/5 mb-6 sm:mb-0">
                            <img src="../assets/img/carousel/sala_Masaje.jpeg" alt="Productos naturales" class="object-cover rounded-lg h-80 w-full">
                        </div>
                        <div class="w-full sm:w-2/8">
                            <img src="../assets/img/carousel/sala_Masaje1.jpeg" alt="Terapias naturales" class="object-cover rounded-lg h-80 w-full ">
                        </div>
                    </div>
                    
                    <!-- Texto inferior -->
                    <div class="flex flex-col justify-center items-center text-center w-full text-white my-8">
                        <p class="text-balance mb-4">Tanto si buscas alivio físico, apoyo emocional o un camino hacia el autoconocimiento, aquí
                             encontrarás el espacio perfecto para cuidar de ti de manera integral. </p>
                        <p class="text-balance">Descubre el poder de las terapias naturales y regálate bienestar. Tu equilibrio empieza aquí. </p>
                    </div>
                </div>
            </div>
            
            <!-- Segundo div con fondo blanco -->
            <div class="bg-white border-y border-gray-200 py-24 w-full text-verde">
                <div class="flex flex-col items-center w-3/4 mx-auto ">
                    <div class="flex flex-col justify-center items-center text-center w-full mb-8">
                        <h1 class="font-display-CormorantGaramond text-4xl font-bold mb-6">"Encuentra la terapia que necesitas" </h1>
                        <p class="mb-3 text-balance">En nuestro herbolario, contamos con <b>tarapeutas especializados</b> en distintas disciplinas naturales, cada
                         una enfocada en ayudarte a recuperar el equilibrio y mejorar tu bienestar. </p>
                        <p class="mb-3 text-balance">Ya sea que busques alivio <b>físico</b>, apoyo <b>emocional</b> o simplemente un momento para <b>reconectar</b> 
                            contigo, aquí encontrarás un espacio donde cuidarte de manera integral. Explora nuestras terapias y elige la que más resuene contigo.</p>
                    </div>
                    <div class="w-full flex justify-center">
                        <img src="../assets/img/terapias/figura2.jpg" alt="Foto de objetos" class="object-contain rounded-lg w-full h-96">
                    </div>
                </div>
            </div>
        </div>
        <h1 class="text-5xl font-display-CormorantGaramond font-bold text-gray-800 mb-10 text-center">Nuestras Terapias</h1>
        <div class="flex justify-center py-10">
            <div class="flex flex-col sm:flex-row items-center gap-8">
                <!-- Miembro 1 -->
                <div class="flex flex-col items-center" data-modal-target="modal1" data-modal-toggle="modal1" type="button">
                    <img src="../assets/img/persona1.jpg" alt="Persona 1"
                        class="w-56 h-56 object-cover rounded-full border-[8px] border-gray-700 hover:scale-105 duration-300">
                    <h2 class="mt-4 text-xl font-semibold text-gray-800">Terapia 1</h2>
                </div>
                <!-- Miembro 2 -->
                <div class="flex flex-col items-center" data-modal-target="modal2" data-modal-toggle="modal2" type="button">
                    <img src="../assets/img/persona2.jpg" alt="Persona 2"
                        class="w-56 h-56 object-cover rounded-full border-[8px] border-gray-700 hover:scale-105 duration-300">
                    <h2 class="mt-4 text-xl font-semibold text-gray-800">Terapia 2</h2>
                </div>
                <!-- Miembro 3 -->
                <div class="flex flex-col items-center" data-modal-target="modal3" data-modal-toggle="modal3" type="button">
                    <img src="../assets/img/persona3.jpg" alt="Persona 3"
                        class="w-56 h-56 object-cover rounded-full border-[8px] border-gray-700 hover:scale-105 duration-300">
                    <h2 class="mt-4 text-xl font-semibold text-gray-800">Terapia 3</h2>
                </div>
                <!-- Miembro 4 -->
                <div class="flex flex-col items-center" data-modal-target="modal4" data-modal-toggle="modal4" type="button">
                    <img src="../assets/img/persona4.jpg" alt="Persona 4"
                        class="w-56 h-56 object-cover rounded-full border-[8px] border-gray-700 hover:scale-105 duration-300">
                    <h2 class="mt-4 text-xl font-semibold text-gray-800">Terapia 4</h2>
                </div>

                <!-- Modal 1 -->
                <div id="modal1" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Terapia 1
                                </h3>
                            </div>
                            <div class="p-4 md:p-5 space-y-4">
                                <img src="../assets/img/persona1.jpg" alt="Persona 1" class="w-full h-auto rounded-lg">
                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    Informacion de la terapia 1.
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
                                    Terapia 2
                                </h3>
                            </div>
                            <div class="p-4 md:p-5 space-y-4">
                                <img src="../assets/img/persona2.jpg" alt="Persona 2" class="w-full h-auto rounded-lg">
                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    Informacion de la terapia 2.
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
                                    Terapia 3
                                </h3>
                            </div>
                            <div class="p-4 md:p-5 space-y-4">
                                <img src="../assets/img/persona3.jpg" alt="Persona 3" class="w-full h-auto rounded-lg">
                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    Informacion de la terapia 3.
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
                                    Terapia 4
                                </h3>
                            </div>
                            <div class="p-4 md:p-5 space-y-4">
                                <img src="../assets/img/persona4.jpg" alt="Persona 4" class="w-full h-auto rounded-lg">
                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    Informacion de la terapia 4.
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
</body>
    <!-- Scripts al final del body -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
</html>