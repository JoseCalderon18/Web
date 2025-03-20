<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Herbolario - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet" alt="Estilos de Flowbite">
    <link href="../assets/css/src/output.css" rel="stylesheet" alt="Estilos personalizados">
</head>
<body>
    <?php require_once '../includes/header.php'; ?>

    <main>
        <!-- Primer div -->
        <div class="bg-beige">
            <div class="pt-4 py-6 flex justify-center items-center h-full w-3/4 mx-auto">
                <div class="flex flex-col justify-center items-center text-center w-1/2">
                    <h1 class="text-4xl font-bold font-display-CormorantGaramond mb-4">El Herbolario</h1>
                    <p class="text-center text-lg font-display-CormorantGaramond">Lorem ipsum dolor sit amet</p>
                    <p class="text-center text-lg font-display-CormorantGaramond">consectetur adipisicing elit.</p>
                    <p class="text-center text-lg font-display-CormorantGaramond">Quisquam, quos.</p>
                </div>
                <div class="w-1/2 flex justify-center">
                    <img src="../assets/img/el_herbolario/foto2.png" alt="El Herbolario" class="object-contain h-auto w-1/2">
                </div>
            </div>
        </div>
        <!-- Segundo div -->
        <div>
            <!-- Primer div con fondo gris claro - ancho completo -->
            <div class="bg-gray-200 py-12 w-full">
                <div class="flex flex-row items-center w-3/4 mx-auto">
                    <div class="w-1/3">
                        <img src="../assets/img/el_herbolario/foto3.jpg" alt="Productos naturales" class="object-cover rounded-lg">
                    </div>
                    <div class="flex flex-col justify-center items-center text-center w-2/3 pl-6 text-negro">
                        <p class="mb-3">Somos un herbolario que funcionamos como un Stock limitado de productos, elegidos
                        en base a la experiencia y que para nosotros son productos estrella para un número de
                        síntomas comunes.</p>
                        <p class="text-balance">El resto de productos funciona <b>BAJO PEDIDO</b>. Nos sirven con rapidez, de la mañana a
                        la tarde y de la tarde al día siguiente el producto está en nuestro espacio.</p>
                    </div>
                </div>
            </div>
            
            <!-- Segundo div con fondo blanco - ancho completo -->
            <div class="bg-white border-y border-gray-200 py-12 w-full text-verde">
                <div class="flex flex-row items-center w-3/4 mx-auto">
                    <div class="flex flex-col justify-center items-center text-center w-2/3 pr-6">
                        <p class="mb-3">Nuestros clientes suelen ser personas que empiezan un tratamiento con su Naturópata
                        o médico de referencia, y nosotros <b>nos encargamos de reunir sus productos</b> 
                        al mejor precio posible.</p>
                        <p class="mb-3 text-balance">Ponemos a tu disposición nuestro <a href="https://wa.me/+34XXXXXXXXX" class="font-bold hover:underline">Whatsapp</a> para realizar los pedidos en cualquier momento.</p>
                    </div>
                    <div class="w-1/3">
                        <img src="../assets/img/el_herbolario/puerta.jpeg" alt="Puerta de Bioespacio" class="object-cover rounded-lg">
                    </div>
                </div>
            </div>
            
            <!-- Tercer div con fondo negro personalizado - ancho completo -->
            <div class="bg-gray-200 py-12 w-full">
                <div class="flex flex-col items-center justify-center w-3/4 mx-auto">
                        <p class="mb-3">Nuestra disponibilidad en tienda es limitada. Los horarios para recoger los productos son:</p>
                        <p class="text-xl font-semibold mb-3 text-negro">Lunes a Viernes: 11 - 13 y 17 - 19</p>
                        <p class="text-negro font-medium">**Servicio de pedidos urgentes</p>
                </div>
            </div>
        </div>
    </main>

    <?php require_once '../includes/footer.php'; ?>
</body>
    <!-- Scripts al final del body -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="assets/js/script.js" alt="Script principal"></script>
</html>