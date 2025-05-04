<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../assets/php/MVC/Controlador/usuarios-controlador.php';
$controlador = new UsuariosControlador();
$usuarios = $controlador->obtenerTodosLosUsuarios();

// Si no hay usuarios, inicializar como array vacío
if (!is_array($usuarios)) {
    $usuarios = [];
}

require_once '../assets/php/MVC/Vista/usuarios-vista.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-beige h-screen">
    <?php include "../includes/header.php"; ?>

    <?php require_once '../assets/php/MVC/Vista/usuarios-vista.php'; ?>

    <?php require_once '../includes/footer.php'; ?>
    <!-- jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Luego SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Después tus scripts -->
    <script src="../assets/js/usuarios.js"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>

<form id="registroForm" class="space-y-4">
    <div>
        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
        <input type="text" 
               id="nombre" 
               name="nombre" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" 
               id="email" 
               name="email" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
        <input type="password" 
               id="password" 
               name="password" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <button type="submit" class="...">Registrarse</button>
</form>


