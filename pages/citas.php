<?php
session_start();

// Verificar si el usuario está autenticado (cualquier rol)
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Citas - BioEspacio</title>
    
    <!-- Estilos -->
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- FullCalendar CSS -->
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.10/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.10/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/es.global.min.js'></script>
    
    <!-- CSS personalizado para el calendario -->
    <style>
        .fc-toolbar-title {
            font-size: 1.2rem !important;
            font-weight: 600 !important;
        }
        
        /* Hacer que el encabezado del calendario siempre sea verde */
        .fc-scrollgrid-sync-inner {
            background-color: #2C5530 !important; /* Verde oscuro */
            color: #FFFFFF !important;
        }
        
        .fc-daygrid-day-top {
            color: #FFFFFF !important;
        }

        .fc-daygrid-day-frame{
            background-color: #FFFFFF !important;
        }

        .fc-daygrid-day-number{
            color: #2C5530 !important;
        }

        /* Estilos responsivos para botones del calendario */
        @media (max-width: 768px) {
            .fc .fc-button {
                padding: 0.2rem 0.4rem !important;
                font-size: 0.8rem !important;
            }

            .fc-toolbar-title {
                font-size: 1rem !important;
            }

            .fc-header-toolbar {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                justify-content: center;
            }

            .fc-toolbar-chunk {
                display: flex;
                gap: 0.25rem;
            }

            /* Ajustar el tamaño de los botones de navegación */
            .fc-prev-button, .fc-next-button {
                padding: 0.2rem 0.3rem !important;
            }
        }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-beige text-sm sm:text-base">
    <?php include "../includes/header.php"; ?>

    <main class="flex-grow pt-44">
        <div class="container mx-auto px-4 py-8">
            <!-- Título y descripción -->
            <div class="mx-auto px-4 my-6 pb-6">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-center text-verde font-display-CormorantGaramond mb-4 py-4">
                    Gestión de Citas
                </h1>
                <p class="text-sm sm:text-base lg:text-lg text-gray-700 text-center">
                    Reserva tus citas para consultas generales o terapias específicas. Selecciona la fecha y hora que mejor se adapte a tus necesidades.
                </p>
            </div>
             <!-- Instrucciones para usuarios -->
            <div class="mt-8 bg-green-50 p-6 rounded-xl shadow-md mx-auto">
                <h3 class="text-xl sm:text-2xl lg:text-3xl font-semibold text-green-800 mb-2">
                   ¿Cómo gestionar las citas?
                </h3>
                
                    <ol class="list-decimal pl-5 space-y-2 text-gray-700 text-sm sm:text-base lg:text-lg">
                        <li>Haz clic en el día y hora que desees reservar en el calendario.</li>
                        <li>Completa el formulario con el nombre del cliente y motivo de la cita.</li>
                        <li>Haz clic en "Reservar" para confirmar la cita.</li>
                        <li>Gestiona el estado de las citas desde el panel lateral.</li>
                    </ol>
                
                <p class="mt-4 text-sm sm:text-base lg:text-lg text-gray-600">
                    <i class="fas fa-info-circle text-green-600 mr-1"></i>
                    Las citas tienen una duración predeterminada de 1 hora y solo están disponibles de lunes a viernes en horario de 10:00-14:00 y 17:00-20:00.
                    <br>
                    <i class="fas fa-clock text-blue-600 mr-1"></i>
                    <strong>Las citas se marcan automáticamente como completadas cuando pasa su fecha y hora programada.</strong>
                </p>
            </div>
            
            <?php require_once '../assets/php/MVC/Vista/citas-vista.php'; ?>
        </div>
    </main>

    <?php include "../includes/footer.php"; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/citas.js?v=<?= time() ?>"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/animaciones.js"></script>
</body>
</html>