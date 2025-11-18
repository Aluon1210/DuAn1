<?php
// File: src/Models/Order.php
namespace Models;

use Core\Model;

class Order extends Model {
    
    protected $table = 'orders';
    
    /**
     * Lấy đơn hàng kèm thông tin user
     * @param int $id
     * @return array|false
     */
    public function getByIdWithUser($id) {
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email 
                FROM {$this->table} o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.id = :id 
                LIMIT 1";
        $result = $this->query($sql, ['id' => $id]);
        return $result ? $result[0] : false;
    }
    
    /**
     * Lấy đơn hàng theo user
     * @param int $userId
     * @return array
     */
    public function getByUserId($userId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC";
        return $this->query($sql, ['user_id' => $userId]);
    }
    
    /**
     * Tạo đơn hàng mới với chi tiết
     * @param array $orderData Dữ liệu đơn hàng
     * @param array $orderDetails Mảng các chi tiết đơn hàng
     * @return int|false ID đơn hàng mới hoặc false nếu lỗi
     */
    public function createWithDetails($orderData, $orderDetails) {
        try {
            $this->db->beginTransaction();
            
            // Tạo đơn hàng
            $orderId = $this->create($orderData);
            
            if ($orderId) {
                // Tạo chi tiết đơn hàng
                $orderDetailModel = new \Models\OrderDetail();
                foreach ($orderDetails as $detail) {
                    $detail['order_id'] = $orderId;
                    $orderDetailModel->create($detail);
                    
                    // Giảm số lượng sản phẩm
                    $productModel = new \Models\Product();
                    $productModel->decreaseQuantity($detail['product_id'], $detail['quantity']);
                }
                
                $this->db->commit();
                return $orderId;
            }
            
            $this->db->rollBack();
            return false;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
?>

