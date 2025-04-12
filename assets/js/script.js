// Esperar a que el documento esté completamente cargado
$(document).ready(function() {
    console.log('DOM listo'); // Para verificar que el DOM está listo
    
    // Funcionalidad del botón hamburguesa
    $("#botonHamburguesa").click(function() {
        $("#menuNav").toggleClass("hidden");
    });

    // Animación para el menú móvil
    $("#botonHamburguesa").click(function() {
        $("#menuNav").slideToggle(300);
    });
});

