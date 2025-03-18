<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bioespacio - Centro de bienestar natural</title>
    <link href="node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet" alt="Estilos de Flowbite">
    <link href="assets/css/src/output.css" rel="stylesheet" alt="Estilos personalizados">
</head>
<body>
    <header>
        <nav class="fixed top-0 left-0 right-0 z-50 flex flex-wrap justify-between items-center p-4 w-full h-32 bg-logo" aria-label="Navegación principal">
            <a href="index.php"><img src="assets/img/logo.png" alt="Logotipo de BioSpace - Centro de bienestar natural" class="h-24 md:h-26 lg:h-28"></a>
            
            <!-- Botón hamburguesa solo visible en móvil -->  
            <button class="block sm:hidden p-2" id="botonHamburguesa" aria-label="Botón para abrir menú de navegación">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-label="Icono de menú hamburguesa">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            
            <!-- Menú de navegación -->
            <ul id="menuNav" class="hidden sm:flex w-full sm:w-auto flex-col sm:flex-row items-start sm:items-center gap-4 lg:gap-6 xl:gap-8 bg-white sm:bg-transparent p-4 sm:p-0 text-[6px] sm:text-[8px] md:text-[10px] lg:text-xs font-display-Lora uppercase font-semibold absolute sm:relative left-0 top-full sm:top-auto" aria-label="Lista de navegación principal">
                <li class="w-full sm:w-auto"><a href="pages/nosotras.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Nosotras">Nosotras</a></li>
                <li class="w-full sm:w-auto"><a href="pages/el_herbolario.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección El Herbolario">El Herbolario</a></li>
                <li class="w-full sm:w-auto"><a href="pages/terapias.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Terapias">Terapias</a></li>
                <li class="w-full sm:w-auto"><a href="pages/equipo.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Equipo">Equipo</a></li>
                <li class="w-full sm:w-auto"><a href="pages/encuentranos.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Encuentranos">Encuentranos</a></li>
            </ul>
        </nav>
        <!-- Espacio para compensar el nav fijo -->
        <div class="h-32 bg-black"></div>
    </header>

    <main>
    <div class="bg-gray-700 w-full h-3" aria-label="Separador decorativo"></div>
    <h1 class="bg-logo text-white text-center text-xl md:text-2xl lg:text-3xl 2xl:text-4xl p-4 font-display-Parisienne font-bold tracking-widest w-full h-full" aria-label="Lema principal">"Recupera tu equilibrio de manera natural"</h1>

        <!-- Carrusel de fotos -->
        <div id="default-carousel" class="relative w-full h-72 md:h-80 lg:h-96 xl:h-[54rem]" data-carousel="slide" aria-label="Carrusel de imágenes de BioEspacio">
            <div class="relative h-72 md:h-80 lg:h-96 xl:h-[54rem] overflow-hidden">
                <!-- Item 1 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item aria-label="Diapositiva 1 del carrusel">
                    <div class="flex flex-col justify-between h-full">
                        <h1 class="text-white text-2xl font-bold drop-shadow-[2px_2px_0px_black] absolute z-10 text-center md:text-5xl lg:text-7xl xl:text-9xl top-10 font-display-Parisienne tracking-widest w-full" aria-label="Título principal del carrusel">BioEspacio Bienestar</h1>
                        <h1 class="text-white text-lg font-bold drop-shadow-[2px_2px_0px_black] absolute z-10 text-center md:text-2xl lg:text-4xl xl:text-7xl bottom-10 font-display-Parisienne tracking-widest w-full" aria-label="Subtítulo del carrusel">"Un lugar para conectar con la naturaleza y las plantas y recuperar tu equilibrio"</h1>
                    </div>
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

        <div class="bg-white h-60 flex flex-col items-center justify-center gap-9" aria-label="Separador decorativo de color menta">
            <h1 class="text-verde text-2xl font-bold text-center md:text-3xl lg:text-4xl w-full font-display-CormorantGaramond">BioEspacio Bienestar</h1>
            <h2 class="text-verde text-sm font-semibold text-center md:text-base lg:text-xl w-full font-display-CormorantGaramond">"Un lugar para conectar con la naturaleza y las plantas y recuperar tu equilibrio"</h2>            
        </div>

        <div class="bg-beige h-full flex flex-col items-center  gap-10 px-30 py-10 text-gray-500">
            <h1 class="font-bold text-xl text-center md:text-2xl lg:text-3xl w-full font-display-CormorantGaramond">Por qué confiar en BioEspacio</h1>
            <!-- Contenedor de las Iconos-->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-3/4">
                <!--Div de Productos Especializados-->
                <div class="flex flex-col items-center justify-start h-48 gap-2 p-3 relative group hover:scale-105 duration-300">
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

                <!--Div de Atencion Personalizada-->
                <div class="flex flex-col items-center justify-start h-48 gap-2 p-3 relative group hover:scale-105 duration-300">
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

                <!--Div de Masajes-->
                <div class="flex flex-col items-center justify-start h-48 gap-2 p-3 relative group hover:scale-105 duration-300">
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

                <!--Div de Experiencia y profesionalidad-->
                <div class="flex flex-col items-center justify-start h-48 gap-2 p-3 relative group hover:scale-105 duration-300">
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

                <!--Div de Ambiente Relajante-->
                <div class="flex flex-col items-center justify-start h-48 gap-2 p-3 relative group hover:scale-105 duration-300">
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

                <!--Div de Comodidad de pago-->
                <div class="flex flex-col items-center justify-start h-48 gap-2 p-3 relative group hover:scale-105 duration-300">
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
                    <!--Social buttons-->
                    <button type="button" class="text-white h-10 w-full sm:w-3/4 bg-[#3b5998] hover:bg-[#3b5998]/80 hover:scale-105 transition-all duration-300 font-medium rounded-lg text-xs px-4 py-2 text-center inline-flex items-center me-2 mb-2 cursor-pointer">
                        <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 8 19">
                            <path fill-rule="evenodd" d="M6.135 3H8V0H6.135a4.147 4.147 0 0 0-4.142 4.142V6H0v3h2v9.938h3V9h2.021l.592-3H5V3.591A.6.6 0 0 1 5.592 3h.543Z" clip-rule="evenodd"/>
                        </svg>
                        Síguenos en Facebook
                    </button>
                    <button type="button" class="text-white h-10 w-full sm:w-3/4 bg-black hover:bg-black/80 hover:scale-105 transition-all duration-300 font-medium rounded-lg text-xs px-4 py-2 text-center inline-flex items-center me-2 mb-2 cursor-pointer">
                        <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13.6823 10.6218L20.2391 3H18.6854L12.9921 9.61788L8.44486 3H3.2002L10.0765 13.0074L3.2002 21H4.75404L10.7663 14.0113L15.5685 21H20.8131L13.6819 10.6218H13.6823ZM11.5541 13.0956L10.8574 12.0991L5.31391 4.16971H7.70053L12.1742 10.5689L12.8709 11.5655L18.6861 19.8835H16.2995L11.5541 13.096V13.0956Z"/>
                        </svg>
                        Síguenos en X
                    </button>
                    <!--Google-->
                    <button type="button" class="text-white h-10 w-full sm:w-3/4 bg-[#4285F4] hover:bg-[#4285F4]/80 hover:scale-105 transition-all duration-300 font-medium rounded-lg text-xs px-4 py-2 text-center inline-flex items-center me-2 mb-2 cursor-pointer">
                        <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 19">
                            <path fill-rule="evenodd" d="M8.842 18.083a8.8 8.8 0 0 1-8.65-8.948 8.841 8.841 0 0 1 8.8-8.652h.153a8.464 8.464 0 0 1 5.7 2.257l-2.193 2.038A5.27 5.27 0 0 0 9.09 3.4a5.882 5.882 0 0 0-.2 11.76h.124a5.091 5.091 0 0 0 5.248-4.057L14.3 11H9V8h8.34c.066.543.095 1.09.088 1.636-.086 5.053-3.463 8.449-8.4 8.449l-.186-.002Z" clip-rule="evenodd"/>
                        </svg>
                        Síguenos en Google
                    </button>
                    <!--WhatsApp-->
                    <button type="button" class="text-white h-10 w-full sm:w-3/4 bg-[#25D366] hover:bg-[#25D366]/80 hover:scale-105 transition-all duration-300 font-medium rounded-lg text-xs px-4 py-2 text-center inline-flex items-center me-2 mb-2 cursor-pointer">
                        <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M20.463 3.488C18.217 1.24 15.231 0 12.05 0 5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.304-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893 0-3.181-1.237-6.167-3.479-8.413zM12.05 21.785h-.004a9.867 9.867 0 01-5.031-1.378l-.36-.214-3.741.981 1-3.648-.235-.374A9.844 9.844 0 012.157 11.892c0-5.46 4.444-9.902 9.897-9.902 2.641 0 5.123 1.03 6.988 2.898a9.837 9.837 0 012.898 6.994c-.003 5.46-4.447 9.903-9.89 9.903zm5.425-7.417c-.299-.149-1.764-.87-2.037-.969-.274-.099-.473-.149-.672.149-.199.298-.771.969-.945 1.168-.174.199-.348.223-.647.074-.3-.149-1.267-.467-2.413-1.488-.892-.796-1.494-1.777-1.669-2.076-.174-.299-.018-.46.131-.609.135-.134.299-.348.448-.522.149-.174.199-.298.298-.497.099-.199.05-.373-.025-.522-.075-.149-.672-1.619-.921-2.217-.242-.581-.487-.502-.672-.511-.174-.008-.373-.01-.572-.01-.199 0-.522.074-.796.373-.274.299-1.045 1.019-1.045 2.488 0 1.469 1.07 2.889 1.219 3.088.149.199 2.096 3.201 5.077 4.487.709.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.273-.198-.572-.347z" clip-rule="evenodd"/>
                        </svg>
                        Síguenos en WhatsApp
                    </button>
                </div>
        </div>
    <!--Copyright-->
    <div class="w-full bg-neutral-700 text-center text-sm text-gray-500 py-4">
        <p>Copyright © 2025</p>
    </div>
    </footer>
</body>
    <!-- Scripts al final del body -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="assets/js/script.js" alt="Script principal"></script>
</html>