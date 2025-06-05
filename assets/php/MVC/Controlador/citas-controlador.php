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

    // Obtener citas para AJAX
    public function obtenerCitas() {
        try {
            $idUsuario = $_SESSION['usuario_id'] ?? null;
            $rolUsuario = $_SESSION['usuario_rol'] ?? 'usuario';
            
            if ($rolUsuario === 'admin') {
                $citas = $this->modelo->obtenerTodasLasCitas();
            } else {
                $citas = $this->modelo->obtenerCitasUsuario($idUsuario);
            }
            
            echo json_encode([
                'exito' => true,
                'datos' => $citas
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'exito' => false,
                'mensaje' => 'Error al obtener las citas: ' . $e->getMessage()
            ]);
        }
    }

    // Crear cita básica (para usuarios normales)
    public function crearCita() {
        try {
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception('Usuario no autenticado');
            }
            
            $idUsuario = $_SESSION['usuario_id'];
            $nombreCliente = $_POST['nombre_cliente'] ?? null;
            $fecha = $_POST['fecha'] ?? null;
            $hora = $_POST['hora'] ?? null;
            $motivo = $_POST['motivo'] ?? null;
            
            if (!$nombreCliente || !$fecha || !$hora || !$motivo) {
                throw new Exception('Faltan datos requeridos');
            }
            
            $resultado = $this->modelo->crearCita($idUsuario, $fecha, $hora, $motivo, $nombreCliente);
            
            if ($resultado) {
                echo json_encode(['exito' => true, 'mensaje' => 'Cita creada con éxito']);
            } else {
                throw new Exception('Error al crear la cita');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'exito' => false,
                'mensaje' => $e->getMessage()
            ]);
        }
    }

    // Crear cita con nombre del cliente (para admins)
    public function crearCitaConNombre() {
        try {
            error_log("=== DEBUG crearCitaConNombre INICIO ===");
            
            // Limpiar cualquier output buffer previo
            if (ob_get_length()) {
                ob_clean();
            }
            
            if (!isset($_SESSION['usuario_id'])) {
                error_log("Usuario no autenticado");
                echo json_encode(['exito' => false, 'mensaje' => 'Usuario no autenticado']);
                exit;
            }

            // Solo admin puede crear citas para otros usuarios
            $esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
            error_log("Es admin: " . ($esAdmin ? 'Sí' : 'No'));
            
            if (!$esAdmin) {
                error_log("No tiene permisos de admin");
                echo json_encode(['exito' => false, 'mensaje' => 'No tienes permisos para crear citas para otros usuarios']);
                exit;
            }

            $nombreCliente = trim($_POST['nombre_cliente'] ?? '');
            $fecha = $_POST['fecha'] ?? '';
            $hora = $_POST['hora'] ?? '';
            $motivo = trim($_POST['motivo'] ?? '');
            
            error_log("=== VALORES RECIBIDOS ===");
            error_log("nombre_cliente: " . $nombreCliente);
            error_log("motivo: " . $motivo);
            error_log("fecha: " . $fecha);
            error_log("hora: " . $hora);
            
            if (empty($nombreCliente) || empty($fecha) || empty($hora) || empty($motivo)) {
                error_log("Campos vacíos detectados");
                echo json_encode(['exito' => false, 'mensaje' => 'Todos los campos son obligatorios']);
                exit;
            }

            // Validar que la fecha no sea pasada
            $fechaSeleccionada = new DateTime($fecha);
            $fechaHoy = new DateTime();
            $fechaHoy->setTime(0, 0, 0);
            
            if ($fechaSeleccionada < $fechaHoy) {
                error_log("Fecha pasada detectada");
                echo json_encode(['exito' => false, 'mensaje' => 'No se pueden crear citas en fechas pasadas']);
                exit;
            }

            $usuarioId = $_SESSION['usuario_id'];
            
            // Verificar disponibilidad
            if (!$this->modelo->verificarDisponibilidad($fecha, $hora)) {
                echo json_encode(['exito' => false, 'mensaje' => 'El horario no está disponible']);
                exit;
            }
            
            $resultado = $this->modelo->crearCita($usuarioId, $fecha, $hora, $motivo, $nombreCliente);
            error_log("Resultado de crear cita: " . ($resultado ? 'true' : 'false'));
            
            if ($resultado) {
                echo json_encode(['exito' => true, 'mensaje' => 'Cita creada exitosamente']);
            } else {
                echo json_encode(['exito' => false, 'mensaje' => 'Error al crear la cita']);
            }
            
        } catch (Exception $e) {
            error_log("Error en crearCitaConNombre: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    // Alias para compatibilidad
    public function crearCitaAdmin() {
        $this->crearCitaConNombre();
    }

    // Obtener usuarios (para admins)
    public function obtenerUsuarios() {
        try {
            // Limpiar cualquier output buffer previo
            if (ob_get_length()) {
                ob_clean();
            }
            
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['exito' => false, 'mensaje' => 'Usuario no autenticado']);
                exit;
            }

            $esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
            
            if (!$esAdmin) {
                echo json_encode(['exito' => false, 'mensaje' => 'No tienes permisos para ver usuarios']);
                exit;
            }

            $usuarios = $this->modelo->obtenerTodosLosUsuarios();
            
            echo json_encode([
                'exito' => true,
                'datos' => $usuarios
            ]);
            
        } catch (Exception $e) {
            error_log("Error en obtenerUsuarios: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    // Actualizar estado de cita
    public function actualizarEstado() {
        try {
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception('Usuario no autenticado');
            }
            
            $idCita = $_POST['id'] ?? null;
            $nuevoEstado = $_POST['estado'] ?? null;
            
            if (!$idCita || !$nuevoEstado) {
                throw new Exception('Faltan datos requeridos');
            }
            
            $resultado = $this->modelo->actualizarEstadoCita($idCita, $nuevoEstado);
            
            if ($resultado) {
                echo json_encode(['exito' => true, 'mensaje' => 'Estado actualizado con éxito']);
            } else {
                throw new Exception('Error al actualizar el estado');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'exito' => false,
                'mensaje' => $e->getMessage()
            ]);
        }
    }

    // Eliminar cita
    public function eliminarCita() {
        try {
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception('Usuario no autenticado');
            }
            
            $idCita = $_POST['id'] ?? null;
            
            if (!$idCita) {
                throw new Exception('ID de cita no proporcionado');
            }
            
            $resultado = $this->modelo->eliminarCita($idCita);
            
            if ($resultado) {
                echo json_encode(['exito' => true, 'mensaje' => 'Cita eliminada con éxito']);
            } else {
                throw new Exception('Error al eliminar la cita');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'exito' => false,
                'mensaje' => $e->getMessage()
            ]);
        }
    }

    // Métodos para obtener citas (sin AJAX)
    public function obtenerTodasLasCitas() {
        return $this->modelo->obtenerTodasLasCitas();
    }

    public function obtenerCitasUsuario($usuarioId) {
        return $this->modelo->obtenerCitasUsuario($usuarioId);
    }
}

// Procesar la acción solicitada
if (isset($_POST['accion'])) {
    $controlador = new CitasControlador();
    $accion = $_POST['accion'];
    
    switch ($accion) {
        case 'obtenerCitas':
            $controlador->obtenerCitas();
            break;
        case 'crearCita':
            $controlador->crearCita();
            break;
        case 'crearCitaConNombre':
            $controlador->crearCitaConNombre();
            break;
        case 'obtenerUsuarios':
            $controlador->obtenerUsuarios();
            break;
        case 'actualizarEstado':
            $controlador->actualizarEstado();
            break;
        case 'eliminar':
            $controlador->eliminarCita();
            break;
        
    }
}
?>