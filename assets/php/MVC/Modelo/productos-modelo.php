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
    public function crear($nombre, $stock, $foto, $precio, $fecha_registro, $comentarios) {
        try {
            $query = "INSERT INTO productos (nombre, stock, foto, precio, fecha_registro, comentarios) 
                      VALUES (:nombre, :stock, :foto, :precio, :fecha_registro, :comentarios)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindParam(':foto', $foto, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_registro', $fecha_registro, PDO::PARAM_STR);
            $stmt->bindParam(':comentarios', $comentarios, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en crear: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar producto
    public function actualizar($id, $nombre, $stock, $foto, $precio, $comentarios) {
        try {
            $query = "UPDATE productos 
                      SET nombre = :nombre, 
                          stock = :stock, 
                          foto = :foto, 
                          precio = :precio, 
                          comentarios = :comentarios 
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindParam(':foto', $foto, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
            $stmt->bindParam(':comentarios', $comentarios, PDO::PARAM_STR);
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
}
?>
