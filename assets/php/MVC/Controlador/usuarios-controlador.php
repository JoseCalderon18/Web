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
                    'message' => 'Login exitoso',
                    'redirect' => '../index.php'
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
            
            // Redirigir a index.php
            header('Location: ../../../../index.php');
            exit();
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al cerrar sesión: ' . $e->getMessage()
            ]);
        }
    }

    // Función para registrar un usuario
    public function registrarUsuario() {
        header('Content-Type: application/json');
        
        try {
            // Debug para ver qué está llegando
            error_log('POST recibido: ' . print_r($_POST, true));

            // Validar datos
            if (!isset($_POST['usuario']) || !isset($_POST['correo']) || !isset($_POST['contrasenia'])) {
                throw new Exception("Faltan datos obligatorios");
            }

            $nombre = trim($_POST['usuario']);
            $email = trim($_POST['correo']);
            $password = $_POST['contrasenia'];

            // Verificar si el correo ya existe
            if ($this->modelo->emailExiste($email)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Este correo electrónico ya está registrado'
                ]);
                return;
            }

            // Siempre asignar rol de usuario
            $rol = 'usuario';

            // Intentar crear el usuario
            if ($this->modelo->crearUsuario($nombre, $email, $password, $rol)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario registrado correctamente',
                    'isAdmin' => isset($_POST['from']) && $_POST['from'] === 'admin'
                ]);
            } else {
                throw new Exception("Error al crear el usuario");
            }

        } catch (Exception $e) {
            error_log("Error en registrarUsuario: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error al registrar el usuario: ' . $e->getMessage()
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
            // Verificar si el usuario es administrador
            if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
                throw new Exception("No tienes permisos para ver esta página");
            }

            $porPagina = 10; // Número de usuarios por página
            $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            $paginaActual = max(1, $paginaActual); // Asegurarse de que no sea menor que 1
            
            // Obtener total de registros
            $totalRegistros = $this->modelo->obtenerTotalUsuarios();
            
            // Calcular total de páginas
            $totalPaginas = max(1, ceil($totalRegistros / $porPagina));
            
            // Asegurar que la página actual no exceda el total de páginas
            $paginaActual = min($paginaActual, $totalPaginas);
            
            // Calcular el inicio para la consulta SQL
            $inicio = ($paginaActual - 1) * $porPagina;
            
            // Obtener los usuarios para la página actual
            $usuarios = $this->modelo->obtenerTodos($inicio, $porPagina);
            
            return [
                'usuarios' => $usuarios,
                'paginaActual' => $paginaActual,
                'totalPaginas' => $totalPaginas,
                'porPagina' => $porPagina,
                'total' => $totalRegistros
            ];
        } catch (Exception $e) {
            error_log("Error en obtenerTodosLosUsuarios: " . $e->getMessage());
            return [
                'usuarios' => [],
                'paginaActual' => 1,
                'totalPaginas' => 1,
                'porPagina' => $porPagina,
                'total' => 0
            ];
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

    public function crearUsuario() {
        try {
            // Debug para ver qué está llegando
            error_log('POST recibido: ' . print_r($_POST, true));

            // Validar datos
            if (!isset($_POST['usuario']) || !isset($_POST['correo']) || !isset($_POST['contrasenia'])) {
                throw new Exception("Faltan datos obligatorios");
            }

            $nombre = trim($_POST['usuario']);
            $email = trim($_POST['correo']);
            $password = $_POST['contrasenia'];

            // Intentar crear el usuario
            if ($this->modelo->crearUsuario($nombre, $email, $password)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario registrado correctamente'
                ]);
                
                // Si la petición viene del panel de administración, redirigir a usuarios.php
                if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin') {
                    header('Location: ../../../../pages/usuarios.php');
                    exit;
                }
            } else {
                throw new Exception("Error al crear el usuario");
            }

        } catch (Exception $e) {
            error_log("Error en registrarUsuario: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error al registrar el usuario: ' . $e->getMessage()
            ]);
        }
    }

    public function buscarUsuarios() {
        try {
            // Verificar si es una petición AJAX
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                
                $termino = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
                
                if (empty($termino)) {
                    throw new Exception("Término de búsqueda vacío");
                }

                $resultados = $this->modelo->buscarUsuarios($termino);
                
                echo json_encode([
                    'success' => true,
                    'data' => $resultados,
                    'total' => count($resultados)
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
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
        case 'crearUsuario':
            $controlador->crearUsuario();
            break;
        case 'buscar':
            $controlador->buscarUsuarios();
            break;
    }
}
