<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbershop Elite - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../public/css/sidebar.css">
    <link rel="stylesheet" href="../public/css/navbar.css">
<!--  AQUI ESTAN TODOS LOS CSS QUE ESTAMOS USANDO BRO-->
    <link rel="stylesheet" href="../public/css/css_opciones/dashboard.css">
    <link rel="stylesheet" href="../public/css/css_opciones/agenda.css">
    <link rel="stylesheet" href="../public/css/css_opciones/caja.css">
    <link rel="stylesheet" href="../public/css/css_opciones/ventas.css">
    <link rel="stylesheet" href="../public/css/css_opciones/inventario.css">
    <link rel="stylesheet" href="../public/css/css_opciones/reporte_ventas.css">
    <link rel="stylesheet" href="../public/css/css_opciones/factura.css">
    <link rel="stylesheet" href="../public/css/css_opciones/login.css">
    <link rel="stylesheet" href="../public/css/css_opciones/reporte_servicios.css">
    <link rel="stylesheet" href="../public/css/css_opciones/panel_cliente.css">
    <link rel="stylesheet" href="../public/css/css_opciones/registro.css">
</head>
<body class="bg-light">