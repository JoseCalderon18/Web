document.addEventListener('DOMContentLoaded', function() {
    const barraBusqueda = document.getElementById('barraBusqueda');
    if (!barraBusqueda) return;

    function realizarBusqueda() {
        const searchTerm = barraBusqueda.value.trim();
        
        // Obtener la URL actual y actualizar el parámetro de búsqueda
        let url = new URL(window.location.href);
        if (searchTerm) {
            url.searchParams.set('buscar', searchTerm);
            // Resetear la página a 1 cuando se hace una nueva búsqueda
            url.searchParams.set('pagina', '1');
        } else {
            url.searchParams.delete('buscar');
        }
        
        // Redirigir a la nueva URL
        window.location.href = url.toString();
    }

    // Eventos para la búsqueda
    let timeoutId;
    barraBusqueda.addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(realizarBusqueda, 500); // Debounce de 500ms
    });

    barraBusqueda.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            clearTimeout(timeoutId);
            realizarBusqueda();
        }
    });

    // Evento para el botón de búsqueda
    const botonBusqueda = barraBusqueda.nextElementSibling;
    if (botonBusqueda) {
        botonBusqueda.addEventListener('click', function(e) {
            e.preventDefault();
            realizarBusqueda();
        });
    }

    // Mantener el término de búsqueda en el input
    const urlParams = new URLSearchParams(window.location.search);
    const terminoBusqueda = urlParams.get('buscar');
    if (terminoBusqueda) {
        barraBusqueda.value = terminoBusqueda;
    }
}); 