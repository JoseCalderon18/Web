$(document).ready(function() {
    console.log("Script de búsqueda cargado");
    
    const barraBusqueda = $('#barraBusqueda');
    const botonBuscar = $('#botonBuscar');
    const tablaUsuarios = $('#tablaUsuarios tbody');
    
    console.log("Barra de búsqueda:", barraBusqueda.length);
    console.log("Botón buscar:", botonBuscar.length);
    console.log("Tabla usuarios:", tablaUsuarios.length);
    
    function realizarBusqueda() {
        const termino = barraBusqueda.val().trim();
        
        if (termino === '') {
            window.location.reload();
            return;
        }

        $.ajax({
            url: '../assets/php/MVC/Controlador/usuarios-controlador.php',
            type: 'GET',
            data: {
                accion: 'buscar',
                buscar: termino
            },
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    
                    if (data.success) {
                        tablaUsuarios.empty();
                        
                        if (data.data.length === 0) {
                            tablaUsuarios.html(`
                                <tr>
                                    <td colspan="3" class="px-3 md:px-8 py-4 md:py-6 text-center text-gray-500 text-sm md:text-base">
                                        No se encontraron resultados para "${termino}"
                                    </td>
                                </tr>
                            `);
                            return;
                        }

                        // Mantener el estilo exacto de tu tabla
                        data.data.forEach(usuario => {
                            tablaUsuarios.append(`
                                <tr class="border-b">
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base font-medium">
                                        ${usuario.nombre}
                                    </td>
                                    <td class="px-3 md:px-8 py-3 md:py-5 text-sm md:text-base">
                                        ${usuario.email}
                                    </td>
                                    <td class="px-3 md:px-8 py-3 md:py-5">
                                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-5">
                                            <button onclick="cambiarRol(${usuario.id}, '${usuario.rol}')" 
                                                    class="px-3 py-2 md:px-4 md:py-2 text-xs md:text-sm ${usuario.rol === 'admin' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-blue-500 hover:bg-blue-600'} text-white rounded-lg shadow-sm cursor-pointer">
                                                <i class="fas ${usuario.rol === 'admin' ? 'fa-user-minus' : 'fa-user-shield'} mr-1 md:mr-2"></i>
                                                ${usuario.rol === 'admin' ? 'Quitar Admin' : 'Hacer Admin'}
                                            </button>
                                            <button onclick="confirmarEliminacion(${usuario.id})" 
                                                    class="px-3 py-2 md:px-4 md:py-2 text-xs md:text-sm bg-red-600 hover:bg-red-800 text-white rounded-lg shadow-sm cursor-pointer">
                                                <i class="fas fa-trash-alt mr-1 md:mr-2"></i>Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `);
                        });

                    } else {
                        throw new Error(data.message);
                    }
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: e.message || 'Error al procesar la búsqueda'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error en la comunicación con el servidor'
                });
            }
        });
    }

    botonBuscar.on('click', function(e) {
        e.preventDefault();
        realizarBusqueda();
    });

    barraBusqueda.on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            realizarBusqueda();
        }
    });

    barraBusqueda.on('input', function() {
        if ($(this).val().trim() === '') {
            window.location.reload();
        }
    });
}); 