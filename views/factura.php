<?php
// views/factura.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['logged_in'])) { header("Location: login.php"); exit(); }
require_once __DIR__ . '/../config/database.php';
$database = new Database();
$db = $database->getConnection();
// ======================================================
// VALIDAR ID VENTA
// ======================================================
if (!isset($_GET['id_venta'])) { die("Venta no especificada."); }
$idVenta = intval($_GET['id_venta']);
// ======================================================
// OBTENER DATOS DE LA VENTA
// ======================================================
$sqlVenta = "SELECT v.id_venta, v.fecha_transaccion, v.metodo_pago, v.total_pagado_bob, c.nombre_completo, c.celular FROM ventas v INNER JOIN clientes c ON v.id_cliente = c.id_cliente WHERE v.id_venta = :id_venta";
$stmtVenta = $db->prepare($sqlVenta);
$stmtVenta->execute([':id_venta' => $idVenta]);
$venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);
if (!$venta) { die("Venta no encontrada."); }
// ======================================================
// OBTENER DETALLE PRODUCTOS
// ======================================================
$sqlDetalle = "SELECT d.cantidad, d.precio_unitario, d.subtotal, p.nombre_producto FROM detalle_ventas d INNER JOIN productos p ON d.id_producto = p.id_producto WHERE d.id_venta = :id_venta";
$stmtDetalle = $db->prepare($sqlDetalle);
$stmtDetalle->execute([':id_venta' => $idVenta]);
$detalle = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Factura Venta</title>
<link rel="stylesheet" href="../public/css/css_opciones/factura.css">
</head>
<body>
<div class="TICKET">
    <div class="CENTRO"><h2>💈 BARBER SHOP</h2><p>Sistema de Ventas</p></div>
    <div class="LINEA"></div>
    <p><strong>Venta #:</strong> <?= $venta['id_venta']; ?></p>
    <p><strong>Fecha:</strong> <?= $venta['fecha_transaccion']; ?></p>
    <p><strong>Cliente:</strong> <?= htmlspecialchars($venta['nombre_completo']); ?></p>
    <p><strong>Celular:</strong> <?= htmlspecialchars($venta['celular']); ?></p>
    <p><strong>Método Pago:</strong> <?= htmlspecialchars($venta['metodo_pago']); ?></p>
    <div class="LINEA"></div>
    <table>
        <thead><tr><th>Producto</th><th>Cant</th><th>SubTotal</th></tr></thead>
        <tbody>
            <?php foreach($detalle as $d): ?>
            <tr>
                <td><?= htmlspecialchars($d['nombre_producto']); ?></td>
                <td><?= $d['cantidad']; ?></td>
                <td><?= number_format($d['subtotal'],2); ?> Bs</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="LINEA"></div>
    <div class="TOTAL">TOTAL: <?= number_format($venta['total_pagado_bob'],2); ?> Bs</div>
    <div class="LINEA"></div>
    <div class="CENTRO"><p>¡Gracias por su compra!</p></div>
    <button class="BTN" onclick="window.print()">🖨 Imprimir Ticket</button>
</div>
</body>
</html>