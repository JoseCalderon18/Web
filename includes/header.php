<html>
    <body>
        <header>
            <nav class="fixed top-0 left-0 right-0 z-50 flex justify-between items-center p-4 w-full h-32 bg-logo" aria-label="Navegación principal">

                <img src="../assets/img/logo.png" alt="Logotipo de BioSpace - Centro de bienestar natural" class="h-24 sm:h-28 md:h-30 lg:h-32">
                
                <!-- Botón hamburguesa solo visible en móvil -->  
                <button class="block sm:hidden p-2" id="botonHamburguesa" aria-label="Botón para abrir menú de navegación">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-label="Icono de menú hamburguesa">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                
                <!-- Menú de navegación -->
                <ul id="menuNav" class="hidden sm:flex sm:w-auto flex-col sm:flex-row items-center gap-4 lg:gap-6 xl:gap-8 bg-white sm:bg-transparent p-2 sm:p-0 text-[6px] sm:text-[8px] md:text-[10px] lg:text-xs font-display-Lora uppercase font-semibold" aria-label="Lista de navegación principal">
                    <li><a href="#" class="block py-1 sm:py-0 text-black hover:text-gray-600 hover:underline" aria-label="Ir a sección Nosotras">Nosotras</a></li>
                    <li><a href="#" class="block py-1 sm:py-0 text-black hover:text-gray-600 hover:underline" aria-label="Ir a sección El Herbolario">El Herbolario</a></li>
                    <li><a href="#" class="block py-1 sm:py-0 text-black hover:text-gray-600 hover:underline" aria-label="Ir a sección Terapias">Terapias</a></li>
                    <li><a href="#" class="block py-1 sm:py-0 text-black hover:text-gray-600 hover:underline" aria-label="Ir a sección Equipo">Equipo</a></li>
                    <li><a href="#" class="block py-1 sm:py-0 text-black hover:text-gray-600 hover:underline" aria-label="Ir a sección Encuentranos">Encuentranos</a></li>
                </ul>
            </nav>
            <!-- Espacio para compensar el nav fijo -->
            <div class="h-32 bg-black"></div>
        </header>
    </body>
</html>