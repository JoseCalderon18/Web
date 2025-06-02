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
            
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);
            return $producto ? $producto : null;
        } catch (PDOException $e) {
            error_log("Error en obtenerPorId: " . $e->getMessage());
            return null;
        }
    }

    // Crear nuevo producto
    public function crear($datos) {
        try {
            $sql = "INSERT INTO productos (nombre, stock, precio, laboratorio, comentarios, foto) 
                    VALUES (:nombre, :stock, :precio, :laboratorio, :comentarios, :foto)";
            
            $stmt = $this->db->prepare($sql);
            
            $resultado = $stmt->execute([
                ':nombre' => $datos['nombre'],
                ':stock' => $datos['stock'],
                ':precio' => $datos['precio'],
                ':laboratorio' => $datos['laboratorio'],
                ':comentarios' => $datos['comentarios'],
                ':foto' => $datos['foto']
            ]);
            
            return $resultado;
            
        } catch (PDOException $e) {
            error_log("Error en crear producto: " . $e->getMessage());
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


    public function actualizarStock($id, $nuevoStock) {
        try {
            $sql = "UPDATE productos SET stock = :stock WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':stock', $nuevoStock, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en actualizarStock: " . $e->getMessage());
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

    public function buscarProductos($termino) {
        try {
            $sql = "SELECT * FROM productos 
                    WHERE LOWER(nombre) LIKE LOWER(:termino) 
                    OR LOWER(laboratorio) LIKE LOWER(:termino)
                    OR LOWER(comentarios) LIKE LOWER(:termino)";
            
            $termino = "%$termino%";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':termino', $termino, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en buscarProductos: " . $e->getMessage());
            return [];
        }
    }
}
?>
