<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Herbolario - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet" alt="Estilos de Flowbite">
    <link href="../assets/css/src/output.css" rel="stylesheet" alt="Estilos personalizados">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>

    <main>
        <!-- Primer div -->
        <div class="bg-beige">
            <div class="pt-4 py-6 flex flex-col sm:flex-row justify-center items-center h-full w-3/4 mx-auto text-gray-900">
                <div class="flex flex-col w-full sm:w-1/2 text-center aparecer">
                    <h1 class="text-4xl font-bold font-display-CormorantGaramond mb-4 text-verde">El Herbolario</h1>
                    <p class="text-base aparecer-secuencial">Bienvenido a nuestro espacio dedicado a la salud natural</p>
                    <p class="text-base aparecer-secuencial">Productos naturales seleccionados con cuidado para tu bienestar</p>
                </div>
                <div class="w-full sm:w-2/3 flex justify-center mt-6 sm:mt-0">
                    <img src="../assets/img/el_herbolario/foto2.png" alt="El Herbolario" class="object-contain w-3/4 sm:w-1/2 imagen-aparecer">
                </div>
            </div>
        </div>
        <!-- Segundo div -->
        <div>
            <!-- Primer div con fondo gris claro -->
            <div class="bg-gray-200 py-24 w-full">
                <div class="flex flex-col sm:flex-row items-center w-3/4 mx-auto aparecer">
                    <div class="w-full sm:w-1/3 mb-6 sm:mb-0 order-1 sm:order-none">
                        <img src="../assets/img/el_herbolario/foto3.jpg" alt="Productos naturales" class="object-cover rounded-lg w-full imagen-aparecer">
                    </div>
                    <div class="flex flex-col justify-center items-center text-center w-full sm:w-2/3 sm:pl-6 text-negro order-2 sm:order-none">
                        <p class="mb-3 aparecer-secuencial">Somos un herbolario que funcionamos como un Stock limitado de productos, elegidos
                        en base a la experiencia y que para nosotros son productos estrella para un número de
                        síntomas comunes.</p>
                        <p class="text-balance aparecer-secuencial">El resto de productos funciona <b>BAJO PEDIDO</b>. Nos sirven con rapidez, de la mañana a
                        la tarde y de la tarde al día siguiente el producto está en nuestro espacio.</p>
                    </div>
                </div>
            </div>
            
            <!-- Segundo div con fondo blanco -->
            <div class="bg-white border-y border-gray-200 py-24 w-full text-verde">
                <div class="flex flex-col-reverse sm:flex-row items-center w-3/4 mx-auto aparecer">
                    <div class="flex flex-col justify-center items-center text-center w-full sm:w-2/3 sm:pr-6 order-2 sm:order-none">
                        <p class="mb-3 aparecer-secuencial">Nuestros clientes suelen ser personas que empiezan un tratamiento con su Naturópata
                        o médico de referencia, y nosotros <b>nos encargamos de reunir sus productos</b> 
                        al mejor precio posible.</p>
                        <p class="mb-3 text-balance aparecer-secuencial">Ponemos a tu disposición nuestro <a href="https://wa.me/+34XXXXXXXXX" class="font-bold hover:underline">Whatsapp</a> para realizar los pedidos en cualquier momento.</p>
                    </div>
                    <div class="w-full sm:w-1/3 mb-6 sm:mb-0 order-1 sm:order-none">
                        <img src="../assets/img/el_herbolario/puerta.jpeg" alt="Puerta de Bioespacio" class="object-cover rounded-lg w-full imagen-aparecer">
                    </div>
                </div>
            </div>
            
            <!-- Tercer div -->
            <div class="bg-gray-200 py-24 w-full">
                <div class="flex flex-col items-center justify-center w-3/4 mx-auto aparecer">
                    <p class="mb-3 text-center">Nuestra disponibilidad en tienda es limitada...</p>
                    <p class="text-xl font-semibold mb-3 text-negro">Lunes a Viernes: 11 - 13 y 17 - 19</p>
                    <p class="text-negro font-medium">**Servicio de pedidos urgentes</p>
                </div>
            </div>
        </div>
    </main>
    <?php include "../includes/footer.php"; ?>
</body>
    <!-- Scripts al final del body -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js" alt="Script principal"></script>
    <script src="../assets/js/animaciones.js"></script>
</html>