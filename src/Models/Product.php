<?php
// File: src/Models/Product.php
namespace Models;

use Core\Model;

class Product extends Model {
    
    protected $table = 'products';
    
    /**
     * Lấy tất cả sản phẩm với thông tin danh mục
     * @return array
     */
    public function getAllWithCategory() {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                ORDER BY p.created_at DESC";
        return $this->query($sql);
    }
    
    /**
     * Lấy sản phẩm theo danh mục
     * @param int $categoryId
     * @return array
     */
    public function getByCategory($categoryId) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = :category_id 
                ORDER BY p.created_at DESC";
        return $this->query($sql, ['category_id' => $categoryId]);
    }
    
    /**
     * Tìm kiếm sản phẩm
     * @param string $keyword
     * @return array
     */
    public function search($keyword) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.name LIKE :keyword 
                   OR p.description LIKE :keyword 
                ORDER BY p.created_at DESC";
        $keyword = "%{$keyword}%";
        return $this->query($sql, ['keyword' => $keyword]);
    }
    
    /**
     * Lấy sản phẩm với thông tin danh mục theo ID
     * @param int $id
     * @return array|false
     */
    public function getByIdWithCategory($id) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = :id 
                LIMIT 1";
        $result = $this->query($sql, ['id' => $id]);
        return $result ? $result[0] : false;
    }
    
    /**
     * Cập nhật số lượng sản phẩm
     * @param int $id
     * @param int $quantity
     * @return bool
     */
    public function updateQuantity($id, $quantity) {
        return $this->update($id, ['quantity' => $quantity]);
    }
    
    /**
     * Giảm số lượng sản phẩm khi bán
     * @param int $id
     * @param int $quantity
     * @return bool
     */
    public function decreaseQuantity($id, $quantity) {
        $product = $this->getById($id);
        if ($product && $product['quantity'] >= $quantity) {
            $newQuantity = $product['quantity'] - $quantity;
            return $this->updateQuantity($id, $newQuantity);
        }
        return false;
    }
}
?>

