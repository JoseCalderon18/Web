$(document).ready(function() {
    // Obtener el valor de esAdmin desde PHP
    const esAdmin = $('#calendario-general').data('esAdmin') === true;
    
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
    
    // Configuración común para ambos calendarios
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
        slotDuration: '00:30:00',
        allDaySlot: false,
        height: 'auto',
        expandRows: true,
        stickyHeaderDates: true,
        navLinks: true,
        dayMaxEvents: true,
        selectable: true,
        selectMirror: true,
        editable: esAdmin,
        eventStartEditable: esAdmin,
        eventDurationEditable: esAdmin,
        eventOverlap: false,
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
        nowIndicator: true,
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        views: {
            timeGrid: {
                dayMaxEventRows: 3
            }
        }
    };
    
    // Inicializar calendario general
    const calendarGeneralEl = document.getElementById('calendario-general');
    const calendarGeneral = new FullCalendar.Calendar(calendarGeneralEl, {
        ...calendarConfig,
        events: {
            url: '../assets/php/MVC/Controlador/citas-controlador.php?accion=obtenerCitas&tipo=general',
            method: 'GET',
            failure: function() {
                console.error('Error al cargar las citas generales');
            }
        },
        eventClassNames: function(arg) {
            return ['fc-event-general'];
        },
        eventClick: function(info) {
            manejarClickEvento(info, 'general', calendarGeneral);
        },
        select: function(info) {
            manejarSeleccion(info, 'general', calendarGeneral);
        },
        datesSet: function() {
            setTimeout(function() {
                aplicarEstilosCalendario('#calendario-general');
            }, 100);
        }
    });
    
    // Inicializar calendario de terapias
    const calendarTerapiasEl = document.getElementById('calendario-terapias');
    const calendarTerapias = new FullCalendar.Calendar(calendarTerapiasEl, {
        ...calendarConfig,
        events: {
            url: '../assets/php/MVC/Controlador/citas-controlador.php?accion=obtenerCitas&tipo=terapia',
            method: 'GET',
            failure: function() {
                console.error('Error al cargar las citas de terapias');
            }
        },
        eventClassNames: function(arg) {
            return ['fc-event-terapia'];
        },
        eventClick: function(info) {
            manejarClickEvento(info, 'terapia', calendarTerapias);
        },
        select: function(info) {
            manejarSeleccion(info, 'terapia', calendarTerapias);
        },
        datesSet: function() {
            setTimeout(function() {
                aplicarEstilosCalendario('#calendario-terapias');
            }, 100);
        }
    });
    
    // Renderizar ambos calendarios
    calendarGeneral.render();
    calendarTerapias.render();
    
    // Aplicar estilos personalizados después de renderizar
    setTimeout(function() {
        aplicarEstilosCalendario('#calendario-general');
        aplicarEstilosCalendario('#calendario-terapias');
    }, 100);
    
    // Función para aplicar estilos al calendario
    function aplicarEstilosCalendario(selector) {
        // Botones
        $(`${selector} .fc-button`).css({
            'background-color': '#4b5563',
            'border-color': '#4b5563',
            'color': 'white',
            'font-weight': '500',
            'font-size': '0.875rem',
            'padding': '0.375rem 0.75rem',
            'border-radius': '0.25rem',
            'box-shadow': '0 1px 2px rgba(0, 0, 0, 0.05)',
            'margin': '0 2px'
        });
        
        // Botón activo
        $(`${selector} .fc-button-active`).css({
            'background-color': '#1f2937',
            'border-color': '#1f2937'
        });
        
        // Cabecera de días
        $(`${selector} .fc-col-header-cell`).css({
            'background-color': '#15803d',
            'color': 'white'
        });
        
        $(`${selector} .fc-col-header-cell-cushion`).css({
            'color': 'white',
            'text-decoration': 'none',
            'font-weight': '500',
            'font-size': '0.875rem'
        });
        
        // Eventos
        $(`${selector} .fc-event-general`).css({
            'background-color': '#3b82f6',
            'border-color': '#3b82f6',
            'border-radius': '0.25rem',
            'box-shadow': '0 1px 2px rgba(0, 0, 0, 0.05)'
        });
        
        $(`${selector} .fc-event-terapia`).css({
            'background-color': '#8b5cf6',
            'border-color': '#8b5cf6',
            'border-radius': '0.25rem',
            'box-shadow': '0 1px 2px rgba(0, 0, 0, 0.05)'
        });
        
        // Título
        $(`${selector} .fc-toolbar-title`).css({
            'font-size': '1.25rem',
            'font-weight': '700',
            'color': '#15803d'
        });
        
        // Día actual
        $(`${selector} .fc-day-today`).css('background-color', '#f0fdf4');
        
        // Texto de eventos
        $(`${selector} .fc-event-title, ${selector} .fc-event-time`).css({
            'color': 'white',
            'font-size': '0.75rem'
        });
        
        // Celdas de tiempo
        $(`${selector} .fc-timegrid-slot`).css({
            'height': '3rem',
            'background-color': 'white'
        });
        
        // Números de hora
        $(`${selector} .fc-timegrid-axis-cushion, ${selector} .fc-timegrid-slot-label-cushion`).css({
            'font-size': '0.75rem',
            'color': '#4b5563'
        });
    }
    
    // Función para manejar el clic en un evento
    function manejarClickEvento(info, tipo, calendar) {
        if (esAdmin) {
            // Si es admin, mostrar opciones de editar/eliminar
            Swal.fire({
                title: info.event.title,
                html: `
                    <div class="text-left">
                        <p><strong>Fecha:</strong> ${info.event.start.toLocaleDateString()}</p>
                        <p><strong>Hora:</strong> ${info.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} - ${info.event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                        <p><strong>Motivo:</strong> ${info.event.extendedProps.motivo || 'No especificado'}</p>
                        <p><strong>Tipo:</strong> ${tipo === 'terapia' ? 'Terapia' : 'General'}</p>
                    </div>
                `,
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: 'Editar',
                denyButtonText: 'Eliminar',
                cancelButtonText: 'Cerrar',
                confirmButtonColor: '#4A6D50',
                denyButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Editar cita
                    editarCita(info.event, tipo);
                } else if (result.isDenied) {
                    // Eliminar cita
                    eliminarCita(info.event.id, tipo);
                }
            });
        } else {
            // Si es usuario normal, solo mostrar detalles
            Swal.fire({
                title: info.event.title,
                html: `
                    <div class="text-left">
                        <p><strong>Fecha:</strong> ${info.event.start.toLocaleDateString()}</p>
                        <p><strong>Hora:</strong> ${info.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                        <p><strong>Motivo:</strong> ${info.event.extendedProps.motivo || 'No especificado'}</p>
                    </div>
                `,
                confirmButtonText: 'Cerrar',
                confirmButtonColor: '#4A6D50'
            });
        }
    }
    
    // Función para manejar la selección de un horario
    function manejarSeleccion(info, tipo, calendar) {
        // Verificar si es un día pasado
        if (esFechaPasada(info.start)) {
            Swal.fire({
                title: 'Fecha no disponible',
                text: 'No puedes reservar citas en fechas pasadas',
                icon: 'warning',
                confirmButtonColor: '#4A6D50'
            });
            calendar.unselect();
            return;
        }
        
        // Verificar si es un día laborable
        if (!esFechaLaborable(info.start)) {
            Swal.fire({
                title: 'Día no laborable',
                text: 'Solo puedes reservar citas de lunes a viernes',
                icon: 'warning',
                confirmButtonColor: '#4A6D50'
            });
            calendar.unselect();
            return;
        }
        
        // Verificar si está dentro del horario laboral
        if (!esHorarioLaboral(info.start)) {
            Swal.fire({
                title: 'Horario no disponible',
                text: 'Solo puedes reservar citas en horario de 10:00-14:00 y 17:00-20:00',
                icon: 'warning',
                confirmButtonColor: '#4A6D50'
            });
            calendar.unselect();
            return;
        }
        
        // Mostrar formulario de reserva
        mostrarFormularioReserva(info.start, info.end, tipo);
        calendar.unselect();
    }
    
    // Función para mostrar el formulario de reserva
    function mostrarFormularioReserva(fechaInicio, fechaFin, tipo) {
        // Formatear fecha para mostrar
        const fechaFormateada = fechaInicio.toLocaleDateString();
        const horaInicioFormateada = fechaInicio.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        const horaFinFormateada = fechaFin ? fechaFin.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : 
                                  new Date(fechaInicio.getTime() + 30*60000).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        // Contenido HTML del formulario
        let formularioHTML = `
            <form id="formulario-reserva" class="space-y-4">
                <div class="mb-4">
                    <p class="block mb-2 text-sm font-medium text-gray-900">Fecha: ${fechaFormateada}</p>
                    <p class="block mb-2 text-sm font-medium text-gray-900">Hora: ${horaInicioFormateada} - ${horaFinFormateada}</p>
                </div>
                <div class="mb-4">
                    <label for="motivo" class="block mb-2 text-sm font-medium text-gray-900">Motivo de la cita</label>
                    <textarea id="motivo" rows="3" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" placeholder="Describe brevemente el motivo de tu cita" required></textarea>
                </div>
        `;
        
        // Si es admin, añadir selector de cliente
        if (esAdmin) {
            formularioHTML += `
                <div class="mb-4">
                    <label for="cliente" class="block mb-2 text-sm font-medium text-gray-900">Cliente</label>
                    <select id="cliente" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                        <option value="" selected disabled>Selecciona un cliente</option>
                    </select>
                </div>
            `;
            
            // Cargar lista de clientes
            $.ajax({
                url: '../assets/php/MVC/Controlador/usuarios-controlador.php?accion=obtenerClientes',
                method: 'GET',
                dataType: 'json',
                success: function(clientes) {
                    const selectCliente = document.getElementById('cliente');
                    if (selectCliente && clientes && clientes.length > 0) {
                        clientes.forEach(cliente => {
                            const option = document.createElement('option');
                            option.value = cliente.id;
                            option.textContent = `${cliente.nombre} ${cliente.apellidos}`;
                            selectCliente.appendChild(option);
                        });
                    }
                },
                error: function() {
                    console.error('Error al cargar la lista de clientes');
                }
            });
        }
        
        formularioHTML += `</form>`;
        
        // Mostrar SweetAlert con el formulario
        Swal.fire({
            title: 'Reservar cita',
            html: formularioHTML,
            showCancelButton: true,
            confirmButtonText: 'Reservar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#4A6D50',
            preConfirm: () => {
                const motivo = document.getElementById('motivo').value;
                if (!motivo) {
                    Swal.showValidationMessage('Por favor, indica el motivo de la cita');
                    return false;
                }
                
                const datos = {
                    fecha_inicio: fechaInicio.toISOString(),
                    fecha_fin: fechaFin ? fechaFin.toISOString() : new Date(fechaInicio.getTime() + 30*60000).toISOString(),
                    motivo: motivo,
                    tipo: tipo
                };
                
                if (esAdmin) {
                    const clienteId = document.getElementById('cliente').value;
                    
                    if (!clienteId) {
                        Swal.showValidationMessage('Por favor, selecciona un cliente');
                        return false;
                    }
                    
                    datos.cliente_id = clienteId;
                }
                
                return datos;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../assets/php/MVC/Controlador/citas-controlador.php?accion=reservar',
                    method: 'POST',
                    data: result.value,
                    success: function(response) {
                        try {
                            const data = JSON.parse(response);
                            if (data.success) {
                                calendarGeneral.refetchEvents();
                                calendarTerapias.refetchEvents();
                                
                                Swal.fire({
                                    title: '¡Reservada!',
                                    text: 'Tu cita ha sido reservada correctamente',
                                    icon: 'success',
                                    confirmButtonColor: '#4A6D50'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message || 'No se pudo reservar la cita',
                                    icon: 'error',
                                    confirmButtonColor: '#4A6D50'
                                });
                            }
                        } catch (e) {
                            console.error('Error al procesar la respuesta:', e, response);
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al procesar la respuesta',
                                icon: 'error',
                                confirmButtonColor: '#4A6D50'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'No se pudo conectar con el servidor',
                            icon: 'error',
                            confirmButtonColor: '#4A6D50'
                        });
                    }
                });
            }
        });
    }
    
    // Función para editar una cita (solo admin)
    function editarCita(evento, tipo) {
        const fechaInicio = evento.start;
        const fechaFin = evento.end;
        
        // Formatear fechas para el formulario
        const fechaInicioISO = fechaInicio.toISOString().slice(0, 16);
        const fechaFinISO = fechaFin.toISOString().slice(0, 16);
        
        // Contenido HTML del formulario
        const contenidoFormulario = `
            <form id="formulario-editar" class="space-y-4">
                <input type="hidden" id="id-editar" value="${evento.id}">
                <div class="mb-4">
                    <label for="fecha_inicio-editar" class="block mb-2 text-sm font-medium text-gray-900">Fecha y hora de inicio</label>
                    <input type="datetime-local" id="fecha_inicio-editar" value="${fechaInicioISO}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                </div>
                <div class="mb-4">
                    <label for="fecha_fin-editar" class="block mb-2 text-sm font-medium text-gray-900">Fecha y hora de fin</label>
                    <input type="datetime-local" id="fecha_fin-editar" value="${fechaFinISO}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                </div>
                <div class="mb-4">
                    <label for="motivo-editar" class="block mb-2 text-sm font-medium text-gray-900">Motivo de la cita</label>
                    <textarea id="motivo-editar" rows="3" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>${evento.extendedProps.motivo || ''}</textarea>
                </div>
            </form>
        `;
        
        // Mostrar formulario con SweetAlert2
        Swal.fire({
            title: 'Editar cita',
            html: contenidoFormulario,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#4A6D50',
            cancelButtonColor: '#6B7280',
            preConfirm: () => {
                const fechaInicio = document.getElementById('fecha_inicio-editar').value;
                const fechaFin = document.getElementById('fecha_fin-editar').value;
                const motivo = document.getElementById('motivo-editar').value;
                
                if (!fechaInicio || !fechaFin || !motivo) {
                    Swal.showValidationMessage('Por favor, completa todos los campos');
                    return false;
                }
                
                return {
                    id: document.getElementById('id-editar').value,
                    fecha_inicio: fechaInicio,
                    fecha_fin: fechaFin,
                    motivo: motivo,
                    tipo: tipo
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar datos al servidor
                $.ajax({
                    url: '../assets/php/MVC/Controlador/citas-controlador.php?accion=actualizar',
                    method: 'POST',
                    data: result.value,
                    success: function(response) {
                        try {
                            const data = JSON.parse(response);
                            if (data.success) {
                                // Recargar eventos
                                calendarGeneral.refetchEvents();
                                calendarTerapias.refetchEvents();
                                
                                Swal.fire({
                                    title: 'Actualizada',
                                    text: 'La cita ha sido actualizada correctamente',
                                    icon: 'success',
                                    confirmButtonColor: '#4A6D50'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message || 'No se pudo actualizar la cita',
                                    icon: 'error',
                                    confirmButtonColor: '#4A6D50'
                                });
                            }
                        } catch (e) {
                            console.error('Error al procesar la respuesta:', e, response);
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al procesar la respuesta',
                                icon: 'error',
                                confirmButtonColor: '#4A6D50'
                            });
                        }
                    }
                });
            }
        });
    }
    
    // Función para eliminar una cita
    function eliminarCita(id, tipo) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../assets/php/MVC/Controlador/citas-controlador.php?accion=eliminar',
                    method: 'POST',
                    data: { id: id, tipo: tipo },
                    success: function(response) {
                        try {
                            const data = JSON.parse(response);
                            if (data.success) {
                                // Recargar eventos
                                calendarGeneral.refetchEvents();
                                calendarTerapias.refetchEvents();
                                
                                Swal.fire({
                                    title: 'Eliminada',
                                    text: 'La cita ha sido eliminada correctamente',
                                    icon: 'success',
                                    confirmButtonColor: '#4A6D50'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message || 'No se pudo eliminar la cita',
                                    icon: 'error',
                                    confirmButtonColor: '#4A6D50'
                                });
                            }
                        } catch (e) {
                            console.error('Error al procesar la respuesta:', e, response);
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al procesar la respuesta',
                                icon: 'error',
                                confirmButtonColor: '#4A6D50'
                            });
                        }
                    }
                });
            }
        });
    }
});