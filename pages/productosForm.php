<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once '../assets/php/MVC/Controlador/productos-controlador.php';
$controlador = new ProductosControlador();

// Verificar si es edición o nuevo producto
$esEdicion = isset($_GET['id']) && !empty($_GET['id']);
$producto = null;

if ($esEdicion) {
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
    <title><?= $esEdicion ? 'Editar' : 'Nuevo' ?> Producto - BioEspacio</title>
    <link href="../node_modules/flowbite/dist/flowbite.min.css" rel="stylesheet">
    <link href="../assets/css/src/output.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-beige flex flex-col">
    <?php include "../includes/header.php"; ?>

    <main class="flex-grow py-12 px-4 md:px-24">
        <!-- Encabezado de sección -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-green-800 mb-4 font-display-CormorantGaramond">
                <?= $esEdicion ? 'Editar Producto' : 'Nuevo Producto' ?>
            </h1>
            <p class="text-gray-600 max-w-2xl my-5 mx-auto">
                Complete los detalles del producto a continuación.
            </p>
        </div>                     

        <!-- Formulario de producto -->
        <div class="bg-white rounded-xl my-4 md:my-10 p-4 md:p-8 shadow-lg max-w-2xl mx-auto">
            <form id="productoForm" enctype="multipart/form-data" method="post">
                <?php if ($esEdicion): ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($producto['id']) ?>">
                <?php endif; ?>
                
                <!-- Nombre del producto -->
                <div class="mb-4">
                    <label for="nombre" class="block mb-2 text-sm md:text-base lg:text-lg font-medium text-gray-900">Nombre del producto</label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           value="<?= $esEdicion ? htmlspecialchars($producto['nombre']) : '' ?>" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm md:text-base lg:text-lg rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" 
                           required>
                </div>
                
                <!-- Stock con botones + y - -->
                <div class="mb-4">
                    <label for="stock" class="block mb-2 text-sm md:text-base lg:text-lg font-medium text-gray-900">Stock</label>
                    <div class="flex items-center gap-2">
                        <input type="number" 
                               id="stock" 
                               name="stock" 
                               value="<?= $esEdicion ? htmlspecialchars($producto['stock']) : '0' ?>" 
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm md:text-base lg:text-lg rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" 
                               required>
                    </div>
                </div>
                
                <!-- Precio -->
                <div class="mb-4">
                    <label for="precio" class="block mb-2 text-sm md:text-base lg:text-lg font-medium text-gray-900">Precio</label>
                    <input type="number" 
                           id="precio" 
                           name="precio" 
                           step="0.01" 
                           value="<?= $esEdicion ? htmlspecialchars($producto['precio']) : '' ?>" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm md:text-base lg:text-lg rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" 
                           required>
                </div>
                
                <!-- Laboratorio -->
                <div class="mb-4">
                    <label for="laboratorio" class="block mb-2 text-sm md:text-base lg:text-lg font-medium text-gray-900">Laboratorio (Opcional)</label>
                    <input type="text" 
                           id="laboratorio" 
                           name="laboratorio" 
                           value="<?= $esEdicion && isset($producto['laboratorio']) && $producto['laboratorio'] !== '' ? htmlspecialchars($producto['laboratorio']) : 'N/D' ?>" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm md:text-base lg:text-lg rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
                </div>
                
                <!-- Foto del producto -->
                <div class="mb-4">
                    <label for="foto" class="block mb-2 text-sm md:text-base lg:text-lg font-medium text-gray-900">Foto del producto (Opcional)</label>
                    <?php if ($esEdicion && !empty($producto['foto'])): ?>
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Foto actual:</p>
                            <img src="../<?= htmlspecialchars($producto['foto']) ?>" 
                                 alt="Foto actual del producto" 
                                 class="w-32 h-32 object-cover rounded-lg">
                            <input type="hidden" name="foto_actual" value="<?= htmlspecialchars($producto['foto']) ?>">
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
                    <p class="mt-1 text-sm text-gray-500">
                        <?php if ($esEdicion): ?>
                            Sube una nueva imagen solo si deseas cambiar la actual.
                        <?php else: ?>
                            Selecciona una imagen para el producto.
                        <?php endif; ?>
                    </p>
                </div>
                
                <!-- Comentarios -->
                <div class="mb-6">
                    <label for="comentarios" class="block mb-2 text-sm md:text-base lg:text-lg font-medium text-gray-900">Comentarios (Opcional)</label>
                    <textarea id="comentarios" 
                              name="comentarios" 
                              rows="4" 
                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5"><?= $esEdicion ? htmlspecialchars($producto['comentarios']) : '' ?></textarea>
                </div>
                
                <!-- Botones -->
                <div class="flex justify-end space-x-4">
                    <a href="productos.php" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <?= $esEdicion ? 'Guardar cambios' : 'Crear producto' ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include "../includes/footer.php"; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/productos.js"></script>
</body>
</html>
