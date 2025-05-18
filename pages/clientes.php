<?php
session_start();
require_once '../assets/php/MVC/Controlador/usuarios-controlador.php';
$controlador = new UsuariosControlador();

// Verificar si el usuario es administrador
$esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';

// Redirigir si no es administrador
if (!$esAdmin) {
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-beige flex flex-col min-h-screen">
    <?php include "../includes/header.php"; ?>

    <main class="flex-grow py-12 px-4 md:px-24">
        <div class="max-w-7xl mx-auto">
            <!-- Encabezado -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-green-800 mb-4 font-display-CormorantGaramond">
                    Gestión de Clientes
                </h1>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Administra los clientes registrados en el sistema.
                </p>
            </div>

            <!-- Contenido principal -->
            <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
                <!-- Aquí va el contenido de gestión de clientes -->
                <!-- ... -->
            </div>

            <!-- Sección de citas de clientes -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h2 class="text-xl font-semibold mb-4 text-green-800">Citas de Clientes</h2>
                
                <!-- Incluir el componente de calendario de citas -->
                <?php include "componentes/citas-calendario.php"; ?>
            </div>
        </div>
    </main>

    <?php require_once '../includes/footer.php'; ?>

    <script src="../assets/js/clientes.js"></script>
</body>
</html> 