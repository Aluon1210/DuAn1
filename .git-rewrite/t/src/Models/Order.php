<?php
// File: src/Models/Order.php
namespace Models;

use Core\Model;
use Core\IdGenerator;

class Order extends Model {
    
    protected $table = 'orders';
    
    /**
     * Lấy đơn hàng kèm thông tin user
     * @param string $id
     * @return array|false
     */
    public function getByIdWithUser($id) {
        $sql = "SELECT o.*, u.FullName as user_name, u.Email as user_email 
                FROM {$this->table} o 
                LEFT JOIN users u ON o._UserName_Id = u._UserName_Id 
                WHERE o.Order_Id = :id 
                LIMIT 1";
        $result = $this->query($sql, ['id' => $id]);
        return $result ? $result[0] : false;
    }
    
    /**
     * Lấy đơn hàng theo user
     * @param string $userId
     * @return array
     */
    public function getByUserId($userId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE _UserName_Id = :user_id 
                ORDER BY Order_date DESC";
        return $this->query($sql, ['user_id' => $userId]);
    }
    
    /**
     * Tạo đơn hàng mới
     * @param array $data
     * @return string|false Order_Id hoặc false nếu lỗi
     */
    public function createOrder($data) {
        try {
            // Generate Order_Id theo format or+0000000001
            $orderId = IdGenerator::generate('or+', $this->table, 'Order_Id', 10);
            
            $dbData = [
                'Order_Id' => $orderId,
                'Order_date' => $data['order_date'] ?? $data['Order_date'] ?? date('Y-m-d'),
                'Adress' => $data['address'] ?? $data['Adress'] ?? '',
                'Note' => $data['note'] ?? $data['Note'] ?? '',
                'TrangThai' => $data['status'] ?? $data['TrangThai'] ?? 'pending',
                '_UserName_Id' => $data['user_id'] ?? $data['_UserName_Id'] ?? ''
            ];
            
            if (empty($dbData['_UserName_Id'])) {
                error_log("Order creation failed: Missing user_id");
                return false;
            }
            
            if ($this->create($dbData)) {
                return $dbData['Order_Id'];
            }
            return false;
        } catch (\PDOException $e) {
            error_log("Order creation SQL Error: " . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            error_log("Order creation Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tạo đơn hàng mới với chi tiết
     * @param array $orderData Dữ liệu đơn hàng
     * @param array $orderDetails Mảng các chi tiết đơn hàng
     * @return string|false Order_Id hoặc false nếu lỗi
     */
    public function createWithDetails($orderData, $orderDetails) {
        try {
            $this->db->beginTransaction();
            
            // Tạo đơn hàng
            $orderId = $this->createOrder($orderData);
            
            if ($orderId) {
                // Tạo chi tiết đơn hàng
                $orderDetailModel = new \Models\OrderDetail();
                foreach ($orderDetails as $detail) {
                    $detailData = [
                        'Order_Id' => $orderId,
                        'Product_Id' => $detail['product_id'] ?? $detail['Product_Id'] ?? '',
                        'quantity' => $detail['quantity'] ?? 0
                    ];
                    $orderDetailModel->create($detailData);
                    
                    // Giảm số lượng sản phẩm
                    $productModel = new \Models\Product();
                    $productModel->decreaseQuantity($detailData['Product_Id'], $detailData['quantity']);
                }
                
                $this->db->commit();
                return $orderId;
            }
            
            $this->db->rollBack();
            return false;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Order createWithDetails Error: " . $e->getMessage());
            return false;
        }
    }
}
?>

