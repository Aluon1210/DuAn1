<?php
namespace Models;
use Core\Model;

class Category extends Model {
    
    protected $table = 'catogory'; // Sửa tên bảng theo duan1.sql
    /**
     * Lấy danh mục kèm số lượng sản phẩm
     * @return array
     */
    public function getAllWithProductCount() {
        // return rows aliased to match view expectations (id,name,description)
        $sql = "SELECT c.Category_Id as id, c.Name as name, c.Description as description, 
                       COUNT(p.Product_Id) as product_count
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
        $stmt = $this->db->prepare("SELECT Category_Id as id, Name as name, Description as description FROM {$this->table} WHERE Name = :name");
        $stmt->execute([':name' => $name]); 
        return $this->getOne(['Name' => $name]);
    }
    
    /**
     * Override getById để dùng Category_Id
     */
    public function getById($id) {
        $sql = "SELECT Category_Id as id, Name as name, Description as description FROM {$this->table} WHERE Category_Id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
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

    /**
     * Tạo danh mục mới
     * @param array $data ['name' => '...', 'description' => '...']
     * @return string|false Trả về Category_Id hoặc false
     */
    public function createCategory($data) {
        try {
            $dbData = [
                'Name' => trim($data['name'] ?? ''),
                'Description' => trim($data['description'] ?? '')
            ];
            
            if (empty($dbData['Name'])) {
                error_log("Category creation failed: Name is required");
                return false;
            }
            
            return $this->create($dbData);
        } catch (\PDOException $e) {
            error_log("Category creation SQL Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cập nhật danh mục
     * @param string $id Category_Id
     * @param array $data ['name' => '...', 'description' => '...']
     * @return bool
     */
    public function updateCategory($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET Name = :name, Description = :description WHERE Category_Id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':name' => trim($data['name'] ?? ''),
                ':description' => trim($data['description'] ?? ''),
                ':id' => $id
            ]);
        } catch (\PDOException $e) {
            error_log("Category update SQL Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Xóa danh mục
     * @param string $id Category_Id
     * @return bool
     */
    public function deleteCategory($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE Category_Id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            error_log("Category delete SQL Error: " . $e->getMessage());
            throw $e;
        }
    }
}
?>

