// Esperar a que el documento esté completamente cargado
$(document).ready(function() {
    
    // Funcionalidad del botón hamburguesa
    $("#botonHamburguesa").click(function() {
        $("#menuNav").toggleClass("hidden");
    });

    // Animación para el menú móvil
    $("#botonHamburguesa").click(function() {
        $("#menuNav").slideToggle(300);
    });
});

