<?php
// Iniciar sesión
session_start();
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
    <link rel="icon" href="../assets/img/iconoBio.ico" type="image/x-icon">
</head>
<body class="bg-beige">
    <!-- Header -->
    <?php include "../includes/header.php"; ?>

    <!-- Contenido principal - Vista de noticias -->
    <?php require_once "../assets/php/MVC/Vista/noticias-vista.php"; ?>

    <!-- Footer -->
    <?php include "../includes/footer.php"; ?>

    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/noticias.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/cerrarSesion.js"></script>
</body>
</html>