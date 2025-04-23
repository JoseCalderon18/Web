<?php
require_once __DIR__ . '/../Modelo/usuarios-modelo.php';

class UsuariosControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new UsuariosModelo();
    }

    // Función para login
    public function login() {
        try {
            // Verificar si se recibieron los datos
            if (!isset($_POST['correo']) || !isset($_POST['contrasenia'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Faltan datos requeridos'
                ]);
                return;
            }

            $email = $_POST['correo'];
            $password = $_POST['contrasenia'];
            
            // Obtener usuario por email
            $usuario = $this->modelo->obtenerUsuarioPorEmail($email);
            
            if (!$usuario) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ]);
                return;
            }

            // Verificar contraseña
            if (password_verify($password, $usuario['password_hash'])) {
                // Si credenciales correctas inicia sesion
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Login exitoso',
                ]);
                return;
            }

            echo json_encode([
                'success' => false,
                'message' => 'Contraseña incorrecta'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ]);
        }
    }
    
    // Función para cerrar sesión
    public function cerrarSesion() {
        try {
            // Iniciar la sesión si no está iniciada
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            // Eliminar todas las variables de sesión
            $_SESSION = array();
            
            // Si se está usando un cookie de sesión, eliminarlo
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            
            // Destruir la sesión
            session_destroy();

            header('Location: ../../../../pages/blog.php');
            
            echo json_encode([
                'success' => true,
                'message' => 'Sesión cerrada correctamente'
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al cerrar sesión: ' . $e->getMessage()
            ]);
        }
    }

    // Función para registrar un usuario
    public function registrarUsuario() {
        try {
            // Verificar si se recibieron los datos
            if (!isset($_POST['usuario']) || !isset($_POST['correo']) || !isset($_POST['contrasenia'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Faltan datos requeridos'
                ]);
                return;
            }

            $nombre = $_POST['usuario'];
            $email = $_POST['correo'];
            $password = $_POST['contrasenia'];

            // Verificar si el usuario ya existe
            $usuarioExistente = $this->modelo->obtenerUsuarioPorNombre($nombre);
            if ($usuarioExistente) {
                echo json_encode([
                    'success' => false,
                    'message' => 'El nombre de usuario ya está registrado'
                ]);
                return;
            }

            // Intentar crear el usuario
            if ($this->modelo->crearUsuario($nombre, $email, $password)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario registrado correctamente'
                ]);
                return;
            }

            echo json_encode([
                'success' => false,
                'message' => 'Error al registrar el usuario'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ]);
        }
    }

    // Funcion para eliminar un usuario
    public function eliminarUsuario($id) {
        try {
            if (empty($id)) {
                throw new Exception("ID de usuario no válido");
            }

            if ($this->modelo->eliminarUsuario($id)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario eliminado correctamente'
                ]);
                return;
            }

            throw new Exception("Error al eliminar el usuario");

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Funcion para obtener un usuario por su nombre
    public function obtenerUsuario($nombre) {
        try {
            $usuario = $this->modelo->obtenerUsuarioPorNombre($nombre);
            
            if ($usuario) {
                echo json_encode([
                    'success' => true,
                    'data' => $usuario
                ]);
                return;
            }

            throw new Exception("Usuario no encontrado");

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Funcion para obtener todos los usuarios
    public function obtenerTodosLosUsuarios() {
        try {
            $usuarios = $this->modelo->obtenerTodosLosUsuarios();
            
            echo json_encode([
                'success' => true,
                'data' => $usuarios
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}

// Manejo de las acciones de los usuarios
header('Content-Type: application/json');
$controlador = new UsuariosControlador();
    
$accion = $_GET['accion'] ?? 'login';

switch ($accion) {
    case 'login':
        $controlador->login();
        break;
    case 'cerrarSesion':
        $controlador->cerrarSesion();
        break;
    case 'registrar':
        $controlador->registrarUsuario();
        break;
    case 'eliminar':
        $controlador->eliminarUsuario($_POST['id']);
        break;
    case 'obtener':
        $controlador->obtenerUsuario($_GET['nombre']);
        break;
    case 'obtenerTodos':
        $controlador->obtenerTodosLosUsuarios();
        break;
}
