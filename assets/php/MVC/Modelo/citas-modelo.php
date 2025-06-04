<?php
require_once __DIR__ . '/../Configuracion/conexion.php';

class CitasModelo {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    /**
     * Obtiene todas las citas con información del usuario
     */
    public function obtenerTodasLasCitas() {
        try {
            // CONSULTA CORREGIDA: Especificar bien los alias para evitar conflictos
            $sql = "SELECT 
                        c.id,
                        c.usuario_id,
                        c.fecha,
                        c.hora,
                        c.motivo,
                        c.estado,
                        c.nombre_cliente,
                        u.nombre as nombre_usuario,
                        u.email as email_usuario
                    FROM citas c 
                    LEFT JOIN usuarios u ON c.usuario_id = u.id 
                    ORDER BY c.fecha DESC, c.hora DESC";
            
            error_log("=== CONSULTA SQL CITAS ===");
            error_log("SQL: " . $sql);
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("=== RESULTADOS DE CONSULTA ===");
            error_log("Total citas encontradas: " . count($resultados));
            
            foreach ($resultados as $i => $cita) {
                error_log("Cita $i:");
                error_log("  - ID: " . $cita['id']);
                error_log("  - motivo: " . $cita['motivo']);
                error_log("  - nombre_cliente: " . ($cita['nombre_cliente'] ?? 'NULL'));
                error_log("  - nombre_usuario: " . ($cita['nombre_usuario'] ?? 'NULL'));
            }
            
            return $resultados;
            
        } catch (PDOException $e) {
            error_log("Error en obtenerTodasLasCitas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Actualizar automáticamente citas vencidas a completadas
     */
    private function actualizarCitasVencidas() {
        try {
            $ahora = date('Y-m-d H:i:s');
            $fechaHoy = date('Y-m-d');
            $horaActual = date('H:i:s');
            
            // Actualizar citas que ya pasaron su fecha y hora
            $query = "UPDATE citas 
                     SET estado = 'completada' 
                     WHERE estado IN ('confirmada') 
                     AND (
                         fecha < :fecha_hoy 
                         OR (fecha = :fecha_hoy AND hora <= :hora_actual)
                     )";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':fecha_hoy', $fechaHoy);
            $stmt->bindParam(':hora_actual', $horaActual);
            
            $result = $stmt->execute();
            
            if ($result) {
                $filasAfectadas = $stmt->rowCount();
                if ($filasAfectadas > 0) {
                    error_log("Actualizadas $filasAfectadas citas vencidas a completadas");
                }
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error en actualizarCitasVencidas: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crear una nueva cita
     */
    public function crearCita($usuarioId, $fecha, $hora, $motivo, $nombreCliente = null) {
        try {
            // Eliminamos creado_por del INSERT
            $query = "INSERT INTO citas (usuario_id, fecha, hora, motivo, nombre_cliente, estado) 
                     VALUES (:usuario_id, :fecha, :hora, :motivo, :nombre_cliente, 'confirmada')";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':usuario_id', $usuarioId);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':hora', $hora);
            $stmt->bindParam(':motivo', $motivo);
            $stmt->bindParam(':nombre_cliente', $nombreCliente);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en crearCita: " . $e->getMessage());
            return false;
        }
    }

    // Verificar disponibilidad de horario
    public function verificarDisponibilidad($fecha, $hora, $idExcluir = null) {
        try {
            error_log("=== VERIFICAR DISPONIBILIDAD ===");
            error_log("Fecha: " . $fecha);
            error_log("Hora: " . $hora);
            error_log("ID a excluir: " . ($idExcluir ?? 'ninguno'));
            
            $sql = "SELECT COUNT(*) as total FROM citas 
                    WHERE fecha = ? AND hora = ? 
                    AND estado NOT IN ('cancelada')";
            
            $params = [$fecha, $hora];
            
            // Si se proporciona un ID, excluirlo (útil para actualizaciones)
            if ($idExcluir) {
                $sql .= " AND id != ?";
                $params[] = $idExcluir;
            }
            
            error_log("SQL: " . $sql);
            error_log("Parámetros: " . print_r($params, true));
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            error_log("Citas encontradas: " . $resultado['total']);
            
            $disponible = $resultado['total'] == 0;
            error_log("¿Disponible?: " . ($disponible ? 'SÍ' : 'NO'));
            
            return $disponible;
            
        } catch (PDOException $e) {
            error_log("Error en verificarDisponibilidad: " . $e->getMessage());
            return false;
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
     * Obtener cita por ID (con validación de permisos)
     */
    public function obtenerCitaPorId($id, $usuarioId) {
        try {
            $rolUsuario = $_SESSION['rol'] ?? 'usuario';
            
            if ($rolUsuario === 'admin') {
                // Admin puede ver cualquier cita
                $query = "SELECT c.*, u.nombre as usuario_nombre 
                         FROM citas c 
                         LEFT JOIN usuarios u ON c.usuario_id = u.id 
                         WHERE c.id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            } else {
                // Usuario normal solo ve sus citas
                $query = "SELECT c.*, u.nombre as usuario_nombre 
                         FROM citas c 
                         LEFT JOIN usuarios u ON c.usuario_id = u.id 
                         WHERE c.id = :id AND c.usuario_id = :usuario_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerCitaPorId: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar cita completa
     */
    public function actualizarCita($id, $usuarioId, $fecha, $hora, $motivo, $estado, $nombreCliente) {
        try {
            // Validar estados permitidos
            $estadosPermitidos = ['confirmada', 'cancelada', 'completada'];
            if (!in_array($estado, $estadosPermitidos)) {
                error_log("Estado no válido en actualizarCita: " . $estado);
                return false;
            }
            
            $rolUsuario = $_SESSION['rol'] ?? 'usuario';
            $usuarioLogueado = $_SESSION['usuario_id'];
            
            // Verificar permisos
            if ($rolUsuario !== 'admin') {
                $query = "SELECT usuario_id FROM citas WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $cita = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$cita || $cita['usuario_id'] != $usuarioLogueado) {
                    error_log("Usuario " . $usuarioLogueado . " no puede actualizar la cita " . $id);
                    return false;
                }
            }
            
            // Verificar disponibilidad si se cambia fecha/hora
            $queryCheck = "SELECT fecha, hora FROM citas WHERE id = :id";
            $stmtCheck = $this->db->prepare($queryCheck);
            $stmtCheck->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtCheck->execute();
            $citaActual = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            
            if ($citaActual && ($citaActual['fecha'] != $fecha || $citaActual['hora'] != $hora)) {
                if (!$this->verificarDisponibilidad($fecha, $hora, $id)) {
                    error_log("Horario no disponible: $fecha $hora");
                    return false;
                }
            }
            
            $query = "UPDATE citas 
                     SET usuario_id = :usuario_id, fecha = :fecha, hora = :hora, 
                         motivo = :motivo, estado = :estado, nombre_cliente = :nombre_cliente 
                     WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':hora', $hora);
            $stmt->bindParam(':motivo', $motivo);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':nombre_cliente', $nombreCliente);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en actualizarCita: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener todas las citas (solo para admin)
     */
    public function obtenerTodasCitas() {
        try {
            $query = "SELECT c.*, u.nombre as usuario_nombre, creador.nombre as creado_por_nombre 
                     FROM citas c 
                     LEFT JOIN usuarios u ON c.usuario_id = u.id 
                     LEFT JOIN usuarios creador ON c.creado_por = creador.id
                     ORDER BY c.fecha DESC, c.hora DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerTodasCitas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Marcar como completadas las citas que ya pasaron de fecha/hora
     */
    public function marcarCitasVencidasComoCompletadas($fechaActual, $horaActual) {
        try {
            $sql = "UPDATE citas 
                    SET estado = 'completada' 
                    WHERE estado IN ('confirmada') 
                    AND (
                        fecha < :fechaActual 
                        OR (fecha = :fechaActual AND hora < :horaActual)
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':fechaActual', $fechaActual);
            $stmt->bindParam(':horaActual', $horaActual);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Error en marcarCitasVencidasComoCompletadas: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar cita (con validación de permisos)
     */
    public function eliminarCita($id) {
        try {
            $rolUsuario = $_SESSION['rol'] ?? 'usuario';
            $usuarioLogueado = $_SESSION['usuario_id'];
            
            // Verificar permisos
            if ($rolUsuario !== 'admin') {
                // Si no es admin, solo puede eliminar sus propias citas
                $query = "SELECT usuario_id FROM citas WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $cita = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$cita || $cita['usuario_id'] != $usuarioLogueado) {
                    error_log("Usuario " . $usuarioLogueado . " no puede eliminar la cita " . $id);
                    return false;
                }
            }
            
            // Si es admin o es su propia cita, proceder con la eliminación
            $query = "DELETE FROM citas WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en eliminarCita: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener todos los usuarios (para admins)
     */
    public function obtenerTodosLosUsuarios() {
        try {
            $query = "SELECT id, nombre, email FROM usuarios ORDER BY nombre";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerTodosLosUsuarios: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Actualizar solo el estado de una cita
     */
    public function actualizarEstadoCita($id, $estado) {
        try {
            // Validar estados permitidos
            $estadosPermitidos = ['confirmada', 'cancelada', 'completada'];
            if (!in_array($estado, $estadosPermitidos)) {
                error_log("Estado no válido en actualizarEstadoCita: " . $estado);
                return false;
            }
            
            $rolUsuario = $_SESSION['rol'] ?? 'usuario';
            $usuarioLogueado = $_SESSION['usuario_id'];
            
            // Verificar permisos
            if ($rolUsuario !== 'admin') {
                // Si no es admin, solo puede actualizar sus propias citas
                $query = "SELECT usuario_id FROM citas WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $cita = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$cita || $cita['usuario_id'] != $usuarioLogueado) {
                    error_log("Usuario " . $usuarioLogueado . " no puede actualizar la cita " . $id);
                    return false;
                }
            }
            
            // Si es admin o es su propia cita, proceder con la actualización
            $query = "UPDATE citas SET estado = :estado WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':estado', $estado);
            
            $resultado = $stmt->execute();
            
            if ($resultado) {
                error_log("Estado de cita $id actualizado a: $estado");
            } else {
                error_log("Error al actualizar estado de cita $id");
            }
            
            return $resultado;
            
        } catch (PDOException $e) {
            error_log("Error en actualizarEstadoCita: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener todas las citas de un usuario específico
     */
    public function obtenerCitasUsuario($usuarioId) {
        try {
            $query = "SELECT 
                        c.id,
                        c.usuario_id,
                        c.fecha,
                        c.hora,
                        c.motivo,
                        c.estado,
                        c.nombre_cliente,
                        u.nombre as nombre_usuario,
                        u.email as email_usuario
                    FROM citas c 
                    LEFT JOIN usuarios u ON c.usuario_id = u.id 
                    WHERE c.usuario_id = :usuario_id
                    ORDER BY c.fecha DESC, c.hora DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error en obtenerCitasUsuario: " . $e->getMessage());
            return [];
        }
    }

    // Función temporal para debug - ver qué citas existen en una fecha/hora
    public function debugCitasEnHorario($fecha, $hora) {
        try {
            $sql = "SELECT * FROM citas WHERE fecha = ? AND hora = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$fecha, $hora]);
            $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("=== CITAS EN " . $fecha . " " . $hora . " ===");
            if (empty($citas)) {
                error_log("No hay citas en ese horario");
            } else {
                foreach ($citas as $cita) {
                    error_log("Cita ID: " . $cita['id'] . 
                             ", Usuario: " . $cita['usuario_id'] . 
                             ", Estado: " . $cita['estado'] . 
                             ", Cliente: " . ($cita['nombre_cliente'] ?? 'N/A'));
                }
            }
            
        } catch (PDOException $e) {
            error_log("Error en debugCitasEnHorario: " . $e->getMessage());
        }
    }
} 