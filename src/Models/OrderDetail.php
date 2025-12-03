<?php
// File: src/Models/OrderDetail.php
namespace Models;

use Core\Model;

class OrderDetail extends Model {
    
    protected $table = 'order_detail';
    
    /**
     * Lấy chi tiết đơn hàng kèm thông tin sản phẩm và variant
     * @param string $orderId
     * @return array
     */
    public function getByOrderIdWithProduct($orderId) {
        $sql = "SELECT od.*, 
                       p.Product_Id as product_id,
                       p.Name as product_name, 
                       p.Image as product_image,
                       pv.Variant_Id as variant_id,
                       c.Name as color_name,
                       c.Hex_Code as color_hex,
                       s.Name as size_name
                FROM {$this->table} od 
                LEFT JOIN products p ON od.Product_Id = p.Product_Id 
                LEFT JOIN product_variants pv ON od.Variant_Id = pv.Variant_Id
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
                       p.Name as product_name, 
                       p.Image as product_image 
                FROM {$this->table} od 
                LEFT JOIN products p ON od.Product_Id = p.Product_Id";
        return $this->query($sql);
    }
    
    /**
     * Tạo chi tiết đơn hàng
     * @param array $data
     * @return bool
     */
    public function create($data) {
        $orderId = $data['Order_Id'] ?? '';
        $productId = $data['Product_Id'] ?? $data['product_id'] ?? '';
        $variantId = $data['Variant_Id'] ?? $data['variant_id'] ?? null;
        $quantity = $data['quantity'] ?? $data['Quantity'] ?? 0;
        
        if (empty($orderId) || empty($productId)         || $quantity <= 0) {
            error_log("OrderDetail create failed: Missing required fields. Order_Id: $orderId        , Product_Id: $productId, Quantity: $quantity");
            return false;
        }
        
        // Vì Variant_Id là NOT NULL trong databa        se, nếu không có variant_id
        // ta cần tạo một 

variant mặc định hoặc dùng giá trị 0 (nếu được phép)
        // Hoặc chỉ lưu khi có variant_id
        
        // Kiểm tra xem có variant_id không
        if (empty($variantId)) {
            // Nếu không có variant, tạo một variant mặc định cho sản phẩm này
            // Hoặc bỏ qua việc lưu variant_id (nhưng database yêu cầu NOT NULL)
            // Giải pháp: Tạo variant mặc định hoặc dùng giá trị đặc biệt
            
            // Tạm thời: Nếu không có variant, không thể lưu vào order_detail
            // Vì Variant_Id là NOT NULL và có foreign key
            error_log("OrderDetail create failed: Variant_Id is required but not provided for Product_Id: $productId");
            return false;
        }
        
        // Có variant_id, insert bình thường
        $sql = "INSERT INTO {$this->table} (Order_Id, Product_Id, Variant_Id, Quantity) 
                VALUES (:order_id, :product_id, :variant_id, :quantity)";
        $params = [
            ':order_id' => $orderId,
            ':product_id' => $productId,
            ':variant_id' => $variantId,
            ':quantity' => (int)$quantity
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