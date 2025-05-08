<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once '../assets/php/MVC/Controlador/productos-controlador.php';
$controlador = new ProductosControlador();

// Verificar si es nuevo producto o no
$tipo = isset($_GET['id']);
$producto = null;

if ($tipo) {
    $producto = $controlador->obtenerProductoPorId($_GET['id']);
    if (!$producto) {
        header("Location: productos.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $tipo ? 'Editar' : 'Nuevo' ?> Producto - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</head>
<body class="bg-beige flex flex-col">
    <?php include "../includes/header.php"; ?>

    <main class="flex-grow py-12 px-4 md:px-24">
        <!-- Encabezado de sección -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-green-800 mb-4 font-display-CormorantGaramond">
                <?= $tipo ? 'Editar Producto' : 'Nuevo Producto' ?>
            </h1>
            <p class="text-gray-600 max-w-2xl my-5 mx-auto">
                Complete los detalles del producto a continuación.
            </p>
        </div>                     

        <!-- Formulario de producto -->
        <div class="bg-white rounded-xl my-4 md:my-10 p-4 md:p-8 shadow-lg max-w-2xl mx-auto">
            <form id="productoForm" method="post" enctype="multipart/form-data" class="space-y-4 md:space-y-6 w-full">
                <?php if ($tipo): ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($producto['id']) ?>">
                <?php endif; ?>

                <!-- Campos del formulario en disposición vertical (flex) -->
                <div class="flex flex-col gap-4 md:gap-6">
                    <!-- Nombre del producto -->
                    <div>
                        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900">Nombre del producto</label>
                        <input type="text" 
                               id="nombre" 
                               name="nombre" 
                               value="<?= $tipo ? htmlspecialchars($producto['nombre']) : '' ?>"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>

                    <!-- Stock -->
                    <div>
                        <label for="stock" class="block mb-2 text-sm font-medium text-gray-900">Stock</label>
                        <input type="number" 
                               id="stock" 
                               name="stock" 
                               value="<?= $tipo ? htmlspecialchars($producto['stock']) : '' ?>"
                               min="0"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>

                    <!-- Precio -->
                    <div>
                        <label for="precio" class="block mb-2 text-sm font-medium text-gray-900">Precio (€)</label>
                        <input type="number" 
                               id="precio" 
                               name="precio" 
                               value="<?= $tipo ? htmlspecialchars($producto['precio']) : '' ?>"
                               step="0.01"
                               min="0"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>

                    <!-- Fecha de registro -->
                    <div>
                        <label for="fecha_registro" class="block mb-2 text-sm font-medium text-gray-900">Fecha de registro</label>
                        <input type="date" 
                               id="fecha_registro" 
                               name="fecha_registro" 
                               value="<?= $tipo ? htmlspecialchars($producto['fecha_registro']) : date('Y-m-d') ?>"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                    </div>

                    <!-- Comentarios -->
                    <div>
                        <label for="comentarios" class="block mb-2 text-sm font-medium text-gray-900">Comentarios</label>
                        <textarea id="comentarios" 
                                  name="comentarios" 
                                  rows="4"
                                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                  placeholder="Añade comentarios sobre el producto..."><?= $tipo ? htmlspecialchars($producto['comentarios']) : '' ?></textarea>
                    </div>

                    <!-- Foto del producto -->
                    <div>
                        <label for="foto" class="block mb-2 text-sm font-medium text-gray-900">Foto del producto (Opcional)</label>
                        <?php if ($tipo && !empty($producto['foto'])): ?>
                            <div class="mb-4">
                                <img src="../<?= htmlspecialchars($producto['foto']) ?>" 
                                     alt="Foto actual del producto" 
                                     class="w-32 h-32 object-cover rounded-lg">
                            </div>
                        <?php endif; ?>
                        <input type="file" 
                               id="foto" 
                               name="foto" 
                               accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-md file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-green-50 file:text-green-700
                                      hover:file:bg-green-100">
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 mt-6">
                    <a href="productos.php" 
                       class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors text-center">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-800 hover:bg-green-700 text-white rounded-lg transition-colors duration-300 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        <?= $tipo ? 'Guardar cambios' : 'Añadir producto' ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php require_once '../includes/footer.php'; ?>

    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/producto.js"></script>
</body>
</html>
