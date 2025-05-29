<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../Modelo/citas-modelo.php';

class CitasControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new CitasModelo();
    }

    // Obtener todas las citas
    public function obtenerTodasLasCitas() {
        try {
            return $this->modelo->obtenerTodasLasCitas();
        } catch (Exception $e) {
            error_log("Error en obtenerTodasLasCitas: " . $e->getMessage());
            return [];
        }
    }

    // Obtener citas de un usuario específico
    public function obtenerCitasUsuario($usuarioId) {
        try {
            return $this->modelo->obtenerCitasUsuario($usuarioId);
        } catch (Exception $e) {
            error_log("Error en obtenerCitasUsuario: " . $e->getMessage());
            return [];
        }
    }

    // Obtener citas para AJAX
    public function obtenerCitas() {
        try {
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['exito' => false, 'mensaje' => 'Usuario no autenticado']);
                return;
            }

            $usuarioId = $_SESSION['usuario_id'];
            $esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
            
            if ($esAdmin) {
                $citas = $this->modelo->obtenerTodasLasCitas();
            } else {
                $citas = $this->modelo->obtenerCitasUsuario($usuarioId);
            }

            echo json_encode([
                'exito' => true,
                'datos' => $citas
            ]);
        } catch (Exception $e) {
            error_log("Error en obtenerCitas: " . $e->getMessage());
            echo json_encode([
                'exito' => false,
                'mensaje' => 'Error al obtener las citas: ' . $e->getMessage()
            ]);
        }
    }

    // Crear cita
    public function crearCita() {
        try {
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['exito' => false, 'mensaje' => 'Usuario no autenticado']);
                return;
            }

            $usuarioId = $_SESSION['usuario_id'];
            $fecha = $_POST['fecha'] ?? '';
            $hora = $_POST['hora'] ?? '';
            $motivo = $_POST['motivo'] ?? '';
            
            if (empty($fecha) || empty($hora) || empty($motivo)) {
                echo json_encode(['exito' => false, 'mensaje' => 'Todos los campos son obligatorios']);
                return;
            }

            $citaId = $this->modelo->crearCita($usuarioId, $fecha, $hora, $motivo);

            echo json_encode([
                'exito' => true,
                'mensaje' => 'Cita creada correctamente',
                'id' => $citaId
            ]);
        } catch (Exception $e) {
            error_log("Error en crearCita: " . $e->getMessage());
            echo json_encode([
                'exito' => false,
                'mensaje' => 'Error al crear la cita: ' . $e->getMessage()
            ]);
        }
    }

    // Crear cita como admin
    public function crearCitaAdmin() {
        try {
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['exito' => false, 'mensaje' => 'Usuario no autenticado']);
                return;
            }

            $esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
            
            if (!$esAdmin) {
                echo json_encode(['exito' => false, 'mensaje' => 'No tienes permisos para esta acción']);
                return;
            }

            $usuarioId = $_POST['usuario_id'] ?? '';
            $fecha = $_POST['fecha'] ?? '';
            $hora = $_POST['hora'] ?? '';
            $motivo = $_POST['motivo'] ?? '';
            
            if (empty($usuarioId) || empty($fecha) || empty($hora) || empty($motivo)) {
                echo json_encode(['exito' => false, 'mensaje' => 'Todos los campos son obligatorios']);
                return;
            }

            $citaId = $this->modelo->crearCita($usuarioId, $fecha, $hora, $motivo);

            echo json_encode([
                'exito' => true,
                'mensaje' => 'Cita creada correctamente',
                'id' => $citaId
            ]);
        } catch (Exception $e) {
            error_log("Error en crearCitaAdmin: " . $e->getMessage());
            echo json_encode([
                'exito' => false,
                'mensaje' => 'Error al crear la cita: ' . $e->getMessage()
            ]);
        }
    }

    // Obtener usuarios
    public function obtenerUsuarios() {
        try {
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['exito' => false, 'mensaje' => 'Usuario no autenticado']);
                return;
            }

            $esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
            
            if (!$esAdmin) {
                echo json_encode(['exito' => false, 'mensaje' => 'No tienes permisos para esta acción']);
                return;
            }

            $usuarios = $this->modelo->obtenerTodosLosUsuarios();

            echo json_encode([
                'exito' => true,
                'datos' => $usuarios
            ]);
        } catch (Exception $e) {
            error_log("Error en obtenerUsuarios: " . $e->getMessage());
            echo json_encode([
                'exito' => false,
                'mensaje' => 'Error al obtener usuarios: ' . $e->getMessage()
            ]);
        }
    }

    // Actualizar estado de cita
    public function actualizarEstado() {
        try {
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['exito' => false, 'mensaje' => 'Usuario no autenticado']);
                return;
            }

            $esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
            
            if (!$esAdmin) {
                echo json_encode(['exito' => false, 'mensaje' => 'No tienes permisos para esta acción']);
                return;
            }

            $citaId = $_POST['id'] ?? '';
            $estado = $_POST['estado'] ?? '';

            if (empty($citaId) || empty($estado)) {
                echo json_encode(['exito' => false, 'mensaje' => 'ID y estado son obligatorios']);
                return;
            }

            $this->modelo->actualizarEstado($citaId, $estado);

            echo json_encode([
                'exito' => true,
                'mensaje' => 'Estado actualizado correctamente'
            ]);
        } catch (Exception $e) {
            error_log("Error en actualizarEstado: " . $e->getMessage());
            echo json_encode([
                'exito' => false,
                'mensaje' => 'Error al actualizar el estado: ' . $e->getMessage()
            ]);
        }
    }
}

// Manejo de acciones
if (isset($_POST['accion'])) {
    header('Content-Type: application/json');
    $controlador = new CitasControlador();
    
    switch ($_POST['accion']) {
        case 'obtenerCitas':
            $controlador->obtenerCitas();
            break;
        case 'crearCita':
            $controlador->crearCita();
            break;
        case 'crearCitaAdmin':
            $controlador->crearCitaAdmin();
            break;
        case 'obtenerUsuarios':
            $controlador->obtenerUsuarios();
            break;
        case 'actualizarEstado':
            $controlador->actualizarEstado();
            break;
        default:
            echo json_encode(['exito' => false, 'mensaje' => 'Acción no válida']);
            break;
    }
}
?> 