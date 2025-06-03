document.addEventListener('DOMContentLoaded', function() {

    const calendarEl = document.getElementById('calendario-citas');
    if (!calendarEl) {
        console.error('No se encontró el elemento del calendario');
        return;
    }

    // Obtener el valor de esAdmin para mostrar información diferente
    const esAdmin = calendarEl.dataset.esAdmin === 'true';
    
    // Hacer esAdmin disponible globalmente
    window.esAdmin = esAdmin;

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'es',
        timeZone: 'local',
        firstDay: 1,
        now: new Date(),
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
        },
        height: 'auto',
        slotMinTime: '10:00:00',
        slotMaxTime: '20:00:00',
        slotDuration: '01:00:00',
        allDaySlot: false,
        weekends: false,
        selectable: true,
        selectMirror: true,
        businessHours: [
            {
                daysOfWeek: [1, 2, 3, 4, 5],
                startTime: '10:00',
                endTime: '14:00',
            },
            {
                daysOfWeek: [1, 2, 3, 4, 5],
                startTime: '17:00',
                endTime: '20:00',
            }
        ],
        selectConstraint: "businessHours",
        selectAllow: function(selectInfo) {
            return true;
        },
        dateClick: function(info) {
            
            const selectedDate = new Date(info.dateStr);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fecha no disponible',
                    text: 'No se pueden crear citas en fechas pasadas. Solo puedes agendar citas para hoy o fechas futuras.',
                    confirmButtonColor: '#2C5530',
                    confirmButtonText: 'Entendido'
                });
                return;
            }
            
            const fecha = info.dateStr.split('T')[0];
            const hora = info.dateStr.includes('T') ? 
                info.dateStr.split('T')[1].substring(0, 5) : '10:00';
            
            mostrarFormularioCita(fecha, hora);
        },
        select: function(info) {

            const selectedDate = new Date(info.startStr);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fecha no disponible',
                    text: 'No se pueden crear citas en fechas pasadas. Solo puedes agendar citas para hoy o fechas futuras.',
                    confirmButtonColor: '#2C5530',
                    confirmButtonText: 'Entendido'
                });
                calendar.unselect();
                return;
            }
            
            const fecha = info.startStr.split('T')[0];
            const hora = info.startStr.split('T')[1].substring(0, 5);
            
            mostrarFormularioCita(fecha, hora);
            calendar.unselect();
        },
        events: function(info, successCallback, failureCallback) {
            
            const formData = new FormData();
            formData.append('accion', 'obtenerCitas');

            fetch('../assets/php/MVC/Controlador/citas-controlador.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if (data.exito) {
                        const eventos = data.datos.map(cita => ({
                            id: cita.id,
                            title: esAdmin ? `${cita.nombre_cliente} - ${cita.motivo}` : cita.motivo,
                            start: `${cita.fecha}T${cita.hora}`,
                            backgroundColor: getColorByStatus(cita.estado),
                            borderColor: getColorByStatus(cita.estado),
                            extendedProps: {
                                estado: cita.estado,
                                motivo: cita.motivo,
                                nombre_cliente: cita.nombre_cliente || 'Usuario'
                            }
                        }));
                        successCallback(eventos);
                    } else {
                        throw new Error(data.mensaje || 'Error al cargar las citas');
                    }
                } catch (e) {
                    console.error('Error al parsear JSON:', e);
                    throw new Error('Error al parsear la respuesta del servidor');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                failureCallback(error);
            });
        },
        eventClick: function(info) {
            if (esAdmin) {
                mostrarDetallesCitaAdmin(info.event);
            } else {
                mostrarDetallesCitaUsuario(info.event);
            }
        }
    });

    // Función personalizada para ir a hoy
    function irAHoy() {
        const hoy = new Date();
        calendar.gotoDate(hoy);
        
        // Si estamos en vista de semana, asegurar que muestre la semana actual
        if (calendar.view.type === 'timeGridWeek') {
            calendar.gotoDate(hoy);
        }
    }

    // Sobrescribir el comportamiento del botón today después del render
    calendar.render();
    
    // Buscar y reemplazar el event listener del botón today
    setTimeout(() => {
        const todayButton = document.querySelector('.fc-today-button');
        if (todayButton) {
            // Clonar el botón para remover todos los event listeners
            const newTodayButton = todayButton.cloneNode(true);
            todayButton.parentNode.replaceChild(newTodayButton, todayButton);
            
            // Agregar nuestro event listener personalizado
            newTodayButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                irAHoy();
            });
            
        }
    }, 500);

    // Función para obtener color según el estado
    function getColorByStatus(estado) {
        switch (estado) {
            case 'confirmada': return '#10B981';  // Verde
            case 'cancelada': return '#EF4444';   // Rojo
            case 'completada': return '#3B82F6';  // Azul 
        }
    }

    // Función para obtener texto según el estado
    function getTextByStatus(estado) {
        switch (estado) {
            case 'confirmada': return 'Confirmada';
            case 'cancelada': return 'Cancelada';
            case 'completada': return 'Completada';
            default: return estado;
        }
    }

    // Función para mostrar citas en la lista
    function mostrarCitas(citas) {
        const listaCitas = document.getElementById('lista-citas');
        if (!listaCitas) return;
        
        if (citas.length === 0) {
            listaCitas.innerHTML = '<div class="text-center text-gray-500 py-8">No hay citas registradas</div>';
            return;
        }
        
        let html = '';
        citas.forEach(cita => {
            const fechaFormateada = new Date(cita.fecha).toLocaleDateString('es-ES');
            const estadoColor = getColorByStatus(cita.estado);
            const estadoTexto = getTextByStatus(cita.estado);
            
            html += `
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4" style="border-left-color: ${estadoColor}">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">${cita.nombre_cliente}</h3>
                            <p class="text-gray-600">${fechaFormateada} a las ${cita.hora}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-medium text-white" style="background-color: ${estadoColor}">
                            ${estadoTexto}
                        </span>
                    </div>
                    
                    <p class="text-gray-700 mb-4">${cita.motivo}</p>
                    
                    <div class="flex gap-2">
                        ${cita.estado === 'confirmada' ? `
                            <button onclick="cambiarEstado(${cita.id}, 'completada')" 
                                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Marcar Completada
                            </button>
                            <button onclick="cambiarEstado(${cita.id}, 'cancelada')" 
                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                Cancelar
                            </button>
                        ` : ''}
                        
                        <button onclick="eliminarCita(${cita.id})" 
                                class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                            Eliminar
                        </button>
                    </div>
                </div>
            `;
        });
        
        listaCitas.innerHTML = html;
    }

    // Función para filtrar por estado
    function filtrarPorEstado(estado) {
        if (estado === 'todos') {
            mostrarCitas(citasData);
        } else {
            const citasFiltradas = citasData.filter(cita => cita.estado === estado);
            mostrarCitas(citasFiltradas);
        }
    }

    // Función para mostrar formulario de cita (para todos los usuarios)
    function mostrarFormularioCita(fecha, hora) {

        // Validación adicional de fecha
        const selectedDate = new Date(fecha);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            Swal.fire({
                icon: 'error',
                title: 'Fecha no válida',
                text: 'No se pueden crear citas en fechas pasadas',
                confirmButtonColor: '#DC3545'
            });
            return;
        }
        
        // Establecer fecha mínima en el input
        const fechaMinima = new Date().toISOString().split('T')[0];
        
        Swal.fire({
            title: 'Nueva Cita',
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Cliente:</label>
                        <input type="text" id="nombre_cliente" placeholder="Nombre completo del cliente" class="w-full p-2 border rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha:</label>
                        <input type="date" id="fecha" value="${fecha}" min="${fechaMinima}" class="w-full p-2 border rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hora:</label>
                        <input type="time" id="hora" value="${hora}" min="10:00" max="20:00" class="w-full p-2 border rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de la consulta:</label>
                        <textarea id="motivo" placeholder="Describe el motivo de la consulta..." class="w-full p-2 border rounded-md" rows="3"></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Crear Cita',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#2C5530',
            cancelButtonColor: '#6B7280',
            preConfirm: () => {
                const nombreCliente = document.getElementById('nombre_cliente').value.trim();
                const fechaSeleccionada = document.getElementById('fecha').value;
                const horaSeleccionada = document.getElementById('hora').value;
                const motivo = document.getElementById('motivo').value.trim();
                
                if (!nombreCliente) {
                    Swal.showValidationMessage('El nombre del cliente es obligatorio');
                    return false;
                }
                
                if (!fechaSeleccionada) {
                    Swal.showValidationMessage('La fecha es obligatoria');
                    return false;
                }
                
                if (!horaSeleccionada) {
                    Swal.showValidationMessage('La hora es obligatoria');
                    return false;
                }
                
                if (!motivo) {
                    Swal.showValidationMessage('El motivo es obligatorio');
                    return false;
                }
                
                // Validar que la fecha no sea del pasado
                const fechaSeleccionadaObj = new Date(fechaSeleccionada);
                const hoy = new Date();
                hoy.setHours(0, 0, 0, 0);
                
                if (fechaSeleccionadaObj < hoy) {
                    Swal.showValidationMessage('No se pueden crear citas en fechas pasadas');
                    return false;
                }
                
                // Validar horario de trabajo (10:00-14:00 y 17:00-20:00)
                const hora = horaSeleccionada.split(':');
                const horaNumerica = parseInt(hora[0]) + parseInt(hora[1]) / 60;
                
                if (!((horaNumerica >= 10 && horaNumerica < 14) || (horaNumerica >= 17 && horaNumerica < 20))) {
                    Swal.showValidationMessage('Solo se pueden crear citas de 10:00-14:00 y 17:00-20:00');
                    return false;
                }
                
                return { nombreCliente, fecha: fechaSeleccionada, hora: horaSeleccionada, motivo };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // TODOS los usuarios usan el mismo método
                crearCita(result.value.nombreCliente, result.value.fecha, result.value.hora, result.value.motivo);
            }
        });
    }

    // Función única para crear citas (para todos los usuarios)
    function crearCita(nombreCliente, fecha, hora, motivo) {

        const formData = new FormData();
        formData.append('accion', 'crearCita');
        formData.append('nombre_cliente', nombreCliente);
        formData.append('fecha', fecha);
        formData.append('hora', hora);
        formData.append('motivo', motivo);

        fetch('../assets/php/MVC/Controlador/citas-controlador.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {

            return response.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                
                if (data.exito) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Cita creada correctamente',
                        confirmButtonColor: '#2C5530',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: true,
                        allowOutsideClick: false
                    }).then(() => {
                        calendar.refetchEvents();
                        location.reload();
                    });
                } else {
                    console.error('=== ERROR DEL SERVIDOR ===');
                    console.error('Mensaje:', data.mensaje);
                    throw new Error(data.mensaje || 'Error al crear la cita');
                }
            } catch (parseError) {
                console.error('=== ERROR AL PARSEAR JSON ===');
                console.error('Error:', parseError);
                console.error('Texto que falló:', text);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de parseo',
                    text: 'Respuesta del servidor no válida: ' + text.substring(0, 100),
                    confirmButtonColor: '#DC3545',
                    timer: 8000,
                    timerProgressBar: true,
                    showConfirmButton: true,
                    allowOutsideClick: false
                });
            }
        })
        .catch(error => {
            console.error('=== ERROR DE FETCH ===');
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: error.message || 'Error al crear la cita',
                confirmButtonColor: '#DC3545',
                timer: 4000,
                timerProgressBar: true,
                showConfirmButton: true,
                allowOutsideClick: false
            });
        });
    }

    // Solo mostrar detalles diferentes según el rol
    function mostrarDetallesCitaAdmin(cita) {
        mostrarDetallesCita(cita, true);
    }

    function mostrarDetallesCitaUsuario(cita) {
        mostrarDetallesCita(cita, false);
    }

    function mostrarDetallesCita(cita, esAdmin) {
        const nombreCliente = cita.extendedProps.nombre_cliente || 'Cliente';
        const motivo = cita.extendedProps.motivo || 'Sin motivo especificado';
        
        let botonesAccion = '';
        if (esAdmin) {
            botonesAccion = `
                <div class="mt-4 space-y-2">
                    <h4 class="font-semibold text-gray-700">Acciones:</h4>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="actualizarEstadoCita(${cita.id}, 'confirmada')" 
                                class="px-3 py-1 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
                            Confirmar
                        </button>
                        <button onclick="actualizarEstadoCita(${cita.id}, 'completada')" 
                                class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                            Completar
                        </button>
                        <button onclick="actualizarEstadoCita(${cita.id}, 'cancelada')" 
                                class="px-3 py-1 bg-yellow-600 text-white rounded-md text-sm hover:bg-yellow-700">
                            Cancelar
                        </button>
                        <button onclick="eliminarCita(${cita.id})" 
                                class="px-3 py-1 bg-red-600 text-white rounded-md text-sm hover:bg-red-700">
                            Eliminar
                        </button>
                    </div>
                </div>
            `;
        }

        Swal.fire({
            title: 'Detalles de la Cita',
            html: `
                <div class="text-left space-y-3">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p><strong>Cliente:</strong> ${nombreCliente}</p>
                        <p><strong>Fecha:</strong> ${formatearFecha(cita.start)}</p>
                        <p><strong>Hora:</strong> ${formatearHora(cita.start)}</p>
                        <p><strong>Estado:</strong> <span class="estado-${cita.extendedProps.estado}">${cita.extendedProps.estado.toUpperCase()}</span></p>
                    </div>
                    <div>
                        <p><strong>Motivo de la consulta:</strong></p>
                        <p class="mt-1 text-gray-700 bg-gray-50 p-2 rounded">${motivo}</p>
                    </div>
                    ${botonesAccion}
                </div>
            `,
            showCloseButton: true,
            showConfirmButton: false,
            width: '600px',
            customClass: {
                popup: 'text-sm'
            }
        });
    }

    // Función para actualizar estado de cita
    window.actualizarEstadoCita = function(citaId, nuevoEstado) {
        const formData = new FormData();
        formData.append('accion', 'actualizarEstado');
        formData.append('id', citaId);
        formData.append('estado', nuevoEstado);

        fetch('../assets/php/MVC/Controlador/citas-controlador.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.exito) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Estado actualizado correctamente',
                    confirmButtonColor: '#2C5530',
                    timer: 3000, // 3 segundos
                    timerProgressBar: true,
                    showConfirmButton: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(() => {
                    calendar.refetchEvents();
                    location.reload();
                });
            } else {
                throw new Error(data.mensaje || 'Error al actualizar el estado');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error al actualizar el estado',
                confirmButtonColor: '#DC3545',
                timer: 4000, // 4 segundos para errores
                timerProgressBar: true,
                showConfirmButton: true,
                allowOutsideClick: false
            });
        });
    }

    // Función para eliminar cita
    window.eliminarCita = function(citaId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer. La cita será eliminada permanentemente.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#DC3545',
            cancelButtonColor: '#6B7280',
            allowOutsideClick: false,
            allowEscapeKey: false,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('accion', 'eliminar');
                formData.append('id', citaId);

                fetch('../assets/php/MVC/Controlador/citas-controlador.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exito) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminada!',
                            text: 'La cita ha sido eliminada correctamente',
                            confirmButtonColor: '#2C5530',
                            timer: 3000, // 3 segundos
                            timerProgressBar: true,
                            showConfirmButton: true,
                            allowOutsideClick: false
                        }).then(() => {
                            calendar.refetchEvents();
                            location.reload();
                        });
                    } else {
                        throw new Error(data.mensaje || 'Error al eliminar la cita');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Error al eliminar la cita',
                        confirmButtonColor: '#DC3545',
                        timer: 4000, // 4 segundos para errores
                        timerProgressBar: true,
                        showConfirmButton: true,
                        allowOutsideClick: false
                    });
                });
            }
        });
    }
});