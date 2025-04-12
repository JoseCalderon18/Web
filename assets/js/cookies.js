$(document).ready(function() {
    // Funciones para manejar las cookies
    function mostrarBanner() {
        $("#banner-cookies").fadeIn(300);
        $("#overlay-cookies").fadeIn(300);
    }

    function ocultarBanner() {
        $("#banner-cookies").fadeOut(300);
        $("#overlay-cookies").fadeOut(300);
    }

    function establecerCookie(nombre, valor, dias) {
        const fecha = new Date();
        fecha.setTime(fecha.getTime() + (dias * 24 * 60 * 60 * 1000));
        const expira = "expires=" + fecha.toUTCString();
        document.cookie = nombre + "=" + valor + ";" + expira + ";path=/";
    }

    function obtenerCookie(nombre) {
        const nombreCookie = nombre + "=";
        const cookies = document.cookie.split(";");
        for(let i = 0; i < cookies.length; i++) {
            let cookie = cookies[i];
            while (cookie.charAt(0) === " ") {
                cookie = cookie.substring(1);
            }
            if (cookie.indexOf(nombreCookie) === 0) {
                return cookie.substring(nombreCookie.length, cookie.length);
            }
        }
        return "";
    }

    // Comprobar si ya se aceptaron las cookies
    function verificarConsentimientoCookies() {
        const consentimientoCookie = obtenerCookie("consentimiento_cookies");
        if (consentimientoCookie === "") {
            setTimeout(mostrarBanner, 2000); // Mostrar banner después de 2 segundos
        }
    }

    // Inicializar al cargar la página
    verificarConsentimientoCookies();

    // Manejar el clic en "Aceptar"
    $("#aceptar-cookies").click(function() {
        establecerCookie("consentimiento_cookies", "aceptado", 365);
        establecerCookie("cookies_analiticas", "true", 365);
        establecerCookie("cookies_publicidad", "true", 365);
        ocultarBanner();
        
        Swal.fire({
            icon: "success",
            title: "¡Preferencias guardadas!",
            text: "Has aceptado todas las cookies",
            confirmButtonColor: "#4A6D50",
            timer: 2000,
            timerProgressBar: true
        });
    });

    // Manejar el clic en "Rechazar"
    $("#rechazar-cookies").click(function() {
        establecerCookie("consentimiento_cookies", "rechazado", 365);
        establecerCookie("cookies_analiticas", "false", 365);
        establecerCookie("cookies_publicidad", "false", 365);
        ocultarBanner();
        
        Swal.fire({
            icon: "info",
            title: "Preferencias guardadas",
            text: "Has rechazado las cookies opcionales",
            confirmButtonColor: "#4A6D50",
            timer: 2000,
            timerProgressBar: true
        });
    });

    // Manejar el clic en "Configurar"
    $("#configurar-cookies").click(function() {
        Swal.fire({
            title: "Configuración de Cookies",
            html: `
                <div class="text-left">
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="cookies-tecnicas" checked disabled>
                            <span class="ml-2">Cookies técnicas (necesarias)</span>
                        </label>
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="cookies-analiticas">
                            <span class="ml-2">Cookies analíticas</span>
                        </label>
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="cookies-publicidad">
                            <span class="ml-2">Cookies de publicidad</span>
                        </label>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: "Guardar preferencias",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#4A6D50",
            preConfirm: () => {
                const cookiesAnaliticas = $("#cookies-analiticas").is(":checked");
                const cookiesPublicidad = $("#cookies-publicidad").is(":checked");
                
                establecerCookie("consentimiento_cookies", "configurado", 365);
                establecerCookie("cookies_analiticas", cookiesAnaliticas, 365);
                establecerCookie("cookies_publicidad", cookiesPublicidad, 365);
                
                return { cookiesAnaliticas, cookiesPublicidad };
            }
        }).then((resultado) => {
            if (resultado.isConfirmed) {
                ocultarBanner();
                Swal.fire({
                    icon: "success",
                    title: "¡Preferencias guardadas!",
                    text: "Tu configuración de cookies ha sido guardada",
                    confirmButtonColor: "#4A6D50",
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        });
    });

    // Manejar clics en enlaces de políticas
    $(".enlace-politica").click(function(e) {
        e.preventDefault();
        const url = $(this).attr("href");
        window.location.href = url;
    });
});
