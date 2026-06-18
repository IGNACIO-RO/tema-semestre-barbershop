<?php
// controllers/InventarioController.php
require_once __DIR__ . '/../config/database.php';

class InventarioController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Obtener todos los productos con sus relaciones relativas
     */
    public function listarProductos() {
        try {
            // CORREGIDO: Añadidos los campos de IDs y precio_costo_bob explícitamente
            $query = "SELECT p.id_producto, p.nombre_producto, p.stock_actual, p.stock_minimo, 
                             p.precio_costo_bob, p.precio_venta_publico_bob AS precio_venta,
                             p.id_categoria, p.id_marca, p.id_proveedor,
                             m.nombre_marca, c.nombre_categoria, prov.razon_social AS nombre_proveedor
                      FROM productos p
                      LEFT JOIN marcas_productos m ON p.id_marca = m.id_marca
                      LEFT JOIN categorias_productos c ON p.id_categoria = c.id_categoria
                      LEFT JOIN proveedores prov ON p.id_proveedor = prov.id_proveedor
                      ORDER BY p.nombre_producto ASC";
                      
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }




/**
     * Crear un nuevo producto
     */
    public function crearProducto($id_cat, $id_marca, $id_prov, $nombre, $stock, $minimo, $costo, $venta) {
        try {
            $query = "INSERT INTO productos (id_categoria, id_marca, id_proveedor, nombre_producto, stock_actual, stock_minimo, precio_costo_bob, precio_venta_publico_bob) 
                      VALUES (:cat, :marca, :prov, :nombre, :stock, :minimo, :costo, :venta)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':cat' => $id_cat, ':marca' => $id_marca, ':prov' => $id_prov,
                ':nombre' => $nombre, ':stock' => $stock, ':minimo' => $minimo,
                ':costo' => $costo, ':venta' => $venta
            ]);
        } catch (PDOException $e) { return false; }
    }

    /**
     * Modificar un producto existente
     */
    public function editarProducto($id, $id_cat, $id_marca, $id_prov, $nombre, $stock, $minimo, $costo, $venta) {
        try {
            $query = "UPDATE productos SET id_categoria = :cat, id_marca = :marca, id_proveedor = :prov, nombre_producto = :nombre, 
                             stock_actual = :stock, stock_minimo = :minimo, precio_costo_bob = :costo, precio_venta_publico_bob = :venta 
                      WHERE id_producto = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':id' => $id, ':cat' => $id_cat, ':marca' => $id_marca, ':prov' => $id_prov,
                ':nombre' => $nombre, ':stock' => $stock, ':minimo' => $minimo,
                ':costo' => $costo, ':venta' => $venta
            ]);
        } catch (PDOException $e) { return false; }
    }

    /**
     * Eliminar físicamente un producto
     */
    public function eliminarProducto($id) {
        try {
            $query = "DELETE FROM productos WHERE id_producto = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) { return false; }
    }
}