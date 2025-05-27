<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Incluir el controlador
require_once '../assets/php/MVC/Controlador/citas-controlador.php';

// Crear una instancia del controlador
$controlador = new CitasControlador();

// Obtener las citas según el rol del usuario
try {
    // Verificar si el controlador se ha inicializado correctamente
    if (!$controlador) {
        throw new Exception("Error al inicializar el controlador de citas");
    }
    
    if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin') {
        $citas = $controlador->obtenerTodasLasCitas();
    } else {
        $citas = $controlador->obtenerCitasUsuario($_SESSION['usuario_id']); // Cambiado el nombre del método
    }
} catch (Exception $e) {
    $error = "Error al cargar las citas: " . $e->getMessage();
    $citas = [];
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
</head>
<body class="flex flex-col min-h-full bg-beige <?php echo isset($_SESSION['usuario_id']) ? 'usuario-autenticado' : ''; ?>">
    <?php include "../includes/header.php"; ?>

    <main class="flex-grow">
        <div class="container mx-auto px-4 py-8">
            <!-- Título y descripción -->
            <div class="mx-auto px-4 my-8 py-6">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-center text-verde font-display-CormorantGaramond mb-4 py-4">
                    Gestión de Citas
                </h1>
                <p class="text-gray-700 text-center">
                    Reserva tus citas para consultas generales o terapias específicas. Selecciona la fecha y hora que mejor se adapte a tus necesidades.
                </p>
            </div>
            
            <?php require_once '../assets/php/MVC/Vista/citas-vista.php'; ?>
        </div>
    </main>

    <?php include "../includes/footer.php"; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/citas.js"></script>
</body>
</html>