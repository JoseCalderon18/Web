$(document).ready(function() {
    // Menú hamburguesa
    const $btnHamburguesa = $("#botonHamburguesa");
    const $menuNav = $("#menuNav");

    if ($btnHamburguesa.length && $menuNav.length) {
        $btnHamburguesa.on("click", function() {
            $menuNav.toggleClass("hidden");
        });
    }

    // Formulario de contacto
    const $form = $("#formulario");
    const $btnEnviar = $("#btnEnviar");
    
    if ($form.length) {
        $form.click('submit', function(e) {
            e.preventDefault();
            
            // Obtener valores
            const nombre = $("#nombre").val().trim();
            const email = $("#email").val().trim();
            const asunto = $("#asunto").val();
            const mensaje = $("#mensaje").val().trim();
            
            // Validaciones simples
            if (!nombre) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El nombre es obligatorio'
                });
                return false;
            }

            if (!email) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El email es obligatorio'
                });
                return false;
            }

            if (!asunto) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debes seleccionar un asunto'
                });
                return false;
            }

            if (!mensaje) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El mensaje es obligatorio'
                });
                return false;
            }
            
            // Deshabilitar botón mientras se envía
            $(this).prop('disabled', true).text('Enviando...');
            
            // Enviar formulario
            $.ajax({
                url: '../assets/php/correo.php',
                type: 'POST',
                data: { nombre, email, asunto, mensaje },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Mensaje enviado correctamente'
                        });
                        $form[0].reset();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al enviar el mensaje'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al enviar el mensaje'
                    });
                },
                complete: function() {
                    $btnEnviar.prop('disabled', false).text('Enviar mensaje');
                }
            });
        });
    }
});

