<?php
require_once __DIR__ . '/../Configuracion/conexion.php';

class NoticiasModelo {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }
    
    // Obtener todas las noticias con paginación
    public function obtenerNoticias($limite = 10, $offset = 0) {
        try {
            $query = "SELECT n.*, u.nombre as autor_nombre 
                     FROM noticias n 
                     LEFT JOIN usuarios u ON n.usuario_id = u.id 
                     ORDER BY n.fecha_publicacion DESC 
                     LIMIT :limite OFFSET :offset";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Contar total de noticias para paginación
            $queryCount = "SELECT COUNT(*) as total FROM noticias";
            $stmtCount = $this->db->prepare($queryCount);
            $stmtCount->execute();
            $totalNoticias = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
            
            return [
                'noticias' => $noticias,
                'total' => $totalNoticias
            ];
        } catch (PDOException $e) {
            error_log("Error en obtenerNoticias: " . $e->getMessage());
            return [
                'noticias' => [],
                'total' => 0
            ];
        }
    }
    
    // Obtener una noticia por su ID
    public function obtenerNoticiaPorId($id) {
        try {
            $query = "SELECT n.*, u.nombre as autor_nombre 
                     FROM noticias n 
                     LEFT JOIN usuarios u ON n.usuario_id = u.id 
                     WHERE n.id = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerNoticiaPorId: " . $e->getMessage());
            return false;
        }
    }
    
    // Crear una nueva noticia
    public function crearNoticia($titulo, $contenido, $imagen_url, $fecha_publicacion, $usuario_id) {
        try {
            $query = "INSERT INTO noticias (titulo, contenido, imagen_url, fecha_publicacion, usuario_id) 
                     VALUES (:titulo, :contenido, :imagen_url, :fecha_publicacion, :usuario_id)";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':contenido', $contenido, PDO::PARAM_STR);
            $stmt->bindParam(':imagen_url', $imagen_url, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_publicacion', $fecha_publicacion, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error en crearNoticia: " . $e->getMessage());
            return false;
        }
    }
    
    // Actualizar una noticia existente
    public function actualizarNoticia($id, $titulo, $contenido, $imagen_url, $fecha_publicacion) {
        try {
            // Si se proporciona una nueva imagen
            if ($imagen_url) {
                $query = "UPDATE noticias 
                         SET titulo = :titulo, 
                             contenido = :contenido, 
                             imagen_url = :imagen_url, 
                             fecha_publicacion = :fecha_publicacion 
                         WHERE id = :id";
                
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':imagen_url', $imagen_url, PDO::PARAM_STR);
            } else {
                // Si no se proporciona una nueva imagen, mantener la actual
                $query = "UPDATE noticias 
                         SET titulo = :titulo, 
                             contenido = :contenido, 
                             fecha_publicacion = :fecha_publicacion 
                         WHERE id = :id";
                
                $stmt = $this->db->prepare($query);
            }
            
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':contenido', $contenido, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_publicacion', $fecha_publicacion, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en actualizarNoticia: " . $e->getMessage());
            return false;
        }
    }
    
    // Eliminar una noticia
    public function eliminarNoticia($id) {
        try {
            // Primero obtenemos la URL de la imagen para eliminarla del servidor
            $query = "SELECT imagen_url FROM noticias WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $noticia = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Luego eliminamos la noticia de la base de datos
            $query = "DELETE FROM noticias WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'imagen_url' => $noticia['imagen_url'] ?? null
                ];
            } else {
                return ['success' => false];
            }
        } catch (PDOException $e) {
            error_log("Error en eliminarNoticia: " . $e->getMessage());
            return ['success' => false];
        }
    }
}
?>