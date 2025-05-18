<?php
session_start();
require_once __DIR__ . '/../Modelo/citas-modelo.php';

class CitasControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new CitasModelo();
    }

    public function obtenerCitas() {
        try {
            $citas = $this->modelo->obtenerCitas();
            
            // Formatear citas para FullCalendar
            $eventos = [];
            foreach ($citas as $cita) {
                $eventos[] = [
                    'id' => $cita['id'],
                    'title' => $cita['motivo'],
                    'start' => $cita['fecha_inicio'],
                    'end' => $cita['fecha_fin'],
                    'nombre_usuario' => $cita['nombre_usuario'],
                    'motivo' => $cita['motivo'],
                    'className' => 'fc-event-ocupado'
                ];
            }
            
            header('Content-Type: application/json');
            echo json_encode($eventos);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener las citas: ' . $e->getMessage()
            ]);
        }
    }
    
    public function obtenerCitasTerapias() {
        try {
            $citas = $this->modelo->obtenerCitasTerapias();
            
            // Formatear citas para FullCalendar
            $eventos = [];
            foreach ($citas as $cita) {
                $eventos[] = [
                    'id' => $cita['id'],
                    'title' => $cita['motivo'],
                    'start' => $cita['fecha_inicio'],
                    'end' => $cita['fecha_fin'],
                    'nombre_usuario' => $cita['nombre_usuario'],
                    'motivo' => $cita['motivo'],
                    'className' => 'fc-event-ocupado'
                ];
            }
            
            header('Content-Type: application/json');
            echo json_encode($eventos);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener las citas de terapias: ' . $e->getMessage()
            ]);
        }
    }

    public function crearCita() {
        try {
            // Verificar si hay un usuario logueado o si es admin creando cita para otro usuario
            $usuario_id = null;
            
            if (isset($_POST['usuario_id']) && isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin') {
                // Admin creando cita para otro usuario
                $usuario_id = $_POST['usuario_id'];
            } elseif (isset($_SESSION['usuario_id'])) {
                // Usuario normal creando su propia cita
                $usuario_id = $_SESSION['usuario_id'];
            } else {
                throw new Exception('Debes iniciar sesión para reservar una cita');
            }

            if (empty($_POST['fecha_inicio']) || empty($_POST['fecha_fin']) || empty($_POST['motivo'])) {
                throw new Exception('Todos los campos son obligatorios');
            }

            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            $motivo = $_POST['motivo'];
            $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'general';

            // Validar horario laboral y días
            $this->validarHorario($fecha_inicio, $fecha_fin);

            // Verificar disponibilidad
            if (!$this->modelo->verificarDisponibilidad($fecha_inicio, $fecha_fin)) {
                throw new Exception('El horario seleccionado no está disponible');
            }

            if ($this->modelo->crearCita($usuario_id, $fecha_inicio, $fecha_fin, $motivo, $tipo)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Cita creada correctamente'
                ]);
            } else {
                throw new Exception('Error al crear la cita');
            }

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function actualizarCita() {
        try {
            // Verificar si es admin
            if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
                throw new Exception('No tienes permisos para actualizar citas');
            }
            
            if (empty($_POST['id']) || empty($_POST['motivo'])) {
                throw new Exception('Faltan datos obligatorios');
            }
            
            $id = $_POST['id'];
            $motivo = $_POST['motivo'];
            
            // Opcionalmente actualizar fechas si se proporcionan
            $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
            $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
            
            if ($fecha_inicio && $fecha_fin) {
                // Validar horario laboral y días
                $this->validarHorario($fecha_inicio, $fecha_fin);
                
                // Verificar disponibilidad (excluyendo la cita actual)
                if (!$this->modelo->verificarDisponibilidadExcluyendo($fecha_inicio, $fecha_fin, $id)) {
                    throw new Exception('El horario seleccionado no está disponible');
                }
            }
            
            if ($this->modelo->actualizarCita($id, $motivo, $fecha_inicio, $fecha_fin)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Cita actualizada correctamente'
                ]);
            } else {
                throw new Exception('Error al actualizar la cita');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function eliminarCita() {
        try {
            // Verificar si es admin
            if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
                throw new Exception('No tienes permisos para eliminar citas');
            }
            
            if (empty($_POST['id'])) {
                throw new Exception('ID de cita no proporcionado');
            }
            
            $id = $_POST['id'];
            
            if ($this->modelo->eliminarCita($id)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Cita eliminada correctamente'
                ]);
            } else {
                throw new Exception('Error al eliminar la cita');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function validarHorario($fecha_inicio, $fecha_fin) {
        $inicio = new DateTime($fecha_inicio);
        $fin = new DateTime($fecha_fin);
        
        // Validar día de la semana (1 = Lunes, 7 = Domingo)
        $dia = (int)$inicio->format('N');
        if ($dia > 5) {
            throw new Exception('Solo se pueden crear citas de lunes a viernes');
        }

        $hora = (int)$inicio->format('H');
        if (($hora < 10 || $hora >= 14) && ($hora < 17 || $hora >= 20)) {
            throw new Exception('Las citas solo pueden ser de 10:00-14:00 o 17:00-20:00');
        }
        
        // Verificar que la duración sea de al menos 1 hora
        $duracion = $inicio->diff($fin);
        $minutos = $duracion->h * 60 + $duracion->i;
        
        if ($minutos < 60) {
            throw new Exception('La duración mínima de una cita es de 1 hora');
        }
    }
}

// Manejo de acciones
if (isset($_GET['accion'])) {
    $controlador = new CitasControlador();
    
    switch ($_GET['accion']) {
        case 'obtenerCitas':
            $controlador->obtenerCitas();
            break;
        case 'obtenerCitasTerapias':
            $controlador->obtenerCitasTerapias();
            break;
        case 'crear':
            $controlador->crearCita();
            break;
        case 'actualizar':
            $controlador->actualizarCita();
            break;
        case 'eliminar':
            $controlador->eliminarCita();
            break;
    }
} 