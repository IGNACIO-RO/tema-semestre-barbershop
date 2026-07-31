<?php
// helpers/AuthHelper.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Valida la sesión y los roles permitidos para acceder a la vista actual.
 *
 * @param array $rolesPermitidos Lista de IDs de rol autorizados (ej: [1, 2])
 */
function checkAuth(array $rolesPermitidos = []) {
    // 1. Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        // Ruta absoluta desde la raíz del servidor local
        header("Location: /barbershop/views/login.php?error=no_session");
        exit();
    }

    // 2. Si se especificaron roles, verificar si el rol del usuario está permitido
    if (!empty($rolesPermitidos) && !in_array((int)$_SESSION['id_rol'], $rolesPermitidos, true)) {
        // Redireccionar al panel correspondiente de su rol para evitar acceso indebido
        redirigirSegunRol((int)$_SESSION['id_rol']);
    }
}

/**
 * Redirige al usuario a su panel correspondiente según su ID de Rol.
 */
function redirigirSegunRol(int $idRol) {
    switch ($idRol) {
        case 1: // Admin / Administrador
            header("Location: /barbershop/views/dashboard.php?error=forbidden");
            break;
        case 2: // Cajero
            header("Location: /barbershop/views/caja.php?error=forbidden");
            break;
        case 3: // Barbero / Personal
            header("Location: /barbershop/views/agenda.php?error=forbidden");
            break;
        case 4: // Cliente
            header("Location: /barbershop/views/panel_cliente.php?error=forbidden");
            break;
        default:
            header("Location: /barbershop/views/login.php?error=forbidden");
            break;
    }
    exit();
}