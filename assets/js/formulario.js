$(document).ready(function() {
    // Seleccionar el formulario por su ID
    $("#formularioContacto").on("submit", function(e) {
        e.preventDefault();

        // Función para validar que el nombre solo contenga letras y espacios
        function validacionNombre(nombre) {
            const regex = /^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/;
            return regex.test(nombre);
        }

        // Obtener valores de los campos
        const nombre = $("#nombre").val().trim();
        const email = $("#email").val().trim();
        const asunto = $("#asunto").val();
        const mensaje = $("#mensaje").val().trim();
        let errores = [];

        // Validar campos obligatorios
        if (nombre === "") {
            errores.push("El nombre es obligatorio");
        } else if (nombre.length < 4) {
            errores.push("El nombre debe tener al menos 4 caracteres");
        } else if (!validacionNombre(nombre)) {
            errores.push("El nombre solo puede contener letras y espacios");
        }

        if (email === "") {
            errores.push("El email es obligatorio");
        }

        if (!asunto) {
            errores.push("Debes seleccionar un asunto");
        }

        if (mensaje === "") {
            errores.push("El mensaje es obligatorio");
        }

        // Si hay errores, mostrar alerta
        if (errores.length > 0) {
            Swal.fire({
                icon: "error",
                title: "Por favor, completa correctamente todos los campos",
                html: errores.join("<br>"),
                confirmButtonText: "Entendido",
                confirmButtonColor: "#4A6D50"
            });
            return;
        }

        // Si no hay errores, enviar por AJAX
        $.ajax({
            url: '../assets/php/correo.php',
            type: 'POST',
            data: {
                nombre: nombre,
                email: email,
                asunto: asunto,
                mensaje: mensaje
            },
            beforeSend: function() {
                // Mostrar loading
                Swal.fire({
                    title: 'Enviando mensaje...',
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false
                });
            },
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "¡Mensaje enviado!",
                            text: "Nos pondremos en contacto contigo pronto",
                            confirmButtonColor: "#4A6D50"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#formularioContacto")[0].reset(); // Limpiar el formulario
                            }
                        });
                    } else {
                        throw new Error(data.message || 'Error al enviar el mensaje');
                    }
                } catch (error) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: error.message || "Hubo un error al enviar el mensaje",
                        confirmButtonColor: "#4A6D50"
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "No se pudo conectar con el servidor",
                    confirmButtonColor: "#4A6D50"
                });
            }
        });
    });
});
