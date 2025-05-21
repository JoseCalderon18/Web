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

    /**
     * Obtiene el color correspondiente al estado de una cita
     */
    public function obtenerColorPorEstado($estado) {
        switch ($estado) {
            case 'pendiente':
                return '#FFC107'; // Amarillo
            case 'confirmada':
                return '#28A745'; // Verde
            case 'cancelada':
                return '#DC3545'; // Rojo
            case 'completada':
                return '#007BFF'; // Azul
            case 'disponible':
                return '#28A745'; // Verde para citas disponibles
            case 'ocupada':
                return '#DC3545'; // Rojo para citas ocupadas
            default:
                return '#6C757D'; // Gris
        }
    }

    /**
     * Obtiene todas las citas (método que faltaba)
     */
    public function obtenerTodasLasCitas() {
        // Verificar si el usuario es administrador
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
            // Si no es admin, solo devolver sus propias citas
            if (isset($_SESSION['usuario_id'])) {
                return $this->obtenerCitasUsuario($_SESSION['usuario_id']);
            }
            return [];
        }
        
        $citas = $this->modelo->obtenerTodasLasCitas();
        
        // Marcar las citas como disponibles u ocupadas
        foreach ($citas as &$cita) {
            if ($this->modelo->verificarDisponibilidad($cita['fecha'], $cita['hora'])) {
                $cita['disponibilidad'] = 'disponible';
                $cita['color_disponibilidad'] = '#28A745'; // Verde
            } else {
                $cita['disponibilidad'] = 'ocupada';
                $cita['color_disponibilidad'] = '#DC3545'; // Rojo
            }
            
            // Añadir botones de acción según el estado actual
            $cita['botones_accion'] = $this->generarBotonesAccion($cita['id'], $cita['estado']);
        }
        
        return $citas;
    }

    /**
     * Obtiene las citas de un usuario específico
     */
    public function obtenerCitasUsuario($usuarioId) {
        $citas = $this->modelo->obtenerCitasPorUsuario($usuarioId);
        
        // Añadir información adicional a cada cita
        foreach ($citas as &$cita) {
            // Añadir botones de acción según el estado actual (limitados para usuarios)
            $cita['botones_accion'] = $this->generarBotonesAccionUsuario($cita['id'], $cita['estado']);
        }
        
        return $citas;
    }

    /**
     * Genera HTML para los botones de acción según el estado de la cita (para administradores)
     */
    private function generarBotonesAccion($citaId, $estado) {
        $botones = '<div class="btn-group btn-group-sm" role="group">';
        
        switch ($estado) {
            case 'pendiente':
                $botones .= '<button type="button" class="btn btn-success btn-cambiar-estado" data-cita-id="'.$citaId.'" data-estado="confirmada">Confirmar</button>';
                $botones .= '<button type="button" class="btn btn-danger btn-cambiar-estado" data-cita-id="'.$citaId.'" data-estado="cancelada">Cancelar</button>';
                break;
                
            case 'confirmada':
                $botones .= '<button type="button" class="btn btn-primary btn-cambiar-estado" data-cita-id="'.$citaId.'" data-estado="completada">Completar</button>';
                $botones .= '<button type="button" class="btn btn-danger btn-cambiar-estado" data-cita-id="'.$citaId.'" data-estado="cancelada">Cancelar</button>';
                break;
                
            case 'cancelada':
                $botones .= '<button type="button" class="btn btn-warning btn-cambiar-estado" data-cita-id="'.$citaId.'" data-estado="pendiente">Reactivar</button>';
                $botones .= '<button type="button" class="btn btn-danger btn-eliminar" data-cita-id="'.$citaId.'">Eliminar</button>';
                break;
                
            case 'completada':
                $botones .= '<button type="button" class="btn btn-info btn-detalles" data-cita-id="'.$citaId.'">Detalles</button>';
                break;
        }
        
        $botones .= '</div>';
        return $botones;
    }

    /**
     * Genera HTML para los botones de acción según el estado de la cita (para usuarios regulares)
     */
    private function generarBotonesAccionUsuario($citaId, $estado) {
        $botones = '<div class="btn-group btn-group-sm" role="group">';
        
        switch ($estado) {
            case 'pendiente':
            case 'confirmada':
                $botones .= '<button type="button" class="btn btn-danger btn-cancelar" data-cita-id="'.$citaId.'">Cancelar</button>';
                break;
                
            case 'cancelada':
            case 'completada':
                $botones .= '<button type="button" class="btn btn-info btn-detalles" data-cita-id="'.$citaId.'">Detalles</button>';
                break;
        }
        
        $botones .= '</div>';
        return $botones;
    }

    /**
     * Verifica la disponibilidad de un horario
     */
    public function verificarDisponibilidad($fecha, $hora) {
        return $this->modelo->verificarDisponibilidad($fecha, $hora);
    }

    /**
     * Obtiene el estilo CSS para los encabezados de tabla
     */
    public function obtenerEstiloEncabezadoTabla() {
        return "background-color: #343a40; color: white; font-weight: bold;";
    }

    /**
     * Muestra la vista de citas
     */
    public function mostrarVista() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login.php');
            exit;
        }
        
        // Cargar la vista correspondiente
        if ($_SESSION['usuario_rol'] === 'admin') {
            // Verificar si el archivo existe antes de incluirlo
            $rutaVistaAdmin = __DIR__ . '/../../Vista/admin/citas.php';
            if (file_exists($rutaVistaAdmin)) {
                include $rutaVistaAdmin;
            } else {
                // Intentar con otra ruta alternativa
                $rutaAlternativa = __DIR__ . '/../../../pages/admin/citas.php';
                if (file_exists($rutaAlternativa)) {
                    include $rutaAlternativa;
                } else {
                    echo "Error: No se pudo encontrar la vista de administrador.";
                    error_log("Error: No se pudo encontrar la vista en $rutaVistaAdmin ni en $rutaAlternativa");
                }
            }
        } else {
            // Verificar si el archivo existe antes de incluirlo
            $rutaVistaUsuario = __DIR__ . '/../../Vista/usuario/citas.php';
            if (file_exists($rutaVistaUsuario)) {
                include $rutaVistaUsuario;
            } else {
                // Intentar con otra ruta alternativa
                $rutaAlternativa = __DIR__ . '/../../../pages/usuario/citas.php';
                if (file_exists($rutaAlternativa)) {
                    include $rutaAlternativa;
                } else {
                    echo "Error: No se pudo encontrar la vista de usuario.";
                    error_log("Error: No se pudo encontrar la vista en $rutaVistaUsuario ni en $rutaAlternativa");
                }
            }
        }
    }

    /**
     * Obtiene las citas para mostrar en el calendario
     */
    public function obtenerCitasCalendario() {
        try {
            // Verificar si el usuario está autenticado
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception("Usuario no autenticado");
            }
            
            $usuarioId = $_SESSION['usuario_id'];
            $esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
            
            // Obtener parámetros
            $inicio = isset($_GET['inicio']) ? $_GET['inicio'] : date('Y-m-d');
            $fin = isset($_GET['fin']) ? $_GET['fin'] : date('Y-m-d', strtotime('+30 days'));
            $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
            
            // Validar tipo
            if (!empty($tipo) && !in_array($tipo, ['general', 'terapias'])) {
                throw new Exception("Tipo de cita no válido");
            }
            
            // Obtener citas
            $citas = $this->modelo->obtenerCitasParaCalendario($inicio, $fin, $tipo);
            
            // Formatear citas para FullCalendar
            $eventos = [];
            foreach ($citas as $cita) {
                // Si no es admin, solo mostrar las citas del usuario
                if (!$esAdmin && $cita['usuario_id'] != $usuarioId) {
                    continue;
                }
                
                $color = $this->obtenerColorPorEstado($cita['estado']);
                
                $titulo = $esAdmin ? 
                    ($cita['nombre_cliente'] ? $cita['nombre_cliente'] : $cita['nombre_usuario']) . ': ' . $cita['motivo'] 
                    : $cita['motivo'];
                
                $eventos[] = [
                    'id' => $cita['id'],
                    'title' => $titulo,
                    'start' => $cita['fecha'] . 'T' . $cita['hora'],
                    'end' => date('Y-m-d\TH:i:s', strtotime($cita['fecha'] . ' ' . $cita['hora'] . ' +1 hour')),
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'estado' => $cita['estado'],
                        'motivo' => $cita['motivo'],
                        'tipo' => $cita['tipo']
                    ]
                ];
            }
            
            echo json_encode(['exito' => true, 'datos' => $eventos]);
        } catch (Exception $e) {
            error_log("Error en obtenerCitasCalendario: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Crea una nueva cita
     */
    public function crearCita() {
        try {
            // Verificar si el usuario está autenticado
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception("Debes iniciar sesión para reservar una cita");
            }
            
            // Validar datos básicos
            if (empty($_POST['fecha']) || empty($_POST['hora']) || empty($_POST['motivo'])) {
                throw new Exception("Faltan datos obligatorios");
            }
            
            $usuarioId = $_SESSION['usuario_id'];
            $fecha = $_POST['fecha'];
            $hora = $_POST['hora'];
            $motivo = $_POST['motivo'];
            $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'general';
            $nombreCliente = isset($_POST['nombre_cliente']) ? $_POST['nombre_cliente'] : null;
            
            // Validar tipo
            if (!in_array($tipo, ['general', 'terapias'])) {
                throw new Exception("Tipo de cita no válido");
            }
            
            // Verificar disponibilidad
            if (!$this->modelo->verificarDisponibilidad($fecha, $hora)) {
                throw new Exception("El horario seleccionado ya no está disponible");
            }
            
            // Crear cita
            $resultado = $this->modelo->crearCita($usuarioId, $fecha, $hora, $motivo, $tipo, $nombreCliente);
            
            if ($resultado) {
                echo json_encode(['exito' => true, 'mensaje' => 'Cita reservada correctamente', 'id' => $resultado]);
            } else {
                throw new Exception("No se pudo crear la cita");
            }
        } catch (Exception $e) {
            error_log("Error en crearCita: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Cancela una cita
     */
    public function cancelarCita() {
        try {
            // Verificar si el usuario está autenticado
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception("Debes iniciar sesión para cancelar una cita");
            }
            
            // Validar datos básicos
            if (empty($_POST['cita_id'])) {
                throw new Exception("ID de cita no proporcionado");
            }
            
            $usuarioId = $_SESSION['usuario_id'];
            $citaId = $_POST['cita_id'];
            $esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
            
            // Cancelar cita
            if ($esAdmin) {
                // El admin puede cancelar cualquier cita
                $resultado = $this->modelo->actualizarEstadoCita($citaId, 'cancelada');
            } else {
                // El usuario solo puede cancelar sus propias citas
                $resultado = $this->modelo->cancelarCita($citaId, $usuarioId);
            }
            
            if ($resultado) {
                echo json_encode(['exito' => true, 'mensaje' => 'Cita cancelada correctamente']);
            } else {
                throw new Exception("No se pudo cancelar la cita");
            }
        } catch (Exception $e) {
            error_log("Error en cancelarCita: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Actualiza el estado de una cita
     */
    public function actualizarEstado() {
        try {
            // Verificar si el usuario es administrador
            if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
                throw new Exception("No tienes permisos para realizar esta acción");
            }
            
            // Validar datos básicos
            if (empty($_POST['cita_id']) || empty($_POST['estado'])) {
                throw new Exception("Faltan datos obligatorios");
            }
            
            $citaId = $_POST['cita_id'];
            $estado = $_POST['estado'];
            
            // Validar estado
            if (!in_array($estado, ['pendiente', 'confirmada', 'cancelada', 'completada'])) {
                throw new Exception("Estado no válido");
            }
            
            // Actualizar estado
            $resultado = $this->modelo->actualizarEstadoCita($citaId, $estado);
            
            if ($resultado) {
                echo json_encode(['exito' => true, 'mensaje' => 'Estado actualizado correctamente']);
            } else {
                throw new Exception("No se pudo actualizar el estado");
            }
        } catch (Exception $e) {
            error_log("Error en actualizarEstado: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Obtiene las próximas citas
     */
    public function obtenerProximasCitas() {
        try {
            // Verificar si el usuario está autenticado
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception("Usuario no autenticado");
            }
            
            $usuarioId = $_SESSION['usuario_id'];
            $esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
            $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null;
            
            // Validar tipo
            if (!empty($tipo) && !in_array($tipo, ['general', 'terapias'])) {
                throw new Exception("Tipo de cita no válido");
            }
            
            // Obtener citas
            if ($esAdmin) {
                $citas = $this->modelo->obtenerProximasCitasAdmin($tipo);
            } else {
                $citas = $this->modelo->obtenerProximasCitas($usuarioId, $tipo);
            }
            
            echo json_encode(['exito' => true, 'datos' => $citas]);
        } catch (Exception $e) {
            error_log("Error en obtenerProximasCitas: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Elimina una cita
     */
    public function eliminarCita() {
        try {
            // Verificar si el usuario es administrador
            if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
                throw new Exception("No tienes permisos para realizar esta acción");
            }
            
            // Validar datos básicos
            if (empty($_POST['cita_id'])) {
                throw new Exception("ID de cita no proporcionado");
            }
            
            $citaId = $_POST['cita_id'];
            
            // Eliminar cita
            $resultado = $this->modelo->eliminarCita($citaId);
            
            if ($resultado) {
                echo json_encode(['exito' => true, 'mensaje' => 'Cita eliminada correctamente']);
            } else {
                throw new Exception("No se pudo eliminar la cita");
            }
        } catch (Exception $e) {
            error_log("Error en eliminarCita: " . $e->getMessage());
            echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
    }
}

// Verificar si la solicitud es AJAX
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $controlador = new CitasControlador();
    
    // Obtener la acción solicitada
    $accion = isset($_GET['accion']) ? $_GET['accion'] : '';
    
    switch ($accion) {
        case 'obtenerCitas':
            $controlador->obtenerCitasCalendario();
            break;
            
        case 'obtenerProximasCitas':
            $controlador->obtenerProximasCitas();
            break;
            
        case 'crear':
            $controlador->crearCita();
            break;
            
        case 'cancelar':
            $controlador->cancelarCita();
            break;
            
        case 'actualizarEstado':
            $controlador->actualizarEstado();
            break;
            
        case 'eliminar':
            $controlador->eliminarCita();
            break;
            
        case 'obtenerTodasLasCitas':
            echo json_encode(['exito' => true, 'datos' => $controlador->obtenerTodasLasCitas()]);
            break;
            
        case 'obtenerCitasUsuario':
            if (!isset($_SESSION['usuario_id'])) {
                echo json_encode(['exito' => false, 'mensaje' => 'Usuario no autenticado']);
                break;
            }
            echo json_encode(['exito' => true, 'datos' => $controlador->obtenerCitasUsuario($_SESSION['usuario_id'])]);
            break;
            
        case 'verificarDisponibilidad':
            $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
            $hora = isset($_GET['hora']) ? $_GET['hora'] : '';
            
            if (empty($fecha) || empty($hora)) {
                echo json_encode(['exito' => false, 'mensaje' => 'Faltan datos obligatorios']);
                break;
            }
            
            $disponible = $controlador->verificarDisponibilidad($fecha, $hora);
            $color = $disponible ? '#28A745' : '#DC3545'; // Verde si está disponible, rojo si no
            
            echo json_encode([
                'exito' => true, 
                'disponible' => $disponible,
                'color' => $color,
                'mensaje' => $disponible ? 'Horario disponible' : 'Horario no disponible'
            ]);
            break;
            
        case 'obtenerEstiloEncabezadoTabla':
            echo json_encode(['exito' => true, 'estilo' => $controlador->obtenerEstiloEncabezadoTabla()]);
            break;
            
        default:
            echo json_encode(['exito' => false, 'mensaje' => 'Acción no válida']);
            break;
    }
} else {
    // Si no es una solicitud AJAX, mostrar la vista
    $controlador = new CitasControlador();
    $controlador->mostrarVista();
} 