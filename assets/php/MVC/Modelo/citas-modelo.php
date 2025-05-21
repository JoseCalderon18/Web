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
    public function obtenerCitasUsuario($usuarioId) {
        try {
            $sql = "SELECT * FROM citas WHERE usuario_id = :usuario_id ORDER BY fecha ASC, hora ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerCitasUsuario: " . $e->getMessage());
            return [];
        }
    }

    // Crear nueva cita
    public function crearCita($usuarioId, $fecha, $hora, $motivo) {
        try {
            $sql = "INSERT INTO citas (usuario_id, fecha, hora, motivo, estado) 
                    VALUES (:usuario_id, :fecha, :hora, :motivo, 'pendiente')";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':hora', $hora);
            $stmt->bindParam(':motivo', $motivo);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en crearCita: " . $e->getMessage());
            return false;
        }
    }

    // Verificar disponibilidad de horario
    public function verificarDisponibilidad($fecha, $hora) {
        try {
            $sql = "SELECT COUNT(*) as total FROM citas WHERE fecha = :fecha AND hora = :hora";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':hora', $hora);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$resultado['total'] === 0; // Retorna true si no hay citas en ese horario
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

    // Actualizar estado de cita (solo admin)
    public function actualizarEstadoCita($citaId, $estado) {
        try {
            $sql = "UPDATE citas SET estado = :estado WHERE id = :cita_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':cita_id', $citaId, PDO::PARAM_INT);
            $stmt->bindParam(':estado', $estado);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en actualizarEstadoCita: " . $e->getMessage());
            return false;
        }
    }
} 