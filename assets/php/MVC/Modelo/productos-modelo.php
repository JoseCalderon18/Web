<?php
require_once __DIR__ . '/../Configuracion/conexion.php';

class ProductosModelo {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    // Obtener total de productos
    public function obtenerTotalProductos() {
        try {
            $sql = "SELECT COUNT(*) as total FROM productos";
            $stmt = $this->db->query($sql);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$resultado['total'];
        } catch (PDOException $e) {
            error_log("Error en obtenerTotalProductos: " . $e->getMessage());
            return 0;
        }
    }

    // Obtener productos paginados
    public function obtenerTodos($inicio, $cantidadPorPagina) {
        try {
            $inicio = (int)$inicio;
            $cantidadPorPagina = (int)$cantidadPorPagina;
            
            $sql = "SELECT * FROM productos ORDER BY id ASC LIMIT :inicio, :cantidadPorPagina";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
            $stmt->bindValue(':cantidadPorPagina', $cantidadPorPagina, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    // Obtener producto por ID
    public function obtenerPorId($id) {
        try {
            $query = "SELECT * FROM productos WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerPorId: " . $e->getMessage());
            return false;
        }
    }

    // Crear nuevo producto
    public function crear($nombre, $stock, $foto, $precio, $fecha_registro, $comentarios, $laboratorio = '') {
        try {
            $query = "INSERT INTO productos (nombre, stock, foto, precio, fecha_registro, comentarios, laboratorio) 
                      VALUES (:nombre, :stock, :foto, :precio, :fecha_registro, :comentarios, :laboratorio)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindParam(':foto', $foto, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_registro', $fecha_registro, PDO::PARAM_STR);
            $stmt->bindParam(':comentarios', $comentarios, PDO::PARAM_STR);
            $stmt->bindParam(':laboratorio', $laboratorio, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en crear: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar producto
    public function actualizar($id, $nombre, $stock, $foto, $precio, $comentarios, $laboratorio = '') {
        try {
            $query = "UPDATE productos 
                      SET nombre = :nombre, 
                          stock = :stock, 
                          foto = :foto, 
                          precio = :precio, 
                          comentarios = :comentarios,
                          laboratorio = :laboratorio 
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindParam(':foto', $foto, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
            $stmt->bindParam(':comentarios', $comentarios, PDO::PARAM_STR);
            $stmt->bindParam(':laboratorio', $laboratorio, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en actualizar: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar producto
    public function eliminar($id) {
        try {
            $query = "DELETE FROM productos WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en eliminar: " . $e->getMessage());
            return false;
        }
    }

    public function agregarFotoProducto($productoId, $rutaFoto) {
        try {
            $sql = "INSERT INTO producto_imagenes (producto_id, ruta_imagen) VALUES (:producto_id, :ruta_imagen)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':producto_id', $productoId);
            $stmt->bindParam(':ruta_imagen', $rutaFoto);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al agregar foto: " . $e->getMessage());
        }
    }

    public function editarProducto($id, $nombre, $stock, $foto, $precio, $fecha_registro, $comentarios) {
        try {
            $sql = "UPDATE productos SET nombre = ?, stock = ?, foto = ?, precio = ?, fecha_registro = ?, comentarios = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombre, $stock, $foto, $precio, $fecha_registro, $comentarios, $id]);
        } catch (PDOException $e) {
            error_log('Error en editarProducto: ' . $e->getMessage());
            return false;
        }
    }

    // Restar una unidad del stock
    public function restarUnidad($id) {
        try {
            $query = "UPDATE productos SET stock = stock - 1 WHERE id = :id AND stock > 0";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en restarUnidad: " . $e->getMessage());
            return false;
        }
    }

    // Sumar una unidad al stock
    public function sumarUnidad($id) {
        try {
            $query = "UPDATE productos SET stock = stock + 1 WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en sumarUnidad: " . $e->getMessage());
            return false;
        }
    }

    public function contarRegistros($sql, $params = []) {
        try {
            // Modificar SQL para contar
            $sqlCount = preg_replace('/SELECT \*/', 'SELECT COUNT(*)', $sql);
            // Remover LIMIT y OFFSET si existen
            $sqlCount = preg_replace('/LIMIT\s+:limit\s+OFFSET\s+:offset/', '', $sqlCount);
            
            $stmt = $this->db->prepare($sqlCount);
            foreach ($params as $param => $value) {
                if ($param !== ':limit' && $param !== ':offset') {
                    $stmt->bindValue($param, $value);
                }
            }
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            // Manejar el error
            return 0;
        }
    }

    public function obtenerProductos($sql, $params = [], $limit = null, $offset = null) {
        try {
            if ($limit !== null && $offset !== null) {
                $sql .= " LIMIT :limit OFFSET :offset";
                $params[':limit'] = $limit;
                $params[':offset'] = $offset;
            }
            
            $stmt = $this->db->prepare($sql);
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerProductos: " . $e->getMessage());
            return [];
        }
    }
}
?>
