<?php
require_once __DIR__ . '/../../config/database.php';

class NoticiasModelo {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    // Obtener todas las noticias con paginación
    public function obtenerNoticias($limite = 10, $offset = 0) {
        try {
            $query = "SELECT n.*, u.nombre as autor_nombre 
                     FROM noticias n 
                     LEFT JOIN usuarios u ON n.usuario_id = u.id 
                     ORDER BY n.fecha_publicacion DESC 
                     LIMIT :limite OFFSET :offset";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Contar total de noticias para paginación
            $queryCount = "SELECT COUNT(*) as total FROM noticias";
            $stmtCount = $this->conn->prepare($queryCount);
            $stmtCount->execute();
            $totalNoticias = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
            
            return [
                'noticias' => $noticias,
                'total' => $totalNoticias
            ];
        } catch (PDOException $e) {
            return [
                'noticias' => [],
                'total' => 0,
                'error' => $e->getMessage()
            ];
        }
    }
    
    // Obtener una noticia por ID
    public function obtenerNoticiaPorId($id) {
        try {
            $query = "SELECT n.*, u.nombre as autor_nombre 
                     FROM noticias n 
                     LEFT JOIN usuarios u ON n.usuario_id = u.id 
                     WHERE n.id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Crear una nueva noticia
    public function crearNoticia($titulo, $contenido, $imagen_url, $fecha_publicacion, $usuario_id) {
        try {
            $query = "INSERT INTO noticias (titulo, contenido, imagen_url, fecha_publicacion, usuario_id) 
                     VALUES (:titulo, :contenido, :imagen_url, :fecha_publicacion, :usuario_id)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':contenido', $contenido, PDO::PARAM_STR);
            $stmt->bindParam(':imagen_url', $imagen_url, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_publicacion', $fecha_publicacion, PDO::PARAM_STR);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Actualizar una noticia existente
    public function actualizarNoticia($id, $titulo, $contenido, $imagen_url = null, $fecha_publicacion) {
        try {
            // Si hay nueva imagen
            if ($imagen_url) {
                $query = "UPDATE noticias 
                         SET titulo = :titulo, 
                             contenido = :contenido, 
                             imagen_url = :imagen_url, 
                             fecha_publicacion = :fecha_publicacion 
                         WHERE id = :id";
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':imagen_url', $imagen_url, PDO::PARAM_STR);
            } else {
                // Si no hay nueva imagen, mantener la existente
                $query = "UPDATE noticias 
                         SET titulo = :titulo, 
                             contenido = :contenido, 
                             fecha_publicacion = :fecha_publicacion 
                         WHERE id = :id";
                
                $stmt = $this->conn->prepare($query);
            }
            
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':contenido', $contenido, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_publicacion', $fecha_publicacion, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Eliminar una noticia
    public function eliminarNoticia($id) {
        try {
            // Primero obtenemos la URL de la imagen para eliminarla del servidor
            $query = "SELECT imagen_url FROM noticias WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $noticia = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Luego eliminamos la noticia de la base de datos
            $query = "DELETE FROM noticias WHERE id = :id";
            $stmt = $this->conn->prepare($query);
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
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
?>