$(document).ready(function() {
    $("#formularioContacto").on("submit", function(e) {
        e.preventDefault();

        // Obtener valores
        const nombre = $("#nombre").val().trim();
        const email = $("#email").val().trim();
        const asuntoNumero = $("#asunto").val();
        const mensaje = $("#mensaje").val().trim();

        // Convertir el número de asunto al texto
        const asuntos = {
            '1': 'Solicitar Información',
            '2': 'Consulta sobre algún producto',
            '3': 'Consulta sobre terapias',
            '4': 'Otro'
        };
        const asunto = asuntos[asuntoNumero] || asuntoNumero;

        // Validaciones básicas
        let errores = [];
        if (!nombre) errores.push("El nombre es obligatorio");
        if (!email) errores.push("El email es obligatorio");
        if (!asuntoNumero) errores.push("El asunto es obligatorio");
        if (!mensaje) errores.push("El mensaje es obligatorio");

        if (errores.length > 0) {
            Swal.fire({
                icon: "error",
                title: "Por favor, completa todos los campos",
                html: errores.join("<br>"),
                confirmButtonColor: "#4A6D50"
            });
            return;
        }

        // Enviar formulario
        $.ajax({
            url: '../assets/php/correo.php',
            type: 'POST',
            data: {
                nombre: nombre,
                email: email,
                asunto: asunto,
                mensaje: mensaje
            },
            dataType: 'json',
            beforeSend: function() {
                Swal.fire({
                    title: 'Enviando mensaje...',
                    text: 'Por favor, espera...',
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            },
            success: function(respuesta) {
                if (respuesta.exito) {
                    Swal.fire({
                        icon: "success",
                        title: "¡Mensaje enviado!",
                        text: "Nos pondremos en contacto contigo pronto",
                        confirmButtonColor: "#4A6D50"
                    }).then(() => {
                        $("#formularioContacto")[0].reset();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: respuesta.mensaje || "Hubo un error al enviar el mensaje",
                        confirmButtonColor: "#4A6D50"
                    });
                }
            },
            error: function(xhr, estado, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error de conexión",
                    text: "No se pudo enviar el mensaje. Por favor, intenta más tarde.",
                    confirmButtonColor: "#4A6D50"
                });
            }
        });
    });
});
