<?php
// controllers/CajaController.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

class CajaController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * NUEVO MÉTODO UNIFICADO: Busca la caja activa para el cajero logueado.
     * Esto reemplaza por completo al antiguo CajaCajeroController.
     */
    public function obtenerCajaActiva($id_cajero) {
        try {
            $query = "SELECT id_caja, id_cajero, monto_apertura_bob, estado_caja 
                      FROM control_cajas 
                      WHERE id_cajero = :id_cajero AND estado_caja = 'Abierta' 
                      LIMIT 1";
                      
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id_cajero' => $id_cajero]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    // 1. Cargar el historial de arqueos en el select superior (CORREGIDO: id_cajero)
    public function listarTodasLasCajas() {
        try {
            $query = "SELECT c.*, u.nombre_completo as nombre_usuario 
                      FROM control_cajas c
                      INNER JOIN usuarios u ON c.id_cajero = u.id_usuario
                      ORDER BY c.fecha_apertura DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // 2. Calcular los montos reales cruzando las tablas 'control_cajas', 'ventas' y 'movimientos_caja'
    public function obtenerTotalesCaja($id_caja) {
        try {
            // Datos base del turno
            $queryCaja = "SELECT monto_apertura_bob, estado_caja FROM control_cajas WHERE id_caja = :id";
            $stmtCaja = $this->db->prepare($queryCaja);
            $stmtCaja->execute([':id' => $id_caja]);
            $caja = $stmtCaja->fetch(PDO::FETCH_ASSOC);
            
            if (!$caja) return null;

            $monto_apertura = $caja['monto_apertura_bob'];

            // SUMA 1: Ventas reales de la barbería (Insumos y Servicios)
            $queryVentas = "SELECT COALESCE(SUM(total_pagado_bob), 0) FROM ventas WHERE id_caja = :id";
            $stmtV = $this->db->prepare($queryVentas);
            $stmtV->execute([':id' => $id_caja]);
            $totalVentas = $stmtV->fetchColumn();

            // SUMA 2: Inyecciones de efectivo manuales (Ajustes positivos)
            $queryAjustesIn = "SELECT COALESCE(SUM(monto), 0) FROM movimientos_caja WHERE id_caja = :id AND tipo = 'ingreso'";
            $stmtAi = $this->db->prepare($queryAjustesIn);
            $stmtAi->execute([':id' => $id_caja]);
            $totalAjustesIn = $stmtAi->fetchColumn();
            
            $ingresos_globales = $totalVentas + $totalAjustesIn;

            // SUMA 3: Salidas de dinero / Gastos (Ajustes negativos)
            $queryEgresos = "SELECT COALESCE(SUM(monto), 0) FROM movimientos_caja WHERE id_caja = :id AND tipo = 'egreso'";
            $stmtE = $this->db->prepare($queryEgresos);
            $stmtE->execute([':id' => $id_caja]);
            $egresos = $stmtE->fetchColumn();

            return [
                'monto_apertura' => $monto_apertura,
                'ingresos' => $ingresos_globales,
                'egresos' => $egresos,
                'saldo_neto' => ($monto_apertura + $ingresos_globales) - $egresos,
                'estado' => $caja['estado_caja']
            ];
        } catch (PDOException $e) {
            return null;
        }
    }

    // 3. Obtener el flujo diario unificando Ventas y Movimientos Manuales con UNION ALL
    public function obtenerHistorialMovimientos($id_caja) {
        try {
            $query = "SELECT DATE_FORMAT(fecha_transaccion, '%H:%i') as hora, 'ingreso' as tipo, CONCAT('Venta - Nota #', id_venta) as descripcion, total_pagado_bob as monto, 'Sistema' as usuario 
                      FROM ventas WHERE id_caja = :id
                      UNION ALL
                      SELECT DATE_FORMAT(fecha, '%H:%i') as hora, tipo, descripcion, monto, usuario 
                      FROM movimientos_caja WHERE id_caja = :id_caja
                      ORDER BY hora ASC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $id_caja, ':id_caja' => $id_caja]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
// =======================================
// CERRAR CAJA
// =======================================

public function cerrarCaja($idCaja)
{
    try {

        $totales = $this->obtenerTotalesCaja($idCaja);

        $sql = "
            UPDATE control_cajas
            SET
                fecha_cierre = NOW(),
                monto_cierre_sistema_bob = :monto,
                estado_caja = 'Cerrada'
            WHERE id_caja = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':monto' => $totales['saldo_neto'],
            ':id' => $idCaja
        ]);

    } catch(PDOException $e){

        die($e->getMessage());
    }
}


// =======================================
// ABRIR NUEVA CAJA
// =======================================

public function abrirCaja($idCajero, $montoApertura)
{
    try {

        // Verificar si ya tiene caja abierta
        $sqlValidar = "
            SELECT id_caja
            FROM control_cajas
            WHERE id_cajero = :id_cajero
            AND estado_caja = 'Abierta'
            LIMIT 1
        ";

        $stmtValidar = $this->db->prepare($sqlValidar);

        $stmtValidar->execute([
            ':id_cajero' => $idCajero
        ]);

        if($stmtValidar->fetch())
        {
            return false;
        }

        $sql = "
            INSERT INTO control_cajas
            (
                id_cajero,
                fecha_apertura,
                monto_apertura_bob,
                monto_cierre_sistema_bob,
                estado_caja
            )
            VALUES
            (
                :id_cajero,
                NOW(),
                :monto_apertura,
                0,
                'Abierta'
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id_cajero' => $idCajero,
            ':monto_apertura' => $montoApertura
        ]);

    } catch(PDOException $e){

        die($e->getMessage());
    }
}

}

// =======================================
// RECEPTOR GET
// =======================================

if (
    $_SERVER['REQUEST_METHOD'] === 'GET'
    &&
    isset($_GET['action'])
)
{
    $controller = new CajaController();

    if ($_GET['action'] === 'cerrar_caja')
    {
        $idCaja = intval($_GET['id_caja']);

        $controller->cerrarCaja($idCaja);

        header(
            "Location: ../views/caja.php?id_caja=" . $idCaja
        );

        exit();
    }
}


// =======================================
// RECEPTOR POST
// =======================================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    &&
    isset($_POST['action'])
)
{
    $controller = new CajaController();

    if ($_POST['action'] === 'abrir_caja')
    {
        $controller->abrirCaja(
            intval($_SESSION['id_usuario']),
            floatval($_POST['monto_apertura'])
        );

        header("Location: ../views/caja.php");
        exit();
    }
}