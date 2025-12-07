<?php
// File: src/Models/OrderDetail.php
namespace Models;

use Core\Model;

class OrderDetail extends Model {
    
    protected $table = 'order_details';
    
    /**
     * Lấy chi tiết đơn hàng kèm thông tin sản phẩm và variant
     * @param string $orderId
     * @return array
     */
    public function getByOrderIdWithProduct($orderId) {
        // order_detail does not store Product_Id directly. Join via product_variants
        $sql = "SELECT od.Order_Id,
                       od.Variant_Id,
                       od.quantity,
                       od.Price,
                       pv.Product_Id as product_id,
                       p.Name as product_name, 
                       p.Image as product_image,
                       c.Name as color_name,
                       c.Hex_Code as color_hex,
                       s.Name as size_name
                FROM {$this->table} od 
                LEFT JOIN product_variants pv ON od.Variant_Id = pv.Variant_Id
                LEFT JOIN products p ON pv.Product_Id = p.Product_Id
                LEFT JOIN colors c ON pv.Color_Id = c.Color_Id
                LEFT JOIN sizes s ON pv.Size_Id = s.Size_Id
                WHERE od.Order_Id = :order_id";
        return $this->query($sql, ['order_id' => $orderId]);
    }
    
    /**
     * Lấy tất cả chi tiết với thông tin sản phẩm
     * @return array
     */
    public function getAllWithProduct() {
        $sql = "SELECT od.*, 
                       pv.Product_Id as product_id,
                       p.Name as product_name, 
                       p.Image as product_image 
                FROM {$this->table} od 
                LEFT JOIN product_variants pv ON od.Variant_Id = pv.Variant_Id
                LEFT JOIN products p ON pv.Product_Id = p.Product_Id";
        return $this->query($sql);
    }
    
    /**
     * Tạo chi tiết đơn hàng
     * @param array $data
     * @return bool
     */
    public function create($data) {
        $orderId = $data['Order_Id'] ?? '';
        $variantId = $data['Variant_Id'] ?? $data['variant_id'] ?? null;
        $quantity = (int)($data['quantity'] ?? $data['Quantity'] ?? 0);
        $price = isset($data['Price']) ? (float)$data['Price'] : (isset($data['price']) ? (float)$data['price'] : 0);

        if (empty($orderId) || empty($variantId) || $quantity <= 0) {
            error_log("OrderDetail create failed: Missing required fields. Order_Id: $orderId, Variant_Id: $variantId, Quantity: $quantity");
            return false;
        }

        // Insert into order_detail: Order_Id, Variant_Id, quantity, Price
        $sql = "INSERT INTO {$this->table} (Order_Id, Variant_Id, quantity, Price) 
                VALUES (:order_id, :variant_id, :quantity, :price)";
        $params = [
            ':order_id' => $orderId,
            ':variant_id' => $variantId,
            ':quantity' => $quantity,
            ':price' => $price
        ];
        
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log("OrderDetail create failed: " . print_r($errorInfo, true));
            }
            return $result;
        } catch (\PDOException $e) {
            error_log("OrderDetail create SQL Error: " . $e->getMessage());
            error_log("SQL: $sql");
            error_log("Params: " . print_r($params, true));
            return false;
        }
    }
}
?>