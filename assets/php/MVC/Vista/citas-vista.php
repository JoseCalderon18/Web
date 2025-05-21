<?php
// Verificar si el usuario es administrador
$esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
?>

<div class="bg-beige">
    <!-- Header con datos del usuario -->
    <div class="bg-gray-800 w-full p-4 mb-4 md:mb-8 my-2">
        <div class="mx-auto flex flex-col sm:flex-row flex-wrap justify-around items-center text-white gap-3">
            <span class="py-2 text-sm md:text-base">
                <i class="fas fa-user mr-2"></i>
                Usuario: <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
            </span>
            <span class="py-2 text-sm md:text-base">
                <i class="fas fa-clock mr-2"></i>
                Última conexión: <?= date('d/m/Y H:i:s') ?>
            </span>
            <div class="flex gap-4">
                <a href="noticias.php" 
                   class="px-4 py-2 bg-green-700 hover:bg-green-800 rounded-lg transition-colors">
                    <i class="fas fa-blog mr-2"></i>Noticias
                </a>
                <a href="../assets/php/MVC/Controlador/usuarios-controlador.php?accion=cerrarSesion" 
                   class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                    <i class="fas fa-sign-out-alt mr-2"></i>Cerrar sesión
                </a>
            </div>
        </div>
    </div>

    <!-- Título y descripción -->
    <div class="mx-auto px-4 my-8 py-6">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-center text-verde font-display-CormorantGaramond mb-4 py-4">
            Reserva de Citas
        </h1>
        <p class="text-gray-700 text-center">
            Reserva tu cita para consultas, tratamientos o cualquier servicio que necesites.
        </p>
    </div>

    <div class="container mx-auto px-4 mb-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Calendario -->
            <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg">
                <div id="calendario"></div>
            </div>

            <!-- Formulario de reserva -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h2 class="text-2xl font-bold text-verde mb-6">Solicitar Cita</h2>
                <form id="formCita" class="space-y-4">
                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                        <input type="date" id="fecha" name="fecha" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    
                    <div>
                        <label for="hora" class="block text-sm font-medium text-gray-700 mb-1">Hora</label>
                        <select id="hora" name="hora" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">Selecciona una hora</option>
                            <?php
                            // Generar opciones de hora (9:00 AM a 6:00 PM)
                            for ($hora = 9; $hora <= 18; $hora++) {
                                $horaFormateada = sprintf("%02d:00", $hora);
                                echo "<option value=\"$horaFormateada\">$horaFormateada</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="motivo" class="block text-sm font-medium text-gray-700 mb-1">Motivo de la cita</label>
                        <textarea id="motivo" name="motivo" rows="3" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                    </div>
                    
                    <div>
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-green-800 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-calendar-plus mr-2"></i>Solicitar Cita
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de citas (solo visible para administradores) -->
        <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
            <div class="mt-12 bg-white p-6 rounded-xl shadow-lg">
                <h2 class="text-2xl font-bold text-verde mb-6">Listado de Citas</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-black text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Motivo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaCitas" class="divide-y divide-gray-200">
                            <!-- Aquí se cargarán las citas dinámicamente -->
                        </tbody>
                    </table>
                </div>
                
                <div id="mensajeNoResultados" class="text-center py-4 text-gray-500" style="display: none;">
                    No hay citas registradas para la fecha seleccionada
                </div>
            </div>
        <?php endif; ?>

        <!-- Mis citas (para usuarios normales) -->
        <?php if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin'): ?>
            <div class="mt-12 bg-white p-6 rounded-xl shadow-lg">
                <h2 class="text-2xl font-bold text-verde mb-6">Mis Citas</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-black text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Motivo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaMisCitas" class="divide-y divide-gray-200">
                            <!-- Aquí se cargarán las citas del usuario dinámicamente -->
                        </tbody>
                    </table>
                </div>
                
                <div id="mensajeNoMisCitas" class="text-center py-4 text-gray-500" style="display: none;">
                    No tienes citas programadas
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>




