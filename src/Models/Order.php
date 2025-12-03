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

            // Tạo đơn hàng
            $orderId = $this->createOrder($orderData);

            if ($orderId) {
                // Tạo chi tiết đơn hàng
                $orderDetailModel = new \Models\OrderDetail();
                $variantModel = new \Models\Product_Varirant();
                $productModel = new \Models\Product();

                // Danh sách các product đã có variant được cập nhật (để cập nhật quantity sau)
                $productsToUpdate = [];

                foreach ($orderDetails as $detail) {
                    $productId = $detail['product_id'] ?? $detail['Product_Id'] ?? '';
                    $variantId = $detail['var                    iant_id'] ?? $detail['Variant_Id'] ?? null;
                    $quantity = $detail['quantity'] ?? 0;

                    if (empty($productId) || $quantity <= 0) {
                        continue;
                    }

                    // Nếu không có variant_id, tìm varian                    t có sẵn của sản phẩm
                    if (empty($variantId)) {
                        // Tìm variant có sẵn của sản phẩm
                        $existingVariants = $variantModel->getByProductId($productId);
                        if (!empty($existingVariants)) {
                            // Dùng variant đầu tiên có sẵn
                            $firstVariant = $existingVariants[0];
                            $variantId = $firstVariant['Variant_Id'] ?? $firstVariant['id'] ?? null;
                        } else {
                            // Sản phẩm không có variant - không thể tạo order detail
                            // Vì Variant_Id là NOT NULL trong database
                            error_log("Cannot create order detail: Product $productId has no variants");
                            $this->db->rollBack();
                            throw new \Exception("Sản phẩm không có phân loại. Vui lòng liên hệ admin để thêm phân loại cho sản phẩm này.");
                        }
                    }

                    if (empty($variantId)) {
                        error_log("Cannot create order detail: No variant available for pro            duct: $productId");
                        continue;
                    }

                    $detailData = [
                        'Order_Id' => $orderId,
                        'Product_Id' => $productId,
                        'Variant_Id' => $variantId,
                        'quantity' => $quantity
                    ];
                    $orderDetailModel->create($detailData);

                    // Giảm số lượng                 sản phẩm/variant
                    if ($variantId) {
                        // Giảm stock của variant
                        $variant = $variantModel->getById($variantId);
                        if ($variant) {
                            $newStock = max(0, ($variant['stock'] ?? 0) - $quantity);
                            $variantModel->updateVariant($variantId, [
                                'product_id' => $productId,
                                'color_id' => $variant['color_id'] ?? null,
                                'size_id' => $variant['size_id'] ?? null,
                                'price' => $variant['price'] ?? 0,
                                'stock' => $newStock,
                                'sku' => $variant['sku'] ?? ''
                            ]);

                            // Đánh dấu product này cần cập nhật quantity từ variants
                            if (!in_array($productId, $productsToUpdate)) {
                                $productsToUpdate[] = $productId;
                            }
                        }
                    } else {
                        // Giảm số lượng sản phẩm (không có variant)
                        $productModel->decreaseQuantity($productId, $quantity);
                    }
                }

                // Cập nhật lại số lượng sản phẩm = tổng stock của tất cả variants
                // (chỉ cập nhật một lần cho mỗi product)
                foreach ($productsToUpdate as $productId) {
                    $productModel->updateQuantityFromVariants($productId);
                }

                $this->db->commit();
                return $orderId;
            }

            $this->db->rollBack();
            return false;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Order createWithDetails Error: " . $e->getMessage());
            return false;
        }
    }
}
?>