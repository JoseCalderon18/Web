function confirmarEliminacion(id) {
    // Mostrar diálogo de confirmación con SweetAlert2
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        // Si el usuario confirma la eliminación, redirigir al controlador
        if (result.isConfirmed) {
            window.location.href = `../assets/php/MVC/Controlador/usuarios-controlador.php?accion=eliminar&id=${id}`;
        }
    });
}

function cambiarRol(id, rolActual) {
    const nuevoRol = rolActual === 'admin' ? 'usuario' : 'admin';
    const mensaje = rolActual === 'admin' ? 'quitar permisos de administrador' : 'dar permisos de administrador';

    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas ${mensaje} a este usuario?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `../assets/php/MVC/Controlador/usuarios-controlador.php?accion=cambiarRol&id=${id}&rol=${nuevoRol}`;
        }
    });
}

$('#registroForm').submit(function(e) {
    e.preventDefault();
    
    const formData = {
        nombre: $('#nombre').val().trim(),
        email: $('#email').val().trim(),
        password: $('#password').val()
    };

    // Debug para ver qué se está enviando
    console.log('Datos a enviar:', formData);

    $.ajax({
        url: '../assets/php/MVC/Controlador/usuarios-controlador.php?accion=registrar',
        type: 'POST',
        data: formData,
        success: function(response) {
            console.log('Respuesta del servidor:', response); // Debug
            try {
                const data = JSON.parse(response);
                if (data.success) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4A6D50'
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error',
                        confirmButtonColor: '#4A6D50'
                    });
                }
            } catch (e) {
                console.error('Error al parsear respuesta:', e);
                console.error('Respuesta raw:', response);
                Swal.fire({
                    title: 'Error',
                    text: 'Error al procesar la respuesta del servidor',
                    icon: 'error',
                    confirmButtonColor: '#4A6D50'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error AJAX:', {xhr, status, error});
            Swal.fire({
                title: 'Error',
                text: 'Error al enviar la solicitud',
                icon: 'error',
                confirmButtonColor: '#4A6D50'
            });
        }
    });
});