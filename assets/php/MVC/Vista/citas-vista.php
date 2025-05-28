<?php
// Verificar si el usuario es administrador
$esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
?>

<div class="mx-auto px-6 mb-6 md:mb-10 w-full py-5">
    <!-- Tabla de citas -->
    <div class="mx-auto px-2 mb-12 py-4">
        <!-- Leyenda del calendario (solo para admin) -->
        <?php if ($esAdmin): ?>
        <div class="mb-8 bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-green-800 mb-2">Leyenda:</h3>
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-400 rounded mr-2"></div>
                    <span class="text-sm">Horario disponible</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-400 rounded mr-2"></div>
                    <span class="text-sm">Cita ocupada</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-gray-300 rounded mr-2"></div>
                    <span class="text-sm">Fuera de horario laboral</span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Grid de calendarios -->
        
        <div class="grid grid-cols-1 <?php echo $esAdmin ? 'md:grid-cols-2' : ''; ?> gap-8">
            <!-- Calendario de citas generales (siempre visible) -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border p-6 transition-all duration-300">
                <h2 class="text-xl font-semibold mb-4 text-green-800">Citas Generales</h2>
                <div id="calendario-general" class="calendar-container" data-es-admin="<?= json_encode($esAdmin) ?>"></div>
                
                <!-- Lista de citas generales (solo para admin) -->
                <?php if ($esAdmin): ?>
                <div class="mt-6 border-t pt-4">
                    <h3 class="text-lg font-semibold text-green-700 mb-3">Próximas citas generales</h3>
                    <div id="lista-citas-general" class="max-h-60 overflow-y-auto">
                        <div class="text-center py-4 text-gray-500 lista-cargando">
                            Cargando citas...
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <!-- Calendario de terapias (solo para admin) -->
            <?php if ($esAdmin): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border p-6 transition-all duration-300">
                <h2 class="text-xl font-semibold mb-4 text-green-800">Citas de Terapias</h2>
                <div id="calendario-terapias" class="calendar-container" data-es-admin="<?= json_encode($esAdmin) ?>"></div>
                
                <!-- Lista de citas de terapias -->
                <div class="mt-6 border-t pt-4">
                    <h3 class="text-lg font-semibold text-green-700 mb-3">Próximas citas de terapias</h3>
                    <div id="lista-citas-terapias" class="max-h-60 overflow-y-auto">
                        <div class="text-center py-4 text-gray-500 lista-cargando">
                            Cargando citas...
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Mensaje cuando no hay resultados -->
        <div id="mensajeNoResultados" class="text-center py-4 text-gray-500" style="display: none;">
            No se encontraron citas para la fecha seleccionada
        </div>
    </div>

    <!-- Instrucciones para usuarios -->
    <?php if (!$esAdmin): ?>
    <div class="mt-8 bg-green-50 p-6 rounded-xl shadow-md">
        <h3 class="text-lg font-semibold text-green-800 mb-2">¿Cómo reservar una cita?</h3>
        <ol class="list-decimal pl-5 space-y-2 text-gray-700">
            <li>Haz clic en el día y hora que desees reservar en el calendario.</li>
            <li>Completa el formulario con el motivo de tu cita.</li>
            <li>Haz clic en "Reservar" para confirmar tu cita.</li>
            <li>Recibirás una confirmación en pantalla.</li>
        </ol>
        <p class="mt-4 text-sm text-gray-600">
            <i class="fas fa-info-circle text-green-600 mr-1"></i>
            Las citas tienen una duración predeterminada de 1 hora y solo están disponibles de lunes a viernes en horario de 10:00-14:00 y 17:00-20:00.
        </p>
    </div>
    <?php endif; ?>
    
    <!-- Modal de login para usuarios no autenticados -->
    <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-green-800">Iniciar sesión</h3>
                <button id="closeLoginModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="mb-4 text-gray-600">Debes iniciar sesión para reservar una cita.</p>
            <div class="space-y-4">
                <div>
                    <label for="loginEmail" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="loginEmail" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label for="loginPassword" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" id="loginPassword" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                </div>
                <div id="loginError" class="text-red-500 text-sm hidden"></div>
                <div class="flex justify-between items-center">
                    <button id="submitLogin" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                        Iniciar sesión
                    </button>
                    <a href="registro.php" class="text-green-600 hover:text-green-800 text-sm">¿No tienes cuenta? Regístrate</a>
                </div>
            </div>
        </div>
    </div>
</div>




