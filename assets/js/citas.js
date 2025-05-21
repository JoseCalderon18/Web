$(document).ready(function() {
    // Agregar estilos CSS personalizados para ajustar tamaños
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .fc .fc-toolbar-title {
                font-size: 1rem !important; /* text-base */
                font-weight: 400 !important;
            }
            .fc .fc-button {
                font-size: 0.8125rem !important; /* entre text-xs y text-sm */
                padding: 0.3rem 0.6rem !important;
            }
            .fc .fc-col-header-cell-cushion,
            .fc .fc-daygrid-day-number {
                font-size: 0.875rem !important; /* text-sm */
                font-weight: 400 !important;
            }
            .fc .fc-timegrid-slot-label-cushion,
            .fc .fc-event-title,
            .fc .fc-event-time {
                font-size: 0.8125rem !important; /* entre text-xs y text-sm */
                font-weight: 400 !important;
            }
        `)
        .appendTo('head');
    
    // Obtener el valor de esAdmin desde PHP
    const esAdmin = $('#calendario-general').data('esAdmin') === true;
    
    // Verificar si el usuario está autenticado - CORREGIDO
    function esUsuarioAutenticado() {
        // Si es admin, definitivamente está autenticado
        if (esAdmin) {
            return true;
        }
        
        // Verificar si hay un ID de usuario en la sesión (comprobación directa desde PHP)
        const usuarioAutenticado = $('body').hasClass('usuario-autenticado') || 
                                  (typeof usuarioId !== 'undefined' && usuarioId > 0);
        
        return usuarioAutenticado;
    }
    
    /**
     * Verifica si el usuario está logueado antes de permitir sacar una cita
     * @returns {boolean} true si está logueado, false si no
     */
    function verificarLoginParaCita() {
        console.log("Verificando login. Es admin:", esAdmin);
        console.log("Usuario autenticado:", esUsuarioAutenticado());
        
        // Si el usuario ya está autenticado, permitir continuar
        if (esUsuarioAutenticado()) {
            return true;
        }
        
        // Si no está autenticado, mostrar alerta
        Swal.fire({
            title: 'Inicio de sesión requerido',
            text: 'Necesitas estar logueado para sacar una cita',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Iniciar sesión',
            cancelButtonText: 'Registrarse'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirigir a la página de login
                window.location.href = 'login.php';
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Redirigir a la página de registro
                window.location.href = 'registro.php';
            }
        });
        return false;
    }
    
    // Horario laboral
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
    
    // Función para verificar si un horario está dentro del horario laboral
    function esHorarioLaboral(fecha) {
        const hora = fecha.getHours();
        const minutos = fecha.getMinutes();
        const horaDecimal = hora + (minutos / 60);
        
        return (horaDecimal >= 10 && horaDecimal < 14) || (horaDecimal >= 17 && horaDecimal < 20);
    }
    
    // Función para verificar si una fecha es pasada
    function esFechaPasada(fecha) {
        const ahora = new Date();
        return fecha < ahora;
    }
    
    // Configuración del calendario
    const calendarConfig = {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: 'timeGridWeek',
        locale: 'es',
        timeZone: 'local',
        firstDay: 1, // Lunes
        slotMinTime: '10:00:00',
        slotMaxTime: '20:00:00',
        slotDuration: '01:00:00',
        allDaySlot: false,
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        businessHours: [
            {
                daysOfWeek: [1, 2, 3, 4, 5], // Lunes a viernes
                startTime: '10:00',
                endTime: '14:00'
            },
            {
                daysOfWeek: [1, 2, 3, 4, 5], // Lunes a viernes
                startTime: '17:00',
                endTime: '20:00'
            }
        ],
        selectAllow: function(selectInfo) {
            const inicio = selectInfo.start;
            const fin = selectInfo.end;
            
            // Verificar si es día laborable
            if (!esFechaLaborable(inicio)) {
                return false;
            }
            
            // Verificar si está dentro del horario laboral
            if (!esHorarioLaboral(inicio)) {
                return false;
            }
            
            // Verificar si es fecha pasada
            if (esFechaPasada(inicio)) {
                return false;
            }
            
            return true;
        }
    };
    
    // Inicializar calendario de citas generales
    const calendarioGeneral = new FullCalendar.Calendar(
        document.getElementById('calendario-general'), 
        {
            ...calendarConfig,
            events: function(info, successCallback, failureCallback) {
                obtenerEventosCalendario('general', info, successCallback, failureCallback);
            },
            select: function(info) {
                manejarSeleccionCalendario(info, 'general');
            }
        }
    );
    calendarioGeneral.render();
    
    // Inicializar calendario de terapias (para todos los usuarios)
    if (document.getElementById('calendario-terapias')) {
        const calendarioTerapias = new FullCalendar.Calendar(
            document.getElementById('calendario-terapias'), 
            {
                ...calendarConfig,
                events: function(info, successCallback, failureCallback) {
                    obtenerEventosCalendario('terapias', info, successCallback, failureCallback);
                },
                select: function(info) {
                    manejarSeleccionCalendario(info, 'terapias');
                }
            }
        );
        calendarioTerapias.render();
    }
    
    // Cargar próximas citas
    cargarProximasCitas('general');
    if (document.getElementById('lista-citas-terapias')) {
        cargarProximasCitas('terapias');
    }
    
    // Función para obtener eventos del calendario
    function obtenerEventosCalendario(tipo, info, successCallback, failureCallback) {
        $.ajax({
            url: '../assets/php/MVC/Controlador/citas-controlador.php?accion=obtenerCitas',
            method: 'GET',
            data: {
                tipo: tipo,
                inicio: info.startStr,
                fin: info.endStr
            },
            dataType: 'json',
            success: function(response) {
                try {
                    if (response.exito) {
                        successCallback(response.datos);
                    } else {
                        failureCallback(response.mensaje);
                    }
                } catch (e) {
                    console.error('Error al procesar la respuesta:', e, response);
                    failureCallback('Error al procesar la respuesta');
                }
            },
            error: function() {
                failureCallback('Error de conexión');
            }
        });
    }
    
    // Función para cargar próximas citas
    function cargarProximasCitas(tipo) {
        console.log("Cargando próximas citas para:", tipo);
        const $listaCitas = tipo === 'general' ? $('#lista-citas-general') : $('#lista-citas-terapias');
        
        if (!$listaCitas.length) {
            console.log("No se encontró el contenedor para las citas de tipo:", tipo);
            return;
        }
        
        // Mostrar mensaje de cargando
        $listaCitas.html('<div class="text-center py-4 text-gray-500 lista-cargando">Cargando citas...</div>');
        
        $.ajax({
            url: '../assets/php/MVC/Controlador/citas-controlador.php?accion=obtenerProximasCitas',
            method: 'GET',
            data: { tipo: tipo },
            dataType: 'json',
            success: function(response) {
                try {
                    console.log("Respuesta de próximas citas:", response);
                    
                    if (response.exito) {
                        $listaCitas.empty();
                        
                        if (response.datos.length === 0) {
                            $listaCitas.html('<div class="text-center py-4 text-gray-500">No hay citas próximas</div>');
                        } else {
                            response.datos.forEach(cita => {
                                // Formatear fecha
                                const fecha = new Date(cita.fecha + 'T' + cita.hora);
                                const fechaFormateada = fecha.toLocaleDateString('es-ES', {
                                    weekday: 'short',
                                    day: '2-digit',
                                    month: 'short'
                                });
                                
                                const horaFormateada = fecha.toLocaleTimeString('es-ES', {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
                                
                                // Crear elemento de lista
                                const $item = $(`
                                    <div class="flex items-center p-3 border-b hover:bg-gray-50 transition-colors">
                                        <div class="flex-1">
                                            <p class="font-medium">${fechaFormateada} - ${horaFormateada}</p>
                                            <p class="text-sm text-gray-600">${cita.motivo}</p>
                                        </div>
                                        <div class="ml-4">
                                            <span class="px-2 py-1 text-xs rounded-full ${obtenerClaseEstado(cita.estado)}">${cita.estado}</span>
                                        </div>
                                    </div>
                                `);
                                
                                $listaCitas.append($item);
                            });
                        }
                    } else {
                        console.error('Error al cargar próximas citas:', response.mensaje);
                        $listaCitas.html('<div class="text-center py-4 text-red-500">Error al cargar citas</div>');
                    }
                } catch (e) {
                    console.error('Error al procesar la respuesta:', e, response);
                    $listaCitas.html('<div class="text-center py-4 text-red-500">Error al procesar datos</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error de conexión al cargar próximas citas:', status, error);
                $listaCitas.html('<div class="text-center py-4 text-red-500">Error de conexión</div>');
            }
        });
    }
    
    // Función para obtener clase CSS según estado
    function obtenerClaseEstado(estado) {
        switch (estado) {
            case 'pendiente':
                return 'bg-yellow-500 text-white';
            case 'confirmada':
                return 'bg-green-500 text-white';
            case 'cancelada':
                return 'bg-red-500 text-white';
            case 'completada':
                return 'bg-blue-500 text-white';
            default:
                return 'bg-gray-500 text-white';
        }
    }
    
    // Función para manejar la selección en el calendario
    function manejarSeleccionCalendario(info, tipo) {
        const inicio = info.start;
        const fin = info.end;
        
        // Verificar si el usuario está autenticado
        if (!verificarLoginParaCita()) {
            // Guardar datos de la cita en localStorage para recuperarlos después del login
            localStorage.setItem('cita_pendiente', JSON.stringify({
                fecha: inicio.toISOString().split('T')[0],
                hora: inicio.toTimeString().split(' ')[0].substring(0, 5),
                tipo: tipo
            }));
            return;
        }
        
        // Si está autenticado, mostrar formulario de reserva
        mostrarFormularioReserva(inicio, fin, tipo);
    }
    
    // Función para mostrar formulario de reserva
    function mostrarFormularioReserva(inicio, fin, tipo) {
        // Formatear fecha y hora para el formulario
        const fecha = inicio.toISOString().split('T')[0];
        const hora = inicio.toTimeString().split(' ')[0].substring(0, 5);
        
        // Crear formulario de reserva
        Swal.fire({
            title: 'Reservar cita',
            html: `
                <form id="formReservaCita" class="text-left">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha y hora:</label>
                        <div class="text-gray-800">${inicio.toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })} a las ${hora}</div>
                    </div>
                    <div class="mb-4">
                        <label for="nombre_cliente" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo:</label>
                        <input type="text" id="nombre_cliente" name="nombre_cliente" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="motivo" class="block text-sm font-medium text-gray-700 mb-1">Motivo de la cita:</label>
                        <textarea id="motivo" name="motivo" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" required></textarea>
                    </div>
                    <input type="hidden" id="fecha" name="fecha" value="${fecha}">
                    <input type="hidden" id="hora" name="hora" value="${hora}">
                    <input type="hidden" id="tipo" name="tipo" value="${tipo}">
                </form>
            `,
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Reservar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const formData = new FormData(document.getElementById('formReservaCita'));
                
                return $.ajax({
                    url: '../assets/php/MVC/Controlador/citas-controlador.php?accion=crear',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json'
                }).then(response => {
                    if (!response.exito) {
                        throw new Error(response.mensaje || 'Error al crear la cita');
                    }
                    return response;
                }).catch(error => {
                    Swal.showValidationMessage(`Error: ${error.message || 'Ha ocurrido un error'}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: '¡Cita reservada!',
                    text: 'Tu cita ha sido reservada correctamente.',
                    icon: 'success',
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    // Recargar calendarios
                    calendarioGeneral.refetchEvents();
                    if (typeof calendarioTerapias !== 'undefined' && calendarioTerapias) {
                        calendarioTerapias.refetchEvents();
                    }
                    
                    // Recargar listas de próximas citas
                    cargarProximasCitas('general');
                    if (document.getElementById('lista-citas-terapias')) {
                        cargarProximasCitas('terapias');
                    }
                    
                    // Agregar la nueva cita a la lista inmediatamente
                    agregarNuevaCitaALista({
                        fecha: fecha,
                        hora: hora,
                        motivo: document.getElementById('motivo').value,
                        estado: 'pendiente',
                        tipo: tipo
                    });
                });
            }
        });
    }
    
    // Función para agregar una nueva cita a la lista inmediatamente
    function agregarNuevaCitaALista(cita) {
        const $listaCitas = cita.tipo === 'general' ? $('#lista-citas-general') : $('#lista-citas-terapias');
        
        if (!$listaCitas.length) {
            console.log("No se encontró el contenedor para las citas de tipo:", cita.tipo);
            return;
        }
        
        // Eliminar mensaje de "No hay citas próximas" si existe
        const $noCitas = $listaCitas.find('.text-center.py-4.text-gray-500');
        if ($noCitas.length) {
            $noCitas.remove();
        }
        
        // Formatear fecha
        const fecha = new Date(cita.fecha + 'T' + cita.hora);
        const fechaFormateada = fecha.toLocaleDateString('es-ES', {
            weekday: 'short',
            day: '2-digit',
            month: 'short'
        });
        
        const horaFormateada = fecha.toLocaleTimeString('es-ES', {
            hour: '2-digit',
            minute: '2-digit'
        });
        
        // Crear elemento de lista
        const $item = $(`
            <div class="flex items-center p-3 border-b hover:bg-gray-50 transition-colors nueva-cita">
                <div class="flex-1">
                    <p class="font-medium">${fechaFormateada} - ${horaFormateada}</p>
                    <p class="text-sm text-gray-600">${cita.motivo}</p>
                </div>
                <div class="ml-4">
                    <span class="px-2 py-1 text-xs rounded-full ${obtenerClaseEstado(cita.estado)}">${cita.estado}</span>
                </div>
            </div>
        `);
        
        // Agregar al principio de la lista
        $listaCitas.prepend($item);
        
        // Aplicar efecto de resaltado
        $item.addClass('bg-green-50');
        setTimeout(() => {
            $item.removeClass('bg-green-50');
        }, 3000);
    }
    
    // Verificar si hay una cita pendiente en localStorage después de login
    if (esUsuarioAutenticado()) {
        const citaPendiente = localStorage.getItem('cita_pendiente');
        if (citaPendiente) {
            try {
                const datos = JSON.parse(citaPendiente);
                const inicio = new Date(datos.fecha + 'T' + datos.hora);
                const fin = new Date(inicio.getTime() + 60 * 60 * 1000); // 1 hora después
                
                // Mostrar formulario de reserva
                setTimeout(() => {
                    mostrarFormularioReserva(inicio, fin, datos.tipo || 'general');
                    localStorage.removeItem('cita_pendiente');
                }, 1000);
            } catch (e) {
                console.error('Error al procesar cita pendiente:', e);
                localStorage.removeItem('cita_pendiente');
            }
        }
    }
});