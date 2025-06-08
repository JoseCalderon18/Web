<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nosotras - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet" alt="Estilos de Flowbite">
    <link href="../assets/css/src/output.css" rel="stylesheet" alt="Estilos personalizados">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="../assets/img/iconoBio.ico" type="image/x-icon">
</head>
<body class="text-sm sm:text-base">
    <?php include "../includes/header.php"; ?>

    <main class="bg-beige">
        <div class="bg-white pt-10 px-8 py-10 w-full aparecer mb-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mx-auto w-3/4">
                <!-- Columna de texto -->
                <div class="flex flex-col justify-center aparecer-secuencial">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-display-CormorantGaramond font-bold text-verde mb-4">"Porque el bienestar es un viaje, y aquí tienes un lugar donde te acompañamos"</h1>
                    <p class="text-sm sm:text-base lg:text-lg text-verde mb-4">Hola, somos <strong>Tessa y Beatriz</strong>.</p>
                    <p class="text-sm sm:text-base lg:text-lg text-verde mb-4">Os presentamos <strong>Bioespacio Bienestar</strong>, un herbolario diferente que comenzó hace unos años para Tessa como un refugio, un espacio de calma y equilibrio en un momento clave de su vida. Con el tiempo, su camino se cruzó con el de Beatriz, y juntas dieron forma a lo que hoy es mucho más que un herbolario: un espacio dedicado al cuidado personal físico y mental.</p>
                    <p class="text-sm sm:text-base lg:text-lg text-verde mb-4">Buscamos y ponemos a vuestra disposición las mejores terapias naturales, terapeutas y suplementos que podemos encontrar. Con trayectorias complementarias y una visión común, <strong>acompañamos a quienes buscan mejorar su calidad de vida a través de las terapias naturales y el poder de las plantas</strong>.</p>
                </div>
                
                <!-- Columna del carrusel -->
                <div class="bg-beige rounded-lg shadow-md h-80 w-full md:w-2/3 mb-8">
                    <div id="default-carousel" class="relative w-full h-full" data-carousel="slide">
                        <div class="relative h-full overflow-hidden rounded-lg">
                            <!-- Item 1 -->
                            <div class="hidden duration-700 ease-in-out h-full" data-carousel-item>
                                <img src="../assets/img/nosotras/puerta.avif" class="absolute block w-full h-full object-cover" alt="Entrada principal">
                            </div>
                            <!-- Item 2 -->
                            <div class="hidden duration-700 ease-in-out h-full" data-carousel-item>
                                <img src="../assets/img/nosotras/recepcion.avif" class="absolute block w-full h-full object-cover" alt="Recepción">
                            </div>
                            <!-- Item 3 -->
                            <div class="hidden duration-700 ease-in-out h-full" data-carousel-item>
                                <img src="../assets/img/nosotras/sala_Masaje.avif" class="absolute block w-full h-full object-cover" alt="Sala de masajes">
                            </div>
                            <!-- Item 4 -->
                            <div class="hidden duration-700 ease-in-out h-full" data-carousel-item>
                                <img src="../assets/img/nosotras/sala_Masaje1.avif" class="absolute block w-full h-full object-cover" alt="Segunda sala de masajes">
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
        <div class="bg-beige py-5 aparecer">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display-CormorantGaramond font-bold text-verde text-center mb-5">Misión y Valores</h2>
                <p class="text-sm sm:text-base lg:text-lg text-verde/80 text-center mb-8 max-w-2xl mx-auto">Comprometidas con tu bienestar integral a través de la naturaleza y el cuidado personalizado</p>
                
                <div class="flex flex-col gap-8 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Misión -->
                    <div class="flex flex-col aparecer-secuencial w-full">
                        <div class="bg-white rounded-[2rem] p-8 shadow-lg h-full transform transition-all duration-500 hover:-translate-y-2">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-verde/5 rounded-full -mr-16 -mt-16"></div>
                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-verde/5 rounded-full -ml-12 -mb-12"></div>
                            <div class="relative">
                                <span class="inline-block text-verde text-sm sm:text-base lg:text-lg font-semibold py-2 bg-verde/10 rounded-full mb-6">Nuestra Misión</span>
                                <h3 class="text-2xl sm:text-3xl lg:text-4xl font-display-CormorantGaramond font-bold text-verde mb-6">Acompañándote en tu camino hacia el bienestar</h3>
                                <p class="text-sm sm:text-base lg:text-lg text-gray-700 leading-relaxed relative z-10">
                                    Acompañar a cada persona en su camino hacia el bienestar físico, emocional y mental, ofreciendo un espacio de calma y conexión con la naturaleza, a través de terapias naturales, productos seleccionados y atención cercana.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Valores -->
                    <div class="aparecer-secuencial w-full mb-10">
                        <div class="bg-white rounded-[2rem] p-8 shadow-lg">
                            <div class="absolute top-0 right-0 w-40 h-40 bg-verde/5 rounded-full -mr-20 -mt-20"></div>
                            <div class="relative">
                                <span class="inline-block text-verde text-sm sm:text-base lg:text-lg font-semibold py-2 bg-verde/10 rounded-full mb-6">Nuestros Valores</span>
                                <div class="space-y-8">
                                    <!-- Empatía -->
                                    <div class="flex items-start gap-6 group">
                                        <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-verde/10 flex items-center justify-center transform transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                                            <i class="fas fa-heart text-verde text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg sm:text-xl lg:text-2xl font-semibold text-verde mb-2 group-hover:translate-x-2 transition-transform duration-300">Empatía</h4>
                                            <p class="text-sm sm:text-base lg:text-lg text-gray-700 leading-relaxed">
                                                Escuchamos con el corazón para comprender las necesidades únicas de cada persona.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Respeto -->
                                    <div class="flex items-start gap-6 group">
                                        <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-verde/10 flex items-center justify-center transform transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                                            <i class="fas fa-hands text-verde text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg sm:text-xl lg:text-2xl font-semibold text-verde mb-2 group-hover:translate-x-2 transition-transform duration-300">Respeto</h4>
                                            <p class="text-sm sm:text-base lg:text-lg text-gray-700 leading-relaxed">
                                                Valoramos profundamente el cuerpo, el tiempo y el proceso de cada cliente.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Compromiso -->
                                    <div class="flex items-start gap-6 group">
                                        <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-verde/10 flex items-center justify-center transform transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                                            <i class="fas fa-star text-verde text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg sm:text-xl lg:text-2xl font-semibold text-verde mb-2 group-hover:translate-x-2 transition-transform duration-300">Compromiso</h4>
                                            <p class="text-sm sm:text-base lg:text-lg text-gray-700 leading-relaxed">
                                                Buscamos constantemente la excelencia y calidad en cada producto y terapia.
                                            </p>
                                        </div>
                                    </div>
                                </div>
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
    <script src="../assets/js/animaciones.js"></script>
    <script src="../assets/js/cerrarSesion.js"></script>
</body>
</html>
