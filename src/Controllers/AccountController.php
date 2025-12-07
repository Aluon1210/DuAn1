<?php
// File: src/Controllers/AccountController.php
namespace Controllers;

use Core\Controller;
use Models\User;
use Models\Order;

class AccountController extends Controller {
    
    /**
     * Hiển thị trang tài khoản người dùng
     * URL: /account
     */
    public function index() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            $_SESSION['redirect'] = ROOT_URL . 'account';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }
        
        $userId = $_SESSION['user']['id'];
        $userModel = new User();
        $user = $userModel->getById($userId);
        
        if (!$user) {
            $_SESSION['error'] = 'Không thể tải thông tin tài khoản';
            header('Location: ' . ROOT_URL);
            exit;
        }
        
        // Lấy các đơn hàng của user
        $orderModel = new Order();
        $orders = $orderModel->getByUserId($userId);

        // Lấy chi tiết cho mỗi đơn (items, total)
        $orderDetailModel = new \Models\OrderDetail();
        if (!empty($orders)) {
            foreach ($orders as &$o) {
                $items = $orderDetailModel->getByOrderIdWithProduct($o['Order_Id']);
                $total = 0;
                foreach ($items as $it) {
                    $qty = (int)($it['quantity'] ?? 0);
                    $price = (float)($it['Price'] ?? $it['price'] ?? 0);
                    $total += $qty * $price;
                }
                $o['items'] = $items;
                $o['total'] = $total;
            }
            unset($o);
        }
        
        $data = [
            'title' => 'Tài Khoản Cá Nhân - Luxury Fashion Store',
            'user' => $user,
            'orders' => $orders ?? [],
            'activeTab' => 'profile'
        ];
        
        $this->renderView('account/profile', $data);
    }
    
    /**
     * Hiển thị tab thông tin cá nhân
     * URL: /account/profile
     */
    public function profile() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }
        
        $userId = $_SESSION['user']['id'];
        $userModel = new User();
        $user = $userModel->getById($userId);
        
        if (!$user) {
            $_SESSION['error'] = 'Không thể tải thông tin tài khoản';
            header('Location: ' . ROOT_URL);
            exit;
        }
        
        $data = [
            'title' => 'Thông Tin Cá Nhân - Luxury Fashion Store',
            'user' => $user,
            'orders' => [],
            'activeTab' => 'profile'
        ];
        
        $this->renderView('account/profile', $data);
    }
    
    /**
     * Hiển thị tab lịch sử đơn hàng
     * URL: /account/orders
     */
    public function orders() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }
        
        $userId = $_SESSION['user']['id'];
        $userModel = new User();
        $user = $userModel->getById($userId);
        
        if (!$user) {
            $_SESSION['error'] = 'Không thể tải thông tin tài khoản';
            header('Location: ' . ROOT_URL);
            exit;
        }
        
        // Lấy các đơn hàng của user
        $orderModel = new Order();
        $orders = $orderModel->getByUserId($userId) ?? [];

        // Lấy chi tiết cho mỗi đơn
        $orderDetailModel = new \Models\OrderDetail();
        if (!empty($orders)) {
            foreach ($orders as &$o) {
                $items = $orderDetailModel->getByOrderIdWithProduct($o['Order_Id']);
                $total = 0;
                foreach ($items as $it) {
                    $qty = (int)($it['quantity'] ?? 0);
                    $price = (float)($it['Price'] ?? $it['price'] ?? 0);
                    $total += $qty * $price;
                }
                $o['items'] = $items;
                $o['total'] = $total;
            }
            unset($o);
        }
        
        $data = [
            'title' => 'Lịch Sử Đơn Hàng - Luxury Fashion Store',
            'user' => $user,
            'orders' => $orders,
            'activeTab' => 'orders'
        ];
        
        $this->renderView('account/profile', $data);
    }
    
    /**
     * Cập nhật thông tin cá nhân
     * URL: /account/updateProfile (POST)
     */
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'account');
            exit;
        }
        
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập trước';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }
        
        $userId = $_SESSION['user']['id'];
        $userModel = new User();
        
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validation
        if (empty($name)) {
            $_SESSION['error'] = 'Tên không được để trống';
            header('Location: ' . ROOT_URL . 'account/profile');
            exit;
        }
        
        try {
            $updateData = [
                'name' => $name,
                'phone' => $phone,
                'address' => $address
            ];
            
            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $_SESSION['error'] = 'Mật khẩu phải có ít nhất 6 ký tự';
                    header('Location: ' . ROOT_URL . 'account/profile');
                    exit;
                }
                $updateData['password'] = $password;
            }
            
            $success = $userModel->updateUser($userId, $updateData);
            
            if ($success) {
                // Cập nhật session
                $updatedUser = $userModel->getById($userId);
                if ($updatedUser) {
                    unset($updatedUser['password']);
                    $_SESSION['user'] = $updatedUser;
                }
                $_SESSION['message'] = 'Cập nhật thông tin thành công';
            } else {
                $_SESSION['error'] = 'Không thể cập nhật thông tin';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }
        
        header('Location: ' . ROOT_URL . 'account/profile');
        exit;
    }
    
    /**
     * Xem chi tiết đơn hàng
     * URL: /account/order/{id}
     */
    public function orderDetail($orderId) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }
        
        $userId = $_SESSION['user']['id'];
        $orderModel = new Order();
        $order = $orderModel->getById($orderId);
        
        if (!$order || $order['user_id'] != $userId) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            header('Location: ' . ROOT_URL . 'account/orders');
            exit;
        }
        
        $data = [
            'title' => 'Chi Tiết Đơn Hàng - Luxury Fashion Store',
            'order' => $order,
            'user' => $_SESSION['user']
        ];
        
        $this->renderView('account/order-detail', $data);
    }
}
?>
