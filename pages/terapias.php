<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terapias - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet" alt="Estilos de Flowbite">
    <link href="../assets/css/src/output.css" rel="stylesheet" alt="Estilos personalizados">
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    
    <main>
        <!-- Primer div -->
        <div class="bg-beige">
            <div class="pt-8 py-10 flex flex-col sm:flex-row justify-center items-center h-full w-3/4 mx-auto text-gray-900 px-4 md:px-8">
                <div class="flex flex-col w-full sm:w-1/2 text-center sm:text-left mb-6 sm:mb-0 aparecer">
                    <h1 class="text-3xl md:text-4xl font-bold font-display-CormorantGaramond mb-4">Salas de Terapias Naturales</h1>
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
                        <p class="mb-5">Contamos con <b>dos salas acogedoras y tranquilas</b>, diseñadas para que puedas
                             recibir terapias naturales de la mano de profesionales especializados.</p>
                        <p class="mb-5">Desde <b>masajes, osteopatía, coaching, hasta astrología y otras disciplinas holísticas</b>, 
                            nuestras salas son un refugio donde cada sesión se convierte en un momento de conexión, sanación y transformación. </p>
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
                        <p class="text-balance mb-4">Tanto si buscas alivio físico, apoyo emocional o un camino hacia el autoconocimiento, aquí
                             encontrarás el espacio perfecto para cuidar de ti de manera integral. </p>
                        <p class="text-balance">Descubre el poder de las terapias naturales y regálate bienestar. Tu equilibrio empieza aquí. </p>
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