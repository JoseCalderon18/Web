$(document).ready(function() {

    // Función para comprobar si un elemento está visible en la ventana del navegador
    // Calcula si el elemento está dentro del área visible comparando su posición con el scroll
    function estaEnViewport($elemento) {
        var posicionElemento = $elemento.offset().top;
        var posicionScroll = $(window).scrollTop();
        var alturaVentana = $(window).height();
        var limiteInferior = posicionScroll + alturaVentana;
        return posicionElemento < limiteInferior - 150;
    }

    // Función principal que maneja las animaciones de los elementos
    // Se encarga de añadir clases para activar las animaciones cuando los elementos son visibles
    function animarElementos() {
        
        // Anima elementos que tienen la clase "aparecer" añadiendo "activo" cuando son visibles
        $(".aparecer").each(function() {
            var $elemento = $(this);
            if (estaEnViewport($elemento) && !$elemento.hasClass("activo")) {
                $elemento.addClass("activo");
            }
        });

        // Anima elementos secuencialmente con un retraso de 200ms entre cada uno
        $(".aparecer-secuencial").each(function(indice) {
            var $elemento = $(this);
            if (estaEnViewport($elemento) && !$elemento.hasClass("activo")) {
                setTimeout(function() {
                    $elemento.addClass("activo");
                }, indice * 200);
            }
        });
    }

    // Inicia las animaciones después de 500ms de cargada la página
    setTimeout(animarElementos, 500);

    // Ejecuta las animaciones cada vez que se hace scroll
    // Usa requestAnimationFrame para optimizar el rendimiento
    $(window).on("scroll", function() {
        requestAnimationFrame(animarElementos);
    });

    // Añade efecto de escala al pasar el mouse por encima de elementos con clase "escalar-hover"
    $(".escalar-hover").hover(
        function() {
            $(this).addClass("escala-105 transicion-transform duracion-300");
        },
        function() {
            $(this).removeClass("escala-105");
        }
    );

    // Implementa scroll suave para enlaces internos que comienzan con #
    $("a[href^=\"#\"]").on("click", function(evento) {
        evento.preventDefault();
        var destino = $(this.hash);
        if (destino.length) {
            $("html, body").animate({
                scrollTop: destino.offset().top - 100
            }, 800, "swing");
        }
    });

    // Maneja la aparición gradual de imágenes cuando se cargan
    $(".imagen-aparecer").on("load", function() {
        $(this).addClass("cargada");
    }).each(function() {
        // Maneja imágenes que ya estaban cargadas cuando se ejecutó el script
        if(this.complete) {
            $(this).addClass("cargada");
        }
    });

    // Configura un observador de intersección para elementos que aparecen más tarde
    const observerTarde = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Añade un retraso adicional antes de mostrar los elementos
                setTimeout(() => {
                    entry.target.classList.add('visible');
                    
                    // Maneja elementos que deben aparecer secuencialmente con más retraso
                    if (entry.target.classList.contains('aparecer-tarde')) {
                        const secuencialesTarde = entry.target.querySelectorAll('.aparecer-secuencial-tarde');
                        secuencialesTarde.forEach((elemento, index) => {
                            setTimeout(() => {
                                elemento.classList.add('visible');
                            }, 200 * (index + 1));
                        });
                    }
                }, 500);
            }
        });
    }, {
        threshold: 0.2, // Define cuánto del elemento debe ser visible para activar
        rootMargin: '-100px' // Ajusta el margen de activación
    });

    // Aplica el observador a todos los elementos con la clase 'aparecer-tarde'
    document.querySelectorAll('.aparecer-tarde').forEach(element => {
        observerTarde.observe(element);
    });
});