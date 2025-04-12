$(document).ready(function() {
    console.log("Script de animaciones cargado");

    // Función para comprobar si un elemento está en el viewport
    function estaEnViewport($elemento) {
        var posicionElemento = $elemento.offset().top;
        var posicionScroll = $(window).scrollTop();
        var alturaVentana = $(window).height();
        var limiteInferior = posicionScroll + alturaVentana;
        return posicionElemento < limiteInferior - 150;
    }

    // Función para animar elementos
    function animarElementos() {
        console.log("Buscando elementos para animar");
        
        // Animar elementos con clase aparecer
        $(".aparecer").each(function() {
            var $elemento = $(this);
            if (estaEnViewport($elemento) && !$elemento.hasClass("activo")) {
                console.log("Animando elemento aparecer");
                $elemento.addClass("activo");
            }
        });

        // Animar elementos con clase aparecer-secuencial
        $(".aparecer-secuencial").each(function(indice) {
            var $elemento = $(this);
            if (estaEnViewport($elemento) && !$elemento.hasClass("activo")) {
                console.log("Animando elemento secuencial " + indice);
                setTimeout(function() {
                    $elemento.addClass("activo");
                }, indice * 200);
            }
        });
    }

    // Ejecutar animaciones al cargar
    setTimeout(animarElementos, 500);

    // Ejecutar animaciones al hacer scroll
    $(window).on("scroll", function() {
        requestAnimationFrame(animarElementos);
    });

    // Animaciones para escalar al hover
    $(".escalar-hover").hover(
        function() {
            $(this).addClass("escala-105 transicion-transform duracion-300");
        },
        function() {
            $(this).removeClass("escala-105");
        }
    );

    // Scroll suave para enlaces internos
    $("a[href^=\"#\"]").on("click", function(evento) {
        evento.preventDefault();
        var destino = $(this.hash);
        if (destino.length) {
            $("html, body").animate({
                scrollTop: destino.offset().top - 100
            }, 800, "swing");
        }
    });

    // Animación de aparición gradual para imágenes
    $(".imagen-aparecer").on("load", function() {
        $(this).addClass("cargada");
    }).each(function() {
        // Para imágenes que ya están cargadas
        if(this.complete) {
            $(this).addClass("cargada");
        }
    });
});
