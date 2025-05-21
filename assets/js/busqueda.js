document.addEventListener('DOMContentLoaded', function() {
    const barraBusqueda = document.getElementById('barraBusqueda');
    if (!barraBusqueda) return;

    function realizarBusqueda() {
        const searchTerm = barraBusqueda.value.toLowerCase().trim();
        const filas = document.querySelectorAll('tbody tr');
        let encontrado = false;

        filas.forEach(fila => {
            let texto = '';
            // Obtener texto de todas las celdas excepto la última (acciones)
            const celdas = fila.querySelectorAll('td:not(:last-child)');
            celdas.forEach(celda => {
                texto += celda.textContent.toLowerCase() + ' ';
            });

            if (texto.includes(searchTerm)) {
                fila.style.display = '';
                encontrado = true;
            } else {
                fila.style.display = 'none';
            }
        });

        // Mostrar mensaje si no hay resultados
        const mensajeNoResultados = document.getElementById('mensajeNoResultados');
        if (mensajeNoResultados) {
            if (searchTerm && !encontrado) {
                mensajeNoResultados.style.display = '';
            } else {
                mensajeNoResultados.style.display = 'none';
            }
        }
    }

    // Eventos para la búsqueda
    barraBusqueda.addEventListener('input', realizarBusqueda);
    barraBusqueda.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            realizarBusqueda();
        }
    });

    // Evento para el botón de búsqueda
    const botonBusqueda = barraBusqueda.nextElementSibling;
    if (botonBusqueda) {
        botonBusqueda.addEventListener('click', realizarBusqueda);
    }
}); 