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
            return $this->modelo->obtenerCitasPorUsuario($usuarioId);
        } catch (Exception $e) {
            error_log("Error en obtenerCitasUsuario: " . $e->getMessage());
            return [];
        }
    }

    // Obtener citas para AJAX
    public function obtenerCitas() {
        try {
            // Limpiar cualquier output buffer previo
            if (ob_get_length()) {
                ob_clean();
            }
            
            if (!isset($_SESSION['usuario_id'])) {
                error_log("Usuario no autenticado");
                echo json_encode(['exito' => false, 'mensaje' => 'Usuario no autenticado']);
                exit;
            }

            // Primero actualizamos las citas vencidas automáticamente
            $this->actualizarCitasVencidas();
            
            // Cualquier usuario logueado puede ver citas
            $esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
            
            error_log("Rol del usuario: " . ($_SESSION['usuario_rol'] ?? 'sin rol'));
            error_log("Es admin: " . ($esAdmin ? 'Sí' : 'No'));
            
            if ($esAdmin) {
                // Admin ve todas las citas
                $citas = $this->modelo->obtenerTodasLasCitas();
            } else {
                // Usuario normal ve solo sus citas
                $usuarioId = $_SESSION['usuario_id'];
                $citas = $this->modelo->obtenerCitasPorUsuario($usuarioId);
            }
            
            error_log("Total de citas obtenidas: " . count($citas));
            
            echo json_encode([
                'exito' => true,
                'datos' => $citas
            ]);
            
        } catch (Exception $e) {
            error_log("Excepción en obtenerCitas: " . $e->getMessage());
            echo json_encode([
                'exito' => false,
                'mensaje' => 'Error al obtener las citas: ' . $e->getMessage()
            ]);
        }
    }

    // Nuevo método para actualizar citas vencidas automáticamente
    private function actualizarCitasVencidas() {
        try {
            // Obtener fecha y hora actual
            $fechaActual = date('Y-m-d');
            $horaActual = date('H:i:s');
            
            // Actualizar citas que ya pasaron y están pendientes o confirmadas
            $resultado = $this->modelo->marcarCitasVencidasComoCompletadas($fechaActual, $horaActual);
            
            if ($resultado) {
                error_log("Citas vencidas actualizadas automáticamente");
            }
            
        } catch (Exception $e) {
            error_log("Error al actualizar citas vencidas: " . $e->getMessage());
        }
    }

    // Crear cita con nombre del cliente
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
            error_log("nombre_cliente (cliente): " . $nombreCliente);
            error_log("motivo (motivo): " . $motivo);
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
            
            error_log("=== ANTES DE LLAMAR AL MODELO ===");
            error_log("usuarioId: " . $usuarioId);
            error_log("fecha: " . $fecha);
            error_log("hora: " . $hora);
            error_log("motivo: " . $motivo);
            error_log("nombreCliente: " . $nombreCliente);
            
            $citaId = $this->modelo->crearCita($usuarioId, $fecha, $hora, $motivo, $nombreCliente);

            if ($citaId) {
                error_log("Cita creada exitosamente con ID: " . $citaId);
                echo json_encode([
                    'exito' => true,
                    'mensaje' => 'Cita creada correctamente',
                    'id' => $citaId
                ]);
            } else {
                error_log("Error al crear la cita");
                echo json_encode([
                    'exito' => false,
                    'mensaje' => 'Error al crear la cita en la base de datos'
                ]);
            }
            
        } catch (Exception $e) {
            error_log("Excepción en crearCitaConNombre: " . $e->getMessage());
            echo json_encode([
                'exito' => false,
                'mensaje' => 'Error al crear la cita: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    // Crear cita (usuario normal)
    public function crearCita() {
        try {
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['exito' => false, 'mensaje' => 'Usuario no autenticado']);
                return;
            }

            $usuarioId = $_SESSION['usuario_id'];
            $fecha = $_POST['fecha'] ?? '';
            $hora = $_POST['hora'] ?? '';
            $motivo = trim($_POST['motivo'] ?? '');
            
            if (empty($fecha) || empty($hora) || empty($motivo)) {
                echo json_encode(['exito' => false, 'mensaje' => 'Todos los campos son obligatorios']);
                return;
            }

            // Validar que la fecha no sea pasada
            $fechaSeleccionada = new DateTime($fecha);
            $fechaHoy = new DateTime();
            $fechaHoy->setTime(0, 0, 0);
            
            if ($fechaSeleccionada < $fechaHoy) {
                echo json_encode(['exito' => false, 'mensaje' => 'No se pueden crear citas en fechas pasadas']);
                return;
            }

            // Para usuarios normales, nombre_cliente será null
            $citaId = $this->modelo->crearCita($usuarioId, $fecha, $hora, $motivo, null);

            if ($citaId) {
                echo json_encode([
                    'exito' => true,
                    'mensaje' => 'Cita creada correctamente',
                    'id' => $citaId
                ]);
            } else {
                echo json_encode([
                    'exito' => false,
                    'mensaje' => 'Error al crear la cita'
                ]);
            }
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
                exit;
            }

            $id = $_POST['id'] ?? null;
            $estado = $_POST['estado'] ?? null;

            if (!$id || !$estado) {
                echo json_encode(['exito' => false, 'mensaje' => 'Datos incompletos']);
                exit;
            }

            $resultado = $this->modelo->actualizarEstadoCita($id, $estado);

            if ($resultado) {
                echo json_encode(['exito' => true, 'mensaje' => 'Estado actualizado correctamente']);
            } else {
                echo json_encode(['exito' => false, 'mensaje' => 'Error al actualizar el estado']);
            }
            
        } catch (Exception $e) {
            echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
    }

    // Eliminar cita
    public function eliminar() {
        try {
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['exito' => false, 'mensaje' => 'Usuario no autenticado']);
                exit;
            }

            $id = $_POST['id'] ?? null;

            if (!$id) {
                echo json_encode(['exito' => false, 'mensaje' => 'ID de cita no proporcionado']);
                exit;
            }

            $resultado = $this->modelo->eliminarCita($id);

            if ($resultado) {
                echo json_encode(['exito' => true, 'mensaje' => 'Cita eliminada correctamente']);
            } else {
                echo json_encode(['exito' => false, 'mensaje' => 'Error al eliminar la cita']);
            }
            
        } catch (Exception $e) {
            echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function listarCitas() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /BioEspacio/login.php');
            exit();
        }
        
        $usuarioId = $_SESSION['usuario_id'];
        
        // IMPORTANTE: Usar el método que filtra por creado_por
        $citas = $this->modelo->obtenerCitasUsuario($usuarioId);
        
        error_log("Listando citas para usuario " . $usuarioId . ": " . count($citas) . " citas encontradas");
        
        include __DIR__ . '/../Vista/citas/listar.php';
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = $_POST['usuario_id'] ?? $_SESSION['usuario_id'];
            $fecha = $_POST['fecha'];
            $hora = $_POST['hora'];
            $motivo = $_POST['motivo'];
            $nombreCliente = $_POST['nombre_cliente'];
            
            // El crearCita ya maneja internamente el creado_por
            if ($this->modelo->crearCita($usuarioId, $fecha, $hora, $motivo, $nombreCliente)) {
                $_SESSION['mensaje'] = "Cita creada exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al crear la cita";
                $_SESSION['tipo_mensaje'] = "error";
            }
            
            header('Location: /BioEspacio/citas.php');
            exit();
        }
        
        // Para el formulario, si es admin puede asignar a otros usuarios
        if ($_SESSION['rol'] === 'admin') {
            $usuarios = $this->modelo->obtenerTodosLosUsuarios();
        }
        
        include __DIR__ . '/../Vista/citas/crear.php';
    }

    public function editar($id) {
        $usuarioId = $_SESSION['usuario_id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioAsignado = $_POST['usuario_id'] ?? $usuarioId;
            $fecha = $_POST['fecha'];
            $hora = $_POST['hora'];
            $motivo = $_POST['motivo'];
            $estado = $_POST['estado'];
            $nombreCliente = $_POST['nombre_cliente'];
            
            if ($this->modelo->actualizarCita($id, $usuarioAsignado, $fecha, $hora, $motivo, $estado, $nombreCliente)) {
                $_SESSION['mensaje'] = "Cita actualizada exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al actualizar la cita o no tienes permisos";
                $_SESSION['tipo_mensaje'] = "error";
            }
            
            header('Location: /BioEspacio/citas.php');
            exit();
        }
        
        // Obtener la cita (solo si el usuario tiene permisos)
        $cita = $this->modelo->obtenerCitaPorId($id, $usuarioId);
        
        if (!$cita) {
            $_SESSION['mensaje'] = "Cita no encontrada o sin permisos";
            $_SESSION['tipo_mensaje'] = "error";
            header('Location: /BioEspacio/citas.php');
            exit();
        }
        
        if ($_SESSION['rol'] === 'admin') {
            $usuarios = $this->modelo->obtenerTodosLosUsuarios();
        }
        
        include __DIR__ . '/../Vista/citas/editar.php';
    }

    public function eliminarCita($id) {
        if ($this->modelo->eliminarCita($id)) {
            $_SESSION['mensaje'] = "Cita eliminada exitosamente";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar la cita o no tienes permisos";
            $_SESSION['tipo_mensaje'] = "error";
        }
        
        header('Location: /BioEspacio/citas.php');
        exit();
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
            $controlador->eliminar();
            break;
        default:
            echo json_encode(['exito' => false, 'mensaje' => 'Acción no válida']);
            break;
    }
}
?> 