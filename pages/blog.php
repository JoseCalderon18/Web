<?php
// Iniciar sesión al principio del archivo, antes de cualquier salida
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-beige">
    <!-- Header -->
    <?php require_once '../includes/header.php'; ?>

    <main class="py-12 px-4 md:px-24 max-w-7xl mx-auto">
        <!-- Encabezado de sección -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-serif font-bold text-green-800 mb-4">Opiniones de nuestros clientes</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">Descubre lo que nuestros clientes dicen sobre su experiencia con BioEspacio. Nos enorgullece ofrecer productos y servicios que marcan la diferencia.</p>
        </div>

        <!-- Botones de añadir reseña y iniciar sesión -->
        <div class="flex justify-between mx-auto py-10">
            <?php
            // Verificar si el usuario tiene sesión iniciada
            if(isset($_SESSION['usuario_id'])) {
                // Si tiene sesión, mostrar botones para usuario logueado
                echo '<a href="blogform.php" class="bg-green-700 hover:bg-green-800 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md flex items-center mx-auto">
                    <i class="fas fa-plus-circle mr-2"></i> Añadir tu reseña
                </a>';
                
                echo '<a href="../assets/php/MVC/Controlador/usuarios-controlador.php?accion=cerrarSesion" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md flex items-center mx-auto">
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

        <!-- Contenedor de tarjetas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Tarjeta de reseña -->
            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300 border-l-4 border-green-500">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="font-serif text-xl font-semibold text-green-800">María García</h2>
                </div>
                <div class="flex text-yellow-400 text-xl mb-3">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="text-gray-700 mb-4">"Los productos son increíbles, totalmente naturales y el servicio es excelente. Recomiendo BioEspacio a todos mis amigos."</p>
                <div class="text-gray-500 text-sm">Hace 2 días</div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300 border-l-4 border-green-500">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="font-serif text-xl font-semibold text-green-800">Carlos Rodríguez</h2>
                </div>
                <div class="flex text-yellow-400 text-xl mb-3">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <p class="text-gray-700 mb-4">"Excelente calidad en todos sus productos. El personal es muy amable y conocedor. Volveré pronto."</p>
                <div class="text-gray-500 text-sm">Hace 1 semana</div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300 border-l-4 border-green-500">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="font-serif text-xl font-semibold text-green-800">Laura Martínez</h2>
                </div>
                <div class="flex text-yellow-400 text-xl mb-3">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <p class="text-gray-700 mb-4">"Me encanta la variedad de productos orgánicos. Desde que descubrí BioEspacio, no compro en otro lugar."</p>
                <div class="text-gray-500 text-sm">Hace 2 semanas</div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300 border-l-4 border-green-500">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="font-serif text-xl font-semibold text-green-800">Javier López</h2>
                </div>
                <div class="flex text-yellow-400 text-xl mb-3">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="text-gray-700 mb-4">"Increíble atención al cliente y productos de primera calidad. Definitivamente vale la pena el precio por la calidad que ofrecen."</p>
                <div class="text-gray-500 text-sm">Hace 1 mes</div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300 border-l-4 border-green-500">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="font-serif text-xl font-semibold text-green-800">Ana Sánchez</h2>
                </div>
                <div class="flex text-yellow-400 text-xl mb-3">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="text-gray-700 mb-4">"Los talleres que ofrecen son muy educativos. He aprendido mucho sobre sostenibilidad y productos ecológicos."</p>
                <div class="text-gray-500 text-sm">Hace 2 meses</div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300 border-l-4 border-green-500">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="font-serif text-xl font-semibold text-green-800">Miguel Torres</h2>
                </div>
                <div class="flex text-yellow-400 text-xl mb-3">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <p class="text-gray-700 mb-4">"Gran selección de productos orgánicos. El personal siempre está dispuesto a ayudar y recomendar productos según tus necesidades."</p>
                <div class="text-gray-500 text-sm">Hace 3 meses</div>
            </div>
        </div>

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

    <!-- Footer -->
    <?php require_once '../includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="../assets/js/script.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
</body>
</html>