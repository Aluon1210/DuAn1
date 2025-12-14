<?php
// File: src/Controllers/AccountController.php
namespace Controllers;

use Core\Controller;
use Models\User;
use Models\Order;

class AccountController extends Controller {
        /**
         * Hủy đơn hàng (chỉ khi trạng thái là 'pending')
         * URL: /account/cancelOrder (POST)
         */
    public function cancelOrder() {
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
            $orderId = $_POST['order_id'] ?? '';
            if (empty($orderId)) {
                $_SESSION['error'] = 'Thiếu mã đơn hàng';
                header('Location: ' . ROOT_URL . 'account');
                exit;
            }
            $orderModel = new Order();
            $order = $orderModel->getById($orderId);
            if (!$order || $order['_UserName_Id'] != $userId) {
                $_SESSION['error'] = 'Đơn hàng không tồn tại hoặc không thuộc về bạn';
                header('Location: ' . ROOT_URL . 'account');
                exit;
            }
            if ($order['TrangThai'] !== 'pending') {
                $_SESSION['error'] = 'Chỉ có thể hủy đơn hàng khi đang chờ xác nhận';
                header('Location: ' . ROOT_URL . 'account');
                exit;
            }
            // Nếu phương thức là online: chuẩn bị hoàn tiền và yêu cầu xác nhận
            $pm = $order['PaymentMethod'] ?? ($order['payment_method'] ?? 'opt');
            if ($pm === 'online') {
                // Tính tổng cần hoàn
                $orderDetailModel = new \Models\OrderDetail();
                $items = $orderDetailModel->getByOrderIdWithProduct($orderId);
                $subtotal = 0;
                foreach ($items as $it) {
                    $qty = (int)($it['quantity'] ?? $it['Quantity'] ?? 0);
                    $price = (float)($it['Price'] ?? $it['price'] ?? 0);
                    $subtotal += $qty * $price;
                }
                $vat = (int)round($subtotal * 0.05);
                $ship = 50000;
                $voucherDiscount = 0;
                $noteStr = (string)($order['Note'] ?? '');
                if ($noteStr !== '' && preg_match('/Voucher:\s*([A-Z0-9_-]+)\s*-\s*(\d+)/i', $noteStr, $m)) {
                    $voucherDiscount = (int)($m[2] ?? 0);
                }
                $refundAmount = max(0, $subtotal + $vat + $ship - max(0, $voucherDiscount));

                // Lấy thông tin ngân hàng người dùng
                $userModel = new User();
                $user = $userModel->getById($userId);
                $bankName = $user['bank_name'] ?? '';
                $bankCode = '';
                $bankCodes = \Core\PaymentHelper::getAllBankCodes();
                foreach ($bankCodes as $code => $name) {
                    if (strcasecmp($name, $bankName) === 0) { $bankCode = $code; break; }
                }
                $accountNo = $user['bank_account_number'] ?? '';
                $accountName = strtoupper($user['name'] ?? $user['username'] ?? '');

                if ($bankCode && $accountNo && $accountName) {
                    $qrUrl = \Core\PaymentHelper::buildQRUrl($bankCode, $accountNo, $accountName, $refundAmount, 'REFUND-' . $orderId, 'compact');
                    $_SESSION['refund_qr'] = [
                        'order_id' => $orderId,
                        'amount' => $refundAmount,
                        'qr_url' => $qrUrl,
                        'bank_code' => $bankCode,
                        'account_no' => $accountNo,
                        'account_name' => $accountName
                    ];
                    $_SESSION['message'] = 'Đã tạo QR hoàn tiền. Vui lòng quét để chuyển lại cho khách. Sau khi chuyển, bấm Xác nhận hoàn.';
                } else {
                    $_SESSION['error'] = 'Chưa đủ thông tin ngân hàng của bạn để hoàn tiền (mã/tên/số tài khoản).';
                }
                // Không đổi trạng thái ngay, cần xác nhận
                header('Location: ' . ROOT_URL . 'account/orders');
                exit;
            }
            $success = $orderModel->updateStatus($orderId, 'cancelled');
            if ($success) {
                try {
                    $orderDetailModel = new \Models\OrderDetail();
                    $variantModel = new \Models\Product_Varirant();
                    $productModel = new \Models\Product();
                    $details = $orderDetailModel->getByOrderIdWithProduct($orderId) ?? [];
                    $productsToUpdate = [];
                    foreach ($details as $d) {
                        $variantId = (string)($d['Variant_Id'] ?? $d['variant_id'] ?? '');
                        $qty = (int)($d['quantity'] ?? $d['Quantity'] ?? 0);
                        if ($variantId === '' || $qty <= 0) {
                            continue;
                        }
                        $variant = $variantModel->getById($variantId);
                        if (!$variant) {
                            continue;
                        }
                        $newStock = (int)($variant['stock'] ?? $variant['Quantity_In_Stock'] ?? 0) + $qty;
                        $variantModel->updateVariant($variantId, [
                            'product_id' => $variant['product_id'] ?? $variant['Product_Id'] ?? '',
                            'color_id' => $variant['color_id'] ?? $variant['Color_Id'] ?? null,
                            'size_id' => $variant['size_id'] ?? $variant['Size_Id'] ?? null,
                            'price' => (float)($variant['price'] ?? $variant['Price'] ?? 0),
                            'stock' => $newStock,
                            'sku' => $variant['sku'] ?? $variant['SKU'] ?? ''
                        ]);
                        $pid = $variant['product_id'] ?? $variant['Product_Id'] ?? ($d['product_id'] ?? null);
                        if ($pid && !in_array($pid, $productsToUpdate, true)) {
                            $productsToUpdate[] = $pid;
                        }
                    }
                    foreach ($productsToUpdate as $pid) {
                        $productModel->updateQuantityFromVariants($pid);
                    }
                } catch (\Exception $e) {
                    error_log('restore stock on user cancel failed for order ' . $orderId . ': ' . $e->getMessage());
                }
                $_SESSION['message'] = 'Đã hủy đơn hàng thành công.';
            } else {
                $_SESSION['error'] = 'Không thể hủy đơn hàng.';
            }
            header('Location: ' . ROOT_URL . 'account');
            exit;
        }
    
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
                $subtotal = 0;
                foreach ($items as $it) {
                    $qty = (int)($it['quantity'] ?? 0);
                    $price = (float)($it['Price'] ?? $it['price'] ?? 0);
                    $subtotal += $qty * $price;
                }
                $vat = (int)round($subtotal * 0.05);
                $ship = 50000;
                $voucherDiscount = 0;
                $noteStr = (string)($o['Note'] ?? '');
                if ($noteStr !== '' && preg_match('/Voucher:\s*([A-Z0-9_-]+)\s*-\s*(\d+)/i', $noteStr, $m)) {
                    $voucherDiscount = (int)($m[2] ?? 0);
                }
                $o['items'] = $items;
                $o['total'] = max(0, $subtotal + $vat + $ship - max(0, $voucherDiscount));
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

    public function confirmRefund() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'account/orders');
            exit;
        }
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập trước';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }
        $orderId = $_POST['order_id'] ?? '';
        $amount = (int)($_POST['amount'] ?? 0);
        if (!$orderId || $amount <= 0) {
            $_SESSION['error'] = 'Thiếu thông tin hoàn tiền';
            header('Location: ' . ROOT_URL . 'account/orders');
            exit;
        }
        $orderModel = new Order();
        $order = $orderModel->getById($orderId);
        if (!$order) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng';
            header('Location: ' . ROOT_URL . 'account/orders');
            exit;
        }
        // Ghi chú và đổi trạng thái
        $orderModel->appendNote($orderId, 'Refund Online Success: +' . number_format($amount, 0, ',', '.') . 'đ');
        $orderModel->updateStatus($orderId, 'cancelled');

        // Cộng số dư ví người dùng
        $userModel = new User();
        $userId = $_SESSION['user']['id'];
        $userModel->increaseBalance($userId, $amount);
        // Update session price
        $updatedUser = $userModel->getById($userId);
        if ($updatedUser) { unset($updatedUser['password']); $_SESSION['user'] = $updatedUser; }

        $_SESSION['message'] = 'Đã xác nhận hoàn tiền online và cộng vào ví.';
        header('Location: ' . ROOT_URL . 'account/orders');
        exit;
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
        
        // Lấy các đơn hàng của user hoặc lọc theo mã đơn nếu có
        $orderModel = new Order();
        $searchOrderId = isset($_GET['order_id']) ? trim($_GET['order_id']) : '';
        if ($searchOrderId !== '') {
            $one = $orderModel->getById($searchOrderId);
            if ($one && ($one['_UserName_Id'] ?? '') === $userId) {
                $orders = [$one];
            } else {
                $orders = [];
            }
        } else {
            $orders = $orderModel->getByUserId($userId) ?? [];
        }

        // Lấy chi tiết cho mỗi đơn
        $orderDetailModel = new \Models\OrderDetail();
        if (!empty($orders)) {
            foreach ($orders as &$o) {
                $items = $orderDetailModel->getByOrderIdWithProduct($o['Order_Id']);
                $subtotal = 0;
                foreach ($items as $it) {
                    $qty = (int)($it['quantity'] ?? 0);
                    $price = (float)($it['Price'] ?? $it['price'] ?? 0);
                    $subtotal += $qty * $price;
                }
                $vat = (int)round($subtotal * 0.05);
                $ship = 50000;
                $voucherDiscount = 0;
                $noteStr = (string)($o['Note'] ?? '');
                if ($noteStr !== '' && preg_match('/Voucher:\s*([A-Z0-9_-]+)\s*-\s*(\d+)/i', $noteStr, $m)) {
                    $voucherDiscount = (int)($m[2] ?? 0);
                }
                $o['items'] = $items;
                $o['total'] = max(0, $subtotal + $vat + $ship - max(0, $voucherDiscount));
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
        $bankAccountNumber = trim($_POST['bank_account_number'] ?? '');
        $bankName = trim($_POST['bank_name'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validation
        if (empty($name)) {
            $_SESSION['error'] = 'Tên không được để trống';
            header('Location: ' . ROOT_URL . 'account/profile');
            exit;
        }
        
        if (!empty($phone)) {
            $byPhone = $userModel->getByPhone($phone);
            if ($byPhone && ($byPhone['id'] ?? '') !== $userId) {
                $_SESSION['error'] = 'Số điện thoại này đã được sử dụng bởi tài khoản khác';
                header('Location: ' . ROOT_URL . 'account/profile');
                exit;
            }
        }
        
        try {
            $updateData = [
                'name' => $name,
                'phone' => $phone,
                'address' => $address,
                'bank_account_number' => $bankAccountNumber,
                'bank_name' => $bankName
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
