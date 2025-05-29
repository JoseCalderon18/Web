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
</head>
<body>
    <?php include "../includes/header.php"; ?>

    <main>
        <div class="bg-white pt-8 px-8 py-10 w-full aparecer">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mx-auto w-3/4">
                <!-- Columna de texto -->
                <div class="flex flex-col justify-center aparecer-secuencial">
                    <h1 class="text-3xl font-display-CormorantGaramond font-bold text-verde mb-4">"Porque el bienestar es un viaje, y aquí tienes un lugar donde te acompañamos"</h1>
                    <p class="text-xs md:text-sm text-verde mb-4">Hola, somos <strong>Tessa y Beatriz</strong>.</p>
                    <p class="text-xs md:text-sm text-verde mb-4">Os presentamos <strong>Bioespacio Bienestar</strong>, un herbolario diferente que comenzó hace unos años para Tessa como un refugio, un espacio de calma y equilibrio en un momento clave de su vida. Con el tiempo, su camino se cruzó con el de Beatriz, y juntas dieron forma a lo que hoy es mucho más que un herbolario: un espacio dedicado al cuidado personal físico y mental.</p>
                    <p class="text-xs md:text-sm text-verde mb-4">Buscamos y ponemos a vuestra disposición las mejores terapias naturales, terapeutas y suplementos que podemos encontrar. Con trayectorias complementarias y una visión común, <strong>acompañamos a quienes buscan mejorar su calidad de vida a través de las terapias naturales y el poder de las plantas</strong>.</p>
                </div>
                
                <!-- Columna del carrusel -->
                <div class="bg-beige rounded-lg shadow-md h-80 w-full md:w-2/3 mb-8">
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
        <section class="bg-beige py-12 px-6 md:px-20 text-center aparecer">
            <h2 class="text-4xl font-display-CormorantGaramond font-bold text-verde mb-10">Misión y Valores</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Misión -->
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition text-left aparecer-secuencial">
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <div class="flex items-center gap-4">
                            <img src="../assets/img/mision.png" alt="Misión" class="w-16 h-16 rounded-full shadow">
                            <h3 class="text-2xl font-semibold text-verde">Nuestra Misión</h3>
                        </div>
                        <button type="button" class="text-verde hover:text-verde-700 focus:outline-none" data-collapse-toggle="mision-content">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="mision-content" class="hidden">
                        <p class="text-sm text-gray-700 leading-relaxed">
                            Acompañar a cada persona en su camino hacia el bienestar físico, emocional y mental, ofreciendo un espacio de calma y conexión con la naturaleza, a través de terapias naturales, productos seleccionados y atención cercana.
                        </p>
                    </div>
                </div>

                <!-- Valores -->
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition text-left aparecer-secuencial">
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <div class="flex items-center gap-4">
                            <img src="../assets/img/valores.png" alt="Valores" class="w-16 h-16 rounded-full shadow">
                            <h3 class="text-2xl font-semibold text-verde">Nuestros Valores</h3>
                        </div>
                        <button type="button" class="text-verde hover:text-verde-700 focus:outline-none" data-collapse-toggle="valores-content">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="valores-content" class="hidden">
                        <ul class="text-sm text-gray-700 leading-relaxed list-disc list-inside">
                            <li><strong>Empatía:</strong> Escuchamos con el corazón para comprender las necesidades de cada persona.</li>
                            <li><strong>Respeto:</strong> Valoramos el cuerpo, el tiempo y el proceso de cada cliente.</li>
                            <li><strong>Compromiso:</strong> Buscamos constantemente la mejora y la calidad en cada producto y terapia.</li>
                    </div>
                </div>
        </section>
    </main>

    <?php include "../includes/footer.php"; ?>

    <!-- Scripts al final del body -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>  
    <script src="../assets/js/animaciones.js"></script>
</body>
</html>
