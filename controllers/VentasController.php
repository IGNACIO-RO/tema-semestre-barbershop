<?php
require_once __DIR__ . '/../config/database.php';

class VentasController {

    private $db;

    public function __construct() {

        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function registrarVenta(
    $productosVendidos,
    $idCaja,
    $idCliente,
    $metodoPago
) {

        try {

            $this->db->beginTransaction();

            $totalVenta = 0;

            // =====================================================
            // VALIDAR STOCK Y CALCULAR TOTAL
            // =====================================================

            foreach ($productosVendidos as $item) {

                $cantidad = intval($item['cantidad']);

                $precio = floatval($item['precio']);

                $subtotal = $cantidad * $precio;

                $totalVenta += $subtotal;

                // Verificar stock

                $queryStock = "
                    SELECT stock_actual
                    FROM productos
                    WHERE id_producto = :id
                ";

                $stmtStock = $this->db->prepare($queryStock);

                $stmtStock->execute([
                    ':id' => intval($item['id'])
                ]);

                $producto = $stmtStock->fetch(PDO::FETCH_ASSOC);

                if (!$producto) {

                    throw new Exception(
                        "Producto inexistente."
                    );
                }

                if ($producto['stock_actual'] < $cantidad) {

                    throw new Exception(
                        "Stock insuficiente para el producto."
                    );
                }
            }

            // =====================================================
            // REGISTRAR VENTA
            // =====================================================

$sqlVenta = "
    INSERT INTO ventas
    (
        id_caja,
        id_cliente,
        metodo_pago,
        total_pagado_bob
    )
    VALUES
    (
        :id_caja,
        :id_cliente,
        :metodo_pago,
        :total
    )
";

            $stmtVenta = $this->db->prepare($sqlVenta);

            $stmtVenta->execute([

    ':id_caja' => $idCaja,

    ':id_cliente' => $idCliente,

    ':metodo_pago' => $metodoPago,

    ':total' => $totalVenta
]);

            // Obtener ID de la venta creada

            $idVenta = $this->db->lastInsertId();

            // =====================================================
            // GUARDAR DETALLE Y DESCONTAR STOCK
            // =====================================================

            foreach ($productosVendidos as $item) {

                $cantidad = intval($item['cantidad']);

                $precio = floatval($item['precio']);

                $subtotal = $cantidad * $precio;

                // =========================================
                // INSERT detalle_ventas
                // =========================================

                $sqlDetalle = "
                    INSERT INTO detalle_ventas
                    (
                        id_venta,
                        id_producto,
                        cantidad,
                        precio_unitario,
                        subtotal
                    )
                    VALUES
                    (
                        :id_venta,
                        :id_producto,
                        :cantidad,
                        :precio,
                        :subtotal
                    )
                ";

                $stmtDetalle = $this->db->prepare($sqlDetalle);

                $stmtDetalle->execute([

                    ':id_venta' => $idVenta,

                    ':id_producto' => intval($item['id']),

                    ':cantidad' => $cantidad,

                    ':precio' => $precio,

                    ':subtotal' => $subtotal
                ]);

                // =========================================
                // DESCONTAR STOCK
                // =========================================

                $sqlStock = "
                    UPDATE productos
                    SET stock_actual = stock_actual - :cantidad
                    WHERE id_producto = :id
                ";

                $stmtActualizar = $this->db->prepare($sqlStock);

                $stmtActualizar->execute([

                    ':cantidad' => $cantidad,

                    ':id' => intval($item['id'])
                ]);
            }

            // =====================================================
            // CONFIRMAR TRANSACCIÓN
            // =====================================================

            $this->db->commit();

            return true;

        } catch (Exception $e) {

            if ($this->db->inTransaction()) {

                $this->db->rollBack();
            }

            die("ERROR: " . $e->getMessage());
        }
    }
}


/* =====================================================
   RECEPTOR POST
===================================================== */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        isset($_POST['accion']) &&
        $_POST['accion'] === 'guardar_venta'
    ) {

        $controller = new VentasController();

        $carritoJSON =
            $_POST['carrito_datos'] ?? '[]';

        $productosVendidos =
            json_decode($carritoJSON, true);

        $idCaja =
            intval($_POST['id_caja'] ?? 0);

        $idCliente =
            intval($_POST['id_cliente_hidden'] ?? 0);
        $metodoPago =
    trim($_POST['metodo_pago'] ?? '');

        // =========================================
        // VALIDACIONES
        // =========================================

        if (empty($productosVendidos)) {

            header(
                "Location: ../views/ventas.php?status=carrito_vacio"
            );

            exit();
        }

        if ($idCaja <= 0) {

            header(
                "Location: ../views/ventas.php?status=caja_invalida"
            );

            exit();
        }

        if ($idCliente <= 0) {
            if (
    $metodoPago !== 'EFECTIVO' &&
    $metodoPago !== 'QR' &&
    $metodoPago !== 'TARJETA'
) {

    header(
        "Location: ../views/ventas.php?status=metodo_invalido"
    );

    exit();
}

            header(
                "Location: ../views/ventas.php?status=cliente_invalido"
            );

            exit();
        }

        // =========================================
        // REGISTRAR VENTA
        // =========================================

        $resultado = $controller->registrarVenta(

    $productosVendidos,

    $idCaja,

    $idCliente,

    $metodoPago
);

        if ($resultado) {

            header(
                "Location: ../views/ventas.php?status=success"
            );

        } else {

            header(
                "Location: ../views/ventas.php?status=error_bd"
            );
        }

        exit();
    }
}
?>