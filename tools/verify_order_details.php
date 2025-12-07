<?php
/**
 * Kiểm tra xem orders có order_detail kèm theo không
 * Chạy: php tools/verify_order_details.php
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include app config
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('ROOT_URL', 'http://localhost/DuAn1/');

require_once ROOT_PATH . '/src/Config/connection.php';
require_once ROOT_PATH . '/src/Core/Model.php';
require_once ROOT_PATH . '/src/Models/Order.php';
require_once ROOT_PATH . '/src/Models/OrderDetail.php';

use Models\Order;
use Models\OrderDetail;

$orderModel = new Order();
$orderDetailModel = new OrderDetail();

// Lấy tất cả orders
$allOrders = $orderModel->query("SELECT * FROM orders ORDER BY Order_date DESC LIMIT 20");

echo "=== KIỂM TRA ORDER-DETAIL ===\n\n";
echo "Tổng orders: " . count($allOrders) . "\n\n";

$orphanOrders = [];
$orderWithDetails = [];

foreach ($allOrders as $order) {
    $orderId = $order['Order_Id'];
    
    // Lấy order-detail
    $details = $orderDetailModel->query(
        "SELECT * FROM order_detail WHERE Order_Id = :id",
        [':id' => $orderId]
    );
    
    $detailCount = count($details ?? []);
    
    if ($detailCount === 0) {
        $orphanOrders[] = [
            'Order_Id' => $orderId,
            'Order_date' => $order['Order_date'],
            'User' => $order['_UserName_Id'] ?? 'N/A'
        ];
        echo "❌ Order {$orderId} ({$order['Order_date']}) - CÓ ORDER NHƯNG KHÔNG CÓ ORDER_DETAIL\n";
    } else {
        $orderWithDetails[] = $orderId;
        echo "✅ Order {$orderId} ({$order['Order_date']}) - Có {$detailCount} item(s)\n";
        
        // Hiển thị chi tiết
        foreach ($details as $detail) {
            $variantId = $detail['Variant_Id'];
            $qty = $detail['quantity'];
            $price = $detail['Price'];
            echo "   → Variant {$variantId}: {$qty} x {$price}₫ = " . ($qty * $price) . "₫\n";
        }
    }
}

echo "\n=== TÓM TẮT ===\n";
echo "Orders có order_detail: " . count($orderWithDetails) . "\n";
echo "Orders bị orphan (không có detail): " . count($orphanOrders) . "\n";

if (!empty($orphanOrders)) {
    echo "\n⚠️ CẢNH BÁO: Các orders bị orphan:\n";
    foreach ($orphanOrders as $orphan) {
        echo "  - {$orphan['Order_Id']} ({$orphan['Order_date']}) - User: {$orphan['User']}\n";
    }
}

echo "\n✅ Kiểm tra hoàn tất.\n";
?>
