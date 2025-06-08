// Función para cerrar sesión
function cerrarSesion() {
    Swal.fire({
        title: '¿Cerrar sesión?',
        text: '¿Estás seguro de que deseas cerrar la sesión?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2C5530',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: '¡Hasta pronto!',
                text: 'Cerrando sesión...',
                icon: 'success',
                timer: 1500,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false
            }).then(() => {
                window.location.href = '/Web_BioSpace/Web/assets/php/MVC/Controlador/usuarios-controlador.php?accion=cerrarSesion';
            });
        }
    });
}
