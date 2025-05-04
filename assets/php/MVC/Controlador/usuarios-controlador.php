<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../Modelo/usuarios-modelo.php';

class UsuariosControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new UsuariosModelo();
    }

    // Función para login
    public function login() {
        try {
            $correo = $_POST['correo'] ?? '';
            $contrasenia = $_POST['contrasenia'] ?? '';

            if (empty($correo) || empty($contrasenia)) {
                throw new Exception("Faltan datos requeridos");
            }

            $usuario = $this->modelo->verificarUsuario($correo, $contrasenia);
            
            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Inicio de sesión exitoso'
                ]);
            } else {
                throw new Exception("Credenciales incorrectas");
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    // Función para cerrar sesión
    public function cerrarSesion() {
        try {
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
    public function eliminar() {
        try {
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception("ID de usuario no proporcionado");
            }

            if ($this->modelo->eliminarUsuario($id)) {
                $_SESSION['mensaje'] = "Usuario eliminado correctamente";
                header('Location: ../../../../pages/usuarios.php');
                exit;
            } else {
                throw new Exception("No se pudo eliminar el usuario");
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ../../../../pages/usuarios.php');
            exit;
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
            return $usuarios; // Retorna directamente el array de usuarios
        } catch (Exception $e) {
            return []; // Retorna array vacío en caso de error
        }
    }

    public function cambiarRol() {
        try {
            if (!isset($_GET['id']) || !isset($_GET['rol'])) {
                throw new Exception("Faltan parámetros necesarios");
            }

            $id = $_GET['id'];
            $nuevoRol = $_GET['rol'];

            if ($this->modelo->cambiarRol($id, $nuevoRol)) {
                $_SESSION['mensaje'] = "Rol actualizado correctamente";
            } else {
                throw new Exception("No se pudo actualizar el rol");
            }

            header('Location: ../../../../pages/usuarios.php');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ../../../../pages/usuarios.php');
            exit;
        }
    }
}

// Solo procesar acciones AJAX
if (isset($_GET['accion'])) {
    $controlador = new UsuariosControlador();
    
    switch ($_GET['accion']) {
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
            $controlador->eliminar();
            break;
        case 'obtener':
            if (isset($_GET['nombre'])) {
                $controlador->obtenerUsuario($_GET['nombre']);
            }
            break;
        case 'obtenerTodos':
            $controlador->obtenerTodosLosUsuarios();
            break;
        case 'cambiarRol':
            $controlador->cambiarRol();
            break;
    }
}
