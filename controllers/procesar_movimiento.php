<?php
// controllers/procesar_movimiento.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validamos que el usuario esté logueado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../views/login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'guardar_movimiento_admin') {
    
    $id_caja = intval($_POST['id_caja']);
    $tipo = $_POST['tipo_movimiento']; // 'ingreso' o 'egreso'
    $monto = floatval($_POST['monto']);
    $descripcion = trim($_POST['descripcion']);
    // Detecta dinámicamente quién es el cajero logueado desde la sesión
$usuario_actual = 'Cajero';

if (isset($_SESSION['nombre'])) {
    $usuario_actual = $_SESSION['nombre'];
}
elseif (isset($_SESSION['nombre_completo'])) {
    $usuario_actual = $_SESSION['nombre_completo'];
}
elseif (isset($_SESSION['nombre_usuario'])) {
    $usuario_actual = $_SESSION['nombre_usuario'];
}
elseif (isset($_SESSION['usuario'])) {
    $usuario_actual = $_SESSION['usuario'];
}

    if ($id_caja > 0 && $monto > 0 && !empty($descripcion)) {
        try {
            $database = new Database();
            $db = $database->getConnection();

            // Insertamos el movimiento manual en la nueva tabla
            $query = "INSERT INTO movimientos_caja (id_caja, tipo, descripcion, monto, usuario) 
                      VALUES (:id_caja, :tipo, :descripcion, :monto, :usuario)";
            
            $stmt = $db->prepare($query);
            $resultado = $stmt->execute([
                ':id_caja' => $id_caja,
                ':tipo' => $tipo,
                ':descripcion' => $descripcion,
                ':monto' => $monto,
                ':usuario' => $usuario_actual
            ]);

            if ($resultado) {
                // Si todo sale bien, regresa a la vista de caja manteniendo seleccionada la misma caja
                header("Location: ../views/caja.php?id_caja=" . $id_caja . "&status=success");
                exit();
            }
        } catch (PDOException $e) {
            header("Location: ../views/caja.php?id_caja=" . $id_caja . "&status=error");
            exit();
        }
    } else {
        header("Location: ../views/caja.php?id_caja=" . $id_caja . "&status=invalid");
        exit();
    }
}