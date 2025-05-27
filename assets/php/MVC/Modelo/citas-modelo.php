<?php
require_once __DIR__ . '/../Configuracion/conexion.php';

class CitasModelo {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    // Obtener todas las citas
    public function obtenerTodasLasCitas() {
        try {
            $sql = "SELECT c.*, u.nombre as nombre_cliente 
                    FROM citas c 
                    JOIN usuarios u ON c.usuario_id = u.id 
                    ORDER BY c.fecha ASC, c.hora ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerTodasLasCitas: " . $e->getMessage());
            return [];
        }
    }

    // Obtener citas por fecha
    public function obtenerCitasPorFecha($fecha) {
        try {
            $sql = "SELECT c.*, u.nombre as nombre_cliente 
                    FROM citas c 
                    JOIN usuarios u ON c.usuario_id = u.id 
                    WHERE c.fecha = :fecha 
                    ORDER BY c.hora ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerCitasPorFecha: " . $e->getMessage());
            return [];
        }
    }

    // Obtener citas de un usuario
    public function obtenerCitasPorUsuario($usuarioId) {
        try {
            $sql = "SELECT * FROM citas WHERE usuario_id = :usuario_id ORDER BY fecha ASC, hora ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerCitasPorUsuario: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Crea una nueva cita
     */
    public function crearCita($usuarioId, $fecha, $hora, $motivo, $tipo = 'general', $nombreCliente = null) {
        try {
            // Validar que el tipo sea válido
            if (!in_array($tipo, ['general', 'terapias'])) {
                error_log("Tipo de cita no válido: " . $tipo);
                return false;
            }

            $sql = "INSERT INTO citas (usuario_id, fecha, hora, motivo, tipo, nombre_cliente, estado) 
                    VALUES (:usuario_id, :fecha, :hora, :motivo, :tipo, :nombre_cliente, 'pendiente')";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':hora', $hora);
            $stmt->bindParam(':motivo', $motivo);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':nombre_cliente', $nombreCliente);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error en crearCita: " . $e->getMessage());
            return false;
        }
    }

    // Verificar disponibilidad de horario
    public function verificarDisponibilidad($fecha, $hora, $tipo = 'general') {
        try {
            $sql = "SELECT COUNT(*) as total FROM citas 
                    WHERE fecha = ? AND hora = ? AND tipo = ? 
                    AND estado NOT IN ('cancelada')";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$fecha, $hora, $tipo]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $resultado['total'] == 0;
        } catch (PDOException $e) {
            error_log("Error en verificarDisponibilidad: " . $e->getMessage());
            return false;
        }
    }

    // Cancelar cita
    public function cancelarCita($citaId, $usuarioId) {
        try {
            $sql = "UPDATE citas SET estado = 'cancelada' WHERE id = :cita_id AND usuario_id = :usuario_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':cita_id', $citaId, PDO::PARAM_INT);
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en cancelarCita: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza el estado de una cita
     */
    public function actualizarEstadoCita($citaId, $estado) {
        try {
            $sql = "UPDATE citas SET estado = :estado WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':id', $citaId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en actualizarEstadoCita: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene las citas para mostrar en el calendario
     */
    public function obtenerCitasParaCalendario($inicio = null, $fin = null, $tipo = null) {
        try {
            $sql = "SELECT c.*, u.nombre as nombre_usuario 
                    FROM citas c 
                    JOIN usuarios u ON c.usuario_id = u.id";
            
            $params = [];
            $whereConditions = [];
            
            if ($inicio && $fin) {
                $whereConditions[] = "c.fecha BETWEEN :inicio AND :fin";
                $params[':inicio'] = $inicio;
                $params[':fin'] = $fin;
            }
            
            if ($tipo) {
                $whereConditions[] = "c.tipo = :tipo";
                $params[':tipo'] = $tipo;
            }
            
            if (!empty($whereConditions)) {
                $sql .= " WHERE " . implode(" AND ", $whereConditions);
            }
            
            $sql .= " ORDER BY c.fecha ASC, c.hora ASC";
            
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerCitasParaCalendario: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene las próximas citas de un usuario
     */
    public function obtenerProximasCitas($usuarioId = null, $tipo = null, $limite = 5) {
        try {
            $fechaActual = date('Y-m-d');
            $horaActual = date('H:i:s');
            
            if ($usuarioId) {
                // Si se proporciona un ID de usuario, obtener sus próximas citas
                $sql = "SELECT * FROM citas 
                        WHERE usuario_id = :usuario_id";
                
                $params = [
                    ':usuario_id' => $usuarioId,
                    ':fecha_actual' => $fechaActual,
                    ':hora_actual' => $horaActual
                ];
                
                if ($tipo) {
                    $sql .= " AND tipo = :tipo";
                    $params[':tipo'] = $tipo;
                }
                
                $sql .= " AND (fecha > :fecha_actual 
                         OR (fecha = :fecha_actual AND hora >= :hora_actual))
                        ORDER BY fecha ASC, hora ASC 
                        LIMIT :limite";
            } else {
                // Si no se proporciona un ID de usuario, obtener todas las próximas citas
                $sql = "SELECT c.*, u.nombre as nombre_cliente 
                        FROM citas c 
                        JOIN usuarios u ON c.usuario_id = u.id 
                        WHERE (c.fecha > :fecha_actual OR (c.fecha = :fecha_actual AND c.hora >= :hora_actual))";
                
                $params = [
                    ':fecha_actual' => $fechaActual,
                    ':hora_actual' => $horaActual
                ];
                
                if ($tipo) {
                    $sql .= " AND c.tipo = :tipo";
                    $params[':tipo'] = $tipo;
                }
                
                $sql .= " ORDER BY c.fecha ASC, c.hora ASC
                        LIMIT :limite";
            }
            
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerProximasCitas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene las próximas citas para el administrador
     */
    public function obtenerProximasCitasAdmin($tipo = null, $limite = 10) {
        try {
            $fechaActual = date('Y-m-d');
            
            $sql = "SELECT c.*, u.nombre as nombre_usuario 
                    FROM citas c 
                    JOIN usuarios u ON c.usuario_id = u.id 
                    WHERE c.fecha >= :fecha_actual";
            
            $params = [
                ':fecha_actual' => $fechaActual
            ];
            
            if ($tipo) {
                $sql .= " AND c.tipo = :tipo";
                $params[':tipo'] = $tipo;
            }
            
            $sql .= " ORDER BY c.fecha ASC, c.hora ASC 
                    LIMIT :limite";
            
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerProximasCitasAdmin: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Elimina una cita
     */
    public function eliminarCita($citaId, $usuarioId = null) {
        try {
            $sql = "DELETE FROM citas WHERE id = :id";
            
            // Si se proporciona un ID de usuario, verificar que la cita pertenezca a ese usuario
            if ($usuarioId !== null) {
                $sql .= " AND usuario_id = :usuario_id";
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $citaId, PDO::PARAM_INT);
            
            if ($usuarioId !== null) {
                $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en eliminarCita: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerTodosLosUsuarios() {
        $sql = "SELECT id, nombre, apellidos, email FROM usuarios WHERE rol != 'admin' ORDER BY nombre, apellidos";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 