<?php
require_once __DIR__ . '/../Configuracion/conexion.php';

class ProductosModelo {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    // Obtener todos los productos
    public function obtenerTodosLosProductos() {
        try {
            $sql = "SELECT id, nombre, stock, foto, precio, fecha_registro, comentarios 
                    FROM productos ORDER BY id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener productos: " . $e->getMessage());
        }
    }

    // Crear producto
    public function crearProducto($nombre, $stock, $fotos, $precio, $fecha_registro, $comentarios = '') {
        try {
            // Convertir el array de fotos a JSON
            $fotosJson = json_encode($fotos);
            
            $sql = "INSERT INTO productos (nombre, stock, foto, precio, fecha_registro, comentarios) 
                    VALUES (:nombre, :stock, :foto, :precio, :fecha_registro, :comentarios)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':foto', $fotosJson);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':fecha_registro', $fecha_registro);
            $stmt->bindParam(':comentarios', $comentarios);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al crear producto: " . $e->getMessage());
        }
    }

    // Eliminar producto
    public function eliminarProducto($id) {
        try {
            $sql = "DELETE FROM productos WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar producto: " . $e->getMessage());
        }
    }

    // Actualizar producto
    public function actualizarProducto($id, $nombre, $stock, $fotos, $precio, $fecha_registro, $comentarios) {
        try {
            // Convertir el array de fotos a JSON
            $fotosJson = json_encode($fotos);
            
            $sql = "UPDATE productos SET 
                    nombre = :nombre, 
                    stock = :stock, 
                    foto = :foto, 
                    precio = :precio, 
                    fecha_registro = :fecha_registro,
                    comentarios = :comentarios 
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':foto', $fotosJson);
            $stmt->bindParam(':precio', $precio);
            $stmt->bindParam(':fecha_registro', $fecha_registro);
            $stmt->bindParam(':comentarios', $comentarios);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar producto: " . $e->getMessage());
        }
    }

    // Obtener producto por ID
    public function obtenerProductoPorId($id) {
        try {
            $sql = "SELECT id, nombre, stock, foto, precio, fecha_registro, comentarios 
                    FROM productos WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Decodificar el JSON de las fotos si existe
            if ($producto && $producto['foto']) {
                $producto['foto'] = json_decode($producto['foto'], true);
            }
            
            return $producto;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener producto: " . $e->getMessage());
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

    public function editarProducto($id, $nombre, $stock, $fotos, $precio, $fecha_registro, $comentarios) {
        try {
            $fotosJson = json_encode($fotos);
            $sql = "UPDATE productos SET nombre = ?, stock = ?, foto = ?, precio = ?, fecha_registro = ?, comentarios = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombre, $stock, $fotosJson, $precio, $fecha_registro, $comentarios, $id]);
        } catch (PDOException $e) {
            error_log('Error en editarProducto: ' . $e->getMessage());
            return false;
        }
    }
}
?>
