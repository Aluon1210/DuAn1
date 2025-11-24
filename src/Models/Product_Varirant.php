<?php
namespace Models;

use Core\Model;
use Model\Models\Product ;
class Product_Varirant extends Model {
    
    protected $table = 'product_varirants';

    /**
     * Lấy variant theo product ID
     * @param string $productId
     * @return array
     */
    public function getByProductId($productId) {
        return $this->getAll(['_Product_Id' => $productId]);
    }
    
    /**
     * Tạo variant mới cho sản phẩm
     * @param array $data
     * @return int|false ID của variant mới hoặc false nếu lỗi
     */
    public function createVariant($data) {
        $dbData = [
            '_Product_Id' => trim($data['_Product_Id'] ?? ''),
            'VariantName' => trim($data['VariantName'] ?? ''),
            'Price' => floatval($data['Price'] ?? 0),
            'Stock' => intval($data['Stock'] ?? 0),
            'SKU' => trim($data['SKU'] ?? '')
        ];
        
    }
}
?>