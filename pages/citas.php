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
