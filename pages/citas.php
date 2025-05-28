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
<body class="bg-beige h-min-screen <?php echo isset($_SESSION['usuario_id']) ? 'usuario-autenticado' : ''; ?>">
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
            <div class="flex gap-2 sm:gap-4">
                <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                    <a href="productos.php" 
                       class="px-2 sm:px-4 py-1.5 sm:py-2 text-sm bg-green-700 hover:bg-green-800 rounded-lg transition-colors">
                        <i class="fas fa-shopping-basket mr-1 sm:mr-2"></i>Productos
                    </a>
                    <a href="usuarios.php" 
                       class="px-2 sm:px-4 py-1.5 sm:py-2 text-sm bg-green-700 hover:bg-green-800 rounded-lg transition-colors">
                        <i class="fas fa-users mr-1 sm:mr-2"></i>Usuarios
                    </a>
                    <a href="citas.php" 
                       class="px-2 sm:px-4 py-1.5 sm:py-2 text-sm bg-green-700 hover:bg-green-800 rounded-lg transition-colors">
                        <i class="fas fa-calendar-check mr-1 sm:mr-2"></i>Citas
                    </a>
                <?php endif; ?>
                <a href="../assets/php/MVC/Controlador/usuarios-controlador.php?accion=cerrarSesion" 
                   class="px-2 sm:px-4 py-1.5 sm:py-2 text-sm bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                    <i class="fas fa-sign-out-alt mr-1 sm:mr-2"></i>Cerrar sesión
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

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

    <?php include "../includes/footer.php"; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js'></script>
    <script>
        // Pasar variables de PHP a JavaScript
        const usuarioId = <?php echo isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 'null'; ?>;
    </script>
    <script src="../assets/js/citas.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
