<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
    <header class="fixed top-0 left-0 right-0 z-50 mb-10">
        <nav class="flex flex-wrap justify-between items-center p-4 w-full h-32 bg-logo" aria-label="Navegación principal">
            <a href="../index.php"><img src="../assets/img/logo.webp" alt="Logotipo de BioSpace - Centro de bienestar natural" class="h-24 md:h-26 lg:h-28"></a>
            
            <!-- Botón hamburguesa solo visible en móvil -->  
            <button class="block sm:hidden p-2" id="botonHamburguesa" aria-label="Botón para abrir menú de navegación">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-label="Icono de menú hamburguesa">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            
            <!-- Menú de navegación -->
            <ul id="menuNav" class="hidden sm:flex w-full sm:w-auto flex-col sm:flex-row items-start sm:items-center gap-4 lg:gap-6 xl:gap-8 bg-white sm:bg-transparent p-4 sm:p-0 text-[6px] sm:text-[8px] md:text-[10px] lg:text-xs font-display-Lora uppercase font-semibold absolute sm:relative left-0 top-full sm:top-auto" aria-label="Lista de navegación principal">
                <li class="w-full sm:w-auto"><a href="../index.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Inicio">Inicio</a></li>
                <li class="w-full sm:w-auto"><a href="../pages/nosotras.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Nosotras">Nosotras</a></li>
                <li class="w-full sm:w-auto"><a href="../pages/el_herbolario.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección El Herbolario">El Herbolario</a></li>
                <li class="w-full sm:w-auto"><a href="../pages/terapias.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Terapias">Terapias</a></li>
                <li class="w-full sm:w-auto"><a href="../pages/encuentranos.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Encuentranos">Encuentranos</a></li>
                <li class="w-full sm:w-auto"><a href="../pages/noticias.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección noticias">Noticias</a></li>
                <!-- Enlace de login con icono de usuario -->
                <a href="../pages/login.php" class="flex items-center hover:opacity-80 transition-opacity">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-label="Icono de usuario">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M12 4a4 4 0 100 8 4 4 0 000-8z"/>
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    </svg>
                </a>
            </ul>

        </nav>
        <!-- Separador -->
        <div class="bg-gray-700 w-full h-3" aria-label="Separador decorativo"></div>
    </header>
    <!-- separado del header -->
    <div class="bg-beige h-32"></div>
    <?php if (isset($_SESSION['usuario_id'])): ?>
    <!-- Header con datos del usuario -->
    <div class="bg-gray-800 w-full p-4 mt-2">
        <div class="mx-auto flex flex-col sm:flex-row flex-wrap justify-around items-center text-white gap-3">
            <!-- Datos del usuario -->
            <div class="flex flex-col sm:flex-row gap-6 items-center">
                <span class="py-2 text-sm md:text-base">
                    <i class="fas fa-user mr-2"></i>
                    Usuario: <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
                </span>
                <span class="py-2 mx-4 text-sm md:text-base">
                    <i class="fas fa-clock mr-2"></i>
                    Última conexión: <?= date('d/m/Y H:i:s') ?>
                </span>
            </div>
            
            <!-- Botones -->
            <div class="grid grid-cols-2 sm:flex gap-4 sm:gap-6">
                <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                    <a href="productos.php" 
                       class="px-3 sm:px-5 py-2 sm:py-3 text-sm sm:text-base bg-green-700 hover:bg-green-800 rounded-lg transition-colors text-center">
                        <i class="fas fa-shopping-basket mr-1 sm:mr-2"></i>Productos
                    </a>
                    <a href="usuarios.php" 
                       class="px-3 sm:px-5 py-2 sm:py-3 text-sm sm:text-base bg-green-700 hover:bg-green-800 rounded-lg transition-colors text-center">
                        <i class="fas fa-users mr-1 sm:mr-2"></i>Usuarios
                    </a>
                <?php endif; ?>

                <a href="citas.php" 
                       class="px-3 sm:px-5 py-2 sm:py-3 text-sm sm:text-base bg-green-700 hover:bg-green-800 rounded-lg transition-colors text-center">
                        <i class="fas fa-calendar-check mr-1 sm:mr-2"></i>Citas
                    </a>
                    <button type="button" onclick="cerrarSesion()" 
                           class="px-3 sm:px-5 py-2 sm:py-3 text-sm sm:text-base bg-red-600 hover:bg-red-800 rounded-lg transition-colors text-center">
                        <i class="fas fa-sign-out-alt mr-1 sm:mr-2"></i>Cerrar sesión
                    </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
