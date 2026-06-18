<?php
// controllers/AgendaController.php

require_once __DIR__ . '/../config/database.php';

class AgendaController {

    private $db;

    public function __construct() {

        $database = new Database();

        $this->db = $database->getConnection();
    }

    // =====================================================
    // LISTAR CLIENTES
    // =====================================================

    public function listarClientes() {

        try {

            $sql = "
                SELECT
                    id_cliente,
                    nombre_completo
                FROM clientes
                ORDER BY nombre_completo ASC
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e){

            return [];
        }
    }

    // =====================================================
    // LISTAR BARBEROS
    // =====================================================

    public function listarBarberos() {

        try {

            $sql = "
                SELECT
                    u.id_usuario,
                    u.nombre_completo
                FROM usuarios u
                WHERE u.id_rol = 3
                AND u.estado_active = 1
                ORDER BY u.nombre_completo ASC
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e){

            return [];
        }
    }

    // =====================================================
    // LISTAR SERVICIOS
    // =====================================================

    public function listarServicios() {

        try {

            $sql = "
                SELECT
                    id_servicio,
                    nombre_servicio,
                    precio_bob,
                    duracion_estimada_min
                FROM servicios
                ORDER BY nombre_servicio ASC
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e){

            return [];
        }
    }

    // =====================================================
    // REGISTRAR CITA
    // =====================================================

    public function registrarCita(
        $idCliente,
        $idBarbero,
        $idServicio,
        $fecha,
        $hora
    ) {

        try {

            // =========================================
            // VALIDAR SI EL BARBERO YA TIENE CITA
            // =========================================

            $sqlValidar = "
                SELECT COUNT(*) as total
                FROM citas
                WHERE id_barbero = :id_barbero
                AND fecha_cita = :fecha
                AND hora_cita = :hora
                AND estado_cita != 'Cancelada'
            ";

            $stmtValidar = $this->db->prepare($sqlValidar);

            $stmtValidar->execute([

                ':id_barbero' => $idBarbero,

                ':fecha' => $fecha,

                ':hora' => $hora
            ]);

            $existe = $stmtValidar->fetch(PDO::FETCH_ASSOC);

            if ($existe['total'] > 0) {

                return [
                    'success' => false,
                    'message' => 'El barbero ya tiene una cita en ese horario.'
                ];
            }

            // =========================================
            // INSERTAR CITA
            // =========================================

            $sql = "
                INSERT INTO citas
                (
                    id_cliente,
                    id_barbero,
                    id_servicio,
                    fecha_cita,
                    hora_cita,
                    estado_cita
                )
                VALUES
                (
                    :id_cliente,
                    :id_barbero,
                    :id_servicio,
                    :fecha,
                    :hora,
                    'Programada'
                )
            ";

            $stmt = $this->db->prepare($sql);

            $resultado = $stmt->execute([

                ':id_cliente' => $idCliente,

                ':id_barbero' => $idBarbero,

                ':id_servicio' => $idServicio,

                ':fecha' => $fecha,

                ':hora' => $hora
            ]);

            if ($resultado) {

                return [
                    'success' => true
                ];
            }

            return [
                'success' => false,
                'message' => 'No se pudo registrar la cita.'
            ];

        } catch(PDOException $e){

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // =====================================================
    // LISTAR CITAS
    // =====================================================

    public function listarCitas() {

        try {

            $sql = "
                SELECT

                    c.id_cita,

                    cli.nombre_completo AS cliente,

                    u.nombre_completo AS barbero,

                    s.nombre_servicio,

                    s.precio_bob,

                    c.fecha_cita,

                    c.hora_cita,

                    c.estado_cita

                FROM citas c

                INNER JOIN clientes cli
                    ON c.id_cliente = cli.id_cliente

                INNER JOIN usuarios u
                    ON c.id_barbero = u.id_usuario

                INNER JOIN servicios s
                    ON c.id_servicio = s.id_servicio

                ORDER BY c.fecha_cita DESC,
                         c.hora_cita ASC
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e){

            return [];
        }
    }

    // =====================================================
    // CAMBIAR ESTADO CITA
    // =====================================================

    public function cambiarEstado(
        $idCita,
        $estado
    ) {

        try {

            $sql = "
                UPDATE citas
                SET estado_cita = :estado
                WHERE id_cita = :id
            ";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([

                ':estado' => $estado,

                ':id' => $idCita
            ]);

        } catch(PDOException $e){

            return false;
        }
    }

public function listarCitasCliente($idCliente)
{
    try {

        $sql = "
            SELECT

                c.id_cita,
                u.nombre_completo AS barbero,
                s.nombre_servicio,
                s.precio_bob,
                c.fecha_cita,
                c.hora_cita,
                c.estado_cita

            FROM citas c

            INNER JOIN usuarios u
                ON c.id_barbero = u.id_usuario

            INNER JOIN servicios s
                ON c.id_servicio = s.id_servicio

            WHERE c.id_cliente = :id_cliente

            ORDER BY c.fecha_cita DESC,
                     c.hora_cita ASC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id_cliente' => $idCliente
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e){

        return [];
    }
}

public function obtenerIdClientePorCorreo($correo)
{
    try {

        $sql = "
            SELECT id_cliente
            FROM clientes
            WHERE correo = :correo
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':correo' => $correo
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch(PDOException $e){

        return null;
    }
}


}

// =========================================================
// RECEPTOR POST
// =========================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $controller = new AgendaController();

    // =============================================
    // REGISTRAR CITA
    // =============================================

    if (
        isset($_POST['action']) &&
        $_POST['action'] === 'registrar_cita'
    ) {

        $resultado = $controller->registrarCita(

            intval($_POST['id_cliente']),

            intval($_POST['id_barbero']),

            intval($_POST['id_servicio']),

            $_POST['fecha_cita'],

            $_POST['hora_cita']
        );

        if ($resultado['success']) {

            header(
                "Location: ../views/agenda.php?status=success"
            );

        } else {

            header(
                "Location: ../views/agenda.php?status=error&msg="
                . urlencode($resultado['message'])
            );
        }

        exit();
    }

    // =============================================
    // CAMBIAR ESTADO
    // =============================================

    if (
        isset($_POST['action']) &&
        $_POST['action'] === 'cambiar_estado'
    ) {

        $controller->cambiarEstado(

            intval($_POST['id_cita']),

            $_POST['estado']
        );

        header(
            "Location: ../views/agenda.php"
        );

        exit();
    }
}


?>