<?php
// Asegurarse de que la sesión esté iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario es administrador
$esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
?>

<!-- Contenedor principal -->
<div id="app-calendario" data-es-admin="<?= $esAdmin ? 'true' : 'false' ?>">
    
    <!-- Leyenda del calendario (solo para admin) -->
    <?php if ($esAdmin): ?>
    <div class="mb-6 p-4 bg-white rounded-lg shadow-sm border border-gray-200">
        <h3 class="mb-3 text-lg font-semibold text-gray-900">Leyenda del Calendario</h3>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center">
                <div class="w-4 h-4 mr-2 bg-green-500 rounded"></div>
                <span class="text-sm text-gray-700">Horario disponible</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 mr-2 bg-red-500 rounded"></div>
                <span class="text-sm text-gray-700">Cita ocupada</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 mr-2 bg-gray-300 rounded"></div>
                <span class="text-sm text-gray-700">Fuera de horario</span>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Calendario general -->
    <div class="mb-8 p-5 bg-white rounded-lg shadow-sm border border-gray-200">
        <h2 class="mb-4 text-xl font-bold text-gray-900">Citas Generales</h2>
        <div id="calendario-general" style="min-height: 600px;"></div>
    </div>
    
    <!-- Calendario de terapias (solo admin) -->
    <?php if ($esAdmin): ?>
    <div class="mb-8 p-5 bg-white rounded-lg shadow-sm border border-gray-200">
        <h2 class="mb-4 text-xl font-bold text-gray-900">Citas de Terapias</h2>
        <div id="calendario-terapias" style="min-height: 600px;"></div>
    </div>
    <?php endif; ?>
    
    <!-- Instrucciones para usuarios -->
    <?php if (!$esAdmin): ?>
    <div class="mt-6 p-4 bg-white rounded-lg shadow-sm border border-gray-200">
        <h3 class="mb-3 text-lg font-semibold text-gray-900">¿Cómo reservar una cita?</h3>
        <ol class="ml-5 space-y-2 list-decimal text-gray-700">
            <li>Haz clic en el día y hora que desees reservar en el calendario.</li>
            <li>Completa el formulario con el motivo de tu cita.</li>
            <li>Haz clic en "Reservar" para confirmar tu cita.</li>
            <li>Recibirás una confirmación en pantalla.</li>
        </ol>
        <div class="mt-4 p-3 bg-blue-50 rounded-lg text-sm text-blue-800">
            <p><strong>Información importante:</strong> Las citas tienen una duración de 1 hora y están disponibles de lunes a viernes en horario de 10:00-14:00 y 17:00-20:00.</p>
        </div>
    </div>
    <?php endif; ?>
</div>




