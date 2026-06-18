<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

$database = new Database();
$db = $database->getConnection();

// ======================================================
// CONSULTAR VENTAS
// ======================================================

$sql = "
    SELECT
        v.id_venta,
        v.fecha_transaccion,
        v.total_pagado_bob,

        c.nombre_completo

    FROM ventas v

    INNER JOIN clientes c
    ON v.id_cliente = c.id_cliente

    ORDER BY v.id_venta DESC
";

$stmt = $db->prepare($sql);

$stmt->execute();

$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/layouts/header.php';
?>
<div class="PANEL">

    <?php include __DIR__ . '/layouts/sidebar.php'; ?>

    <div class="CONTENIDO">

        <?php include __DIR__ . '/layouts/navbar.php'; ?>

        <div class="BLOQUE">
            <div class="HEADER">
                <h2>
                    Historial de Ventas
                </h2>
            </div>

            <table class="TABLA">
                <thead>
                    <tr>
                        <th>ID Venta</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Factura</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($ventas as $v): ?>
                        <tr>
                            <td>
                                #<?php echo $v['id_venta']; ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($v['nombre_completo']); ?>
                            </td>
                            <td>
                                <?php echo $v['fecha_transaccion']; ?>
                            </td>
                            <td>
                                <strong>
                                    <?php echo number_format($v['total_pagado_bob'],2); ?> Bs
                                </strong>
                            </td>
                            <td>
                                <a
                                    class="BTN"
                                    href="factura.php?id_venta=<?php echo $v['id_venta']; ?>">
                                    Ver Factura
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>