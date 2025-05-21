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
    $citas = $controlador->obtenerMisCitas();
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
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
</head>
<body class="bg-beige h-min-screen">
    <?php include "../includes/header.php"; ?>

    <?php require_once '../assets/php/MVC/Vista/citas-vista.php'; ?>

    <?php include "../includes/footer.php"; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>
    <script src="../assets/js/citas.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
