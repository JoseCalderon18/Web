<?php
session_start();
require_once '../assets/php/MVC/Controlador/citas-controlador.php';
$controlador = new CitasControlador();

// Verificar si el usuario es administrador
$esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
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
<body class="bg-beige flex flex-col min-h-screen">
    <?php include "../includes/header.php"; ?>

    <main class="flex-grow py-12 px-4 md:px-24">
        <div class="max-w-7xl mx-auto">
            <!-- Encabezado -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-green-800 mb-4 font-display-CormorantGaramond">
                    Calendario de Citas
                </h1>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    <?= $esAdmin ? 'Gestiona las citas programadas y añade nuevas citas.' : 'Reserva tu cita con nosotros.' ?>
                </p>
            </div>

            <!-- Leyenda del calendario (solo para admin) -->
            <?php if ($esAdmin): ?>
            <div class="mb-8 bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-green-800 mb-2">Leyenda:</h3>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-400 rounded mr-2"></div>
                        <span class="text-sm">Horario disponible</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-400 rounded mr-2"></div>
                        <span class="text-sm">Cita ocupada</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-gray-300 rounded mr-2"></div>
                        <span class="text-sm">Fuera de horario laboral</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Contenedor de calendarios en grid de 2 columnas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Calendario de citas generales -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 text-green-800">Citas Generales</h2>
                    <div id="calendario-general" style="height: 600px;" data-es-admin="<?= json_encode($esAdmin) ?>"></div>
                </div>
                
                <!-- Calendario de terapias -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 text-green-800">Citas de Terapias</h2>
                    <div id="calendario-terapias" style="height: 600px;" data-es-admin="<?= json_encode($esAdmin) ?>"></div>
                </div>
            </div>

            <!-- Instrucciones para usuarios -->
            <?php if (!$esAdmin): ?>
            <div class="mt-8 bg-green-50 p-6 rounded-xl shadow-md">
                <h3 class="text-lg font-semibold text-green-800 mb-2">¿Cómo reservar una cita?</h3>
                <ol class="list-decimal pl-5 space-y-2 text-gray-700">
                    <li>Haz clic en el día y hora que desees reservar en el calendario.</li>
                    <li>Completa el formulario con el motivo de tu cita.</li>
                    <li>Haz clic en "Reservar" para confirmar tu cita.</li>
                    <li>Recibirás una confirmación en pantalla.</li>
                </ol>
                <p class="mt-4 text-sm text-gray-600">
                    <i class="fas fa-info-circle text-green-600 mr-1"></i>
                    Las citas tienen una duración predeterminada de 1 hora y solo están disponibles de lunes a viernes en horario de 10:00-14:00 y 17:00-20:00.
                </p>
            </div>
            <?php endif; ?>

            <!-- Incluir la vista de citas -->
            <?php include "../assets/php/MVC/Vista/citas-vista.php"; ?>
        </div>
    </main>

    <?php require_once '../includes/footer.php'; ?>

    <!-- Pasar variables PHP a JavaScript -->
    <script>
        const esAdmin = <?= json_encode($esAdmin) ?>;
    </script>
    
    <!-- Cargar el script de citas -->
    <script src="../assets/js/citas.js"></script>
</body>
</html>
