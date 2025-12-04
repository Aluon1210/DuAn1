<?php
// File: src/Models/Order.php
namespace Models;

use Core\Model;
use Core\IdGenerator;

class Order extends Model
{

    protected $table = 'orders';

    /**
     * Lấy đơn hàng kèm thông tin user
     * @param string $id
     * @return array|false
     */
    public function getByIdWithUser($id)
    {
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
    public function getByUserId($userId)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE _UserName_Id = :user_id 
                ORDER BY Order_date DESC";
        return $this->query($sql, ['user_id' => $userId]);
    }

    /**
     * Các trạng thái đơn hàng
     */
    public static function getStatuses()
    {
        return [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Chờ giao hàng',
            'shipping' => 'Vận chuyển',
            'delivered' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            'return' => 'Trả hàng'
        ];
    }

    /**
     * Kiểm tra trạng thái hợp lệ
     */
    public static function isValidStatus($status)
    {
        return array_key_exists($status, self::getStatuses());
    }

    /**
     * Tạo đơn hàng mới
     * @param array $data
     * @return string|false Order_Id hoặc false nếu lỗi
     */
    public function createOrder($data)
    {
        try {
            // Generate Order_Id theo format or+0000000001
            $orderId = IdGenerator::generate('or+', $this->table, 'Order_Id', 10);

            // Mặc định trạng thái là 'pending' (chờ xác nhận)
            $status = $data['status'] ?? $data['TrangThai'] ?? 'pending';
            if (!self::isValidStatus($status)) {
                $status = 'pending';
            }

            $dbData = [
                'Order_Id' => $orderId,
                'Order_date' => $data['order_date'] ?? $data['Order_date'] ?? date('Y-m-d'),
                'Adress' => $data['address'] ?? $data['Adress'] ?? '',
                'Note' => $data['note'] ?? $data['Note'] ?? '',
                'TrangThai' => $status,
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
     * Cập nhật trạng thái đơn hàng
     * @param string $orderId
     * @param string $status
     * @return bool
     */
    public function updateStatus($orderId, $status)
    {
        if (!self::isValidStatus($status)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET TrangThai = :status WHERE Order_Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $orderId]);
    }

    /**
     * Kiểm tra user đã nhận hàng sản phẩm chưa
     * @param string $userId
     * @param string $productId
     * @return bool
     */
    public function hasUserReceivedProduct($userId, $productId)
    {
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->table} o
                INNER JOIN order_detail od ON o.Order_Id = od.Order_Id
                INNER JOIN product_variants pv ON od.Variant_Id = pv.Variant_Id
                WHERE o._UserName_Id = :user_id 
                  AND pv.Product_Id = :product_id 
                  AND o.TrangThai = 'delivered'";
        $result = $this->query($sql, [
            'user_id' => $userId,
            'product_id' => $productId
        ]);
        return !empty($result) && $result[0]['count'] > 0;
    }

    /**
     * Tạo đơn hàng mới với chi tiết (chuẩn theo product_variants)
     * @param array $orderData Dữ liệu đơn hàng
     * @param array $orderDetails Mảng các chi tiết: Product_Id, Variant_Id, quantity
     * @return string|false Order_Id hoặc false nếu lỗi
     */
    public function createWithDetails($orderData, $orderDetails)
    {
        try {
            // 1. Tạo bản ghi trong bảng orders (không dùng transaction để tránh lock lâu)
            $orderId = $this->createOrder($orderData);
            if (!$orderId) {
                return false;
            }

            $orderDetailModel = new \Models\OrderDetail();
            $variantModel = new \Models\Product_Varirant();
            $productModel = new \Models\Product();

            $productsToUpdate = [];
            $createdAnyDetail = false;

            foreach ($orderDetails as $detail) {
                $productId = $detail['product_id'] ?? $detail['Product_Id'] ?? '';
                $variantId = isset($detail['variant_id']) ? (int) $detail['variant_id'] : (int) ($detail['Variant_Id'] ?? 0);
                $quantity = (int) ($detail['quantity'] ?? $detail['Quantity'] ?? 0);

                if (empty($productId) || $variantId <= 0 || $quantity <= 0) {
                    error_log("Order createWithDetails skip: invalid detail " . print_r($detail, true));
                    continue;
                }

                // Lấy variant để kiểm tra tồn kho và giá
                $variant = $variantModel->getById($variantId);
                if (!$variant) {
                    error_log("Order createWithDetails skip: variant not found id={$variantId}");
                    continue;
                }

                $variantProductId = $variant['product_id'] ?? $variant['Product_Id'] ?? null;
                if ($variantProductId !== $productId) {
                    error_log("Order createWithDetails skip: variant {$variantId} not belong to product {$productId}");
                    continue;
                }

                $price = (int) ($variant['price'] ?? $variant['Price'] ?? 0);
                $stock = (int) ($variant['stock'] ?? $variant['Quantity_In_Stock'] ?? 0);

                if ($stock <= 0) {
                    error_log("Order createWithDetails skip: variant {$variantId} out of stock");
                    continue;
                }

                if ($quantity > $stock) {
                    $quantity = $stock; // bán tối đa bằng tồn kho
                }

                if ($quantity <= 0) {
                    continue;
                }

                // 2. Tạo bản ghi order_detail
                $detailData = [
                    'Order_Id' => $orderId,
                    'Variant_Id' => $variantId,
                    'quantity' => $quantity,
                    'Price' => $price
                ];

                // Thực hiện insert, nếu lỗi thì log và quay lại false (không giữ transaction lâu)
                if (!$orderDetailModel->create($detailData)) {
                    error_log("Order createWithDetails: failed to create order_detail " . print_r($detailData, true));
                    return false;
                }
                $createdAnyDetail = true;

                // 3. Trừ tồn kho variant
                $newStock = max(0, $stock - $quantity);
                $variantModel->updateVariant($variantId, [
                    'product_id' => $variantProductId,
                    'color_id' => $variant['color_id'] ?? $variant['Color_Id'] ?? null,
                    'size_id' => $variant['size_id'] ?? $variant['Size_Id'] ?? null,
                    'price' => $variant['price'] ?? $variant['Price'] ?? 0,
                    'stock' => $newStock,
                    'sku' => $variant['sku'] ?? $variant['SKU'] ?? ''
                ]);

                if (!in_array($productId, $productsToUpdate, true)) {
                    $productsToUpdate[] = $productId;
                }
            }

            if (!$createdAnyDetail) {
                error_log("Order createWithDetails: no valid order_detail for order {$orderId}");
                return false;
            }

            // 4. Đồng bộ lại Quantity của bảng products từ tổng Quantity_In_Stock của variants
            foreach ($productsToUpdate as $pid) {
                $productModel->updateQuantityFromVariants($pid);
            }
            return $orderId;
        } catch (\Exception $e) {
            error_log("Order createWithDetails Error: " . $e->getMessage());
            return false;
        }
    }
}
?>