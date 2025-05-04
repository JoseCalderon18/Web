<?php
require_once __DIR__ . '/../Modelo/citas-modelo.php';

class CitasControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new CitasModelo();
    }

    public function obtenerCitas() {
        try {
            $citas = $this->modelo->obtenerCitas();
            echo json_encode([
                'success' => true,
                'data' => $citas
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener las citas: ' . $e->getMessage()
            ]);
        }
    }

    public function crearCita() {
        try {
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception('Debes iniciar sesión para reservar una cita');
            }

            if (empty($_POST['fecha_inicio']) || empty($_POST['fecha_fin']) || empty($_POST['motivo'])) {
                throw new Exception('Todos los campos son obligatorios');
            }

            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            $motivo = $_POST['motivo'];
            $usuario_id = $_SESSION['usuario_id'];

            // Validar horario laboral y días
            $this->validarHorario($fecha_inicio, $fecha_fin);

            // Verificar disponibilidad
            if (!$this->modelo->verificarDisponibilidad($fecha_inicio, $fecha_fin)) {
                throw new Exception('El horario seleccionado no está disponible');
            }

            if ($this->modelo->crearCita($usuario_id, $fecha_inicio, $fecha_fin, $motivo)) {
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
    }
}

// Manejo de acciones
if (isset($_GET['accion'])) {
    $controlador = new CitasControlador();
    
    switch ($_GET['accion']) {
        case 'obtener':
            $controlador->obtenerCitas();
            break;
        case 'crear':
            $controlador->crearCita();
            break;
    }
} 