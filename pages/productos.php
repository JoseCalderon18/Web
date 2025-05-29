<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once '../assets/php/MVC/Controlador/productos-controlador.php';
$controlador = new ProductosControlador();

// Obtener los datos paginados
$resultado = $controlador->obtenerTodosLosProductos();

// Extraer los datos
$productos = $resultado['productos'];
$paginaActual = $resultado['paginaActual'];
$totalPaginas = $resultado['totalPaginas'];
$total = $resultado['total'];

// Si no hay productos, inicializar como array vacío
if (!is_array($productos)) {
    $productos = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-beige h-max-screen">
    <?php include "../includes/header.php"; ?>

    <?php require_once '../assets/php/MVC/Vista/productos-vista.php'; ?>

    <?php include "../includes/footer.php"; ?>
    
    <script src="../assets/js/busqueda-productos.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/productos-lista.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/productos.js"></script>
</body>
</html>
