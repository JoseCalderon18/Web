$(document).ready(function() {
    
    // Validacion y envio del formulario de  login
    $("#loginForm").on("submit", function(e){
        e.preventDefault();

        // Obtener datos del formulario
        const correo = $("#correoElectronico").val().trim();
        const contrasenia = $("#password-admin").val();

        let errores = [];

        // Validación del correo electrónico
        if (!correo) {
            errores.push("El correo electrónico es obligatorio");
        }

        // Validación de la contraseña
        if (!contrasenia) {
            errores.push("La contraseña es obligatoria");
        }

        // Si hay errores, mostrar alerta
        if(errores.length > 0){
            Swal.fire({
                icon: "error",
                title: "Por favor, completa correctamente todos los campos",
                html: errores.join("<br>"),
                confirmButtonText: "Aceptar",                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
                confirmButtonColor: "#4A6D50"
            });
            return;
        }

        // Si no hay errores, enviar datos
        $.ajax({
            url: "../assets/php/MVC/Controlador/usuarios-controlador.php?accion=login",
            type: "POST",
            data: {
                correo: correo,
                contrasenia: contrasenia
            },
            dataType: 'json',
            success: function(response){
                if(response.success){
                    Swal.fire({
                        icon: "success",
                        title: "¡Inicio de sesión exitoso!",
                        text: response.message,
                        confirmButtonColor: "#4A6D50"
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.href = "blog.php";
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "error",  
                        title: "Error",
                        text: response.message,
                        confirmButtonColor: "#4A6D50"
                    });
                }
            },
            error: function(xhr, status, error){
                console.log("Error Status:", status);
                console.log("Error:", error);
                console.log("Response Text:", xhr.responseText);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Ocurrió un error al procesar el inicio de sesión",
                    confirmButtonColor: "#4A6D50"
                });
            }
        });
    }); 
});
