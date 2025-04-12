$(document).ready(function() {
    // Funciones para manejar las cookies
    function mostrarBanner() {
        $("#cookie-banner").fadeIn(300);
        $("#cookie-overlay").fadeIn(300);
    }

    function ocultarBanner() {
        $("#cookie-banner").fadeOut(300);
        $("#cookie-overlay").fadeOut(300);
    }

    function setCookie(nombre, valor, dias) {
        const fecha = new Date();
        fecha.setTime(fecha.getTime() + (dias * 24 * 60 * 60 * 1000));
        const expira = "expires=" + fecha.toUTCString();
        document.cookie = nombre + "=" + valor + ";" + expira + ";path=/";
    }

    function getCookie(nombre) {
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
    function checkCookieConsent() {
        const cookieConsent = getCookie("cookie_consent");
        if (cookieConsent === "") {
            setTimeout(mostrarBanner, 2000); // Mostrar banner después de 2 segundos
        }
    }

    // Inicializar al cargar la página
    checkCookieConsent();

    // Manejar el clic en "Aceptar"
    $("#aceptar-cookies").click(function() {
        setCookie("cookie_consent", "accepted", 365);
        setCookie("cookies_analiticas", "true", 365);
        setCookie("cookies_publicidad", "true", 365);
        ocultarBanner();
        
        Swal.fire({
            icon: "success",
            title: "Preferencias guardadas",
            text: "Has aceptado el uso de cookies",
            confirmButtonColor: "#4A6D50",
            timer: 2000,
            timerProgressBar: true
        });
    });

    // Manejar el clic en "Rechazar"
    $("#rechazar-cookies").click(function() {
        setCookie("cookie_consent", "rejected", 365);
        setCookie("cookies_analiticas", "false", 365);
        setCookie("cookies_publicidad", "false", 365);
        ocultarBanner();
        
        Swal.fire({
            icon: "info",
            title: "Preferencias guardadas",
            text: "Has rechazado el uso de cookies opcionales",
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
                const analiticas = $("#cookies-analiticas").is(":checked");
                const publicidad = $("#cookies-publicidad").is(":checked");
                
                setCookie("cookie_consent", "configured", 365);
                setCookie("cookies_analiticas", analiticas, 365);
                setCookie("cookies_publicidad", publicidad, 365);
                
                return { analiticas, publicidad };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                ocultarBanner();
                Swal.fire({
                    icon: "success",
                    title: "Preferencias guardadas",
                    text: "Tu configuración de cookies ha sido guardada",
                    confirmButtonColor: "#4A6D50",
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        });
    });

    // Manejar clics en enlaces de políticas
    $(".politica-link").click(function(e) {
        e.preventDefault();
        const url = $(this).attr("href");
        window.location.href = url;
    });
});
