// Esperar a que el documento esté completamente cargado
$(document).ready(function() {
    // Funcionalidad del botón hamburguesa con animación
    $('#botonHamburguesa').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Animación suave del menú
        if ($('#menuNav').hasClass('hidden')) {
            $('#menuNav').removeClass('hidden').hide().slideDown(200);
        } else {
            $('#menuNav').slideUp(200, function() {
                $(this).addClass('hidden');
            });
        }
    });

    // Cerrar el menú al hacer click fuera con animación
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#botonHamburguesa, #menuNav').length && !$('#menuNav').hasClass('hidden')) {
            $('#menuNav').slideUp(200, function() {
                $(this).addClass('hidden');
            });
        }
    });

    // Manejar resize de ventana
    $(window).on('resize', function() {
        if ($(window).width() >= 640) {
            $('#menuNav').removeClass('hidden').removeAttr('style');
        }
    });
});

