<?php
// Iniciar sesión
session_start();

// Incluir el controlador para obtener las noticias
require_once '../assets/php/MVC/Controlador/noticias-controlador.php';

// Crear instancia del controlador
$controlador = new NoticiasControlador();

// Obtener las noticias (con límite de 10 por página)
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$resultado_noticias = $controlador->obtenerNoticias(10, ($pagina - 1) * 10);
$noticias = $resultado_noticias['noticias'];
$total_noticias = $resultado_noticias['total'];
$total_paginas = ceil($total_noticias / 10);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
</head>
<body class="bg-beige">
    <!-- Header -->
    <?php include "../includes/header.php"; ?>

    <!-- Contenido principal - Vista de noticias -->
    <?php require_once "../assets/php/MVC/Vista/noticias-vista.php"; ?>

    <!-- Footer -->
    <?php include "../includes/footer.php"; ?>

    <!-- Scripts -->
    <script>
        // Variable para el JavaScript
        const userRole = "<?= isset($_SESSION['rol']) ? $_SESSION['rol'] : '' ?>";
    </script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/noticias.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>

</body>
</html>