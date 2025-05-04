<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../Modelo/productos-modelo.php';

class ProductosControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new ProductosModelo();
    }

    // Obtener todos los productos
    public function obtenerTodosLosProductos() {
        try {
            return $this->modelo->obtenerTodosLosProductos();
        } catch (Exception $e) {
            return [];
        }
    }

    // Crear producto
    public function crearProducto() {
        try {
            // Validaciones
            if (empty($_POST['nombre']) || empty($_POST['stock']) || empty($_POST['precio']) || empty($_POST['fecha_registro'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Todos los campos son obligatorios'
                ]);
                return;
            }

            // Procesar las fotos
            if (!isset($_FILES['fotos']) || empty($_FILES['fotos']['name'][0])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Debe seleccionar al menos una foto'
                ]);
                return;
            }

            $nombre = $_POST['nombre'];
            $stock = $_POST['stock'];
            $precio = $_POST['precio'];
            $fecha_registro = $_POST['fecha_registro'];
            $comentarios = $_POST['comentarios'] ?? '';
            $fotos = [];

            // Procesar múltiples fotos
            if (isset($_FILES['fotos']) && is_array($_FILES['fotos']['name'])) {
                foreach ($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['fotos']['error'][$key] === UPLOAD_ERR_OK) {
                        $archivo = [
                            'name' => $_FILES['fotos']['name'][$key],
                            'type' => $_FILES['fotos']['type'][$key],
                            'tmp_name' => $tmp_name,
                            'error' => $_FILES['fotos']['error'][$key],
                            'size' => $_FILES['fotos']['size'][$key]
                        ];
                        
                        $rutaFoto = $this->procesarFoto($archivo);
                        $fotos[] = $rutaFoto;
                    }
                }
            }

            if ($this->modelo->crearProducto($nombre, $stock, $fotos, $precio, $fecha_registro, $comentarios)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Producto creado correctamente'
                ]);
            } else {
                throw new Exception("Error al crear el producto");
            }

        } catch (Exception $e) {
            error_log('Error en crearProducto: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error al crear el producto: ' . $e->getMessage()
            ]);
        }
    }

    private function reordenarArchivos($files) {
        $fotos = array();
        foreach ($files as $key => $all) {
            foreach ($all as $i => $val) {
                $fotos[$i][$key] = $val;
            }
        }
        return $fotos;
    }

    // Eliminar producto
    public function eliminarProducto() {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception("ID de producto no proporcionado");
            }

            $id = $_GET['id'];
            
            if ($this->modelo->eliminarProducto($id)) {
                $_SESSION['mensaje'] = "Producto eliminado correctamente";
            } else {
                throw new Exception("No se pudo eliminar el producto");
            }

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: ../../../../pages/productos.php');
        exit;
    }

    // Procesar foto
    private function procesarFoto($archivo) {
        $directorio = "../../../../assets/img/productos/";
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }
        
        $nombreArchivo = uniqid() . "_" . basename($archivo['name']);
        $rutaCompleta = $directorio . $nombreArchivo;

        if (!move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
            throw new Exception("Error al subir la imagen");
        }

        return "assets/img/productos/" . $nombreArchivo;
    }

    // Obtener producto por ID
    public function obtenerProductoPorId($id) {
        try {
            return $this->modelo->obtenerProductoPorId($id);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return null;
        }
    }

    // Editar producto
    public function editarProducto() {
        try {
            // Validaciones básicas
            if (empty($_POST['id']) || empty($_POST['nombre']) || empty($_POST['stock']) || 
                empty($_POST['precio']) || empty($_POST['fecha_registro'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Todos los campos son obligatorios'
                ]);
                return;
            }

            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $stock = $_POST['stock'];
            $precio = $_POST['precio'];
            $fecha_registro = $_POST['fecha_registro'];
            $comentarios = $_POST['comentarios'] ?? '';

            // Obtener el producto actual para las fotos
            $productoActual = $this->modelo->obtenerProductoPorId($id);
            $fotos = []; // Inicializar array vacío

            // Si hay fotos existentes, usarlas
            if (!empty($productoActual['foto'])) {
                // Si foto ya es un array, usarlo directamente
                if (is_array($productoActual['foto'])) {
                    $fotos = $productoActual['foto'];
                } 
                // Si es un string JSON, decodificarlo
                else if (is_string($productoActual['foto'])) {
                    $fotos = json_decode($productoActual['foto'], true) ?? [];
                }
            }

            // Procesar nuevas fotos si se han subido
            if (isset($_FILES['fotos']) && !empty($_FILES['fotos']['name'][0])) {
                foreach ($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['fotos']['error'][$key] === UPLOAD_ERR_OK) {
                        $archivo = [
                            'name' => $_FILES['fotos']['name'][$key],
                            'type' => $_FILES['fotos']['type'][$key],
                            'tmp_name' => $tmp_name,
                            'error' => $_FILES['fotos']['error'][$key],
                            'size' => $_FILES['fotos']['size'][$key]
                        ];
                        
                        $rutaFoto = $this->procesarFoto($archivo);
                        $fotos[] = $rutaFoto;
                    }
                }
            }

            // Actualizar el producto
            if ($this->modelo->editarProducto($id, $nombre, $stock, $fotos, $precio, $fecha_registro, $comentarios)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Producto actualizado correctamente'
                ]);
            } else {
                throw new Exception("Error al actualizar el producto");
            }

        } catch (Exception $e) {
            error_log('Error en editarProducto: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar el producto: ' . $e->getMessage()
            ]);
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
            $controlador->obtenerTodosLosProductos();
            break;
    }
}
?>
