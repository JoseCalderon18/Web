<?php
require_once __DIR__ . '/../Configuracion/conexion.php';

class ReseniasModelo {
    private $conexion;

    private $resenias;
    
    public function __construct() {
        $this->conexion = Conexion::conectar();
        $this->resenias = array();
    }
    
    public function crearResenia($usuario_id, $puntuacion, $comentario, $fuente = 'interna', $fotos = null) {
        try {
            // Iniciar transacción
            $this->conexion->beginTransaction();
            
            // Insertar la reseña
            $sql = "INSERT INTO resenias (usuario_id, puntuacion, comentario, fuente) 
                    VALUES (:usuario_id, :puntuacion, :comentario, :fuente)";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->bindParam(':puntuacion', $puntuacion);
            $stmt->bindParam(':comentario', $comentario);
            $stmt->bindParam(':fuente', $fuente);
            
            $stmt->execute();
            $resenia_id = $this->conexion->lastInsertId();
            
            // Si hay fotos, guardarlas
            if ($fotos && is_array($fotos['name'])) {
                // Crear directorio para las fotos
                $directorio = __DIR__ . "/../../../../uploads/resenias/" . $resenia_id;
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777, true);
                }
                
                $foto_urls = [];
                
                // Procesar cada foto
                for ($i = 0; $i < count($fotos['name']); $i++) {
                    if ($fotos['error'][$i] === UPLOAD_ERR_OK) {
                        $extension = pathinfo($fotos['name'][$i], PATHINFO_EXTENSION);
                        $nombre_archivo = uniqid() . '.' . $extension;
                        $ruta_destino = $directorio . '/' . $nombre_archivo;
                        
                        if (move_uploaded_file($fotos['tmp_name'][$i], $ruta_destino)) {
                            $foto_urls[] = "uploads/resenias/" . $resenia_id . '/' . $nombre_archivo;
                        }
                    }
                }
                
                // Si se guardaron fotos, actualizar la reseña
                if (!empty($foto_urls)) {
                    $urls_string = implode(';', $foto_urls);
                    $sql_update = "UPDATE resenias SET foto_url = :foto_url WHERE id = :resenia_id";
                    $stmt_update = $this->conexion->prepare($sql_update);
                    $stmt_update->execute([
                        ':foto_url' => $urls_string,
                        ':resenia_id' => $resenia_id
                    ]);
                }
            }
            
            // Confirmar transacción
            $this->conexion->commit();
            
            return [
                'success' => true,
                'message' => 'Reseña creada correctamente',
                'resenia_id' => $resenia_id
            ];
            
        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }
    
    public function obtenerResenias($limite = 10, $offset = 0) {
        try {
            $sql = "SELECT r.*, u.nombre as nombre_usuario 
                    FROM resenias r 
                    LEFT JOIN usuarios u ON r.usuario_id = u.id 
                    ORDER BY r.fecha DESC 
                    LIMIT :limite OFFSET :offset";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $resenias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Procesar las URLs de las fotos
            foreach ($resenias as &$resenia) {
                if (!empty($resenia['foto_url'])) {
                    $resenia['imagenes'] = explode(';', rtrim($resenia['foto_url'], ';'));
                } else {
                    $resenia['imagenes'] = [];
                }
                unset($resenia['foto_url']);
            }
            
            return [
                'success' => true,
                'resenas' => $resenias
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error al obtener las reseñas: ' . $e->getMessage()
            ];
        }
    }
    
    public function obtenerReseniaPorId($id) {
        try {
            $sql = "SELECT r.*, u.nombre as nombre_usuario 
                    FROM resenias r 
                    LEFT JOIN usuarios u ON r.usuario_id = u.id 
                    WHERE r.id = :id";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $resenia = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resenia) {
                // Procesar las URLs de las fotos
                if (!empty($resenia['foto_url'])) {
                    $resenia['imagenes'] = explode(';', rtrim($resenia['foto_url'], ';'));
                } else {
                    $resenia['imagenes'] = [];
                }
                unset($resenia['foto_url']);
                
                return [
                    'success' => true,
                    'resena' => $resenia
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Reseña no encontrada'
                ];
            }
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error al obtener la reseña: ' . $e->getMessage()
            ];
        }
    }
    
    public function actualizarResenia($id, $puntuacion, $comentario) {
        try {
            $sql = "UPDATE resenias 
                    SET puntuacion = :puntuacion, comentario = :comentario 
                    WHERE id = :id";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':puntuacion', $puntuacion);
            $stmt->bindParam(':comentario', $comentario);
            
            $stmt->execute();
            
            return [
                'success' => true,
                'message' => 'Reseña actualizada correctamente'
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error al actualizar la reseña: ' . $e->getMessage()
            ];
        }
    }
    

    public function eliminarResenia($id) {
        try {
            // Iniciar transacción
            $this->conexion->beginTransaction();
            
            // Obtener información de la reseña para eliminar archivos
            $sql_select = "SELECT foto_url FROM resenias WHERE id = :id";
            $stmt_select = $this->conexion->prepare($sql_select);
            $stmt_select->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_select->execute();
            
            $resenia = $stmt_select->fetch(PDO::FETCH_ASSOC);
            
            // Eliminar la reseña de la base de datos
            $sql_delete = "DELETE FROM resenias WHERE id = :id";
            $stmt_delete = $this->conexion->prepare($sql_delete);
            $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_delete->execute();
            
            // Eliminar archivos físicos si existen
            if ($resenia && !empty($resenia['foto_url'])) {
                $fotos = explode(';', rtrim($resenia['foto_url'], ';'));
                foreach ($fotos as $foto) {
                    $ruta_completa = "../" . $foto;
                    if (file_exists($ruta_completa)) {
                        unlink($ruta_completa);
                    }
                }
                
                // Eliminar directorio si está vacío
                $directorio = "../uploads/resenias/" . $id;
                if (file_exists($directorio) && is_dir($directorio)) {
                    rmdir($directorio);
                }
            }
            
            // Confirmar la transacción
            $this->conexion->commit();
            
            return [
                'success' => true,
                'message' => 'Reseña eliminada correctamente'
            ];
            
        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $this->conexion->rollBack();
            
            return [
                'success' => false,
                'message' => 'Error al eliminar la reseña: ' . $e->getMessage()
            ];
        }
    }
    
    public function obtenerPromedioPuntuaciones() {
        try {
            $sql = "SELECT AVG(puntuacion) as promedio, COUNT(*) as total 
                    FROM resenias";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'success' => true,
                'promedio' => round($resultado['promedio'], 1),
                'total' => $resultado['total']
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error al obtener el promedio: ' . $e->getMessage()
            ];
        }
    }
}
?>
