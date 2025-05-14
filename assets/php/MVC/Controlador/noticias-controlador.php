<?php
// Iniciar sesión
session_start();

// Incluir el modelo
require_once __DIR__ . '/../Modelo/noticias-modelo.php';

class NoticiasControlador {

    private $noticias;

    public function __construct() {
        $this->noticias = new NoticiasModelo();
    }

    // Función para crear una noticia
    public function crearNoticia() {
        try {
            // Obtener datos del formulario
            $titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
            $texto = isset($_POST['texto']) ? trim($_POST['texto']) : '';
            $foto = isset($_FILES['foto']) ? $_FILES['foto'] : null;
            
            // Validar datos
            if (empty($titulo)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'El título no puede estar vacío'
                ]);
                exit;
            }
            
            if (empty($texto)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'El texto no puede estar vacío'
                ]);
                exit;
            }
            
            // Crear la noticia
            $noticia = $this->noticias->crearNoticia($titulo, $texto, $foto);
            
            // Devolver el resultado
            echo json_encode($noticia);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al crear la noticia: ' . $e->getMessage()
            ]);
        }
    }   

    // Función para listar las noticias
    public function listarNoticias() {
        $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        
        $resultado = $this->noticias->obtenerNoticias($limite, $offset);
        
        echo json_encode($resultado);
    }
    
    // Función para obtener una noticia por ID
    public function obtenerNoticiaPorId($id) {
        $resultado = $this->noticias->obtenerNoticiaPorId($id);
        
        echo json_encode($resultado);
    }
    
    // Función para actualizar una noticia
    public function actualizarNoticia() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Debes iniciar sesión para actualizar una noticia'
            ]);
            exit;
        }
        
        // Obtener datos
        if (!isset($_POST['id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'ID de noticia no especificado'
            ]);
            exit;
        }
        
        $id = intval($_POST['id']);
        $titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
        $texto = isset($_POST['texto']) ? trim($_POST['texto']) : '';
        $foto = isset($_FILES['foto']) ? $_FILES['foto'] : null;
        
        // Validar datos
        if (empty($titulo)) {
            echo json_encode([
                'success' => false,
                'message' => 'El título no puede estar vacío'
            ]);
            exit;
        }
        
        if (empty($texto)) {
            echo json_encode([
                'success' => false,
                'message' => 'El texto no puede estar vacío'
            ]);
            exit;
        }
        
        // Verificar que el usuario sea administrador
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'admin') {
            echo json_encode([
                'success' => false,
                'message' => 'No tienes permiso para editar noticias'
            ]);
            exit;
        }
        
        // Actualizar la noticia
        $resultado = $this->noticias->actualizarNoticia($id, $titulo, $texto, $foto);
        
        echo json_encode($resultado);
    }
    
    // Función para eliminar una noticia
    public function eliminarNoticia() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Debes iniciar sesión para eliminar una noticia'
            ]);
            exit;
        }
        
        // Obtener ID
        if (!isset($_POST['id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'ID de noticia no especificado'
            ]);
            exit;
        }
        
        $id = intval($_POST['id']);
        
        // Verificar que el usuario sea administrador
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 'admin') {
            echo json_encode([
                'success' => false,
                'message' => 'No tienes permiso para eliminar noticias'
            ]);
            exit;
        }
        
        // Eliminar la noticia
        $resultado = $this->noticias->eliminarNoticia($id);
        
        echo json_encode($resultado);
    }
}

// Crear instancia del controlador
$controlador = new NoticiasControlador();

// Verificar la acción solicitada
if (isset($_GET['accion'])) {
    $accion = $_GET['accion'];
    
    // Procesar la acción solicitada
    switch ($accion) {
        case 'crearNoticia':
            $controlador->crearNoticia();
            break;
            
        case 'listarNoticias':
            $controlador->listarNoticias();
            break;
            
        case 'obtenerNoticia':
            if (!isset($_GET['id'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID de noticia no especificado'
                ]);
                exit;
            }
            
            $id = intval($_GET['id']);
            $controlador->obtenerNoticiaPorId($id);
            break;
            
        case 'actualizarNoticia':
            $controlador->actualizarNoticia();
            break;
            
        case 'eliminarNoticia':
            $controlador->eliminarNoticia();
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
