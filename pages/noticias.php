<?php
// Iniciar sesión
session_start();

// Incluir el controlador para obtener las noticias
require_once '../assets/php/MVC/Controlador/noticias-controlador.php';

// Crear instancia del controlador
$controlador = new NoticiasControlador();

// Obtener las noticias (con límite de 10 por página)
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$resultado_noticias = $controlador->obtenerNoticias(10, ($pagina - 1) * 10);
$noticias = $resultado_noticias['noticias'];
$total_noticias = $resultado_noticias['total'];
$total_paginas = ceil($total_noticias / 10);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
</head>
<body class="bg-beige">
    <!-- Header -->
    <?php include "../includes/header.php"; ?>

<<<<<<< Updated upstream
    <!-- Contenido principal - Vista de noticias -->
    <?php require_once "../assets/php/MVC/Vista/noticias-vista.php"; ?>
=======
    <main class="py-12 px-4 md:px-24 max-w-7xl mx-auto">
        <!-- Encabezado de sección -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-serif font-bold text-green-800 mb-4">Bienvenidos a nuestro blog</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">Aquí podrás informarte sobre novedades y noticias relacionadas con los cuidados, la salud y el bienestar tanto de tu cuerpo como de tu mente.</p>
        </div>

        <!-- Botones de añadir reseña y iniciar sesión -->
        <div class="flex justify-between mx-auto py-10">
            <?php
            // Verificar si el usuario tiene sesión iniciada
            if(isset($_SESSION['usuario_id'])) {
                // Si tiene sesión, mostrar botones para usuario logueado
                echo '<a href="blogform.php" class="bg-green-700 hover:bg-green-800 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md flex items-center mx-auto">
                    <i class="fas fa-plus-circle mr-2"></i> Añadir noticia
                </a>';
                
                echo '<a href="../assets/php/MVC/Controlador/usuarios-controlador.php?accion=cerrarSesion" class="bg-red-600 hover:bg-red-800 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md flex items-center mx-auto">
                    <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
                </a>';
            } else {
                // Si no tiene sesión, mostrar botones para visitantes
                echo '<a href="login.php" class="bg-green-700 hover:bg-green-800 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md flex items-center mx-auto">
                    <i class="fas fa-plus-circle mr-2"></i> Añadir tu reseña
                </a>';
                
                echo '<a href="login.php" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md flex items-center mx-auto">
                    <i class="fas fa-user-circle mr-2"></i> Iniciar sesión
                </a>';
            }
            ?>
        </div>

        <!-- Noticias -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Tarjeta 1 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden cursor-pointer transform transition-transform hover:scale-105" onclick="mostrarModal('Terapias naturales para el estrés', 'Las terapias naturales como la aromaterapia, la meditación y el yoga han demostrado ser efectivas para reducir los niveles de estrés y ansiedad. En BioEspacio ofrecemos sesiones personalizadas que combinan estas técnicas para ayudarte a encontrar tu equilibrio interior y mejorar tu bienestar general. Nuestros especialistas te guiarán en un viaje de autodescubrimiento y sanación.', '../assets/img/terapia-natural.jpg')">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <img src="../assets/img/terapia-natural.jpg" alt="Terapias naturales" class="w-full h-full object-cover" onerror="this.src='../assets/img/placeholder.jpg'; this.onerror='';">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-green-800 mb-2">Terapias naturales para el estrés</h3>
                    <p class="text-gray-600 line-clamp-3">Las terapias naturales como la aromaterapia, la meditación y el yoga han demostrado ser efectivas para reducir los niveles de estrés y ansiedad...</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-sm text-gray-500"><i class="far fa-calendar-alt mr-2"></i>15/05/2023</span>
                        <span class="text-sm text-green-700 hover:text-green-900">Leer más <i class="fas fa-arrow-right ml-1"></i></span>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden cursor-pointer transform transition-transform hover:scale-105" onclick="mostrarModal('Beneficios de los productos ecológicos', 'Los productos ecológicos no solo son mejores para el medio ambiente, sino también para nuestra salud. Libres de pesticidas y químicos dañinos, estos alimentos conservan más nutrientes y sabor. En BioEspacio, seleccionamos cuidadosamente nuestros productos para garantizar la máxima calidad y frescura. Apoyamos a productores locales comprometidos con prácticas sostenibles y respetuosas con el entorno.', '../assets/img/productos-eco.jpg')">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <img src="../assets/img/productos-eco.jpg" alt="Productos ecológicos" class="w-full h-full object-cover" onerror="this.src='../assets/img/placeholder.jpg'; this.onerror='';">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-green-800 mb-2">Beneficios de los productos ecológicos</h3>
                    <p class="text-gray-600 line-clamp-3">Los productos ecológicos no solo son mejores para el medio ambiente, sino también para nuestra salud. Libres de pesticidas y químicos dañinos...</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-sm text-gray-500"><i class="far fa-calendar-alt mr-2"></i>02/06/2023</span>
                        <span class="text-sm text-green-700 hover:text-green-900">Leer más <i class="fas fa-arrow-right ml-1"></i></span>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden cursor-pointer transform transition-transform hover:scale-105" onclick="mostrarModal('Plantas medicinales esenciales', 'Las plantas medicinales han sido utilizadas durante milenios para tratar diversas dolencias. En BioEspacio, contamos con una amplia variedad de hierbas frescas y secas con propiedades curativas. Desde la manzanilla para la digestión hasta la valeriana para el insomnio, nuestros expertos pueden asesorarte sobre qué plantas son más adecuadas para tus necesidades específicas y cómo utilizarlas correctamente.', '../assets/img/plantas-medicinales.jpg')">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <img src="../assets/img/plantas-medicinales.jpg" alt="Plantas medicinales" class="w-full h-full object-cover" onerror="this.src='../assets/img/placeholder.jpg'; this.onerror='';">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-green-800 mb-2">Plantas medicinales esenciales</h3>
                    <p class="text-gray-600 line-clamp-3">Las plantas medicinales han sido utilizadas durante milenios para tratar diversas dolencias. En BioEspacio, contamos con una amplia variedad...</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-sm text-gray-500"><i class="far fa-calendar-alt mr-2"></i>20/06/2023</span>
                        <span class="text-sm text-green-700 hover:text-green-900">Leer más <i class="fas fa-arrow-right ml-1"></i></span>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 4 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden cursor-pointer transform transition-transform hover:scale-105" onclick="mostrarModal('Talleres de bienestar holístico', 'Nuestros talleres de bienestar holístico ofrecen una experiencia transformadora para cuerpo, mente y espíritu. Desde clases de cocina saludable hasta sesiones de meditación guiada, cada taller está diseñado para proporcionarte herramientas prácticas que puedas incorporar a tu vida diaria. Únete a nuestra comunidad y descubre cómo pequeños cambios pueden tener un gran impacto en tu calidad de vida.', '../assets/img/talleres-bienestar.jpg')">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <img src="../assets/img/talleres-bienestar.jpg" alt="Talleres de bienestar" class="w-full h-full object-cover" onerror="this.src='../assets/img/placeholder.jpg'; this.onerror='';">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-green-800 mb-2">Talleres de bienestar holístico</h3>
                    <p class="text-gray-600 line-clamp-3">Nuestros talleres de bienestar holístico ofrecen una experiencia transformadora para cuerpo, mente y espíritu. Desde clases de cocina saludable...</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-sm text-gray-500"><i class="far fa-calendar-alt mr-2"></i>10/07/2023</span>
                        <span class="text-sm text-green-700 hover:text-green-900">Leer más <i class="fas fa-arrow-right ml-1"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script para el modal -->
        <script>
            function mostrarModal(titulo, contenido, imagen) {
                Swal.fire({
                    title: titulo,
                    html: `
                        <div class="text-left">
                            <div class="mb-4">
                                <img src="${imagen}" alt="${titulo}" class="w-full h-64 object-cover rounded-lg" onerror="this.src='../assets/img/placeholder.jpg'; this.onerror='';">
                            </div>
                            <p class="text-gray-700">${contenido}</p>
                        </div>
                    `,
                    width: '600px',
                    showCloseButton: true,
                    showConfirmButton: false,
                    customClass: {
                        container: 'swal-wide',
                        popup: 'rounded-xl'
                    }
                });
            }
        </script>
        <!-- Paginación -->
        <div class="flex justify-center my-12">
            <nav aria-label="Paginación">
                <ul class="flex items-center -space-x-px h-10 text-base">
                    <li>
                        <a href="#" class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700">
                            <span class="sr-only">Anterior</span>
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-green-700 text-white border border-gray-300 hover:bg-green-800 hover:text-white">1</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">2</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">3</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700">
                            <span class="sr-only">Siguiente</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </main>
>>>>>>> Stashed changes

    <!-- Footer -->
    <?php include "../includes/footer.php"; ?>

    <!-- Scripts -->
    <script>
        // Variable para el JavaScript
        const userRole = "<?= isset($_SESSION['rol']) ? $_SESSION['rol'] : '' ?>";
    </script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/noticias.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>

</body>
</html>