<?php
// File: src/Controllers/AdminOrderController.php
namespace Controllers;

use Core\Controller;
use Models\Order;
use Models\OrderDetail;

class AdminOrderController extends Controller
{
    /**
     * Hiển thị danh sách tất cả đơn hàng
     * URL: /admin/orders
     */
    public function index()
    {
        // Kiểm tra admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        $orderModel = new Order();
        $orderDetailModel = new OrderDetail();

        // Lấy tất cả đơn hàng
        $allOrders = $orderModel->query("SELECT * FROM orders ORDER BY Order_date DESC");

        // Tính toán tổng tiền cho mỗi đơn
        if ($allOrders) {
            foreach ($allOrders as &$order) {
                $items = $orderDetailModel->getByOrderIdWithProduct($order['Order_Id']);
                $total = 0;
                if ($items) {
                    foreach ($items as $item) {
                        $qty = (int)($item['quantity'] ?? 0);
                        $price = (float)($item['Price'] ?? 0);
                        $total += $qty * $price;
                    }
                }
                $order['total'] = $total;
                $order['items_count'] = count($items ?? []);
            }
        }

        $data = [
            'title' => 'Quản Lý Đơn Hàng - Admin',
            'orders' => $allOrders ?? []
        ];

        $this->renderView('admin/orders', $data);
    }

    /**
     * Cập nhật trạng thái đơn hàng
     * URL: /admin/orders/updateStatus (POST)
     */
    public function updateStatus()
    {
        // Kiểm tra admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 400 Bad Request');
            exit;
        }

        $orderId = $_POST['order_id'] ?? '';
        $newStatus = $_POST['status'] ?? '';

        if (empty($orderId) || empty($newStatus)) {
            header('HTTP/1.1 400 Bad Request');
            exit;
        }

        // Validate status
        $validStatuses = ['pending', 'confirmed', 'shipping', 'delivered', 'cancelled', 'return'];
        if (!in_array($newStatus, $validStatuses)) {
            header('HTTP/1.1 400 Bad Request');
            exit;
        }

        $orderModel = new Order();
        $success = $orderModel->updateStatus($orderId, $newStatus);

        if ($success) {
            $_SESSION['message'] = 'Cập nhật trạng thái đơn hàng thành công';
            header('Location: ' . ROOT_URL . 'admin/orders');
        } else {
            $_SESSION['error'] = 'Cập nhật trạng thái thất bại';
            header('Location: ' . ROOT_URL . 'admin/orders');
        }
        exit;
    }

    /**
     * Xem chi tiết đơn hàng
     * URL: /admin/orders/detail/{id}
     */
    public function detail($orderId)
    {
        // Kiểm tra admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        $orderModel = new Order();
        $orderDetailModel = new OrderDetail();

        $order = $orderModel->getByIdWithUser($orderId);

        if (!$order) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            header('Location: ' . ROOT_URL . 'admin/orders');
            exit;
        }

        $items = $orderDetailModel->getByOrderIdWithProduct($orderId);
        $total = 0;
        if ($items) {
            foreach ($items as $item) {
                $qty = (int)($item['quantity'] ?? 0);
                $price = (float)($item['Price'] ?? 0);
                $total += $qty * $price;
            }
        }

        $data = [
            'title' => 'Chi Tiết Đơn Hàng - Admin',
            'order' => $order,
            'items' => $items ?? [],
            'total' => $total
        ];

        $this->renderView('admin/order-detail', $data);
    }
}
?>
