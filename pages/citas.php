<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../assets/php/MVC/Controlador/citas-controlador.php';
$controlador = new CitasControlador();

// Obtener las citas según el rol del usuario
if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin') {
    $citas = $controlador->obtenerTodasLasCitas();
} else {
    $citas = $controlador->obtenerCitasUsuario($_SESSION['usuario_id']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Citas - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
</head>
<body class="bg-beige h-min-screen">
    <?php include "../includes/header.php"; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-green-800 mb-6">Reserva de Citas</h1>
        
        <?php require_once '../assets/php/MVC/Vista/citas-vista.php'; ?>
    </div>

    <?php include "../includes/footer.php"; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js'></script>
    <script src="../assets/js/citas.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si el usuario está autenticado y hay una cita pendiente
        if (document.body.classList.contains('usuario-autenticado')) {
            const citaPendiente = localStorage.getItem('cita_pendiente');
            if (citaPendiente) {
                try {
                    const datos = JSON.parse(citaPendiente);
                    
                    // Esperar a que el calendario esté completamente cargado
                    setTimeout(function() {
                        // Crear objetos de fecha para la cita pendiente
                        const inicio = new Date(datos.fecha + 'T' + datos.hora);
                        const fin = new Date(inicio.getTime() + 60 * 60 * 1000); // 1 hora después
                        
                        // Mostrar formulario de reserva
                        mostrarFormularioReserva(inicio, fin, datos.tipo || 'general');
                        
                        // Limpiar la cita pendiente del localStorage
                        localStorage.removeItem('cita_pendiente');
                    }, 1000);
                } catch (e) {
                    console.error('Error al procesar cita pendiente:', e);
                    localStorage.removeItem('cita_pendiente');
                }
            }
        }
    });
    </script>
</body>
</html>
