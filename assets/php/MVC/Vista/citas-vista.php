<?php
// Verificar si el usuario está logueado
$esUsuarioLogueado = isset($_SESSION['usuario_id']);

// Incluir el controlador
require_once __DIR__ . '/../Controlador/citas-controlador.php';

// Crear una instancia del controlador
$controlador = new CitasControlador();

// Obtener las citas según el rol del usuario
try {
    // Verificar si el controlador se ha inicializado correctamente
    if (!$controlador) {
        throw new Exception("Error al inicializar el controlador de citas");
    }
    
    if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin') {
        $citas = $controlador->obtenerTodasLasCitas();
    } else {
        $citas = $controlador->obtenerCitasUsuario($_SESSION['usuario_id']); // Cambiado el nombre del método
    }
} catch (Exception $e) {
    $error = "Error al cargar las citas: " . $e->getMessage();
    $citas = [];
}
?>
        <div class="container mx-auto px-4 py-8">
            <?php if ($esUsuarioLogueado): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Calendario de citas -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border p-6 transition-all duration-300">
                    <h2 class="text-xl font-semibold mb-4 text-green-800 bg-green-50 p-3 rounded-lg">
                        Calendario de Citas
                    </h2>
                    <div id="calendario-citas" class="calendar-container" 
                        data-es-admin="<?php echo isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin' ? 'true' : 'false'; ?>"
                        data-usuario-id="<?php echo $_SESSION['usuario_id']; ?>">
                    </div>
                    
                    <!-- Leyenda de estados -->
                    <div class="flex justify-center gap-4 mt-4">
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
                            if (!empty($citas)):
                                // Filtrar y ordenar las citas
                                $citasFuturas = array_filter($citas, function($cita) {
                                    return strtotime($cita['fecha']) >= strtotime('today');
                                });
                                
                                // Ordenar por fecha y hora
                                usort($citasFuturas, function($a, $b) {
                                    $fecha1 = strtotime($a['fecha'] . ' ' . $a['hora']);
                                    $fecha2 = strtotime($b['fecha'] . ' ' . $b['hora']);
                                    return $fecha1 - $fecha2;
                                });

                                // Mostrar solo las 2 primeras citas
                                $citasMostradas = 0;
                                foreach (array_slice($citasFuturas, 0, 2) as $cita):
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
                                                case 'confirmada':
                                                    echo 'bg-green-100 text-green-800';
                                                    break;
                                                case 'completada':
                                                    echo 'bg-blue-100 text-blue-800';
                                                    break;
                                                case 'cancelada':
                                                    echo 'bg-red-100 text-red-800';
                                                    break;
                                                default:
                                                    echo 'bg-blue-100 text-blue-800';
                                            }
                                            ?>">
                                            <?= ucfirst($cita['estado']) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php 
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
                <div class="bg-white rounded-xl shadow-lg border p-6 transition-all duration-300">
                    <h2 class="text-xl font-semibold mb-4 text-green-800 bg-green-50 p-3 rounded-lg">Todas las Citas de Este Mes</h2>
                    <div id="lista-todas-citas" class="overflow-y-auto" style="max-height: 740px;">
                        <?php 
                        $citasActuales = array_filter($citas, function($cita) {
                            return strtotime($cita['fecha']) >= strtotime('today');
                        });
                        
                        $citasPasadas = array_filter($citas, function($cita) {
                            return strtotime($cita['fecha']) < strtotime('today');
                        });
                        
                        usort($citasActuales, function($a, $b) {
                            return strtotime($a['fecha'] . ' ' . $a['hora']) - strtotime($b['fecha'] . ' ' . $b['hora']);
                        });
                        
                        usort($citasPasadas, function($a, $b) {
                            return strtotime($b['fecha'] . ' ' . $b['hora']) - strtotime($a['fecha'] . ' ' . $a['hora']);
                        });
                        
                        if (!empty($citasActuales)): ?>
                            <?php foreach ($citasActuales as $cita): ?>
                                <div class="mb-4 p-4 border rounded-lg hover:bg-gray-50 transition-colors w-full">
                                    <div class="flex flex-col sm:flex-row justify-between items-start mb-2">
                                        <div>
                                            <p class="font-semibold text-lg">
                                                <?= date('d/m/Y', strtotime($cita['fecha'])) ?>
                                            </p>
                                            <p class="text-green-700">
                                                <?= date('H:i', strtotime($cita['hora'])) ?> h
                                            </p>
                                        </div>
                                        <span class="mt-2 sm:mt-0 px-3 py-1 rounded-full text-sm 
                                            <?php
                                            switch($cita['estado']) {
                                                case 'confirmada':
                                                    echo 'bg-green-100 text-green-800';
                                                    break;
                                                case 'completada':
                                                    echo 'bg-blue-100 text-blue-800';
                                                    break;
                                                case 'cancelada':
                                                    echo 'bg-red-100 text-red-800';
                                                    break;
                                                default:
                                                    echo 'bg-gray-100 text-gray-800';
                                            }
                                            ?>">
                                            <?= ucfirst($cita['estado']) ?>
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <p class="text-gray-600"><strong>Cliente:</strong> <?= htmlspecialchars($cita['nombre_cliente']) ?></p>
                                        <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                                            <p class="text-gray-600"><strong>Gestionado por:</strong> <?= htmlspecialchars($cita['nombre_usuario'] ?? 'Usuario Desconocido') ?></p>
                                        <?php endif; ?>
                                        <p class="text-gray-600"><strong>Motivo:</strong> <?= htmlspecialchars($cita['motivo']) ?></p>
                                    </div>

                                    <!-- Botones: 2x2 en móvil, 4x1 en desktop -->
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="button" onclick="return actualizarEstadoCita(<?= $cita['id'] ?>, 'confirmada')" 
                                                class="w-full h-10 px-2 text-sm bg-green-500 text-white rounded-lg hover:bg-green-800 transition-colors flex items-center justify-center whitespace-nowrap pointer-cursor">
                                            <i class="fas fa-check mr-1"></i>
                                            <span>Confirmar</span>
                                        </button>
                                        <button type="button" onclick="return actualizarEstadoCita(<?= $cita['id'] ?>, 'completada')" 
                                                class="w-full h-10 px-2 text-sm bg-blue-500 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center whitespace-nowrap pointer-cursor">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            <span>Completada</span>
                                        </button>
                                        <button type="button" onclick="return actualizarEstadoCita(<?= $cita['id'] ?>, 'cancelada')" 
                                                class="w-full h-10 px-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-800 transition-colors flex items-center justify-center whitespace-nowrap pointer-cursor">
                                            <i class="fas fa-times mr-1"></i>
                                            <span>Cancelada</span>
                                        </button>
                                        <button type="button" onclick="eliminarCita(<?= $cita['id'] ?>); return false;" 
                                                class="w-full h-10 px-2 text-sm bg-gray-800 text-white rounded-lg hover:bg-black transition-colors flex items-center justify-center whitespace-nowrap pointer-cursor">
                                            <i class="fas fa-trash mr-1"></i>
                                            <span>Eliminar</span>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-3"></i>
                                <p>No hay citas programadas para este mes</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Botón para mostrar/ocultar citas pasadas -->
            <div class="flex justify-center my-8">
                <button id="toggleCitasPasadas" 
                        class="px-8 py-4 rounded-lg text-white transition-colors text-lg font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-1"
                        onclick="toggleCitasPasadas()"
                        style="background-color: #2C5530;">
                    <i class="fas fa-history mr-2"></i>
                    Ver citas pasadas
                </button>
            </div>

            <!-- Lista de citas pasadas (inicialmente oculta) -->
            <div id="lista-citas-pasadas" class="bg-white rounded-xl shadow-lg overflow-hidden border p-6 transition-all duration-300 hidden">
                <h3 class="text-xl font-semibold mb-4 text-gray-700 bg-gray-50 p-3 rounded-lg">Citas Pasadas</h3>
                <div class="overflow-y-auto" style="max-height: 600px;">
                    <?php if (!empty($citasPasadas)): ?>
                        <div class="flex flex-col items-center">
                            <?php foreach ($citasPasadas as $cita): ?>
                                <div class="mb-4 p-4 border rounded-lg hover:bg-gray-50 transition-colors w-full">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-semibold text-lg">
                                                <?= date('d/m/Y', strtotime($cita['fecha'])) ?>
                                            </p>
                                            <p class="text-gray-700">
                                                <?= date('H:i', strtotime($cita['hora'])) ?> h
                                            </p>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-sm 
                                            <?php
                                            switch($cita['estado']) {
                                                case 'confirmada':
                                                    echo 'bg-green-100 text-green-800';
                                                    break;
                                                case 'completada':
                                                    echo 'bg-blue-100 text-blue-800';
                                                    break;
                                                case 'cancelada':
                                                    echo 'bg-red-100 text-red-800';
                                                    break;
                                                default:
                                                    echo 'bg-gray-100 text-gray-800';
                                            }
                                            ?>">
                                            <?= ucfirst($cita['estado']) ?>
                                        </span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <p class="text-gray-600"><strong>Cliente:</strong> <?= htmlspecialchars($cita['nombre_cliente']) ?></p>
                                        <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                                            <p class="text-gray-600"><strong>Gestionado por:</strong> <?= htmlspecialchars($cita['nombre_usuario'] ?? 'Usuario Desconocido') ?></p>
                                        <?php endif; ?>
                                        <p class="text-gray-600"><strong>Motivo:</strong> <?= htmlspecialchars($cita['motivo']) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8 text-gray-500">
                            <p>No hay citas pasadas</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Mensaje cuando no hay resultados -->
            <div id="mensajeNoResultados" class="text-center py-4 text-gray-500" style="display: none;">
                No se encontraron citas para la fecha seleccionada
            </div>
        </div>
