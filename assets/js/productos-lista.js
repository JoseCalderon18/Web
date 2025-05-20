function confirmarEliminacion(id) {
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
        if (result.isConfirmed) {
            // Crear y enviar un formulario para eliminar el producto
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../assets/php/MVC/Controlador/productos-controlador.php?accion=eliminar';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id';
            input.value = id;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Función para mostrar la galería de productos
function mostrarGaleria(foto, nombreProducto) {
    if (!foto) {
        Swal.fire({
            title: nombreProducto,
            text: 'No hay imagen disponible para este producto',
            confirmButtonText: 'Cerrar'
        });
        return;
    }

    Swal.fire({
        title: nombreProducto,
        imageUrl: '../' + foto,
        imageAlt: 'Foto de ' + nombreProducto,
        width: '60%',
        imageWidth: 'auto',
        imageHeight: '50vh',
        confirmButtonText: 'Cerrar',
        confirmButtonColor: '#166534'
    });
}

// Función para mostrar comentarios
function mostrarComentarios(nombreProducto, comentarios) {
    Swal.fire({
        title: `Comentarios de ${nombreProducto}`,
        text: comentarios,
        confirmButtonText: 'Cerrar'
    });
}

// Funcionalidad de búsqueda
document.addEventListener('DOMContentLoaded', function() {
    const barraBusqueda = document.getElementById('barraBusqueda');
    if (barraBusqueda) {
        barraBusqueda.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const filas = document.querySelectorAll('tbody tr');
            
            filas.forEach(fila => {
                const nombre = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                if (nombre.includes(searchTerm)) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });
    }
}); 