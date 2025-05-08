<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bioespacio - Centro de bienestar natural</title>
    <link href="node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet" alt="Estilos de Flowbite">
    <link href="assets/css/src/output.css" rel="stylesheet" alt="Estilos personalizados">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include "pages/cookieBanner.php"; ?>
    <header class="fixed top-0 left-0 right-0 z-50">
        <nav class="flex flex-wrap justify-between items-center p-4 w-full h-32 bg-logo" aria-label="Navegación principal">
            <a href="index.php"><img src="assets/img/logo.png" alt="Logotipo de BioSpace - Centro de bienestar natural" class="h-24 md:h-26 lg:h-28"></a>
            
            <!-- Botón hamburguesa solo visible en móvil -->  
            <button class="block sm:hidden p-2" id="botonHamburguesa" aria-label="Botón para abrir menú de navegación">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-label="Icono de menú hamburguesa">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            
            <!-- Menú de navegación -->
            <ul id="menuNav" class="hidden sm:flex w-full sm:w-auto flex-col sm:flex-row items-start sm:items-center gap-4 lg:gap-6 xl:gap-8 bg-white sm:bg-transparent p-4 sm:p-0 text-[6px] sm:text-[8px] md:text-[10px] lg:text-xs font-display-Lora uppercase font-semibold absolute sm:relative left-0 top-full sm:top-auto" aria-label="Lista de navegación principal">
                <li class="w-full sm:w-auto"><a href="index.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Inicio">Inicio</a></li>
                <li class="w-full sm:w-auto"><a href="pages/nosotras.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Nosotras">Nosotras</a></li>
                <li class="w-full sm:w-auto"><a href="pages/el_herbolario.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección El Herbolario">El Herbolario</a></li>
                <li class="w-full sm:w-auto"><a href="pages/terapias.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Terapias">Terapias</a></li>
                <li class="w-full sm:w-auto"><a href="pages/equipo.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Equipo">Equipo</a></li>
                <li class="w-full sm:w-auto"><a href="pages/encuentranos.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Encuentranos">Encuentranos</a></li>
                <li class="w-full sm:w-auto"><a href="pages/noticias.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Noticias">Noticias</a></li>           
                <!-- Solo mostrar enlaces de admin si el usuario tiene rol admin -->
                <?php if(isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                    <li class="w-full sm:w-auto"><a href="pages/usuarios.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Usuarios">Usuarios</a></li>
                    <li class="w-full sm:w-auto"><a href="pages/productos.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Productos">Productos</a></li>
                    <li class="w-full sm:w-auto"><a href="pages/calendarios.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Calendarios">Calendarios</a></li>
                <?php endif; ?>
            </ul>
        </nav> 
        <div class="bg-gray-700 w-full h-3" aria-label="Separador decorativo"></div>
    </header>
    <!-- Espacio para compensar el nav fijo -->
    <div class="h-32 bg-black"></div>
    <main>
    <h1 class="bg-logo text-white text-center text-xl md:text-2xl lg:text-3xl 2xl:text-4xl p-4 font-display-Parisienne font-bold tracking-widest w-full h-full mt-3" aria-label="Lema principal">"Recupera tu equilibrio de manera natural"</h1>

        <!-- Carrusel de fotos -->
        <div id="default-carousel" class="relative w-full h-72 md:h-80 lg:h-96 xl:h-[54rem]" data-carousel="slide" aria-label="Carrusel de imágenes de BioEspacio">
            <div class="relative h-72 md:h-80 lg:h-96 xl:h-[54rem] overflow-hidden">
                <!-- Item 1 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item aria-label="Diapositiva 1 del carrusel">
                    <img src="assets/img/carousel/puerta.jpeg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="Entrada principal de BioEspacio - Puerta de madera con detalles naturales">
                </div>
                <!-- Item 2 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item aria-label="Diapositiva 2 del carrusel">
                    <img src="assets/img/carousel/recepcion.jpeg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="Área de recepción de BioEspacio - Mostrador acogedor con productos naturales">
                </div>
                <!-- Item 3 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item aria-label="Diapositiva 3 del carrusel">
                    <img src="assets/img/carousel/sala_Masaje.jpeg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="Sala de masajes principal - Ambiente relajante con camilla y decoración zen">
                </div>
                <!-- Item 4 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item aria-label="Diapositiva 4 del carrusel">
                    <img src="assets/img/carousel/sala_Masaje1.jpeg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="Segunda sala de masajes - Espacio tranquilo con iluminación suave">
                </div>
            </div>
            <!-- Controles del carrusel -->
            <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev aria-label="Botón para ver imagen anterior">
                <span class="inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10" aria-label="Flecha izquierda">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                    </svg>
                    <span class="sr-only">Anterior</span>
                </span>
            </button>
            <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next aria-label="Botón para ver imagen siguiente">
                <span class="inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10" aria-label="Flecha derecha">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="sr-only">Siguiente</span>
                </span>
            </button>
        </div>

        <!-- Sección de bienvenida -->
        <div class="bg-white h-60 flex flex-col items-center justify-center gap-9 aparecer">
            <h1 class="text-verde text-2xl font-bold text-center md:text-3xl lg:text-4xl w-full font-display-CormorantGaramond">
                BioEspacio Bienestar
            </h1>
            <h2 class="text-verde text-sm font-semibold text-center md:text-base lg:text-xl w-full font-display-CormorantGaramond">
                "Un lugar para conectar con la naturaleza y las plantas y recuperar tu equilibrio"
            </h2>            
        </div>

        <div class="bg-beige h-full flex flex-col items-center gap-10 px-30 py-10 text-gray-500">
            <h1 class="font-bold text-xl text-center md:text-2xl lg:text-3xl w-full font-display-CormorantGaramond aparecer">
                Por qué confiar en BioEspacio
            </h1>
            
            <!-- Contenedor de los Iconos -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-3/4">
                <!-- Cada div de icono con animación secuencial -->
                <div class="flex flex-col items-center justify-start h-48 gap-2 p-3 relative group aparecer-secuencial hover:scale-105 transition-transform duration-500">
                    <!--Icono de estrella-->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                    </svg>
                    
                    <!--Texto de la productos especializados-->
                    <h1 class="text-xs font-semibold text-center md:text-sm lg:text-base w-full font-display-CormorantGaramond">Productos Especializados</h1>
                    <p class="text-[10px] text-center md:text-xs lg:text-sm w-full">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.</p>
                                    
                    <!-- Bordes con animacion-->
                    <div class="absolute top-0 left-0 w-10 h-10 border-t-1 border-l-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-top-left scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                    <div class="absolute bottom-0 right-0 w-10 h-10 border-b-1 border-r-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-bottom-right scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                </div>

                <div class="flex flex-col items-center justify-start h-48 gap-2 p-3 relative group aparecer-secuencial hover:scale-105 transition-transform duration-500">
                    <!--Icono de chat-->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                    </svg>
                    
                    <!--Texto de la Atencion Personalizada-->
                    <h1 class="text-xs font-semibold text-center md:text-sm lg:text-base w-full font-display-CormorantGaramond">Atencion Personalizada</h1>
                    <p class="text-[10px] text-center md:text-xs lg:text-sm w-full">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.</p>
                                    
                    <!-- Bordes con animacion-->
                    <div class="absolute top-0 left-0 w-10 h-10 border-t-1 border-l-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-top-left scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                    <div class="absolute bottom-0 right-0 w-10 h-10 border-b-1 border-r-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-bottom-right scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                </div>

                <div class="flex flex-col items-center justify-start h-48 gap-2 p-3 relative group aparecer-secuencial hover:scale-105 transition-transform duration-500">
                    <!--Icono de incienso-->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-10v3m0 0c-1.5 1-3 2.1-3 4 0 3 6 3 6 0 0-1.9-1.5-3-3-4zm0 15v4m-5-9s2 3 5 3 5-3 5-3" />
                    </svg>
                    <!--Texto de masajes-->
                    <h1 class="text-xs font-semibold text-center md:text-sm lg:text-base w-full font-display-CormorantGaramond">Masajes Terapéuticos</h1>
                    <p class="text-[10px] text-center md:text-xs lg:text-sm w-full">Ofrecemos masajes personalizados para aliviar el estrés y mejorar tu bienestar físico.</p>
                                    
                    <!-- Bordes con animacion-->
                    <div class="absolute top-0 left-0 w-10 h-10 border-t-1 border-l-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-top-left scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                    <div class="absolute bottom-0 right-0 w-10 h-10 border-b-1 border-r-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-bottom-right scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                </div>

                <div class="flex flex-col items-center justify-start h-48 gap-2 p-3 relative group aparecer-secuencial hover:scale-105 transition-transform duration-500">
                    <!--Icono de experiencia y profesionalidad-->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                    </svg>
                    
                    <!--Texto de experiencia y profesionalidad-->
                    <h1 class="text-xs font-semibold text-center md:text-sm lg:text-base w-full font-display-CormorantGaramond">Experiencia y profesionalidad</h1>
                    <p class="text-[10px] text-center md:text-xs lg:text-sm w-full">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.</p>
                                    
                    <!-- Bordes con animacion-->
                    <div class="absolute top-0 left-0 w-10 h-10 border-t-1 border-l-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-top-left scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                    <div class="absolute bottom-0 right-0 w-10 h-10 border-b-1 border-r-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-bottom-right scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                </div>

                <div class="flex flex-col items-center justify-start h-48 gap-2 p-3 relative group aparecer-secuencial hover:scale-105 transition-transform duration-500">
                    <!--Icono de ambiente-->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                    </svg>
                    
                    <!--Texto del ambiente-->
                    <h1 class="text-xs font-semibold text-center md:text-sm lg:text-base w-full font-display-CormorantGaramond">Ambiente Relajante</h1>
                    <p class="text-[10px] text-center md:text-xs lg:text-sm w-full">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.</p>
                                    
                    <!-- Bordes con animacion-->
                    <div class="absolute top-0 left-0 w-10 h-10 border-t-1 border-l-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-top-left scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                    <div class="absolute bottom-0 right-0 w-10 h-10 border-b-1 border-r-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-bottom-right scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                </div>

                <div class="flex flex-col items-center justify-start h-48 gap-2 p-3 relative group aparecer-secuencial hover:scale-105 transition-transform duration-500">
                    <!--Icono de pago-->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 group-hover:text-gray-500 duration-300">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>
                    
                    <!--Texto de la pago-->
                    <h1 class="text-xs font-semibold text-center md:text-sm lg:text-base w-full font-display-CormorantGaramond">Comodidad de pago</h1>
                    <p class="text-[10px] text-center md:text-xs lg:text-sm w-full">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.</p>
                                    
                    <!-- Bordes con animacion-->
                    <div class="absolute top-0 left-0 w-10 h-10 border-t-1 border-l-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-top-left scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                    <div class="absolute bottom-0 right-0 w-10 h-10 border-b-1 border-r-1 border-transparent group-hover:border-gray-500 transition-all duration-300 origin-bottom-right scale-0 group-hover:scale-200 opacity-0 group-hover:opacity-100"></div>
                </div>
            </div>
        </div>
    </main>
    <!-- Footer -->
    <footer class="w-full bg-gris">
        <div class="flex flex-col sm:flex-row w-2/3 sm:w-2/3 p-5 pt-8 mx-auto me-auto h-full text-white gap-2">
            <!--Primer bloque-->
            <div class="flex flex-col gap-3 w-full sm:w-1/3 p-4 m-4 sm:mb-0">
                <img src="assets/img/logoBlanco.png" alt="Logotipo en blanco de BioSpace - Centro de bienestar natural" class="w-auto h-20 sm:h-24 md:h-28 lg:h-32 object-contain">
                <p class="text-xs">Bio epacio tu herbolario de confianza en Madrid</p>
                <!--Telefono de contacto-->
                <p class="flex flex-row gap-2 text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                    </svg>
                    <span>91 666 66 66</span>
                </p>
                <!--Correo de contacto-->     
                <p class="flex flex-row gap-2 text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    <span>bioespaciobienestar@gmail.com</span>
                </p>
                <!--Direccion-->
                <p class="flex flex-row gap-2 text-xs">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                    <span>P.º de Fuente Lucha, 7, 28100 Alcobendas, Madrid</span>
                </p>
            </div>
            <!--Segundo bloque-->
            <div class="flex flex-col gap-3 w-full sm:w-1/3 p-4 m-4 sm:mb-0">
                <!--Horario-->
                <h1 class="text-base font-bold font-display-CormorantGaramond">Horario</h1>
                <p>━━━━━━━</p>
                <p class="text-xs">En BioEspacio, nos esforzamos por ofrecerte el mejor servicio en el mejor tiempo posible. Por eso, nuestro horario es el siguiente:</p>
                <p class="font-bold text-xs">Lunes a Viernes:</p>
                <p class="text-xs">10:00 - 14:00 y 17:00 - 20:00</p>
                <p class="font-bold text-xs">Sábados y Domingos:</p>
                <p class="text-xs">Cerrado</p>
            </div>
            <!--Tercer bloque-->
            <div class="flex flex-col gap-3 w-full sm:w-1/3 p-4 m-2 md:m-4 sm:mb-0">
                <!--Redes sociales-->
                <h1 class="text-base font-bold font-display-CormorantGaramond">Redes sociales</h1>
                <p>━━━━━━━</p>
                <p class="text-xs mb-2">Síguenos en nuestras redes sociales para estar al día de todas nuestras novedades y eventos.</p>
                <!--WhatsApp-->
                <a href="https://wa.me/+34916666666" target="_blank" class="text-white h-10 w-full sm:w-3/4 bg-[#25D366] hover:bg-[#25D366]/80 hover:scale-105 transition-all duration-300 font-medium rounded-lg text-xs px-4 py-2 text-center inline-flex items-center me-2 mb-2 cursor-pointer">
                    <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M20.463 3.488C18.217 1.24 15.231 0 12.05 0 5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.304-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893 0-3.181-1.237-6.167-3.479-8.413zM12.05 21.785h-.004a9.867 9.867 0 01-5.031-1.378l-.36-.214-3.741.981 1-3.648-.235-.374A9.844 9.844 0 012.157 11.892c0-5.46 4.444-9.902 9.897-9.902 2.641 0 5.123 1.03 6.988 2.898a9.837 9.837 0 012.898 6.994c-.003 5.46-4.447 9.903-9.89 9.903zm5.425-7.417c-.299-.149-1.764-.87-2.037-.969-.274-.099-.473-.149-.672.149-.199.298-.771.969-.945 1.168-.174.199-.348.223-.647.074-.3-.149-1.267-.467-2.413-1.488-.892-.796-1.494-1.777-1.669-2.076-.174-.299-.018-.46.131-.609.135-.134.299-.348.448-.522.149-.174.199-.298.298-.497.099-.199.05-.373-.025-.522-.075-.149-.672-1.619-.921-2.217-.242-.581-.487-.502-.672-.511-.174-.008-.373-.01-.572-.01-.199 0-.522.074-.796.373-.274.299-1.045 1.019-1.045 2.488 0 1.469 1.07 2.889 1.219 3.088.149.199 2.096 3.201 5.077 4.487.709.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.273-.198-.572-.347z" clip-rule="evenodd"/>
                    </svg>
                    Síguenos en WhatsApp
                </a>
                <!--Instagram-->
                <a href="https://www.instagram.com/" target="_blank" style="background: linear-gradient(45deg, #833AB4, #C13584, #E1306C, #FD1D1D); color: white;" class="h-10 w-full sm:w-3/4 hover:scale-105 transition-all duration-300 font-medium rounded-lg text-xs px-4 py-2 text-center inline-flex items-center me-2 mb-2">
                    <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                    </svg>
                    Síguenos en Instagram
                </a>
                <!--YouTube-->
                <a href="https://www.youtube.com/" target="_blank" class="bg-red-600 hover:bg-red-700 h-10 w-full sm:w-3/4 hover:scale-105 transition-all duration-300 font-medium rounded-lg text-xs px-4 py-2 text-center inline-flex items-center me-2 mb-2">
                    <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                    </svg>
                    Síguenos en YouTube
                </a>
            </div>
        </div>
    <!--Copyright-->
    <div class="w-full bg-neutral-700 text-center text-sm text-gray-500 py-4">
        <p>Copyright © 2025</p>
    </div>
    </footer>
    <!-- Scripts al final del body -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/animaciones.js"></script>
</body>
</html>