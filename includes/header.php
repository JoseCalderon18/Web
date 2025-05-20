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
    <!-- jQuery debe ser lo primero que se carga -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Resto de tus etiquetas head -->
</head>
    <?php include "cookieBanner.php"; ?>
    
    <header class="fixed top-0 left-0 right-0 z-50 mb-10">
    <nav class="flex flex-wrap justify-between items-center p-4 w-full h-32 bg-logo" aria-label="Navegación principal">
        <a href="../index.php"><img src="../assets/img/logo.png" alt="Logotipo de BioSpace - Centro de bienestar natural" class="h-24 md:h-26 lg:h-28"></a>
        
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
            <li class="w-full sm:w-auto"><a href="../pages/equipo.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Equipo">Equipo</a></li>
            <li class="w-full sm:w-auto"><a href="../pages/encuentranos.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Encuentranos">Encuentranos</a></li>
            <li class="w-full sm:w-auto"><a href="../pages/noticias.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección noticias">Noticias</a></li>
            <li class="w-full sm:w-auto"><a href="../pages/citas.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección citas">Citas</a></li>
            
            <!-- Enlaces solo para administradores -->
            <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                <li class="w-full sm:w-auto"><a href="../pages/usuarios.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Gestión de Usuarios">Usuarios</a></li>
                <li class="w-full sm:w-auto"><a href="../pages/productos.php" class="block py-2 sm:py-0 text-black hover:text-gray-600 hover:underline pl-4 sm:pl-0" aria-label="Ir a sección Productos">Productos</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <!-- Separador -->
    <div class="bg-gray-700 w-full h-3" aria-label="Separador decorativo"></div>
</header>
    <!-- separado del header -->
    <div class="bg-beige h-32"></div>
</html>