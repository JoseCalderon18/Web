<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../assets/php/MVC/Controlador/citas-controlador.php';
$controlador = new CitasControlador();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Citas - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Mantén solo estas dependencias para FullCalendar -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class=" bg-beige">

    <?php include "../includes/header.php"; ?>

    <?php if (isset($_SESSION['usuario_id'])): ?>
    <!-- Header con datos del usuario -->
    <div class="bg-gray-800 w-full p-4 mb-4 md:mb-8 my-2">
        <div class="mx-auto flex flex-col sm:flex-row flex-wrap justify-around items-center text-white gap-3">
            <span class="py-2 text-sm md:text-base">
                <i class="fas fa-user mr-2"></i>
                Usuario: <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
            </span>
            <span class="py-2 text-sm md:text-base">
                <i class="fas fa-clock mr-2"></i>
                Última conexión: <?= date('d/m/Y H:i:s') ?>
            </span>
            <div class="flex gap-4">
                <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                    <a href="productos.php" 
                       class="px-4 py-2 bg-green-700 hover:bg-green-800 rounded-lg transition-colors">
                        <i class="fas fa-shopping-basket mr-2"></i>Productos
                    </a>
                <?php endif; ?>
                <a href="../assets/php/MVC/Controlador/usuarios-controlador.php?accion=cerrarSesion" 
                   class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                    <i class="fas fa-sign-out-alt mr-2"></i>Cerrar sesión
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <main>
        <div class="bg-beige my-10 py-5">
            <!-- Título y descripción -->
            <div class="mx-auto px-4">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-center text-verde font-display-CormorantGaramond mb-4">
                    Calendario de Citas
                </h1>
                <p class="text-gray-700 text-center">
                    Reserva tu cita con nosotros o gestiona las citas programadas
                </p>
            </div>

            <!-- Incluir vista de citas -->
            <?php require_once "../assets/php/MVC/Vista/citas-vista.php"; ?>
        </div>
    </main>

    <?php include "../includes/footer.php"; ?>
    <script src="../assets/js/citas.js"></script>
</body>
</html>
