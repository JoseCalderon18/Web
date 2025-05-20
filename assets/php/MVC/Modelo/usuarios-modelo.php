<?php
require_once __DIR__ . '/../Configuracion/conexion.php';

class UsuariosModelo {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    // Verificar usuario para login
    public function verificarUsuario($email, $password) {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            
            while ($usuario = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (password_verify($password, $usuario['password_hash'])) {
                    unset($usuario['password_hash']); // No enviar el hash
                    return $usuario;
                }
            }
            
            return false;
        } catch (PDOException $e) {
            throw new Exception("Error al verificar usuario: " . $e->getMessage());
        }
    }

    // Obtener total de usuarios
    public function obtenerTotalUsuarios() {
        try {
            $sql = "SELECT COUNT(*) as total FROM usuarios";
            $stmt = $this->db->query($sql);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$resultado['total'];
        } catch (PDOException $e) {
            error_log("Error en obtenerTotalUsuarios: " . $e->getMessage());
            return 0;
        }
    }

    // Obtener usuarios paginados
    public function obtenerTodos($offset, $limit) {
        try {
            // Asegurarnos de que los parámetros son enteros
            $offset = (int)$offset;
            $limit = (int)$limit;
            
            $sql = "SELECT id, nombre, email, rol FROM usuarios ORDER BY id DESC LIMIT :offset, :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    // Obtener usuario por nombre
    public function obtenerUsuarioPorNombre($nombre) {
        try {
            $sql = "SELECT id, nombre, email, rol FROM usuarios WHERE nombre = :nombre";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener usuario: " . $e->getMessage());
        }
    }

    // Crear usuario
    public function crearUsuario($nombre, $email, $password, $rol = 'usuario') {
        try {
            // Hashear la contraseña
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Preparar la consulta
            $sql = "INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES (:nombre, :email, :password_hash, :rol)";
            $stmt = $this->db->prepare($sql);
            
            // Ejecutar la consulta
            $resultado = $stmt->execute([
                ':nombre' => $nombre,
                ':email' => $email,
                ':password_hash' => $password_hash,
                ':rol' => $rol
            ]);

            if ($resultado) {
                error_log("Usuario creado correctamente: " . $nombre);
                return true;
            } else {
                error_log("Error al crear usuario: " . $nombre);
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error en crearUsuario: " . $e->getMessage());
            throw new Exception("Error al crear el usuario: " . $e->getMessage());
        }
    }

    // Eliminar usuario
    public function eliminarUsuario($id) {
        try {
            $sql = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar usuario: " . $e->getMessage());
        }
    }

    public function cambiarRol($id, $nuevoRol) {
        try {
            // Verificar que el rol sea válido
            if (!in_array($nuevoRol, ['usuario', 'admin'])) {
                throw new Exception("Rol no válido");
            }

            $sql = "UPDATE usuarios SET rol = :rol WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':rol', $nuevoRol);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error al cambiar el rol: " . $e->getMessage());
        }
    }

    public function emailExiste($email) {
        try {
            $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            throw new Exception("Error al verificar el email: " . $e->getMessage());
        }
    }

    public function obtenerClientes() {
        try {
            $sql = "SELECT id, nombre, email FROM usuarios WHERE rol = 'cliente'";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener los clientes: " . $e->getMessage());
        }
    }
}
