<?php
session_start();

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once '../assets/php/MVC/Controlador/usuarios-controlador.php';
$controlador = new UsuariosControlador();

// Obtener los datos paginados
$resultado = $controlador->obtenerTodosLosUsuarios();

// Extraer los datos
$usuarios = $resultado['usuarios'];
$paginaActual = $resultado['paginaActual'];
$totalPaginas = $resultado['totalPaginas'];
$total = $resultado['total'];

// Si no hay usuarios, inicializar como array vacío
if (!is_array($usuarios)) {
    $usuarios = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-beige h-min-creen">
    <?php include "../includes/header.php"; ?>

    <?php require_once '../assets/php/MVC/Vista/usuarios-vista.php'; ?>

    <?php include "../includes/footer.php"; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/usuarios.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>




