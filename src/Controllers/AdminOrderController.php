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

        // Params: status, page, perPage, optional search q
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = max(1, min(100, (int)($_GET['perPage'] ?? 20)));
        $search = isset($_GET['q']) ? trim($_GET['q']) : '';

        $where = '';
        $params = [];
        if ($status && $status !== 'all') {
            $where = 'WHERE o.TrangThai = :status';
            $params[':status'] = $status;
        }

        if ($search !== '') {
            $params[':q'] = '%' . $search . '%';
            if ($where) {
                $where .= ' AND (o.Order_Id LIKE :q OR u.FullName LIKE :q OR u.Email LIKE :q)';
            } else {
                $where = 'WHERE (o.Order_Id LIKE :q OR u.FullName LIKE :q OR u.Email LIKE :q)';
            }
        }

        // Total count
        $countSql = "SELECT COUNT(*) as cnt FROM orders o LEFT JOIN users u ON o._UserName_Id = u._UserName_Id " . ($where ? $where : '');
        $countRes = $orderModel->query($countSql, $params);
        $totalOrders = (int)($countRes[0]['cnt'] ?? 0);

        $totalPages = (int)max(1, ceil($totalOrders / $perPage));
        if ($page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $perPage;

        // Main query: Get product details from order_detail
        // Query structure: orders -> order_detail -> product_variants -> products (for name, color, size)
        // NOTE: LIMIT and OFFSET must be literal integers, not bound parameters
        $sql = "SELECT o.Order_Id, o.Order_date, o.TrangThai, o.Adress, o.Note, o._UserName_Id,
                       u.FullName as user_name, u.Email as user_email, u.Phone as user_phone,
                       SUM(od.quantity) as items_count,
                       SUM(od.quantity * od.Price) as total,
                       GROUP_CONCAT(
                            CONCAT(p.Name, ' (', IFNULL(c.Name,'N/A'), ' ', IFNULL(s.Name,'N/A'), ')')
                            SEPARATOR '; '
                        ) as product_variants
                FROM orders o
                LEFT JOIN users u ON o._UserName_Id = u._UserName_Id
                LEFT JOIN order_detail od ON o.Order_Id = od.Order_Id
                LEFT JOIN product_variants pv ON od.Variant_Id = pv.Variant_Id
                LEFT JOIN products p ON pv.Product_Id = p.Product_Id
                LEFT JOIN colors c ON pv.Color_Id = c.Color_Id
                LEFT JOIN sizes s ON pv.Size_Id = s.Size_Id
                " . ($where ? $where : '') . "
                GROUP BY o.Order_Id, o.Order_date, o.TrangThai, o.Adress, o.Note, o._UserName_Id, u.FullName, u.Email, u.Phone
                ORDER BY o.Order_date DESC
                LIMIT " . (int)$perPage . " OFFSET " . (int)$offset;

        $orders = $orderModel->query($sql, $params);

        $data = [
            'title' => 'Quản Lý Đơn Hàng - Admin',
            'orders' => $orders ?? [],
            'pagination' => [
                'page' => $page,
                'perPage' => $perPage,
                'total' => $totalOrders,
                'totalPages' => $totalPages
            ],
            'filter' => [
                'status' => $status,
                'q' => $search
            ]
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
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Không có quyền']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Phương thức không hợp lệ']);
            exit;
        }

        $orderId = $_POST['order_id'] ?? '';
        $newStatus = $_POST['status'] ?? '';

        if (empty($orderId) || empty($newStatus)) {
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Thiếu tham số']);
            exit;
        }

        // Validate status
        $validStatuses = ['pending', 'confirmed', 'shipping', 'delivered', 'cancelled', 'return'];
        if (!in_array($newStatus, $validStatuses)) {
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Trạng thái không hợp lệ']);
            exit;
        }

        $orderModel = new Order();
        $success = $orderModel->updateStatus($orderId, $newStatus);

        // Detect AJAX (X-Requested-With) or Accept header
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => (bool)$success, 'error' => $success ? null : 'Cập nhật thất bại']);
            exit;
        }

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
     * Xóa đơn hàng
     * URL: /admin/orders/delete/{id} (GET hoặc POST)
     */
    public function delete($orderId = null)
    {
        // Kiểm tra admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        // Lấy order_id từ URL param hoặc POST
        if (empty($orderId)) {
            $orderId = $_POST['order_id'] ?? '';
        }

        if (empty($orderId)) {
            $_SESSION['error'] = 'Order id missing';
            header('Location: ' . ROOT_URL . 'admin/orders');
            exit;
        }

        $orderModel = new Order();
        try {
            // Delete order details then order
            $orderModel->query('DELETE FROM order_detail WHERE Order_Id = :id', [':id' => $orderId]);
            $orderModel->query('DELETE FROM orders WHERE Order_Id = :id', [':id' => $orderId]);
            $_SESSION['message'] = 'Xóa đơn hàng thành công';
        } catch (\Exception $e) {
            error_log('Failed to delete order ' . $orderId . ': ' . $e->getMessage());
            $_SESSION['error'] = 'Không thể xóa đơn hàng';
        }

        header('Location: ' . ROOT_URL . 'admin/orders');
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

        $this->renderView('admin/order', $data);
    }
}
?>
