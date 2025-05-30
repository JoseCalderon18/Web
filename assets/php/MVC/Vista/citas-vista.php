<?php
// Verificar si el usuario está logueado
$esUsuarioLogueado = isset($_SESSION['usuario_id']);
?>

<div class="container mx-auto px-4">
    <?php if ($esUsuarioLogueado): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Calendario de citas -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border p-6 transition-all duration-300">
            <h2 class="text-xl font-semibold mb-4 text-green-800 bg-green-50 p-3 rounded-lg">
                Calendario de Citas
            </h2>
            <div id="calendario-citas" class="calendar-container" data-es-admin="true"></div>
            
            <!-- Leyenda de estados -->
            <div class="flex justify-center gap-4 mt-4">
                <span class="inline-flex items-center">
                    <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                    Pendiente
                </span>
                <span class="inline-flex items-center">
                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                    Confirmada
                </span>
                <span class="inline-flex items-center">
                    <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                    Cancelada
                </span>
                <span class="inline-flex items-center">
                    <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                    Completada
                </span>
            </div>
            
            <!-- Próximas citas -->
            <div class="mt-6 border-t pt-4">
                <h3 class="text-lg font-semibold text-green-700 mb-3 bg-green-50 p-2 rounded">Próximas citas</h3>
                <div id="lista-citas" class="space-y-4">
                    <?php 
                    $citasMostradas = 0;
                    if (!empty($citas)):
                        foreach ($citas as $cita):
                            if ($citasMostradas >= 2) break; // Solo mostrar 2 citas
                            if (strtotime($cita['fecha']) >= strtotime('today')):
                                $citasMostradas++;
                    ?>
                        <div class="p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-lg">
                                        <?= date('d/m/Y', strtotime($cita['fecha'])) ?>
                                    </p>
                                    <p class="text-green-700">
                                        <?= date('H:i', strtotime($cita['hora'])) ?> h
                                    </p>
                                    <p class="text-gray-600 mt-1">
                                        <strong>Cliente:</strong> <?= htmlspecialchars($cita['nombre_cliente']) ?>
                                    </p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-sm 
                                    <?php
                                    switch($cita['estado']) {
                                        case 'pendiente':
                                            echo 'bg-yellow-100 text-yellow-800';
                                            break;
                                        case 'completada':
                                            echo 'bg-green-100 text-green-800';
                                            break;
                                        case 'cancelada':
                                            echo 'bg-red-100 text-red-800';
                                            break;
                                    }
                                    ?>">
                                    <?= ucfirst($cita['estado']) ?>
                                </span>
                            </div>
                        </div>
                    <?php 
                            endif;
                        endforeach;
                        if ($citasMostradas === 0):
                    ?>
                        <div class="text-center py-4 text-gray-500">
                            No hay próximas citas programadas
                        </div>
                    <?php 
                        endif;
                    endif; 
                    ?>
                </div>
            </div>
        </div>

        <!-- Lista completa de citas -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border p-6 transition-all duration-300">
            <h2 class="text-xl font-semibold mb-4 text-green-800 bg-green-50 p-3 rounded-lg">Todas las Citas</h2>
            <div id="lista-todas-citas" class="overflow-y-auto" style="max-height: 600px;">
                <?php if (!empty($citas)): ?>
                    <?php foreach ($citas as $cita): ?>
                        <div class="mb-4 p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-semibold text-lg">
                                        <?= date('d/m/Y', strtotime($cita['fecha'])) ?>
                                    </p>
                                    <p class="text-green-700">
                                        <?= date('H:i', strtotime($cita['hora'])) ?> h
                                    </p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-sm 
                                    <?php
                                    switch($cita['estado']) {
                                        case 'pendiente':
                                            echo 'bg-yellow-100 text-yellow-800';
                                            break;
                                        case 'completada':
                                            echo 'bg-green-100 text-green-800';
                                            break;
                                        case 'cancelada':
                                            echo 'bg-red-100 text-red-800';
                                            break;
                                    }
                                    ?>">
                                    <?= ucfirst($cita['estado']) ?>
                                </span>
                            </div>
                            
                            <div class="mb-2">
                                <p class="text-gray-600"><strong>Cliente:</strong> <?= htmlspecialchars($cita['nombre_cliente']) ?></p>
                                <p class="text-gray-600"><strong>Motivo:</strong> <?= htmlspecialchars($cita['motivo']) ?></p>
                            </div>

                            <div class="flex gap-2 mt-3">
                                <button type="button" onclick="return actualizarEstadoCita(<?= $cita['id'] ?>, 'pendiente')" 
                                        class="px-3 py-1 text-sm bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                                    <i class="fas fa-clock mr-1"></i> Pendiente
                                </button>
                                <button type="button" onclick="return actualizarEstadoCita(<?= $cita['id'] ?>, 'completada')" 
                                        class="px-3 py-1 text-sm bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                    <i class="fas fa-check mr-1"></i> Completada
                                </button>
                                <button type="button" onclick="return actualizarEstadoCita(<?= $cita['id'] ?>, 'cancelada')" 
                                        class="px-3 py-1 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                    <i class="fas fa-times mr-1"></i> Cancelada
                                </button>
                                <button type="button" onclick="eliminarCita(<?= $cita['id'] ?>); return false;" 
                                        class="px-3 py-1 text-sm bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                                    <i class="fas fa-trash mr-1"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-calendar-times text-4xl mb-3"></i>
                        <p>No hay citas programadas</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Mensaje cuando no hay resultados -->
    <div id="mensajeNoResultados" class="text-center py-4 text-gray-500" style="display: none;">
        No se encontraron citas para la fecha seleccionada
    </div>
</div>