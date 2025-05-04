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

    // Obtener todos los usuarios
    public function obtenerTodosLosUsuarios() {
        try {
            $sql = "SELECT id, nombre, email, rol FROM usuarios";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener usuarios: " . $e->getMessage());
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
    public function crearUsuario($nombre, $email, $password) {
        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $rol = 'usuario'; // Rol por defecto

            $sql = "INSERT INTO usuarios (nombre, email, password_hash, rol) 
                    VALUES (:nombre, :email, :password_hash, :rol)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password_hash', $password_hash);
            $stmt->bindParam(':rol', $rol);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en crearUsuario: ' . $e->getMessage());
            return false;
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
}
