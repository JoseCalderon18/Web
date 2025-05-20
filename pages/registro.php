<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="flex flex-col xl:min-h-screen min-h-screen">
    <?php include "../includes/header.php"; ?>
    <main class="flex-grow flex items-center justify-center bg-beige">
        <div class="w-full px-4 py-10">
            <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-4xl font-bold text-center mb-6 text-green-800 font-display-CormorantGaramond">Crear Cuenta</h1>
            
            <!-- Formulario de Registro -->
            <form id="formularioRegistro" class="max-w-sm mx-auto mb-8 space-y-4" >
                <!-- Campo de Usuario -->
                <div class="mb-4">
                    <label for="usuario" class="block mb-2 text-sm text-gray-900">Nombre del usuario</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-100 border border-e-0 border-gray-300 rounded-s-md">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                            </svg>
                        </span>
                        <input type="text" 
                               id="usuario" 
                               name="usuario" 
                               autocomplete="username"
                               class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 min-w-0 w-full text-sm p-2.5" 
                               placeholder="Ingrese su nombre de usuario">
                    </div>
                </div>
                
                <!-- Campo de Email -->
                <div class="mb-4">
                    <label for="correo" class="block mb-2 text-sm text-gray-900">Email</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-100 border border-e-0 border-gray-300 rounded-s-md">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                                <path d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z"/>
                                <path d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z"/>
                            </svg>
                        </span>
                        <input type="email" 
                               id="correo" 
                               name="correo" 
                               autocomplete="email"
                               class="rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 min-w-0 w-full text-sm p-2.5" 
                               placeholder="nombre@ejemplo.com">
                    </div>
                </div>
                
                <!-- Campo de Contraseña -->
                <div class="mb-4">
                    <label for="contrasenia" class="block mb-2 text-sm text-gray-900">Contraseña</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-100 border border-e-0 border-gray-300 rounded-s-md">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                                <path d="M14 7h-1.5V4.5a4.5 4.5 0 1 0-9 0V7H2a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2Zm-5 8a1 1 0 1 1-2 0v-3a1 1 0 1 1 2 0v3Zm1.5-8h-5V4.5a2.5 2.5 0 1 1 5 0V7Z"/>
                            </svg>
                        </span>
                        <input type="password" 
                               id="contrasenia" 
                               name="contrasenia" 
                               autocomplete="new-password"
                               class="rounded-none bg-gray-50 border border-gray-300 text-gray-900 block flex-1 min-w-0 w-full text-sm p-2.5" 
                               placeholder="Ingrese su contraseña(Min 8 dígitos)">
                        <!-- Botón para mostrar contraseña -->
                        <button type="button" id="mostrarContrasenia" class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-100 border border-s-0 border-gray-300 rounded-e-md hover:bg-gray-200 cursor-pointer">
                            <svg class="w-4 h-4 text-gray-500" id="iconoMostrarContrasenia" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Campo de Confirmar Contraseña -->
                <div class="mb-4">
                    <label for="confirmarContrasenia" class="block mb-2 text-sm text-gray-900">Confirmar Contraseña</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-100 border border-e-0 border-gray-300 rounded-s-md">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                                <path d="M14 7h-1.5V4.5a4.5 4.5 0 1 0-9 0V7H2a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2Zm-5 8a1 1 0 1 1-2 0v-3a1 1 0 1 1 2 0v3Zm1.5-8h-5V4.5a2.5 2.5 0 1 1 5 0V7Z"/>
                            </svg>
                        </span>
                        <input type="password" 
                               id="confirmarContrasenia" 
                               name="confirmarContrasenia" 
                               autocomplete="new-password"
                               class="rounded-none bg-gray-50 border border-gray-300 text-gray-900 block flex-1 min-w-0 w-full text-sm p-2.5" 
                               placeholder="Confirme su contraseña">
                        <!-- Botón para mostrar contraseña -->
                        <button type="button" id="mostrarConfirmarContrasenia" class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-100 border border-s-0 border-gray-300 rounded-e-md hover:bg-gray-200 cursor-pointer">
                            <svg class="w-4 h-4 text-gray-500" id="iconoMostrarConfirmarContrasenia" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Codigo Captcha -->
                <div class="flex justify-center">
                    <div class="g-recaptcha" data-sitekey="6LdvVR4rAAAAAPT-iP-013nOxJyozIDXUxVtDR84"></div>
                </div>
                
                <!-- Botón de Registrarse -->
                <button type="submit" id="botonRegistro" 
                    class="w-full text-white bg-green-700 hover:bg-green-800 rounded-lg text-sm px-5 py-2.5 text-center cursor-pointer mt-6">
                    Crear Cuenta
                </button>

                <!-- Enlace a login -->
                <div class="text-sm font-medium text-gray-500 text-center mt-4">
                    ¿Ya tienes cuenta? <a href="login.php" class="text-green-700 hover:underline">Inicia sesión aquí</a>
                </div>
            </form>
            </div>
        </div>
    </main>
    <?php include "../includes/footer.php"; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/registro-usuarios.js"></script>

</body>
</html>