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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Calendario de citas generales -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border p-6 transition-all duration-300">
                <h2 class="text-xl font-semibold mb-4 text-green-800">Citas Generales</h2>
                <div id="calendario-general" class="calendar-container" data-es-admin="<?= json_encode($esAdmin) ?>"></div>
            </div>
            
            <!-- Calendario de terapias -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border p-6 transition-all duration-300">
                <h2 class="text-xl font-semibold mb-4 text-green-800">Citas de Terapias</h2>
                <div id="calendario-terapias" class="calendar-container" data-es-admin="<?= json_encode($esAdmin) ?>"></div>
            </div>
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
</div>




