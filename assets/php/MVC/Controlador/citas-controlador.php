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
                $citas = $this->modelo->obtenerCitasUsuario($usuarioId);
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

    // Crear cita básica (para usuarios normales)
    public function crearCita() {
        try {
            // Limpiar cualquier output buffer previo
            if (ob_get_length()) {
                ob_clean();
            }
            
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['exito' => false, 'mensaje' => 'Usuario no autenticado']);
                exit;
            }

            $usuarioId = $_SESSION['usuario_id'];
            $fecha = $_POST['fecha'] ?? '';
            $hora = $_POST['hora'] ?? '';
            $motivo = trim($_POST['motivo'] ?? '');
            $nombreCliente = trim($_POST['nombre_cliente'] ?? $_SESSION['usuario_nombre'] ?? 'Cliente');
            
            if (empty($fecha) || empty($hora) || empty($motivo)) {
                echo json_encode(['exito' => false, 'mensaje' => 'Todos los campos son obligatorios']);
                exit;
            }

            // Validar que la fecha no sea pasada
            $fechaSeleccionada = new DateTime($fecha);
            $fechaHoy = new DateTime();
            $fechaHoy->setTime(0, 0, 0);
            
            if ($fechaSeleccionada < $fechaHoy) {
                echo json_encode(['exito' => false, 'mensaje' => 'No se pueden crear citas en fechas pasadas']);
                exit;
            }

            // Verificar disponibilidad
            if (!$this->modelo->verificarDisponibilidad($fecha, $hora)) {
                echo json_encode(['exito' => false, 'mensaje' => 'El horario no está disponible']);
                exit;
            }
            
            if ($this->modelo->crearCita($usuarioId, $fecha, $hora, $motivo, $nombreCliente)) {
                echo json_encode(['exito' => true, 'mensaje' => 'Cita creada exitosamente']);
            } else {
                echo json_encode(['exito' => false, 'mensaje' => 'Error al crear la cita']);
            }
            
        } catch (Exception $e) {
            error_log("Error en crearCita: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
        exit;
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
            // Limpiar cualquier output buffer previo
            if (ob_get_length()) {
                ob_clean();
            }
            
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['exito' => false, 'mensaje' => 'Usuario no autenticado']);
                exit;
            }

            $id = $_POST['id'] ?? null;
            $estado = $_POST['estado'] ?? null;

            error_log("=== DEBUG actualizarEstado ===");
            error_log("ID recibido: " . ($id ?? 'null'));
            error_log("Estado recibido: " . ($estado ?? 'null'));

            if (!$id || !$estado) {
                error_log("Datos incompletos - ID: " . ($id ?? 'null') . ", Estado: " . ($estado ?? 'null'));
                echo json_encode(['exito' => false, 'mensaje' => 'Datos incompletos']);
                exit;
            }

            // Validar estados permitidos
            $estadosPermitidos = ['confirmada', 'cancelada', 'completada'];
            if (!in_array($estado, $estadosPermitidos)) {
                error_log("Estado no válido: " . $estado);
                echo json_encode(['exito' => false, 'mensaje' => 'Estado no válido. Estados permitidos: ' . implode(', ', $estadosPermitidos)]);
                exit;
            }

            // Verificar que el método existe antes de llamarlo
            if (!method_exists($this->modelo, 'actualizarEstadoCita')) {
                error_log("Método actualizarEstadoCita no existe en el modelo");
                
                // Buscar métodos similares
                $metodos = get_class_methods($this->modelo);
                $metodosSimilares = array_filter($metodos, function($metodo) {
                    return stripos($metodo, 'actualizar') !== false || stripos($metodo, 'estado') !== false;
                });
                error_log("Métodos similares encontrados: " . implode(', ', $metodosSimilares));
                
                echo json_encode(['exito' => false, 'mensaje' => 'Error interno: método no encontrado']);
                exit;
            }

            error_log("Llamando a actualizarEstadoCita con ID: $id, Estado: $estado");
            
            // Intentar la actualización con manejo de errores más específico
            try {
                $resultado = $this->modelo->actualizarEstadoCita($id, $estado);
                error_log("Resultado de actualizarEstadoCita: " . ($resultado ? 'true' : 'false'));
                
                if ($resultado === true) {
                    echo json_encode(['exito' => true, 'mensaje' => 'Estado actualizado correctamente']);
                } else {
                    error_log("El modelo retornó: " . var_export($resultado, true));
                    echo json_encode(['exito' => false, 'mensaje' => 'Error al actualizar el estado en la base de datos']);
                }
                
            } catch (PDOException $e) {
                error_log("Error PDO en actualizarEstado: " . $e->getMessage());
                echo json_encode(['exito' => false, 'mensaje' => 'Error de base de datos: ' . $e->getMessage()]);
            } catch (Exception $e) {
                error_log("Error en el modelo actualizarEstadoCita: " . $e->getMessage());
                echo json_encode(['exito' => false, 'mensaje' => 'Error en el modelo: ' . $e->getMessage()]);
            }
            
        } catch (Exception $e) {
            error_log("Excepción en actualizarEstado: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    // Eliminar cita
    public function eliminar() {
        try {
            // Limpiar cualquier output buffer previo
            if (ob_get_length()) {
                ob_clean();
            }
            
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['exito' => false, 'mensaje' => 'Usuario no autenticado']);
                exit;
            }

            $id = $_POST['id'] ?? null;

            if (!$id) {
                echo json_encode(['exito' => false, 'mensaje' => 'ID de cita no proporcionado']);
                exit;
            }

            if ($this->modelo->eliminarCita($id)) {
                echo json_encode(['exito' => true, 'mensaje' => 'Cita eliminada exitosamente']);
            } else {
                echo json_encode(['exito' => false, 'mensaje' => 'Error al eliminar la cita o no tienes permisos']);
            }
            
        } catch (Exception $e) {
            error_log("Error en eliminar: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    // Actualizar citas vencidas
    private function actualizarCitasVencidas() {
        try {
            $fechaActual = date('Y-m-d');
            $horaActual = date('H:i:s');
            
            return $this->modelo->marcarCitasVencidasComoCompletadas($fechaActual, $horaActual);
        } catch (Exception $e) {
            error_log("Error en actualizarCitasVencidas: " . $e->getMessage());
            return false;
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

// Manejo de acciones AJAX
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