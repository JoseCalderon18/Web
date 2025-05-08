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
            // Create and submit a form to delete the product
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

// Function to show product gallery
function mostrarGaleria(fotos, nombreProducto) {
    let fotosHtml = fotos.map(foto => 
        `<div class="swiper-slide">
            <img src="../${foto}" class="w-full h-auto" alt="Foto de ${nombreProducto}">
         </div>`
    ).join('');

    Swal.fire({
        title: nombreProducto,
        html: `
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    ${fotosHtml}
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        `,
        width: '80%',
        showConfirmButton: false,
        didRender: () => {
            new Swiper('.swiper-container', {
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
            });
        }
    });
}

// Function to show comments
function mostrarComentarios(nombreProducto, comentarios) {
    Swal.fire({
        title: `Comentarios de ${nombreProducto}`,
        text: comentarios,
        confirmButtonText: 'Cerrar'
    });
}

// Search functionality
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