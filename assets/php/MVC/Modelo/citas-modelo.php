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

    /**
     * Obtiene las citas de un usuario específico
     */
    public function obtenerCitasPorUsuario($usuarioId) {
        try {
            // CONSULTA CORREGIDA
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
                    WHERE c.usuario_id = :usuario_id 
                    ORDER BY c.fecha DESC, c.hora DESC";
            
            error_log("=== CONSULTA SQL CITAS POR USUARIO ===");
            error_log("SQL: " . $sql);
            error_log("Usuario ID: " . $usuarioId);
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("=== RESULTADOS DE CONSULTA POR USUARIO ===");
            error_log("Total citas encontradas para usuario $usuarioId: " . count($resultados));
            
            foreach ($resultados as $i => $cita) {
                error_log("Cita $i:");
                error_log("  - ID: " . $cita['id']);
                error_log("  - motivo: " . $cita['motivo']);
                error_log("  - nombre_cliente: " . ($cita['nombre_cliente'] ?? 'NULL'));
                error_log("  - nombre_usuario: " . ($cita['nombre_usuario'] ?? 'NULL'));
            }
            
            return $resultados;
            
        } catch (PDOException $e) {
            error_log("Error en obtenerCitasPorUsuario: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Crea una nueva cita - AHORA con creado_por
     */
    public function crearCita($usuarioId, $fecha, $hora, $motivo, $nombreCliente = null) {
        try {
            error_log("=== DEBUG crearCita MODELO ===");
            error_log("usuarioId: " . $usuarioId);
            error_log("fecha: " . $fecha);
            error_log("hora: " . $hora);
            error_log("motivo: " . $motivo);
            error_log("nombreCliente: " . $nombreCliente);

            // IMPORTANTE: Obtener quien está creando la cita
            $creadoPor = $_SESSION['usuario_id'];
            error_log("creadoPor (usuario logueado): " . $creadoPor);

            if (!$this->db) {
                error_log("ERROR: No hay conexión a la base de datos");
                return false;
            }

            // SQL actualizado con creado_por
            $sql = "INSERT INTO citas (usuario_id, creado_por, fecha, hora, motivo, nombre_cliente, estado) 
                    VALUES (:usuario_id, :creado_por, :fecha, :hora, :motivo, :nombre_cliente, 'pendiente')";
            
            error_log("SQL Query: " . $sql);
            
            $stmt = $this->db->prepare($sql);
            
            if (!$stmt) {
                error_log("Error al preparar statement: " . print_r($this->db->errorInfo(), true));
                return false;
            }
            
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->bindParam(':creado_por', $creadoPor, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':hora', $hora);
            $stmt->bindParam(':motivo', $motivo);
            $stmt->bindParam(':nombre_cliente', $nombreCliente);
            
            $resultado = $stmt->execute();
            
            if ($resultado) {
                error_log("✅ Cita creada exitosamente con creado_por: " . $creadoPor);
                return $this->db->lastInsertId();
            } else {
                error_log("❌ Error al ejecutar: " . print_r($stmt->errorInfo(), true));
                return false;
            }
            
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
     * Obtener SOLO las citas que el usuario creó
     */
    public function obtenerCitasUsuario($usuarioId) {
        try {
            $rolUsuario = $_SESSION['rol'] ?? 'usuario';
            
            if ($rolUsuario === 'admin') {
                // Admin ve todas las citas + quien las creó
                $query = "SELECT c.*, u.nombre as usuario_nombre, creador.nombre as creado_por_nombre 
                         FROM citas c 
                         LEFT JOIN usuarios u ON c.usuario_id = u.id 
                         LEFT JOIN usuarios creador ON c.creado_por = creador.id
                         ORDER BY c.fecha DESC, c.hora DESC";
                $stmt = $this->db->prepare($query);
            } else {
                // Usuario normal SOLO ve las citas que ÉL creó
                $query = "SELECT c.*, u.nombre as usuario_nombre 
                         FROM citas c 
                         LEFT JOIN usuarios u ON c.usuario_id = u.id 
                         WHERE c.creado_por = :creado_por 
                         ORDER BY c.fecha DESC, c.hora DESC";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':creado_por', $usuarioId, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Citas obtenidas para usuario " . $usuarioId . " (rol: " . $rolUsuario . "): " . count($citas));
            
            return $citas;
        } catch (PDOException $e) {
            error_log("Error en obtenerCitasUsuario: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener cita por ID - Solo si el usuario la creó
     */
    public function obtenerCitaPorId($id, $usuarioId = null) {
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
                // Usuario normal solo puede ver citas que él creó
                $query = "SELECT c.*, u.nombre as usuario_nombre 
                         FROM citas c 
                         LEFT JOIN usuarios u ON c.usuario_id = u.id 
                         WHERE c.id = :id AND c.creado_por = :creado_por";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':creado_por', $usuarioId, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerCitaPorId: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualizar cita - Solo si el usuario la creó
     */
    public function actualizarCita($id, $usuarioId, $fecha, $hora, $motivo, $estado, $nombreCliente = null) {
        try {
            $rolUsuario = $_SESSION['rol'] ?? 'usuario';
            $usuarioLogueado = $_SESSION['usuario_id'];
            
            // Verificar permisos
            if ($rolUsuario !== 'admin') {
                $query = "SELECT creado_por FROM citas WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $cita = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$cita || $cita['creado_por'] != $usuarioLogueado) {
                    error_log("Usuario " . $usuarioLogueado . " no tiene permisos para editar cita " . $id);
                    return false;
                }
            }
            
            $query = "UPDATE citas SET usuario_id = :usuario_id, fecha = :fecha, hora = :hora, 
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
     * Eliminar cita - Solo si el usuario la creó
     */
    public function eliminarCita($id) {
        try {
            $rolUsuario = $_SESSION['rol'] ?? 'usuario';
            $usuarioLogueado = $_SESSION['usuario_id'];
            
            // Verificar permisos
            if ($rolUsuario !== 'admin') {
                $query = "SELECT creado_por FROM citas WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $cita = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$cita || $cita['creado_por'] != $usuarioLogueado) {
                    error_log("Usuario " . $usuarioLogueado . " no tiene permisos para eliminar cita " . $id);
                    return false;
                }
            }
            
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
                    WHERE estado IN ('pendiente', 'confirmada') 
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
     * Obtener todos los usuarios (para el admin)
     */
    public function obtenerTodosLosUsuarios() {
        try {
            $query = "SELECT id, nombre, email FROM usuarios ORDER BY nombre ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerTodosLosUsuarios: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Actualizar estado de cita - Solo si el usuario la creó
     */
    public function actualizarEstadoCita($id, $estado) {
        try {
            $rolUsuario = $_SESSION['rol'] ?? 'usuario';
            $usuarioLogueado = $_SESSION['usuario_id'];
            
            // Verificar permisos
            if ($rolUsuario !== 'admin') {
                $query = "SELECT creado_por FROM citas WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $cita = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$cita || $cita['creado_por'] != $usuarioLogueado) {
                    error_log("Usuario " . $usuarioLogueado . " no tiene permisos para actualizar estado de cita " . $id);
                    return false;
                }
            }
            
            $query = "UPDATE citas SET estado = :estado WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':estado', $estado);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en actualizarEstadoCita: " . $e->getMessage());
            return false;
        }
    }
} 