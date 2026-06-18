<?php
// controllers/DashboardController.php
require_once __DIR__ . '/../config/database.php';

class DashboardController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getIngresosHoy() {
        try {
            $query = "SELECT SUM(total_pagado_bob) as total FROM ventas WHERE DATE(fecha_transaccion) = CURDATE()";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return floatval($res['total'] ?? 0.00);
        } catch (PDOException $e) {
            return 0.00;
        }
    }

    public function getProductosAlertaStock() {
        try {
            $query = "SELECT COUNT(*) as total FROM productos WHERE stock_actual <= stock_minimo";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return intval($res['total'] ?? 0);
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getCitasHoy() {
        try {
            $query = "SELECT COUNT(*) as total FROM citas WHERE fecha_cita = CURDATE()";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return intval($res['total'] ?? 0);
        } catch (PDOException $e) {
            return 0;
        }
    }
}