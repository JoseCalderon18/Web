<?php
require_once __DIR__ . '/../Configuracion/conexion.php';

class NoticiasModelo {
    private $conexion;
    private $noticias;
    
    public function __construct() {
        $this->conexion = Conexion::conectar();
        $this->noticias = array();
    }
    
    public function crearNoticia($titulo, $texto, $foto = null) {
        try {
            $this->conexion->beginTransaction();
            
            $sql = "INSERT INTO noticias (titulo, texto, fecha) 
                    VALUES (:titulo, :texto, NOW())";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':texto', $texto);
            
            $stmt->execute();
            $noticia_id = $this->conexion->lastInsertId();
            
            if ($foto && $foto['error'] === UPLOAD_ERR_OK) {
                $directorio = __DIR__ . "/../../../../uploads/noticias/";
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777, true);
                }
                
                $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
                $nombre_archivo = uniqid() . '.' . $extension;
                $ruta_destino = $directorio . $nombre_archivo;
                
                if (move_uploaded_file($foto['tmp_name'], $ruta_destino)) {
                    $sql_update = "UPDATE noticias SET foto = :foto WHERE id = :noticia_id";
                    $stmt_update = $this->conexion->prepare($sql_update);
                    $stmt_update->execute([
                        ':foto' => "uploads/noticias/" . $nombre_archivo,
                        ':noticia_id' => $noticia_id
                    ]);
                }
            }
            
            $this->conexion->commit();
            
            return [
                'success' => true,
                'message' => 'Noticia creada correctamente',
                'noticia_id' => $noticia_id
            ];
            
        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }
    
    public function obtenerNoticias($limite = 10, $offset = 0) {
        try {
            $sql = "SELECT * FROM noticias 
                    ORDER BY fecha DESC 
                    LIMIT :limite OFFSET :offset";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'success' => true,
                'noticias' => $noticias
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error al obtener las noticias: ' . $e->getMessage()
            ];
        }
    }
    
    public function obtenerNoticiaPorId($id) {
        try {
            $sql = "SELECT * FROM noticias WHERE id = :id";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $noticia = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($noticia) {
                return [
                    'success' => true,
                    'noticia' => $noticia
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Noticia no encontrada'
                ];
            }
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error al obtener la noticia: ' . $e->getMessage()
            ];
        }
    }
    
    public function actualizarNoticia($id, $titulo, $texto, $foto = null) {
        try {
            $this->conexion->beginTransaction();
            
            $sql = "UPDATE noticias 
                    SET titulo = :titulo, texto = :texto 
                    WHERE id = :id";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':texto', $texto);
            
            $stmt->execute();
            
            if ($foto && $foto['error'] === UPLOAD_ERR_OK) {
                // Eliminar foto anterior si existe
                $sql_select = "SELECT foto FROM noticias WHERE id = :id";
                $stmt_select = $this->conexion->prepare($sql_select);
                $stmt_select->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt_select->execute();
                $noticia_actual = $stmt_select->fetch(PDO::FETCH_ASSOC);
                
                if ($noticia_actual && $noticia_actual['foto']) {
                    $ruta_foto_anterior = "../" . $noticia_actual['foto'];
                    if (file_exists($ruta_foto_anterior)) {
                        unlink($ruta_foto_anterior);
                    }
                }
                
                // Guardar nueva foto
                $directorio = __DIR__ . "/../../../../uploads/noticias/";
                $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
                $nombre_archivo = uniqid() . '.' . $extension;
                $ruta_destino = $directorio . $nombre_archivo;
                
                if (move_uploaded_file($foto['tmp_name'], $ruta_destino)) {
                    $sql_update = "UPDATE noticias SET foto = :foto WHERE id = :id";
                    $stmt_update = $this->conexion->prepare($sql_update);
                    $stmt_update->execute([
                        ':foto' => "uploads/noticias/" . $nombre_archivo,
                        ':id' => $id
                    ]);
                }
            }
            
            $this->conexion->commit();
            
            return [
                'success' => true,
                'message' => 'Noticia actualizada correctamente'
            ];
            
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            return [
                'success' => false,
                'message' => 'Error al actualizar la noticia: ' . $e->getMessage()
            ];
        }
    }
    
    public function eliminarNoticia($id) {
        try {
            $this->conexion->beginTransaction();
            
            // Obtener información de la noticia para eliminar archivos
            $sql_select = "SELECT foto FROM noticias WHERE id = :id";
            $stmt_select = $this->conexion->prepare($sql_select);
            $stmt_select->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_select->execute();
            
            $noticia = $stmt_select->fetch(PDO::FETCH_ASSOC);
            
            // Eliminar la noticia de la base de datos
            $sql_delete = "DELETE FROM noticias WHERE id = :id";
            $stmt_delete = $this->conexion->prepare($sql_delete);
            $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_delete->execute();
            
            // Eliminar archivo físico si existe
            if ($noticia && !empty($noticia['foto'])) {
                $ruta_completa = "../" . $noticia['foto'];
                if (file_exists($ruta_completa)) {
                    unlink($ruta_completa);
                }
            }
            
            $this->conexion->commit();
            
            return [
                'success' => true,
                'message' => 'Noticia eliminada correctamente'
            ];
            
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            return [
                'success' => false,
                'message' => 'Error al eliminar la noticia: ' . $e->getMessage()
            ];
        }
    }
}
?>