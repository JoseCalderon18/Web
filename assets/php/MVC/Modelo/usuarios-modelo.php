<?php
require_once __DIR__ . '/../Configuracion/conexion.php';

class UsuariosModelo {
    private $db;
    private $usuarios;

    public function __construct() {
        // Conexion a la base de datos
        $this->db = Conexion::conectar();
        // Inicializa el array de usuarios
        $this->usuarios = array();
    }

    // Funcion para crear un usuario
    public function crearUsuario($nombre, $email, $password) {
        // Crear el hash de la contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT); 
        // Insertar el usuario en la base de datos
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, email, password_hash) VALUES (?, ?, ?)");
        return $stmt->execute([$nombre, $email, $passwordHash]);
    }

    // Funcion para eliminar un usuario
    public function eliminarUsuario($id) {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Funcion para obtener un usuario por su nombre    
    public function obtenerUsuarioPorNombre($nombre) {
        $stmt = $this->db->prepare("SELECT id, nombre, email FROM usuarios WHERE nombre = ?");
        $stmt->execute([$nombre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Funcion para obtener todos los usuarios
    public function obtenerTodosLosUsuarios() {
        $stmt = $this->db->prepare("SELECT id, nombre, email FROM usuarios");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Funcion para obtener un usuario por su email    
    public function obtenerUsuarioPorEmail($email) {
        $stmt = $this->db->prepare("SELECT id, nombre, email, password_hash FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
