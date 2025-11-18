<?php
// File: src/Models/Comment.php
namespace Models;

use Core\Model;
use Core\IdGenerator;

class Comment extends Model {
    
    protected $table = 'comments';
    
    /**
     * Lấy tất cả comment của một sản phẩm
     * @param string $productId
     * @return array
     */
    public function getByProductId($productId) {
        $sql = "SELECT c.*, u.FullName as user_name 
                FROM {$this->table} c 
                LEFT JOIN users u ON c._UserName_Id = u._UserName_Id 
                WHERE c.Product_Id = :product_id 
                ORDER BY c.Create_at DESC";
        return $this->query($sql, ['product_id' => $productId]);
    }
    
    /**
     * Tạo comment mới
     * @param array $data
     * @return string|false Comment_Id hoặc false nếu lỗi
     */
    public function createComment($data) {
        try {
            // Generate Comment_Id theo format cm+0000000001
            $commentId = IdGenerator::generate('cm+', $this->table, 'Comment_Id', 10);
            
            $dbData = [
                'Comment_Id' => $commentId,
                'Content' => $data['content'] ?? $data['Content'] ?? '',
                'Create_at' => date('Y-m-d'),
                '_UserName_Id' => $data['user_id'] ?? $data['_UserName_Id'] ?? '',
                'Product_Id' => $data['product_id'] ?? $data['Product_Id'] ?? ''
            ];
            
            if (empty($dbData['Content']) || empty($dbData['_UserName_Id']) || empty($dbData['Product_Id'])) {
                error_log("Comment creation failed: Missing required fields");
                return false;
            }
            
            if ($this->create($dbData)) {
                return $dbData['Comment_Id'];
            }
            return false;
        } catch (\PDOException $e) {
            error_log("Comment creation SQL Error: " . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            error_log("Comment creation Error: " . $e->getMessage());
            return false;
        }
    }
}
?>

