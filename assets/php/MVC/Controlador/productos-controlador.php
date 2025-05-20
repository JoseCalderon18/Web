<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../Modelo/productos-modelo.php';

class ProductosControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new ProductosModelo();
    }

    // Obtener todos los productos
    public function obtenerTodosLosProductos() {
        try {
            // Verificar si el usuario es administrador
            if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
                throw new Exception("No tienes permisos para ver esta página");
            }

            $porPagina = 10; // Número de productos por página
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            $paginaActual = max(1, $paginaActual);
            
            // Obtener total de registros
            $totalRegistros = $this->modelo->obtenerTotalProductos();
            
            // Calcular total de páginas
            $totalPaginas = max(1, ceil($totalRegistros / $porPagina));
            
            // Asegurar que la página actual no exceda el total de páginas
            $paginaActual = min($paginaActual, $totalPaginas);
            
            // Calcular el offset para la consulta SQL
            $offset = ($paginaActual - 1) * $porPagina;
            
            // Obtener los productos para la página actual
            $productos = $this->modelo->obtenerTodos($offset, $porPagina);
            
            return [
                'productos' => $productos,
                'paginaActual' => $paginaActual,
                'totalPaginas' => $totalPaginas,
                'porPagina' => $porPagina,
                'total' => $totalRegistros
            ];
        } catch (Exception $e) {
            error_log("Error en obtenerTodosLosProductos: " . $e->getMessage());
            return [
                'productos' => [],
                'paginaActual' => 1,
                'totalPaginas' => 1,
                'porPagina' => $porPagina,
                'total' => 0
            ];
        }
    }

    // Crear producto
    public function crearProducto() {
        try {
            // Validar datos
            if (!isset($_POST['nombre']) || !isset($_POST['stock']) || !isset($_POST['precio'])) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
                return;
            }

            $nombre = $_POST['nombre'];
            $stock = $_POST['stock'];
            $precio = $_POST['precio'];
            $comentarios = isset($_POST['comentarios']) ? $_POST['comentarios'] : '';
            $fecha_registro = date('Y-m-d');
            
            // Procesar la foto
            $rutaFoto = '';
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0 && $_FILES['foto']['size'] > 0) {
                $foto = $_FILES['foto'];
                $nombreArchivo = uniqid() . '_' . basename($foto['name']);
                
                // IMPORTANTE: Determinar la ruta base del proyecto
                $rutaBase = realpath(__DIR__ . '/../../../..');
                
                // Ruta relativa para guardar en la base de datos
                $rutaRelativa = 'assets/img/productos/' . $nombreArchivo;
                
                // Ruta absoluta para guardar el archivo
                $rutaAbsoluta = $rutaBase . '/' . $rutaRelativa;
                
                // Asegurarse de que el directorio existe
                $directorioDestino = dirname($rutaAbsoluta);
                if (!is_dir($directorioDestino)) {
                    mkdir($directorioDestino, 0755, true);
                }
                
                // Información de depuración
                echo "<script>console.log('Información de depuración:');</script>";
                echo "<script>console.log('Ruta base del proyecto: " . addslashes($rutaBase) . "');</script>";
                echo "<script>console.log('Ruta relativa: " . addslashes($rutaRelativa) . "');</script>";
                echo "<script>console.log('Ruta absoluta: " . addslashes($rutaAbsoluta) . "');</script>";
                
                if (move_uploaded_file($foto['tmp_name'], $rutaAbsoluta)) {
                    $rutaFoto = $rutaRelativa;
                    echo "<script>console.log('Imagen subida correctamente a: " . addslashes($rutaFoto) . "');</script>";
                } else {
                    echo "<script>console.log('Error al subir la imagen: " . addslashes(error_get_last()['message']) . "');</script>";
                    echo json_encode(['success' => false, 'message' => 'Error al subir la imagen: ' . error_get_last()['message']]);
                    return;
                }
            }
            
            // Crear el producto
            $resultado = $this->modelo->crear($nombre, $stock, $rutaFoto, $precio, $fecha_registro, $comentarios);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Producto creado correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al crear el producto']);
            }
        } catch (Exception $e) {
            echo "<script>console.log('Error: " . addslashes($e->getMessage()) . "');</script>";
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    // Eliminar producto
    public function eliminarProducto() {
        try {
            if (!isset($_POST['id'])) {
                throw new Exception("ID de producto no proporcionado");
            }

            $id = $_POST['id'];
            
            // Obtener el producto para eliminar la foto si existe
            $producto = $this->modelo->obtenerPorId($id);
            
            if (!$producto) {
                throw new Exception("Producto no encontrado");
            }
            
            if ($this->modelo->eliminar($id)) {
                // Si el producto tenía una foto, eliminarla
                if (!empty($producto['foto'])) {
                    $rutaCompleta = '../../../' . $producto['foto'];
                    if (file_exists($rutaCompleta)) {
                        unlink($rutaCompleta);
                    }
                }
                
                $_SESSION['mensaje'] = "Producto eliminado correctamente";
            } else {
                throw new Exception("No se pudo eliminar el producto");
            }

        } catch (Exception $e) {
            error_log("Error en eliminarProducto: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: ../../../../pages/productos.php');
        exit;
    }

    // Obtener producto por ID
    public function obtenerProductoPorId($id) {
        try {
            return $this->modelo->obtenerPorId($id);
        } catch (Exception $e) {
            error_log("Error en obtenerProductoPorId: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            return null;
        }
    }

    // Editar producto
    public function editarProducto() {
        try {
            // Validar datos
            if (!isset($_POST['id']) || !isset($_POST['nombre']) || !isset($_POST['stock']) || !isset($_POST['precio'])) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
                return;
            }

            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $stock = $_POST['stock'];
            $precio = $_POST['precio'];
            $comentarios = isset($_POST['comentarios']) ? $_POST['comentarios'] : '';
            
            // Obtener el producto actual para verificar si hay una foto existente
            $productoActual = $this->modelo->obtenerPorId($id);
            
            if (!$productoActual) {
                echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
                return;
            }
            
            // Usar la foto actual por defecto
            $rutaFoto = $productoActual['foto'];
            
            // Procesar la nueva foto si se ha subido
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0 && $_FILES['foto']['size'] > 0) {
                $foto = $_FILES['foto'];
                $nombreArchivo = uniqid() . '_' . basename($foto['name']);
                
                // IMPORTANTE: Determinar la ruta base del proyecto
                $rutaBase = realpath(__DIR__ . '/../../../..');
                
                // Ruta relativa para guardar en la base de datos
                $rutaRelativa = 'assets/img/productos/' . $nombreArchivo;
                
                // Ruta absoluta para guardar el archivo
                $rutaAbsoluta = $rutaBase . '/' . $rutaRelativa;
                
                // Asegurarse de que el directorio existe
                $directorioDestino = dirname($rutaAbsoluta);
                if (!is_dir($directorioDestino)) {
                    mkdir($directorioDestino, 0755, true);
                }
                
                // Información de depuración
                echo "<script>console.log('Información de depuración:');</script>";
                echo "<script>console.log('Ruta base del proyecto: " . addslashes($rutaBase) . "');</script>";
                echo "<script>console.log('Ruta relativa: " . addslashes($rutaRelativa) . "');</script>";
                echo "<script>console.log('Ruta absoluta: " . addslashes($rutaAbsoluta) . "');</script>";
                
                if (move_uploaded_file($foto['tmp_name'], $rutaAbsoluta)) {
                    // Si hay una foto anterior, eliminarla
                    if (!empty($rutaFoto)) {
                        $rutaCompletaAnterior = $rutaBase . '/' . $rutaFoto;
                        if (file_exists($rutaCompletaAnterior)) {
                            unlink($rutaCompletaAnterior);
                        }
                    }
                    $rutaFoto = $rutaRelativa;
                    echo "<script>console.log('Imagen subida correctamente a: " . addslashes($rutaFoto) . "');</script>";
                } else {
                    echo "<script>console.log('Error al subir la imagen: " . addslashes(error_get_last()['message']) . "');</script>";
                    echo json_encode(['success' => false, 'message' => 'Error al subir la imagen: ' . error_get_last()['message']]);
                    return;
                }
            }
            
            // Actualizar el producto
            $resultado = $this->modelo->actualizar($id, $nombre, $stock, $rutaFoto, $precio, $comentarios);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Producto actualizado correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el producto']);
            }
        } catch (Exception $e) {
            echo "<script>console.log('Error: " . addslashes($e->getMessage()) . "');</script>";
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}

// Manejo de acciones
if (isset($_GET['accion'])) {
    $controlador = new ProductosControlador();
    
    switch ($_GET['accion']) {
        case 'crear':
            $controlador->crearProducto();
            break;
        case 'editar':
            $controlador->editarProducto();
            break;
        case 'eliminar':
            $controlador->eliminarProducto();
            break;
        case 'obtener':
            echo json_encode($controlador->obtenerTodosLosProductos());
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
            break;
    }
}
?>
