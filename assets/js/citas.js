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
            case 'confirmada': return '#10B981';
            case 'cancelada': return '#EF4444';
            case 'completada': return '#6B7280';
            default: return '#6B7280';
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

    // Función para mostrar formulario de nueva cita (usuarios)
    function mostrarFormularioCita(fecha, hora = '') {
        console.log('Mostrando formulario usuario:', fecha, hora);
        
        // Validar que no sea una fecha pasada
        const fechaSeleccionada = new Date(fecha);
        const fechaHoy = new Date();
        fechaHoy.setHours(0, 0, 0, 0);
        
        if (fechaSeleccionada < fechaHoy) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha no válida',
                text: 'No puedes crear citas en fechas pasadas',
                confirmButtonColor: '#DC3545'
            });
            return;
        }

        const fechaMinima = new Date().toISOString().split('T')[0];

        Swal.fire({
            title: 'Reservar Nueva Cita',
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha:</label>
                        <input type="date" id="fecha-cita" class="w-full p-2 border border-gray-300 rounded-md" value="${fecha}" min="${fechaMinima}">
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
            confirmButtonText: 'Reservar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#2C5530',
            cancelButtonColor: '#6B7280',
            preConfirm: () => {
                const fechaSeleccionada = document.getElementById('fecha-cita').value;
                const horaSeleccionada = document.getElementById('hora-cita').value;
                const motivo = document.getElementById('motivo-cita').value.trim();

                if (!fechaSeleccionada || !horaSeleccionada || !motivo) {
                    Swal.showValidationMessage('Todos los campos son obligatorios');
                    return false;
                }

                // Validar fecha nuevamente
                const fechaValidacion = new Date(fechaSeleccionada);
                const hoy = new Date();
                hoy.setHours(0, 0, 0, 0);
                
                if (fechaValidacion < hoy) {
                    Swal.showValidationMessage('No puedes seleccionar una fecha pasada');
                    return false;
                }

                return { fecha: fechaSeleccionada, hora: horaSeleccionada, motivo };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { fecha, hora, motivo } = result.value;
                crearCita(fecha, hora, motivo);
            }
        });
    }

    // Función para mostrar formulario de nueva cita (admin)
    function mostrarFormularioCitaAdmin(fecha, hora = '') {
        console.log('Mostrando formulario admin:', fecha, hora);
        
        // Validar que no sea una fecha pasada
        const fechaSeleccionada = new Date(fecha);
        const fechaHoy = new Date();
        fechaHoy.setHours(0, 0, 0, 0);
        
        if (fechaSeleccionada < fechaHoy) {
            Swal.fire({
                icon: 'warning',
                title: 'Fecha no válida',
                text: 'No puedes crear citas en fechas pasadas',
                confirmButtonColor: '#DC3545'
            });
            return;
        }
        
        mostrarFormularioConNombreCliente(fecha, hora);
    }

    // Función para mostrar el formulario con nombre de cliente
    function mostrarFormularioConNombreCliente(fecha, hora) {
        const fechaMinima = new Date().toISOString().split('T')[0];
        
        Swal.fire({
            title: 'Reservar Cita para Cliente',
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Cliente:</label>
                        <input type="text" id="nombre-cliente" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Ingresa el nombre completo del cliente" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha:</label>
                        <input type="date" id="fecha-cita" class="w-full p-2 border border-gray-300 rounded-md" value="${fecha}" min="${fechaMinima}">
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
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Reservar Cita',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#2C5530',
            cancelButtonColor: '#6B7280',
            preConfirm: () => {
                const nombreCliente = document.getElementById('nombre-cliente').value.trim();
                const fechaSeleccionada = document.getElementById('fecha-cita').value;
                const horaSeleccionada = document.getElementById('hora-cita').value;
                const motivo = document.getElementById('motivo-cita').value.trim();

                if (!nombreCliente || !fechaSeleccionada || !horaSeleccionada || !motivo) {
                    Swal.showValidationMessage('Todos los campos son obligatorios');
                    return false;
                }

                // Validar fecha nuevamente
                const fechaValidacion = new Date(fechaSeleccionada);
                const hoy = new Date();
                hoy.setHours(0, 0, 0, 0);
                
                if (fechaValidacion < hoy) {
                    Swal.showValidationMessage('No puedes seleccionar una fecha pasada');
                    return false;
                }

                return { nombreCliente, fecha: fechaSeleccionada, hora: horaSeleccionada, motivo };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { nombreCliente, fecha, hora, motivo } = result.value;
                crearCitaConNombre(nombreCliente, fecha, hora, motivo);
            }
        });
    }

    // Función para crear cita (usuarios)
    function crearCita(fecha, hora, motivo) {
        const formData = new FormData();
        formData.append('accion', 'crearCita');
        formData.append('fecha', fecha);
        formData.append('hora', hora);
        formData.append('motivo', motivo);

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
                    text: 'Cita creada exitosamente',
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
                throw new Error(data.mensaje || 'Error al crear la cita');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error al crear la cita',
                confirmButtonColor: '#DC3545',
                timer: 4000, // 4 segundos para errores
                timerProgressBar: true,
                showConfirmButton: true,
                allowOutsideClick: false
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

    // Función para mostrar detalles de la cita
    function mostrarDetallesCita(cita) {
        console.log('=== DEBUG mostrar detalles ===');
        console.log('Cita completa:', cita);
        console.log('nombre_cliente:', cita.nombre_cliente);
        console.log('motivo:', cita.motivo);
        console.log('nombre_usuario:', cita.nombre_usuario);
        
        // Determinar el nombre del cliente correcto
        let nombreCliente = '';
        if (cita.nombre_cliente && cita.nombre_cliente.trim() !== '') {
            // Si hay nombre_cliente, es una cita creada por admin para un cliente específico
            nombreCliente = cita.nombre_cliente;
        } else if (cita.nombre_usuario) {
            // Si no hay nombre_cliente, mostrar el nombre del usuario que creó la cita
            nombreCliente = cita.nombre_usuario;
        } else {
            nombreCliente = 'Cliente no especificado';
        }
        
        // El motivo SIEMPRE debe ser el campo motivo
        const motivo = cita.motivo || 'Sin motivo especificado';

        const esAdmin = window.esAdmin || false;
        
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
                        <p><strong>Fecha:</strong> ${formatearFecha(cita.fecha)}</p>
                        <p><strong>Hora:</strong> ${cita.hora}</p>
                        <p><strong>Estado:</strong> <span class="estado-${cita.estado}">${cita.estado.toUpperCase()}</span></p>
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

    // Función para crear cita con nombre del cliente
    function crearCitaConNombre(nombreCliente, fecha, hora, motivo) {
        console.log('=== DEBUG crearCitaConNombre ===');
        console.log('Nombre Cliente:', nombreCliente);
        console.log('Fecha:', fecha);
        console.log('Hora:', hora);
        console.log('Motivo:', motivo);

        const formData = new FormData();
        formData.append('accion', 'crearCitaConNombre');
        formData.append('nombre_cliente', nombreCliente);
        formData.append('fecha', fecha);
        formData.append('hora', hora);
        formData.append('motivo', motivo);

        fetch('../assets/php/MVC/Controlador/citas-controlador.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.text();
        })
        .then(text => {
            console.log('Response text:', text);
            try {
                const data = JSON.parse(text);
                console.log('Response data:', data);
                
                if (data.exito) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Cita creada correctamente',
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
                    throw new Error(data.mensaje || 'Error al crear la cita');
                }
            } catch (parseError) {
                console.error('Error al parsear JSON:', parseError);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error en la respuesta del servidor',
                    confirmButtonColor: '#DC3545',
                    timer: 4000, // 4 segundos para errores
                    timerProgressBar: true,
                    showConfirmButton: true,
                    allowOutsideClick: false
                });
            }
        })
        .catch(error => {
            console.error('Error fetch:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error al crear la cita',
                confirmButtonColor: '#DC3545',
                timer: 4000, // 4 segundos para errores
                timerProgressBar: true,
                showConfirmButton: true,
                allowOutsideClick: false
            });
        });
    }
});