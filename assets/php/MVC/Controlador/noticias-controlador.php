<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir el modelo
require_once __DIR__ . '/../Modelo/noticias-modelo.php';

class NoticiasControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new NoticiasModelo();
    }

    // Obtener todas las noticias
    public function obtenerNoticias($limite = 10, $offset = 0) {
        return $this->modelo->obtenerNoticias($limite, $offset);
    }

    // Obtener una noticia por su ID
    public function obtenerNoticiaPorId($id) {
        return $this->modelo->obtenerNoticiaPorId($id);
    }

    // Crear una nueva noticia
    public function crearNoticia() {
        // Verificar si se enviaron los datos del formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar datos
            if (empty($_POST['titulo']) || empty($_POST['contenido']) || empty($_POST['fecha'])) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
                return;
            }

            // Verificar si se subió una imagen
            if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['success' => false, 'message' => 'Debes subir una imagen']);
                return;
            }

            // Procesar la imagen
            $imagen_url = $this->procesarImagen($_FILES['imagen']);
            if (!$imagen_url) {
                echo json_encode(['success' => false, 'message' => 'Error al procesar la imagen']);
                return;
            }

            // Obtener datos del formulario
            $titulo = $_POST['titulo'];
            $contenido = $_POST['contenido'];
            $fecha = $_POST['fecha'];
            $usuario_id = $_SESSION['usuario_id'];

            // Crear la noticia
            $resultado = $this->modelo->crearNoticia($titulo, $contenido, $imagen_url, $fecha, $usuario_id);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Noticia creada correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al crear la noticia']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }

    // Actualizar una noticia existente
    public function actualizarNoticia() {
        // Verificar si se enviaron los datos del formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar datos
            if (empty($_POST['id']) || empty($_POST['titulo']) || empty($_POST['contenido']) || empty($_POST['fecha'])) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
                return;
            }

            // Obtener datos del formulario
            $id = $_POST['id'];
            $titulo = $_POST['titulo'];
            $contenido = $_POST['contenido'];
            $fecha = $_POST['fecha'];
            $imagen_actual = isset($_POST['imagen_actual']) ? $_POST['imagen_actual'] : null;
            
            // Verificar si se subió una nueva imagen
            $imagen_url = null;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen_url = $this->procesarImagen($_FILES['imagen']);
                if (!$imagen_url) {
                    echo json_encode(['success' => false, 'message' => 'Error al procesar la imagen']);
                    return;
                }
                
                // Eliminar la imagen anterior si existe
                if ($imagen_actual) {
                    $ruta_imagen = $_SERVER['DOCUMENT_ROOT'] . '/' . $imagen_actual;
                    if (file_exists($ruta_imagen)) {
                        unlink($ruta_imagen);
                    }
                }
            }

            // Actualizar la noticia
            $resultado = $this->modelo->actualizarNoticia($id, $titulo, $contenido, $imagen_url, $fecha);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Noticia actualizada correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la noticia']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }

    // Eliminar una noticia
    public function eliminarNoticia() {
        // Verificar si se enviaron los datos
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            
            // Obtener la noticia para conseguir la URL de la imagen
            $noticia = $this->modelo->obtenerNoticiaPorId($id);
            
            // Eliminar la noticia
            $resultado = $this->modelo->eliminarNoticia($id);
            
            if ($resultado['success']) {
                // Eliminar la imagen si existe
                if (!empty($noticia['imagen_url'])) {
                    $ruta_imagen = $_SERVER['DOCUMENT_ROOT'] . '/' . $noticia['imagen_url'];
                    if (file_exists($ruta_imagen)) {
                        unlink($ruta_imagen);
                    }
                }
                
                echo json_encode(['success' => true, 'message' => 'Noticia eliminada correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar la noticia']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }

    // Obtener una noticia por AJAX
    public function obtenerNoticia() {
        if (isset($_GET['id'])) {
            $noticia = $this->obtenerNoticiaPorId($_GET['id']);
            if ($noticia) {
                echo json_encode(['success' => true, 'noticia' => $noticia]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Noticia no encontrada']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
        }
    }

    // Procesar imagen subida
    private function procesarImagen($archivo) {
        $directorio_destino = $_SERVER['DOCUMENT_ROOT'] . '/assets/img/noticias/';
        
        // Crear directorio si no existe
        if (!file_exists($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }
        
        // Generar nombre único para la imagen
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombre_archivo = uniqid('noticia_') . '.' . $extension;
        $ruta_completa = $directorio_destino . $nombre_archivo;
        
        // Mover archivo
        if (move_uploaded_file($archivo['tmp_name'], $ruta_completa)) {
            return 'assets/img/noticias/' . $nombre_archivo;
        } else {
            return false;
        }
    }
}

// Verificar la acción solicitada
if (isset($_GET['accion'])) {
    $controlador = new NoticiasControlador();
    $accion = $_GET['accion'];
    
    switch ($accion) {
        case 'obtenerNoticia':
            $controlador->obtenerNoticia();
            break;
            
        case 'crearNoticia':
            $controlador->crearNoticia();
            break;
            
        case 'actualizarNoticia':
            $controlador->actualizarNoticia();
            break;
            
        case 'eliminarNoticia':
            $controlador->eliminarNoticia();
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
            break;
    }
} else {
    // Si no hay acción, no hacemos nada
    // Esto permite incluir el controlador en otros archivos sin ejecutar código adicional
}
?>
