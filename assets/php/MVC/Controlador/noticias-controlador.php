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

    // Obtener una noticia por ID
    public function obtenerNoticiaPorId($id) {
        return $this->modelo->obtenerNoticiaPorId($id);
    }

    // Método para la API: obtener noticia
    public function obtenerNoticia() {
        try {
            // Verificar si se proporcionó un ID
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                echo json_encode(['success' => false, 'message' => 'ID de noticia no proporcionado']);
                return;
            }
            
            $id = $_GET['id'];
            $noticia = $this->modelo->obtenerNoticiaPorId($id);
            
            if ($noticia) {
                echo json_encode(['success' => true, 'noticia' => $noticia]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Noticia no encontrada']);
            }
        } catch (Exception $e) {
            error_log("Error en obtenerNoticia: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
        }
    }

    // Crear una nueva noticia
    public function crearNoticia() {
        try {
            // Verificar sesión
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                return;
            }
            
            // Validar datos
            if (!isset($_POST['titulo']) || empty($_POST['titulo']) || 
                !isset($_POST['contenido']) || empty($_POST['contenido']) || 
                !isset($_POST['fecha']) || empty($_POST['fecha'])) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
                return;
            }
            
            $titulo = $_POST['titulo'];
            $contenido = $_POST['contenido'];
            $fecha = $_POST['fecha'];
            $usuario_id = $_SESSION['usuario_id'];
            $imagen_url = '';
            
            // Procesar imagen si existe
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen_url = $this->procesarImagen($_FILES['imagen']);
                
                if ($imagen_url === false) {
                    echo json_encode(['success' => false, 'message' => 'Error al procesar la imagen']);
                    return;
                }
            }
            
            // Crear noticia
            $resultado = $this->modelo->crearNoticia($titulo, $contenido, $imagen_url, $fecha, $usuario_id);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Noticia creada con éxito', 'id' => $resultado]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al crear la noticia']);
            }
        } catch (Exception $e) {
            error_log("Error en crearNoticia: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
        }
    }

    // Actualizar una noticia existente
    public function actualizarNoticia() {
        try {
            // Verificar sesión
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                return;
            }
            
            // Validar datos
            if (!isset($_POST['id']) || empty($_POST['id']) || 
                !isset($_POST['titulo']) || empty($_POST['titulo']) || 
                !isset($_POST['contenido']) || empty($_POST['contenido']) || 
                !isset($_POST['fecha']) || empty($_POST['fecha'])) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
                return;
            }
            
            $id = $_POST['id'];
            $titulo = $_POST['titulo'];
            $contenido = $_POST['contenido'];
            $fecha = $_POST['fecha'];
            $imagen_url = null; // Usar null para mantener la imagen actual
            
            // Procesar imagen si existe una nueva
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen_url = $this->procesarImagen($_FILES['imagen']);
                
                if ($imagen_url === false) {
                    echo json_encode(['success' => false, 'message' => 'Error al procesar la imagen']);
                    return;
                }
            }
            
            // Actualizar noticia
            $resultado = $this->modelo->actualizarNoticia($id, $titulo, $contenido, $imagen_url, $fecha);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Noticia actualizada con éxito']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la noticia']);
            }
        } catch (Exception $e) {
            error_log("Error en actualizarNoticia: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
        }
    }

    // Eliminar una noticia
    public function eliminarNoticia() {
        try {
            // Verificar sesión
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
                return;
            }
            
            // Validar ID
            if (!isset($_POST['id']) || empty($_POST['id'])) {
                echo json_encode(['success' => false, 'message' => 'ID de noticia no proporcionado']);
                return;
            }
            
            $id = $_POST['id'];
            
            // Eliminar noticia
            $resultado = $this->modelo->eliminarNoticia($id);
            
            if ($resultado['success']) {
                echo json_encode(['success' => true, 'message' => 'Noticia eliminada con éxito']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar la noticia']);
            }
        } catch (Exception $e) {
            error_log("Error en eliminarNoticia: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
        }
    }

    // Procesar imagen
    private function procesarImagen($archivo) {
        try {
            $directorio_destino = 'assets/img/noticias/';
            $ruta_completa = $_SERVER['DOCUMENT_ROOT'] . '/' . $directorio_destino;
            
            // Crear directorio si no existe
            if (!file_exists($ruta_completa)) {
                if (!mkdir($ruta_completa, 0777, true)) {
                    error_log("Error al crear el directorio: " . $ruta_completa);
                    return false;
                }
            }
            
            // Validar tipo de archivo
            $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($archivo['type'], $tipos_permitidos)) {
                error_log("Tipo de archivo no permitido: " . $archivo['type']);
                return false;
            }
            
            // Generar nombre único
            $nombre_archivo = uniqid() . '_' . basename($archivo['name']);
            $ruta_archivo = $directorio_destino . $nombre_archivo;
            $ruta_completa_archivo = $_SERVER['DOCUMENT_ROOT'] . '/' . $ruta_archivo;
            
            // Mover archivo
            if (move_uploaded_file($archivo['tmp_name'], $ruta_completa_archivo)) {
                return $ruta_archivo;
            } else {
                error_log("Error al mover el archivo: " . error_get_last()['message']);
                return false;
            }
        } catch (Exception $e) {
            error_log("Error en procesarImagen: " . $e->getMessage());
            return false;
        }
    }
}

// Crear instancia del controlador
$controlador = new NoticiasControlador();

// Si hay una acción en la URL, procesarla
if (isset($_GET['accion'])) {
    $accion = $_GET['accion'];
    
    switch ($accion) {
        case 'crearNoticia':
            $controlador->crearNoticia();
            break;
        case 'actualizarNoticia':
            $controlador->actualizarNoticia();
            break;
        case 'eliminarNoticia':
            $controlador->eliminarNoticia();
            break;
        case 'obtenerNoticia':
            $controlador->obtenerNoticia();
            break;
        // Otras acciones...
    }
}
?>
