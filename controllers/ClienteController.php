<?php
// controllers/ClienteController.php

require_once __DIR__ . '/../config/database.php';

class ClienteController {

    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function listarClientes() {

        $query = "
            SELECT id_cliente, nombre_completo
            FROM clientes
            ORDER BY nombre_completo ASC
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>