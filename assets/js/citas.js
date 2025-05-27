document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando calendario...');

    const calendarEl = document.getElementById('calendario-citas');
    if (!calendarEl) {
        console.error('No se encontró el elemento del calendario');
        return;
    }

    // Obtener el valor de esAdmin
    const esAdmin = calendarEl.dataset.esAdmin === 'true';
    console.log('Es admin:', esAdmin);

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'es',
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
        dateClick: function(info) {
            console.log('Click en fecha:', info.dateStr);
            const fecha = info.dateStr.split('T')[0];
            const hora = info.dateStr.includes('T') ? 
                info.dateStr.split('T')[1].substring(0, 5) : '10:00';
            
            if (esAdmin) {
                mostrarFormularioCitaAdmin(fecha, hora);
            } else {
                mostrarFormularioCita(fecha, hora);
            }
        },
        select: function(info) {
            console.log('Selección:', info);
            const fecha = info.startStr.split('T')[0];
            const hora = info.startStr.split('T')[1].substring(0, 5);
            
            if (esAdmin) {
                mostrarFormularioCitaAdmin(fecha, hora);
            } else {
                mostrarFormularioCita(fecha, hora);
            }
            calendar.unselect();
        },
        events: function(info, successCallback, failureCallback) {
            console.log('Cargando eventos...');
            
            const formData = new FormData();
            formData.append('accion', 'obtenerCitas');

            fetch('../assets/php/MVC/Controlador/citas-controlador.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                console.log('Response text:', text);
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

    calendar.render();
    console.log('Calendario renderizado');

    // Función para obtener color según el estado
    function getColorByStatus(estado) {
        switch (estado) {
            case 'pendiente': return '#F59E0B';
            case 'confirmada': return '#10B981';
            case 'cancelada': return '#EF4444';
            case 'completada': return '#3B82F6';
            default: return '#6B7280';
        }
    }

    // Función para mostrar formulario de nueva cita (usuarios)
    function mostrarFormularioCita(fecha, hora = '') {
        console.log('Mostrando formulario para usuario:', fecha, hora);
        
        Swal.fire({
            title: 'Reservar Nueva Cita',
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha:</label>
                        <input type="date" id="fecha-cita" class="w-full p-2 border border-gray-300 rounded-md" value="${fecha}" min="${new Date().toISOString().split('T')[0]}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hora:</label>
                        <select id="hora-cita" class="w-full p-2 border border-gray-300 rounded-md">
                            <option value="">Selecciona una hora</option>
                            <option value="10:00" ${hora === '10:00' ? 'selected' : ''}>10:00</option>
                            <option value="11:00" ${hora === '11:00' ? 'selected' : ''}>11:00</option>
                            <option value="12:00" ${hora === '12:00' ? 'selected' : ''}>12:00</option>
                            <option value="13:00" ${hora === '13:00' ? 'selected' : ''}>13:00</option>
                            <option value="17:00" ${hora === '17:00' ? 'selected' : ''}>17:00</option>
                            <option value="18:00" ${hora === '18:00' ? 'selected' : ''}>18:00</option>
                            <option value="19:00" ${hora === '19:00' ? 'selected' : ''}>19:00</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de la consulta:</label>
                        <textarea id="motivo-cita" class="w-full p-2 border border-gray-300 rounded-md" rows="3" placeholder="Describe brevemente el motivo de tu consulta..."></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Reservar Cita',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#2C5530',
            cancelButtonColor: '#6B7280',
            preConfirm: () => {
                const fecha = document.getElementById('fecha-cita').value;
                const hora = document.getElementById('hora-cita').value;
                const motivo = document.getElementById('motivo-cita').value;

                if (!fecha || !hora || !motivo.trim()) {
                    Swal.showValidationMessage('Por favor, completa todos los campos');
                    return false;
                }

                return { fecha, hora, motivo };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                crearCita(result.value.fecha, result.value.hora, result.value.motivo);
            }
        });
    }

    // Función para mostrar formulario de nueva cita (admin)
    function mostrarFormularioCitaAdmin(fecha, hora = '') {
        console.log('Mostrando formulario para admin:', fecha, hora);
        
        // Primero obtenemos la lista de usuarios
        obtenerUsuarios().then(usuarios => {
            Swal.fire({
                title: 'Crear Nueva Cita (Admin)',
                html: `
                    <div class="text-left space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Usuario:</label>
                            <select id="usuario-cita" class="w-full p-2 border border-gray-300 rounded-md">
                                <option value="">Selecciona un usuario</option>
                                ${usuarios.map(usuario => 
                                    `<option value="${usuario.id}">${usuario.nombre} ${usuario.apellidos} (${usuario.email})</option>`
                                ).join('')}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha:</label>
                            <input type="date" id="fecha-cita" class="w-full p-2 border border-gray-300 rounded-md" value="${fecha}" min="${new Date().toISOString().split('T')[0]}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hora:</label>
                            <select id="hora-cita" class="w-full p-2 border border-gray-300 rounded-md">
                                <option value="">Selecciona una hora</option>
                                <option value="10:00" ${hora === '10:00' ? 'selected' : ''}>10:00</option>
                                <option value="11:00" ${hora === '11:00' ? 'selected' : ''}>11:00</option>
                                <option value="12:00" ${hora === '12:00' ? 'selected' : ''}>12:00</option>
                                <option value="13:00" ${hora === '13:00' ? 'selected' : ''}>13:00</option>
                                <option value="17:00" ${hora === '17:00' ? 'selected' : ''}>17:00</option>
                                <option value="18:00" ${hora === '18:00' ? 'selected' : ''}>18:00</option>
                                <option value="19:00" ${hora === '19:00' ? 'selected' : ''}>19:00</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de la consulta:</label>
                            <textarea id="motivo-cita" class="w-full p-2 border border-gray-300 rounded-md" rows="3" placeholder="Describe brevemente el motivo de la consulta..."></textarea>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <p class="text-sm text-blue-700"><strong>Nota:</strong> Como administrador, puedes crear citas para cualquier usuario.</p>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Crear Cita',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#2C5530',
                cancelButtonColor: '#6B7280',
                preConfirm: () => {
                    const usuarioId = document.getElementById('usuario-cita').value;
                    const fecha = document.getElementById('fecha-cita').value;
                    const hora = document.getElementById('hora-cita').value;
                    const motivo = document.getElementById('motivo-cita').value;

                    if (!usuarioId || !fecha || !hora || !motivo.trim()) {
                        Swal.showValidationMessage('Por favor, completa todos los campos');
                        return false;
                    }

                    return { usuarioId, fecha, hora, motivo };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    crearCitaAdmin(result.value.usuarioId, result.value.fecha, result.value.hora, result.value.motivo);
                }
            });
        }).catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar los usuarios',
                confirmButtonColor: '#DC3545'
            });
        });
    }

    // Función para obtener usuarios
    function obtenerUsuarios() {
        return new Promise((resolve, reject) => {
            const formData = new FormData();
            formData.append('accion', 'obtenerUsuarios');

            fetch('../assets/php/MVC/Controlador/citas-controlador.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.exito) {
                    resolve(data.datos);
                } else {
                    reject(new Error(data.mensaje));
                }
            })
            .catch(error => {
                reject(error);
            });
        });
    }

    // Función para crear cita como admin (para otro usuario)
    function crearCitaAdmin(usuarioId, fecha, hora, motivo) {
        console.log('Creando cita admin para usuario:', usuarioId, { fecha, hora, motivo });
        
        const formData = new FormData();
        formData.append('accion', 'crearCitaAdmin');
        formData.append('usuario_id', usuarioId);
        formData.append('fecha', fecha);
        formData.append('hora', hora);
        formData.append('motivo', motivo);

        fetch('../assets/php/MVC/Controlador/citas-controlador.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            console.log('Respuesta:', text);
            try {
                const data = JSON.parse(text);
                if (data.exito) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cita creada!',
                        text: 'La cita ha sido creada correctamente.',
                        confirmButtonColor: '#2C5530'
                    });
                    calendar.refetchEvents();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(data.mensaje || 'Error al crear la cita');
                }
            } catch (e) {
                console.error('Error al parsear JSON:', e);
                throw new Error('Error al procesar la respuesta del servidor');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error al crear la cita',
                confirmButtonColor: '#DC3545'
            });
        });
    }

    // Función para mostrar detalles de cita (usuario)
    function mostrarDetallesCitaUsuario(evento) {
        Swal.fire({
            title: 'Detalles de tu Cita',
            html: `
                <div class="text-left">
                    <p><strong>Fecha:</strong> ${evento.start.toLocaleDateString()}</p>
                    <p><strong>Hora:</strong> ${evento.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                    <p><strong>Motivo:</strong> ${evento.extendedProps.motivo}</p>
                    <p><strong>Estado:</strong> ${evento.extendedProps.estado}</p>
                </div>
            `,
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#2C5530'
        });
    }

    // Función para mostrar detalles de cita (admin)
    function mostrarDetallesCitaAdmin(evento) {
        Swal.fire({
            title: 'Gestionar Cita',
            html: `
                <div class="text-left">
                    <p><strong>Cliente:</strong> ${evento.extendedProps.nombre_cliente}</p>
                    <p><strong>Fecha:</strong> ${evento.start.toLocaleDateString()}</p>
                    <p><strong>Hora:</strong> ${evento.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                    <p><strong>Motivo:</strong> ${evento.extendedProps.motivo}</p>
                    <p><strong>Estado actual:</strong> ${evento.extendedProps.estado}</p>
                </div>
            `,
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            denyButtonText: 'Cancelar Cita',
            cancelButtonText: 'Cerrar',
            confirmButtonColor: '#10B981',
            denyButtonColor: '#EF4444'
        }).then((result) => {
            if (result.isConfirmed) {
                actualizarEstadoCita(evento.id, 'confirmada');
            } else if (result.isDenied) {
                actualizarEstadoCita(evento.id, 'cancelada');
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
                    confirmButtonColor: '#2C5530'
                });
                calendar.refetchEvents();
                location.reload();
            } else {
                throw new Error(data.mensaje || 'Error al actualizar el estado');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error al actualizar el estado',
                confirmButtonColor: '#DC3545'
            });
        });
    }
});