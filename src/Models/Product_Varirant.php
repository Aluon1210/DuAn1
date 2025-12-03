<?php
namespace Models;

use Core\Model;
use Core\IdGenerator;

class Product_Varirant extends Model
{
    protected $table = 'product_variants';

    /**
     * Lấy tất cả variant kèm thông tin liên quan.
     */
    public function getAllWithRelations()
    {
        $sql = "SELECT pv.Variant_Id as id,
                   pv.Product_Id as product_id,
                   p.Name as product_name,
                   pv.Color_Id as color_id,
                   c.Name as color_name,
                   c.Hex_Code as color_hex,
                   pv.Size_Id as size_id,
                   s.Name as size_value,
                       pv.Price as price,
                       pv.Quantity_In_Stock as stock,
                   pv.SKU as sku
            FROM {$this->table} pv
            LEFT JOIN products p ON pv.Product_Id = p.Product_Id
            LEFT JOIN colors c ON pv.Color_Id = c.Color_Id
            LEFT JOIN sizes s ON pv.Size_Id = s.Size_Id
            ORDER BY p.Name ASC";

        return $this->query($sql);
    }

    /**
     * Lấy variant theo ID.
     */
    public function getById($id)
    {
        $sql = "SELECT pv.Variant_Id as id,
                   pv.Product_Id as product_id,
                   pv.Color_Id as color_id,
                   pv.Size_Id as size_id,
                       pv.Price as price,
                       pv.Quantity_In_Stock as stock,
                   pv.SKU as sku
            FROM {$this->table} pv
            WHERE pv.Variant_Id = :id
            LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Lấy variant theo product ID.
     */
    public function getByProductId($productId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE Product_Id = :pid";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':pid' => $productId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Lấy dữ liệu chi tiết sản phẩm: variants, colors, sizes
     * Trả về mảng dữ liệu được cấu trúc: {variants, colors, sizes}
     * 
     * @param string $productId ID của sản phẩm
     * @return array {
     *     'variants' => [...],  // Tất cả variants của sản phẩm
     *     'colors' => [...],    // Danh sách colors có sẵn
     *     'sizes' => [...]      // Danh sách sizes có sẵn
     * }
     */
    public function getProductVariantData($productId)
    {
        // Query lấy tất cả variants kèm colors và sizes
        $sql = "SELECT pv.Variant_Id as variant_id,
                       pv.Product_Id as product_id,
                       pv.Color_Id as color_id,
                       c.Color_Id as color_db_id,
                       c.Name as color_name,
                       c.Hex_Code as color_hex_code,
                       pv.Size_Id as size_id,
                       s.Size_Id as size_db_id,
                       s.Name as size_name,
                       s.Type as size_type,
                       pv.Price as price,
                       pv.Quantity_In_Stock as stock,
                       pv.SKU as sku
                FROM {$this->table} pv
                LEFT JOIN colors c ON pv.Color_Id = c.Color_Id
                LEFT JOIN sizes s ON pv.Size_Id = s.Size_Id
                WHERE pv.Product_Id = :pid
                ORDER BY c.Name ASC, s.Name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':pid' => $productId]);
        $variantsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Cấu trúc dữ liệu
        $variants = [];
        $colors = [];
        $sizes = [];
        
        foreach ($variantsData as $row) {
            // Thêm variant
            $variant = [
                'id' => $row['variant_id'],
                'product_id' => $row['product_id'],
                'color_id' => $row['color_id'],
                'size_id' => $row['size_id'],
                'price' => $row['price'],
                'stock' => $row['stock'],
                'sku' => $row['sku']
            ];
            $variants[] = $variant;
            
            // Thêm color (nếu có)
            if ($row['color_db_id']) {
                if (!isset($colors[$row['color_db_id']])) {
                    $colors[$row['color_db_id']] = [
                        'id' => $row['color_db_id'],
                        'name' => $row['color_name'],
                        'hex_code' => $row['color_hex_code']
                    ];
                }
            }
            
            // Thêm size (nếu có)
            if ($row['size_db_id']) {
                if (!isset($sizes[$row['size_db_id']])) {
                    $sizes[$row['size_db_id']] = [
                        'id' => $row['size_db_id'],
                        'name' => $row['size_name'],
                        'type' => $row['size_type']
                    ];
                }
            }
        }
        
        return [
            'variants' => $variants,
            'colors' => array_values($colors),
            'sizes' => array_values($sizes)
        ];
    }

    /**
     * Lấy variants kèm thông tin colors và sizes cho sản phẩm.
     * Tối ưu hóa: 1 query thay vì N+1 queries
     */
    public function getByProductIdWithDetails($productId)
    {
        $sql = "SELECT pv.Variant_Id as id,
                       pv.Product_Id as product_id,
                       pv.Color_Id as color_id,
                       c.Color_Id as color_db_id,
                       c.Name as color_name,
                       c.Hex_Code as color_hex,
                       pv.Size_Id as size_id,
                       s.Size_Id as size_db_id,
                       s.Name as size_value,
                       s.Type as size_description,
                       pv.Price as price,
                       pv.Quantity_In_Stock as stock,
                       pv.SKU as sku
                FROM {$this->table} pv
                LEFT JOIN colors c ON pv.Color_Id = c.Color_Id
                LEFT JOIN sizes s ON pv.Size_Id = s.Size_Id
                WHERE pv.Product_Id = :pid
                ORDER BY c.Name ASC, s.Name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':pid' => $productId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Tạo variant mới cho sản phẩm.
     */
    public function createVariant(array $data)
    {
        $variantId = IdGenerator::generate('PV+', $this->table, 'Variant_Id', 10);

        $payload = [
            'Variant_Id' => $variantId,
            'Product_Id' => trim($data['product_id'] ?? ''),
            'Color_Id' => trim($data['color_id'] ?? ''),
            'Size_Id' => trim($data['size_id'] ?? ''),
            'Price' => (float)($data['price'] ?? 0),
            'Quantity_In_Stock' => (int)($data['stock'] ?? 0),
            'SKU' => trim($data['sku'] ?? '')
        ];

        if (empty($payload['Product_Id'])) {
            return false;
        }

        return $this->create($payload);
    }

    /**
     * Cập nhật variant.
     */
    public function updateVariant(string $id, array $data)
    {
        $sql = "UPDATE {$this->table}
                   SET Product_Id = :product_id,
                       Color_Id = :color_id,
                       Size_Id = :size_id,
                       Price = :price,
                       Quantity_In_Stock = :stock,
                       SKU = :sku
                 WHERE Variant_Id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':product_id' => trim($data['product_id'] ?? ''),
            ':color_id' => trim($data['color_id'] ?? ''),
            ':size_id' => trim($data['size_id'] ?? ''),
            ':price' => (float)($data['price'] ?? 0),
            ':stock' => (int)($data['stock'] ?? 0),
            ':sku' => trim($data['sku'] ?? ''),
            ':id' => $id
        ]);
    }

    /**
     * Xóa variant.
     */
    public function deleteVariant(string $id)
    {
        $sql = "DELETE FROM {$this->table} WHERE Variant_Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}

?>