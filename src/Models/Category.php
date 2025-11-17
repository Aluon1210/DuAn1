<?php
// File: src/Models/Category.php
namespace Models;

use Core\Model;

class Category extends Model {
    
    protected $table = 'catogory'; // Sửa tên bảng theo duan1.sql
    
    /**
     * Lấy danh mục kèm số lượng sản phẩm
     * @return array
     */
    public function getAllWithProductCount() {
        $sql = "SELECT c.*, COUNT(p.Product_Id) as product_count 
                FROM {$this->table} c 
                LEFT JOIN products p ON c.Category_Id = p.Category_Id 
                GROUP BY c.Category_Id 
                ORDER BY c.Name ASC";
        return $this->query($sql);
    }
    
    /**
     * Lấy danh mục theo tên
     * @param string $name
     * @return array|false
     */
    public function getByName($name) {
        return $this->getOne(['Name' => $name]);
    }
    
    /**
     * Override getById để dùng Category_Id
     */
    public function getById($id) {
        return $this->getOne(['Category_Id' => $id]);
    }
    
    /**
     * Override getAll để trả về dữ liệu với alias
     */
    public function getAll($conditions = []) {
        $sql = "SELECT Category_Id as id, Name as name, Description as description FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                // Map tên cột
                $dbKey = $this->mapColumnName($key);
                $where[] = "$dbKey = :$key";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $sql .= " ORDER BY Name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Map tên cột từ code sang database
     */
    private function mapColumnName($key) {
        $map = [
            'id' => 'Category_Id',
            'name' => 'Name',
            'description' => 'Description'
        ];
        return $map[$key] ?? ucfirst($key);
    }
}
?>

