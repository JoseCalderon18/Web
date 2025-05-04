<?php
// Iniciar sesión
session_start();

// Incluir el modelo
require_once __DIR__ . '/../Modelo/resenias-modelo.php';

class ReseniasControlador {

    private $resenias;

    public function __construct() {
        $this->resenias = new ReseniasModelo();
    }

    // Funcion para crear una reseña
    public function crearResenia() {
        try {
            // Obtener datos del formulario
            $usuario_id = $_SESSION['usuario_id'];
            $puntuacion = isset($_POST['rating']) ? floatval($_POST['rating']) : 0;
            $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';
            
            // Validar datos
            if ($puntuacion < 0.5 || $puntuacion > 5) {
                echo json_encode([
                    'success' => false,
                    'message' => 'La puntuación debe estar entre 0.5 y 5'
                ]);
                exit;
            }
            
            if (empty($comentario)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'El comentario no puede estar vacío'
                ]);
                exit;
            }
            
            // Procesar fotos si existen
            $fotos = isset($_FILES['fotos']) ? $_FILES['fotos'] : null;
            
            // Crear la reseña
            $resenia = $this->resenias->crearResenia($usuario_id, $puntuacion, $comentario, 'interna', $fotos);
            
            // Devolver el resultado
            echo json_encode($resenia);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al crear la reseña: ' . $e->getMessage()
            ]);
        }
    }   

    // Funcion para listar las reseñas
    public function listarResenias() {
        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        
        $resultado = $this->resenias->obtenerResenias($limite, $offset);
        
        echo json_encode($resultado);
    }
    
    // Función para obtener una reseña por ID
    public function obtenerReseniaPorId($id) {
        $resultado = $this->resenias->obtenerReseniaPorId($id);
        
        echo json_encode($resultado);
    }
    
    // Función para actualizar una reseña
    public function actualizarResenia() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Debes iniciar sesión para actualizar una reseña'
            ]);
            exit;
        }
        
        // Obtener datos
        if (!isset($_POST['id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'ID de reseña no especificado'
            ]);
            exit;
        }
        
        $id = intval($_POST['id']);
        $puntuacion = isset($_POST['rating']) ? floatval($_POST['rating']) : 0;
        $comentario = isset($_POST['comment']) ? trim($_POST['comment']) : '';
        
        // Validar datos
        if ($puntuacion < 0.5 || $puntuacion > 5) {
            echo json_encode([
                'success' => false,
                'message' => 'La puntuación debe estar entre 0.5 y 5'
            ]);
            exit;
        }
        
        if (empty($comentario)) {
            echo json_encode([
                'success' => false,
                'message' => 'El comentario no puede estar vacío'
            ]);
            exit;
        }
        
        // Verificar que la reseña pertenezca al usuario
        $resenia = $this->resenias->obtenerReseniaPorId($id);
        if (!$resenia['success'] || $resenia['resena']['usuario_id'] != $_SESSION['usuario_id']) {
            echo json_encode([
                'success' => false,
                'message' => 'No tienes permiso para editar esta reseña'
            ]);
            exit;
        }
        
        // Actualizar la reseña
        $resultado = $this->resenias->actualizarResenia($id, $puntuacion, $comentario);
        
        echo json_encode($resultado);
    }
    
    // Función para eliminar una reseña
    public function eliminarResenia() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Debes iniciar sesión para eliminar una reseña'
            ]);
            exit;
        }
        
        // Obtener ID
        if (!isset($_POST['id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'ID de reseña no especificado'
            ]);
            exit;
        }
        
        $id = intval($_POST['id']);
        
        // Verificar que la reseña pertenezca al usuario o sea administrador
        $resenia = $this->resenias->obtenerReseniaPorId($id);
        if (!$resenia['success'] || 
            ($resenia['resena']['usuario_id'] != $_SESSION['usuario_id'] && 
             (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'admin'))) {
            echo json_encode([
                'success' => false,
                'message' => 'No tienes permiso para eliminar esta reseña'
            ]);
            exit;
        }
        
        // Eliminar la reseña
        $resultado = $this->resenias->eliminarResenia($id);
        
        echo json_encode($resultado);
    }
    
    // Función para obtener el promedio de puntuaciones
    public function obtenerPromedioPuntuaciones() {
        $resultado = $this->resenias->obtenerPromedioPuntuaciones();
        
        echo json_encode($resultado);
    }
}

// Crear instancia del controlador
$controlador = new ReseniasControlador();

// Verificar la acción solicitada
if (isset($_GET['accion'])) {
    $accion = $_GET['accion'];
    
    // Procesar la acción solicitada
    switch ($accion) {
        case 'crearResenia':
            $controlador->crearResenia();
            break;
            
        case 'listarResenias':
            $controlador->listarResenias();
            break;
            
        case 'obtenerResenia':
            if (!isset($_GET['id'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID de reseña no especificado'
                ]);
                exit;
            }
            
            $id = intval($_GET['id']);
            $controlador->obtenerReseniaPorId($id);
            break;
            
        case 'actualizarResenia':
            $controlador->actualizarResenia();
            break;
            
        case 'eliminarResenia':
            $controlador->eliminarResenia();
            break;
            
        case 'obtenerPromedio':
            $controlador->obtenerPromedioPuntuaciones();
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Acción no válida'
            ]);
            break;
    }
}
?>
