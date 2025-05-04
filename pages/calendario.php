<?php
session_start();
require_once '../assets/php/MVC/Controlador/citas-controlador.php';
$controlador = new CitasControlador();

$esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Citas - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-beige flex flex-col min-h-screen">
    <?php include "../includes/header.php"; ?>

    <main class="flex-grow py-12 px-4 md:px-24">
        <div class="max-w-7xl mx-auto">
            <!-- Encabezado -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-green-800 mb-4 font-display-CormorantGaramond">
                    Calendario de Citas
                </h1>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    <?= $esAdmin ? 'Gestiona las citas programadas.' : 'Reserva tu cita con nosotros.' ?>
                </p>
            </div>

            <!-- Contenedor de calendarios -->
            <div class="grid <?= $esAdmin ? 'md:grid-cols-2' : 'md:grid-cols-1' ?> gap-8">
                <!-- Calendario general -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 text-green-800">Citas Generales</h2>
                    <div id="calendario-general"></div>
                </div>

                <?php if ($esAdmin): ?>
                <!-- Calendario de terapias (solo admin) -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 text-green-800">Citas de Terapias</h2>
                    <div id="calendario-terapias"></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php require_once '../includes/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const esAdmin = <?= json_encode($esAdmin) ?>;
            const horarioLaboral = {
                inicio: '10:00',
                pausaInicio: '14:00',
                pausaFin: '17:00',
                fin: '20:00'
            };

            // Función para verificar si una fecha es laborable
            function esFechaLaborable(fecha) {
                const dia = fecha.getDay();
                return dia >= 1 && dia <= 5; // 1 = Lunes, 5 = Viernes
            }

            // Configuración común para ambos calendarios
            const configCalendario = {
                locale: 'es',
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                slotMinTime: horarioLaboral.inicio,
                slotMaxTime: horarioLaboral.fin,
                slotDuration: '00:30:00',
                allDaySlot: false,
                selectable: !esAdmin,
                editable: esAdmin,
                businessHours: [
                    {
                        daysOfWeek: [1, 2, 3, 4, 5],
                        startTime: horarioLaboral.inicio,
                        endTime: horarioLaboral.pausaInicio
                    },
                    {
                        daysOfWeek: [1, 2, 3, 4, 5],
                        startTime: horarioLaboral.pausaFin,
                        endTime: horarioLaboral.fin
                    }
                ],
                selectConstraint: 'businessHours',
                events: '../assets/php/MVC/Controlador/citas-controlador.php?accion=obtenerCitas',
                select: function(info) {
                    if (!esFechaLaborable(info.start)) {
                        this.unselect();
                        Swal.fire({
                            title: 'Fecha no disponible',
                            text: 'Solo se pueden programar citas de lunes a viernes',
                            icon: 'warning',
                            confirmButtonColor: '#4A6D50'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Nueva Cita',
                        html: `
                            <form id="formCita">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Fecha y hora</label>
                                    <input type="text" class="mt-1 block w-full" 
                                           value="${info.start.toLocaleString()}" readonly>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Motivo de la cita</label>
                                    <textarea id="motivo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                </div>
                            </form>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Reservar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#4A6D50',
                        preConfirm: () => {
                            return {
                                motivo: document.getElementById('motivo').value
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Aquí iría la lógica para guardar la cita
                            $.ajax({
                                url: '../assets/php/MVC/Controlador/citas-controlador.php?accion=crear',
                                method: 'POST',
                                data: {
                                    fecha_inicio: info.startStr,
                                    fecha_fin: info.endStr,
                                    motivo: result.value.motivo
                                },
                                success: function(response) {
                                    const data = JSON.parse(response);
                                    if (data.success) {
                                        calendario.refetchEvents();
                                        Swal.fire({
                                            title: '¡Éxito!',
                                            text: 'Cita reservada correctamente',
                                            icon: 'success',
                                            confirmButtonColor: '#4A6D50'
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Error',
                                            text: data.message,
                                            icon: 'error',
                                            confirmButtonColor: '#4A6D50'
                                        });
                                    }
                                }
                            });
                        }
                    });
                }
            };

            // Inicializar calendario general
            const calendarioGeneral = new FullCalendar.Calendar(
                document.getElementById('calendario-general'),
                configCalendario
            );
            calendarioGeneral.render();

            // Inicializar calendario de terapias (solo para admin)
            if (esAdmin) {
                const configTerapias = {...configCalendario};
                configTerapias.events = '../assets/php/MVC/Controlador/citas-controlador.php?accion=obtenerCitasTerapias';
                
                const calendarioTerapias = new FullCalendar.Calendar(
                    document.getElementById('calendario-terapias'),
                    configTerapias
                );
                calendarioTerapias.render();
            }
        });
    </script>
</body>
</html>
