<?php
// File: src/Models/OrderDetail.php
namespace Models;

use Core\Model;

class OrderDetail extends Model {
    
    protected $table = 'order_detail';
    
    /**
     * Lấy chi tiết đơn hàng kèm thông tin sản phẩm
     * @param int $orderId
     * @return array
     */
    public function getByOrderIdWithProduct($orderId) {
        $sql = "SELECT od.*, p.name as product_name, p.image as product_image 
                FROM {$this->table} od 
                LEFT JOIN products p ON od.product_id = p.id 
                WHERE od.order_id = :order_id";
        return $this->query($sql, ['order_id' => $orderId]);
    }
    
    /**
     * Lấy tất cả chi tiết với thông tin sản phẩm
     * @return array
     */
    public function getAllWithProduct() {
        $sql = "SELECT od.*, p.name as product_name, p.image as product_image 
                FROM {$this->table} od 
                LEFT JOIN products p ON od.product_id = p.id";
        return $this->query($sql);
    }
}
?>

