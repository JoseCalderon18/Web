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