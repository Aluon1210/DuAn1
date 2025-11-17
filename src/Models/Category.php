<?php
// File: src/Models/Category.php
namespace Models;

use Core\Model;

class Category extends Model {
    
    protected $table = 'categories';
    
    /**
     * Lấy danh mục kèm số lượng sản phẩm
     * @return array
     */
    public function getAllWithProductCount() {
        $sql = "SELECT c.*, COUNT(p.id) as product_count 
                FROM {$this->table} c 
                LEFT JOIN products p ON c.id = p.category_id 
                GROUP BY c.id 
                ORDER BY c.name ASC";
        return $this->query($sql);
    }
    
    /**
     * Lấy danh mục theo tên
     * @param string $name
     * @return array|false
     */
    public function getByName($name) {
        return $this->getOne(['name' => $name]);
    }
}
?>

