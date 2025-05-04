<?php
require_once __DIR__ . '/../Configuracion/conexion.php';

class CitasModelo {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function obtenerCitas() {
        try {
            $sql = "SELECT c.*, u.nombre as nombre_usuario 
                    FROM citas c 
                    JOIN usuarios u ON c.usuario_id = u.id 
                    WHERE c.tipo = 'general'";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener las citas: " . $e->getMessage());
        }
    }

    public function crearCita($usuario_id, $fecha_inicio, $fecha_fin, $motivo, $tipo = 'general') {
        try {
            $sql = "INSERT INTO citas (usuario_id, fecha_inicio, fecha_fin, motivo, tipo) 
                    VALUES (:usuario_id, :fecha_inicio, :fecha_fin, :motivo, :tipo)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':usuario_id' => $usuario_id,
                ':fecha_inicio' => $fecha_inicio,
                ':fecha_fin' => $fecha_fin,
                ':motivo' => $motivo,
                ':tipo' => $tipo
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error al crear la cita: " . $e->getMessage());
        }
    }

    public function verificarDisponibilidad($fecha_inicio, $fecha_fin) {
        try {
            $sql = "SELECT COUNT(*) FROM citas 
                    WHERE (fecha_inicio BETWEEN :inicio AND :fin 
                    OR fecha_fin BETWEEN :inicio AND :fin)
                    OR (:inicio BETWEEN fecha_inicio AND fecha_fin)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':inicio' => $fecha_inicio,
                ':fin' => $fecha_fin
            ]);

            return $stmt->fetchColumn() === 0;
        } catch (PDOException $e) {
            throw new Exception("Error al verificar disponibilidad: " . $e->getMessage());
        }
    }
} 