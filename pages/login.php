<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
</head>
<body class="flex flex-col xl:min-h-screen min-h-screen">
    <?php include "../includes/header.php"; ?>
    <main class="flex-grow flex items-center justify-center bg-beige">
        <div class="w-full px-4 py-10">
            <div class="max-w-md mx-auto bg-white p-6 sm:p-8 rounded-lg shadow-md">
                <h1 class="text-3xl sm:text-4xl font-bold text-center mb-6 text-green-800 font-display-CormorantGaramond">Iniciar Sesión</h1>
                
                <!-- Formulario de Login -->
                <form id="loginForm" class="w-full max-w-sm mx-auto mb-6 sm:mb-8 space-y-3 sm:space-y-4">
                    <!-- Campo de Usuario -->
                    <div class="mb-3 sm:mb-4">
                        <label for="username-admin" class="block mb-2 text-sm text-gray-900">Usuario</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-100 border border-e-0 border-gray-300 rounded-s-md">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                                </svg>
                            </span>
                            <input type="text" id="username-admin" 
                                class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 min-w-0 w-full text-sm p-2.5" 
                                placeholder="Ingrese su usuario">
                        </div>
                    </div>
                    
                    <!-- Campo de Contraseña -->
                    <div class="mb-3 sm:mb-4">
                        <label for="password-admin" class="block mb-2 text-sm text-gray-900">Contraseña</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-100 border border-e-0 border-gray-300 rounded-s-md">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                                    <path d="M14 7h-1.5V4.5a4.5 4.5 0 1 0-9 0V7H2a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2Zm-5 8a1 1 0 1 1-2 0v-3a1 1 0 1 1 2 0v3Zm1.5-8h-5V4.5a2.5 2.5 0 1 1 5 0V7Z"/>
                                </svg>
                            </span>
                            <input type="password" id="password-admin" 
                                class="rounded-none bg-gray-50 border border-gray-300 text-gray-900 block flex-1 min-w-0 w-full text-sm p-2.5" 
                                placeholder="Ingrese su contraseña">
                            <!-- Botón para mostrar contraseña -->
                            <button type="button" id="togglePassword" class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-100 border border-s-0 border-gray-300 rounded-e-md hover:bg-gray-200 cursor-pointer">
                                <svg class="w-4 h-4 text-gray-500" id="showPasswordIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Recordarme y Olvidé mi contraseña -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 sm:mb-4 gap-2">
                        <div class="flex items-start">
                            <div class="flex items-center h-5 cursor-pointer">
                                <input id="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50">
                            </div>
                            <label for="remember" class="ms-2 text-sm text-gray-900">Recordarme</label>
                        </div>
                        <a href="#" class="text-sm text-green-700 hover:underline">¿Olvidaste tu contraseña?</a>
                    </div>
                    
                    <!-- Botón de Enviar -->
                    <button type="submit" id="btnLogin" 
                        class="w-full text-white bg-green-700 hover:bg-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center cursor-pointer transition-colors duration-300">
                        Iniciar Sesión
                    </button>
                    
                    <!-- Enlace a registro -->
                    <div class="text-sm font-medium text-gray-500 text-center mt-4">
                        ¿No tienes cuenta? <a href="registro.php" class="text-green-700 hover:underline">Regístrate aquí</a>
                    </div>
                </form>
            </div>
        </div> 
    </main>
<!-- Footer -->
<?php require_once "../includes/footer.php"; ?>
</body>
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
<script>
$(document).ready(function() {
    // Toggle password visibility
    $("#togglePassword").on("click", function() {
        const passwordInput = $("#password-admin");
        const icon = $("#showPasswordIcon");
        
        if (passwordInput.attr("type") === "password") {
            passwordInput.attr("type", "text");
            icon.html(`
                <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                </svg>
            `);
        } else {
            passwordInput.attr("type", "password");
            icon.html(`
                <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            `);
        }
    });

    $("#loginForm").on("submit", function(e) {
        e.preventDefault();
        
        // Obtener valores
        const username = $("#username-admin").val().trim();
        const password = $("#password-admin").val().trim();
        
        // Validaciones simples
        if (!username) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El usuario es obligatorio'
            });
            return false;
        }

        if (!password) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La contraseña es obligatoria'
            });
            return false;
        }
        
        // Aquí iría la lógica de autenticación
        // Por ahora solo mostramos un mensaje de éxito
        Swal.fire({
            icon: 'success',
            title: '¡Bienvenido!',
            text: 'Has iniciado sesión correctamente'
        });
    });
});
</script>
</html>
