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
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao hàng',
            'delivered' => 'Đã nhận hàng'
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
     * Tạo đơn hàng mới với chi tiết
     * @param array $orderData Dữ liệu đơn hàng
     * @param array $orderDetails Mảng các chi tiết đơn hàng
     * @return string|false Order_Id hoặc false nếu lỗi
     */
    public function createWithDetails($orderData, $orderDetails)
    {
        try {
            $this->db->beginTransaction();

            // Tạo đơn hàng trước
            $orderId = $this->createOrder($orderData);
            if (!$orderId) {
                // createOrder đã log lỗi chi tiết
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                }
                return false;
            }

            // Chuẩn bị model dùng chung
            $orderDetailModel = new \Models\OrderDetail();
            $variantModel = new \Models\Product_Varirant();
            $productModel = new \Models\Product();

            // Danh sách product cần sync lại số lượng từ variants
            $productsToUpdate = [];

            // Đánh dấu xem có tạo được chi tiết nào không
            $createdAnyDetail = false;

            foreach ($orderDetails as $detail) {
                $productId = $detail['product_id'] ?? $detail['Product_Id'] ?? '';
                $variantId = $detail['variant_id'] ?? $detail['Variant_Id'] ?? null;
                $quantity = (int) ($detail['quantity'] ?? $detail['Quantity'] ?? 0);

                if (empty($productId) || $quantity <= 0) {
                    // Bỏ qua item lỗi dữ liệu, không làm hỏng cả đơn hàng
                    error_log("Order createWithDetails skip item: invalid product or quantity. Data=" . print_r($detail, true));
                    continue;
                }

                // Lấy sản phẩm (nếu lỗi thì log và bỏ qua)
                $product = $productModel->getById($productId);
                if (!$product) {
                    error_log("Order createWithDetails: product not found for id {$productId}");
                    continue;
                }

                // Giá: ưu tiên từ dữ liệu truyền vào, sau đó variant, cuối cùng là product
                $priceFromInput = isset($detail['Price']) ? (float) $detail['Price'] : (isset($detail['price']) ? (float) $detail['price'] : null);
                $price = $priceFromInput !== null ? $priceFromInput : (int) ($product['price'] ?? 0);

                // Nếu có variant thì lấy thêm thông tin variant (nếu không có thì vẫn tiếp tục với giá product)
                $variant = null;

                // Nếu chưa có Variant_Id (do controller không truyền), cố gắng tìm variant đầu tiên của sản phẩm
                if (empty($variantId)) {
                    $existingVariants = $variantModel->getByProductId($productId);
                    if (!empty($existingVariants)) {
                        $firstVariant = $existingVariants[0];
                        $variantId = $firstVariant['Variant_Id'] ?? $firstVariant['id'] ?? null;
                    }
                }

                if (!empty($variantId)) {
                    $variant = $variantModel->getById($variantId);
                    if (!$variant) {
                        error_log("Order createWithDetails: variant not found for id {$variantId}, product {$productId}");
                    } else {
                        if ($priceFromInput === null) {
                            $price = (int) ($variant['price'] ?? $price);
                        }
                    }
                } else {
                    // Không tìm được variant hợp lệ → không thể chèn vào order_detail (có ràng buộc NOT NULL + FK)
                    error_log("Order createWithDetails: no valid variant for product {$productId}, skip detail");
                    continue;
                }

                // Tạo dữ liệu chi tiết đơn hàng
                $detailData = [
                    'Order_Id' => $orderId,
                    'Variant_Id' => $variantId,
                    'quantity' => $quantity,
                    'Price' => $price
                ];

                $createResult = $orderDetailModel->create($detailData);
                if (!$createResult) {
                    // Nếu 1 chi tiết lỗi, rollback cả đơn để tránh trạng thái nửa vời
                    throw new \Exception("Không thể tạo order_detail cho Order {$orderId}. Data=" . print_r($detailData, true));
                }
                $createdAnyDetail = true;

                // Cập nhật tồn kho: ở đây giả định controller đã kiểm tra tồn kho trước
                if ($variant) {
                    $newStock = max(0, (int) ($variant['stock'] ?? 0) - $quantity);
                    $variantModel->updateVariant($variantId, [
                        'product_id' => $productId,
                        'color_id' => $variant['color_id'] ?? null,
                        'size_id' => $variant['size_id'] ?? null,
                        'price' => $variant['price'] ?? 0,
                        'stock' => $newStock,
                        'sku' => $variant['sku'] ?? ''
                    ]);

                    if (!in_array($productId, $productsToUpdate, true)) {
                        $productsToUpdate[] = $productId;
                    }
                } else {
                    // Không có variant → trừ trực tiếp quantity trên product
                    $productModel->decreaseQuantity($productId, $quantity);
                }
            }

            // Nếu vì lý do nào đó không tạo được chi tiết nào thì rollback
            if (!$createdAnyDetail) {
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                }
                error_log("Order createWithDetails: no order_detail created for order {$orderId}");
                return false;
            }

            // Sync lại quantity sản phẩm từ variants (mỗi product chỉ xử lý một lần)
            foreach ($productsToUpdate as $pid) {
                $productModel->updateQuantityFromVariants($pid);
            }

            $this->db->commit();
            return $orderId;
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Order createWithDetails Error: " . $e->getMessage());
            return false;
        }
    }
}
?>