// Espera a que el DOM esté completamente cargado
$(document).ready(function() {
    // Obtiene las referencias a los elementos
    const $btnHamburguesa = $("#botonHamburguesa");
    const $menuNav = $("#menuNav");

    // Verifica que los elementos existan antes de añadir el evento
    if ($btnHamburguesa.length && $menuNav.length) {
        $btnHamburguesa.on("click", function() {
            $menuNav.toggleClass("hidden");
        });
    }
});


