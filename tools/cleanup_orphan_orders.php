<?php
/**
 * Xóa orders orphan (không có order_detail)
 * Chạy: php tools/cleanup_orphan_orders.php
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT_PATH', dirname(dirname(__FILE__)));
define('ROOT_URL', 'http://localhost/DuAn1/');

require_once ROOT_PATH . '/src/Config/connection.php';
require_once ROOT_PATH . '/src/Core/Model.php';
require_once ROOT_PATH . '/src/Models/Order.php';
require_once ROOT_Path . '/src/Models/OrderDetail.php';

use Models\Order;
use Models\OrderDetail;

$db = \Core\Database::getInstance();
$orderModel = new Order();

// Tìm orders không có order_detail
$orphanOrders = $db->query(
    "SELECT o.Order_Id FROM orders o 
     LEFT JOIN order_detail od ON o.Order_Id = od.Order_Id 
     WHERE od.Order_Id IS NULL"
)->fetchAll(\PDO::FETCH_ASSOC);

echo "Tìm thấy " . count($orphanOrders) . " orders bị orphan\n\n";

foreach ($orphanOrders as $orphan) {
    $orderId = $orphan['Order_Id'];
    
    try {
        $db->query("DELETE FROM orders WHERE Order_Id = :id", [':id' => $orderId]);
        echo "✅ Xóa order {$orderId}\n";
    } catch (\Exception $e) {
        echo "❌ Lỗi khi xóa {$orderId}: " . $e->getMessage() . "\n";
    }
}

echo "\n✅ Xóa orphan orders hoàn tất.\n";
?>
