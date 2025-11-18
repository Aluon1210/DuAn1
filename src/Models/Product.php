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
        try {
            $sql = "SELECT p.*, 
                           c.Name as category_name,
                           p.Product_Id as id,
                           p.Name as name,
                           p.Description as description,
                           p.Price as price,
                           p.Quantity as quantity,
                           p.Image as image,
                           p.Category_Id as category_id,
                           p.Branch_Id as branch_id,
                           p.Create_at as created_at,
                           p.Product_View as product_view
                    FROM {$this->table} p 
                    LEFT JOIN catogory c ON p.Category_Id = c.Category_Id 
                    ORDER BY p.Create_at DESC";
            $results = $this->query($sql);
            // Query đã có alias, không cần normalize lại
            // Kiểm tra kết quả
            if ($results === false || empty($results)) {
                return [];
            }
            return $results;
        } catch (\PDOException $e) {
            // Log lỗi SQL chi tiết
            error_log("SQL Error in getAllWithCategory: " . $e->getMessage());
            error_log("SQL: " . $sql);
            return [];
        } catch (\Exception $e) {
            // Log lỗi và trả về mảng rỗng
            error_log("Error in getAllWithCategory: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy sản phẩm theo danh mục
     * @param string $categoryId
     * @return array
     */
    public function getByCategory($categoryId) {
        try {
            $sql = "SELECT p.*, 
                           c.Name as category_name,
                           p.Product_Id as id,
                           p.Name as name,
                           p.Description as description,
                           p.Price as price,
                           p.Quantity as quantity,
                           p.Image as image,
                           p.Category_Id as category_id,
                           p.Branch_Id as branch_id,
                           p.Create_at as created_at,
                           p.Product_View as product_view
                    FROM {$this->table} p 
                    LEFT JOIN catogory c ON p.Category_Id = c.Category_Id 
                    WHERE p.Category_Id = :category_id 
                    ORDER BY p.Create_at DESC";
            $results = $this->query($sql, ['category_id' => $categoryId]);
            // Query đã có alias, không cần normalize lại
            return $results ? $results : [];
        } catch (\Exception $e) {
            error_log("Error in getByCategory: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Tìm kiếm sản phẩm
     * @param string $keyword
     * @return array
     */
    public function search($keyword) {
        try {
            $sql = "SELECT p.*, 
                           c.Name as category_name,
                           p.Product_Id as id,
                           p.Name as name,
                           p.Description as description,
                           p.Price as price,
                           p.Quantity as quantity,
                           p.Image as image,
                           p.Category_Id as category_id,
                           p.Branch_Id as branch_id,
                           p.Create_at as created_at,
                           p.Product_View as product_view
                    FROM {$this->table} p 
                    LEFT JOIN catogory c ON p.Category_Id = c.Category_Id 
                    WHERE p.Name LIKE :keyword 
                       OR p.Description LIKE :keyword 
                    ORDER BY p.Create_at DESC";
            $keywordParam = "%{$keyword}%";
            $results = $this->query($sql, ['keyword' => $keywordParam]);
            // Query đã có alias, không cần normalize lại
            return $results ? $results : [];
        } catch (\Exception $e) {
            error_log("Error in search: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy sản phẩm với thông tin danh mục theo ID
     * @param string $id
     * @return array|false
     */
    public function getByIdWithCategory($id) {
        try {
            $sql = "SELECT p.*, 
                           c.Name as category_name,
                           p.Product_Id as id,
                           p.Name as name,
                           p.Description as description,
                           p.Price as price,
                           p.Quantity as quantity,
                           p.Image as image,
                           p.Category_Id as category_id,
                           p.Branch_Id as branch_id,
                           p.Create_at as created_at,
                           p.Product_View as product_view
                    FROM {$this->table} p 
                    LEFT JOIN catogory c ON p.Category_Id = c.Category_Id 
                    WHERE p.Product_Id = :id 
                    LIMIT 1";
            $result = $this->query($sql, ['id' => $id]);
            // Query đã có alias, trả về phần tử đầu tiên
            return $result && !empty($result) ? $result[0] : false;
        } catch (\Exception $e) {
            error_log("Error in getByIdWithCategory: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Override getById để dùng Product_Id
     */
    public function getById($id) {
        $product = $this->getOne(['Product_Id' => $id]);
        return $product ? $this->normalizeSingleProduct($product) : false;
    }
    
    /**
     * Override getAll để trả về dữ liệu chuẩn hóa
     */
    public function getAll($conditions = []) {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $params = [];
            
            if (!empty($conditions)) {
                $where = [];
                foreach ($conditions as $key => $value) {
                    $dbKey = $this->mapColumnName($key);
                    $where[] = "$dbKey = :$key";
                    $params[$key] = $value;
                }
                $sql .= " WHERE " . implode(" AND ", $where);
            }
            
            $sql .= " ORDER BY Create_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $this->normalizeProductData($results);
        } catch (\Exception $e) {
            error_log("Error in getAll: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Chuẩn hóa dữ liệu sản phẩm từ DB sang format code
     */
    private function normalizeProductData($products) {
        if (empty($products)) {
            return [];
        }
        
        $normalized = [];
        foreach ($products as $product) {
            $normalized[] = $this->normalizeSingleProduct($product);
        }
        return $normalized;
    }
    
    /**
     * Chuẩn hóa một sản phẩm
     */
    private function normalizeSingleProduct($product) {
        // Nếu đã được normalize rồi (có cả id và name ở dạng lowercase) thì return luôn
        if (isset($product['id']) && isset($product['name']) && !isset($product['Product_Id'])) {
            return $product;
        }
        
        // Normalize từ database format sang code format
        return [
            'id' => $product['id'] ?? $product['Product_Id'] ?? '',
            'name' => $product['name'] ?? $product['Name'] ?? '',
            'description' => $product['description'] ?? $product['Description'] ?? '',
            'price' => (int)($product['price'] ?? $product['Price'] ?? 0),
            'quantity' => (int)($product['quantity'] ?? $product['Quantity'] ?? 0),
            'image' => $product['image'] ?? $product['Image'] ?? '',
            'category_id' => $product['category_id'] ?? $product['Category_Id'] ?? '',
            'branch_id' => $product['branch_id'] ?? $product['Branch_Id'] ?? '',
            'created_at' => $product['created_at'] ?? $product['Create_at'] ?? '',
            'product_view' => (int)($product['product_view'] ?? $product['Product_View'] ?? 0),
            'category_name' => $product['category_name'] ?? ''
        ];
    }
    
    /**
     * Map tên cột từ code sang database
     */
    private function mapColumnName($key) {
        $map = [
            'id' => 'Product_Id',
            'name' => 'Name',
            'description' => 'Description',
            'price' => 'Price',
            'quantity' => 'Quantity',
            'image' => 'Image',
            'category_id' => 'Category_Id',
            'branch_id' => 'Branch_Id',
            'created_at' => 'Create_at',
            'product_view' => 'Product_View'
        ];
        return $map[$key] ?? $key;
    }
    
    /**
     * Cập nhật số lượng sản phẩm
     * @param string $id
     * @param int $quantity
     * @return bool
     */
    public function updateQuantity($id, $quantity) {
        return $this->update($id, ['Quantity' => $quantity], 'Product_Id');
    }
    
    /**
     * Giảm số lượng sản phẩm khi bán
     * @param string $id
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
    
    /**
     * Override update để hỗ trợ custom primary key
     */
    public function update($id, $data, $primaryKey = 'Product_Id') {
        $set = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            $dbKey = $this->mapColumnName($key);
            $set[] = "$dbKey = :$key";
            $params[$key] = $value;
        }
        
        $setClause = implode(", ", $set);
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$primaryKey} = :id";
        $params['id'] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
?>

