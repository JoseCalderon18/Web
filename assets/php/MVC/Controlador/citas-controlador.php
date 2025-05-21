<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Modelo/citas-modelo.php';

class CitasControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new CitasModelo();
    }

    // Obtener todas las citas (solo admin)
    public function obtenerTodasLasCitas() {
        try {
            // Verificar si el usuario es administrador
            if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
                throw new Exception("No tienes permisos para ver esta información");
            }

            return $this->modelo->obtenerTodasLasCitas();
        } catch (Exception $e) {
            error_log("Error en obtenerTodasLasCitas: " . $e->getMessage());
            return [];
        }
    }

    // Obtener citas por fecha (solo admin)
    public function obtenerCitasPorFecha($fecha) {
        try {
            // Verificar si el usuario es administrador
            if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
                throw new Exception("No tienes permisos para ver esta información");
            }

            return $this->modelo->obtenerCitasPorFecha($fecha);
        } catch (Exception $e) {
            error_log("Error en obtenerCitasPorFecha: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
    }

    // Obtener citas del usuario actual
    public function obtenerMisCitas() {
        try {
            // Verificar si el usuario está autenticado
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception("Debes iniciar sesión para ver tus citas");
            }

            $usuarioId = $_SESSION['usuario_id'];
            return $this->modelo->obtenerCitasPorUsuario($usuarioId);
        } catch (Exception $e) {
            error_log("Error en obtenerMisCitas: " . $e->getMessage());
            return [];
        }
    }

    // Crear una nueva cita
    public function crearCita() {
        try {
            // Verificar si el usuario está autenticado
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception("Debes iniciar sesión para crear una cita");
            }

            // Validar datos
            if (!isset($_POST['fecha']) || !isset($_POST['hora']) || !isset($_POST['motivo'])) {
                throw new Exception("Faltan datos obligatorios");
            }

            $fecha = $_POST['fecha'];
            $hora = $_POST['hora'];
            $motivo = $_POST['motivo'];
            $usuarioId = $_SESSION['usuario_id'];

            // Validar disponibilidad
            if (!$this->modelo->verificarDisponibilidad($fecha, $hora)) {
                throw new Exception("La hora seleccionada no está disponible");
            }

            // Crear la cita
            $resultado = $this->modelo->crearCita($usuarioId, $fecha, $hora, $motivo);
            
            if ($resultado) {
                echo json_encode(['exito' => true, 'mensaje' => 'Cita creada correctamente']);
            } else {
                throw new Exception("Error al crear la cita");
            }
        } catch (Exception $e) {
            echo json_encode(['exito' => false, 'mensaje' => $e->getMessage()]);
        }
    }

    // Cancelar una cita
    public function cancelarCita() {
        try {
            // Verificar si el usuario está autenticado
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception("Debes iniciar sesión para cancelar una cita");
            }

            // Validar datos
            if (!isset($_POST['cita_id'])) {
                throw new Exception("ID de cita no proporcionado");
            }

            $citaId = $_POST['cita_id'];
            $usuarioId = $_SESSION['usuario_id'];

            // Cancelar la cita
            $resultado = $this->modelo->cancelarCita($citaId, $usuarioId);
            
            if ($resultado) {
                echo json_encode(['exito' => true, 'mensaje' => 'Cita cancelada correctamente']);
            } else {
                throw new Exception("Error al cancelar la cita");
            }
        } catch (Exception $e) {
            echo json_encode(['exito' => false, 'mensaje' => $e->getMessage()]);
        }
    }

    // Actualizar estado de cita (solo admin)
    public function actualizarEstadoCita() {
        try {
            // Verificar si el usuario es administrador
            if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
                throw new Exception("No tienes permisos para actualizar el estado de las citas");
            }

            // Validar datos
            if (!isset($_POST['cita_id']) || !isset($_POST['estado'])) {
                throw new Exception("Faltan datos obligatorios");
            }

            $citaId = $_POST['cita_id'];
            $estado = $_POST['estado'];

            // Validar estado
            $estadosValidos = ['pendiente', 'confirmada', 'cancelada', 'completada'];
            if (!in_array($estado, $estadosValidos)) {
                throw new Exception("Estado no válido");
            }

            // Actualizar estado
            $resultado = $this->modelo->actualizarEstadoCita($citaId, $estado);
            
            if ($resultado) {
                echo json_encode(['exito' => true, 'mensaje' => 'Estado de cita actualizado correctamente']);
            } else {
                throw new Exception("Error al actualizar el estado de la cita");
            }
        } catch (Exception $e) {
            echo json_encode(['exito' => false, 'mensaje' => $e->getMessage()]);
        }
    }

    // Obtener citas para el calendario
    public function obtenerCitasCalendario() {
        try {
            $eventos = [];
            
            // Si es admin, obtener todas las citas
            if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin') {
                $citas = $this->modelo->obtenerTodasLasCitas();
                
                foreach ($citas as $cita) {
                    $eventos[] = [
                        'id' => $cita['id'],
                        'title' => $cita['nombre_cliente'],
                        'start' => $cita['fecha'] . 'T' . $cita['hora'],
                        'end' => $cita['fecha'] . 'T' . date('H:i:s', strtotime($cita['hora'] . ' +1 hour')),
                        'backgroundColor' => $this->obtenerColorPorEstado($cita['estado']),
                        'borderColor' => $this->obtenerColorPorEstado($cita['estado']),
                        'extendedProps' => [
                            'motivo' => $cita['motivo'],
                            'estado' => $cita['estado']
                        ]
                    ];
                }
            } 
            // Si es usuario normal, obtener solo sus citas
            else if (isset($_SESSION['usuario_id'])) {
                $citas = $this->modelo->obtenerCitasPorUsuario($_SESSION['usuario_id']);
                
                foreach ($citas as $cita) {
                    $eventos[] = [
                        'id' => $cita['id'],
                        'title' => 'Mi cita',
                        'start' => $cita['fecha'] . 'T' . $cita['hora'],
                        'end' => $cita['fecha'] . 'T' . date('H:i:s', strtotime($cita['hora'] . ' +1 hour')),
                        'backgroundColor' => $this->obtenerColorPorEstado($cita['estado']),
                        'borderColor' => $this->obtenerColorPorEstado($cita['estado']),
                        'extendedProps' => [
                            'motivo' => $cita['motivo'],
                            'estado' => $cita['estado']
                        ]
                    ];
                }
            }
            
            echo json_encode(['exito' => true, 'datos' => $eventos]);
        } catch (Exception $e) {
            error_log("Error en obtenerCitasCalendario: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
    }
    
    // Obtener color según el estado de la cita
    private function obtenerColorPorEstado($estado) {
        switch ($estado) {
            case 'pendiente':
                return '#FFA500'; // Naranja
            case 'confirmada':
                return '#28a745'; // Verde
            case 'cancelada':
                return '#dc3545'; // Rojo
            case 'completada':
                return '#17a2b8'; // Azul
            default:
                return '#6c757d'; // Gris
        }
    }
}

// Manejo de acciones (fuera de la clase)
if (isset($_GET['accion'])) {
    $controlador = new CitasControlador();
    
    switch ($_GET['accion']) {
        case 'crear':
            $controlador->crearCita();
            break;
        case 'cancelar':
            $controlador->cancelarCita();
            break;
        case 'actualizar_estado':
            $controlador->actualizarEstadoCita();
            break;
        case 'obtener_calendario':
            $controlador->obtenerCitasCalendario();
            break;
        default:
            echo json_encode(['exito' => false, 'mensaje' => 'Acción no válida']);
            break;
    }
} 