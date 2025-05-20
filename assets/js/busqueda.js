$(document).ready(function() {
    const $barraBusqueda = $('#barraBusqueda');
    if (!$barraBusqueda.length) return;

    function realizarBusqueda() {
        const searchTerm = $barraBusqueda.val().toLowerCase().trim();
        const $filas = $('tbody tr');
        const $mensajeNoResultados = $('#mensajeNoResultados');

        // Si hay menos de 2 caracteres, mostrar todas las filas y ocultar mensaje
        if (searchTerm.length < 2) {
            $filas.show();
            $mensajeNoResultados.hide();
            return;
        }

        let encontrado = false;

        $filas.each(function() {
            const $fila = $(this);
            let texto = '';
            
            // Obtener texto de todas las celdas excepto la última (acciones)
            $fila.find('td:not(:last-child)').each(function() {
                texto += $(this).text().toLowerCase() + ' ';
            });

            if (texto.includes(searchTerm)) {
                $fila.show();
                encontrado = true;
            } else {
                $fila.hide();
            }
        });

        // Mostrar mensaje si no hay resultados
        if (searchTerm && !encontrado) {
            $mensajeNoResultados.show();
        } else {
            $mensajeNoResultados.hide();
        }
    }

    // Eventos para la búsqueda
    $barraBusqueda.on('input', realizarBusqueda);
    $barraBusqueda.on('keypress', function(e) {
        if (e.key === 'Enter') {
            realizarBusqueda();
        }
    });

    // Evento para el botón de búsqueda
    $barraBusqueda.next('button').on('click', realizarBusqueda);
}); 