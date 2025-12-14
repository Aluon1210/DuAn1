<?php
// File: src/Controllers/AdminController.php
namespace Controllers;

use Core\Controller;
use Models\Product;
use Models\Category;
use Models\Branch;
use Models\Color;
use Models\Size;
use Models\Product_Varirant;
use Models\User;
use Models\Dashboard;

class AdminController extends Controller {

public function users() {
    $this->requireAdmin();
    $userModel = new User();
    $users = $userModel->getAllUsers();
    $data = [
        'title' => 'Quản lý người dùng',
        'users' => $users,
        'totalUsers' => count($users),
        'editing' => false
    ];

    // Nếu có tham số ?edit=ID thì load user đó để điền vào form
    if (isset($_GET['edit']) && $_GET['edit'] !== '') {
        $editId = trim($_GET['edit']);
        $user = $userModel->getById($editId);
        if ($user) {
            $data['editing'] = true;
            $data['user'] = $user;
            $data['title'] = 'Sửa người dùng';
        } else {
            $_SESSION['error'] = 'Người dùng không tồn tại';
            // giữ nguyên danh sách, không dừng
        }
    }

    $this->renderView('admin/user', $data);
}

/**
 * Hiển thị form sửa user
 * URL: /admin/editUser/{id}
 */
public function editUser($id) {
    $userModel = new User();
    $user = $userModel->getById($id);
    
    if (!$user) {
        $_SESSION['error'] = 'Người dùng không tồn tại';
        header('Location: ' . ROOT_URL . 'admin/users');
        exit;
    }
    
    $users = $userModel->getAllUsers();
    
    $data = [
        'title' => 'Sửa người dùng',
        'user' => $user,
        'users' => $users,
        'totalUsers' => count($users),
        'editing' => true
    ];
    
    $this->renderView('admin/user', $data);
}

/**
 * Xử lý thêm / cập nhật user
 * URL: /admin/saveUser (POST)
 */
public function saveUser() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . ROOT_URL . 'admin/users');
        exit;
    }
    
    $userModel = new User();
    
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : 'user';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    // Validation
    if ($email === '' || $name === '') {
        $_SESSION['error'] = 'Email và Họ tên không được để trống';
        header('Location: ' . ROOT_URL . 'admin/users');
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Email không hợp lệ';
        header('Location: ' . ROOT_URL . 'admin/users');
        exit;
    }
    
    try {
        if ($id) {
            // Cập nhật
            $updateData = [
                'email' => $email,
                'name' => $name,
                'phone' => $phone,
                'role' => $role,
                'address' => $address
            ];
            if ($password !== '') {
                $updateData['password'] = $password;
            }
            $success = $userModel->updateUser($id, $updateData);
            $_SESSION['message'] = $success ? 'Cập nhật người dùng thành công' : 'Không thể cập nhật người dùng';
        } else {
            // Thêm mới
            if ($password === '') {
                $_SESSION['error'] = 'Mật khẩu không được để trống khi thêm người dùng mới';
                header('Location: ' . ROOT_URL . 'admin/users');
                exit;
            }
            
            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Mật khẩu phải có ít nhất 6 ký tự';
                header('Location: ' . ROOT_URL . 'admin/users');
                exit;
            }
            
            $createData = [
                'email' => $email,
                'name' => $name,
                'phone' => $phone,
                'role' => $role,
                'address' => $address,
                'password' => $password
            ];
            $newId = $userModel->createUser($createData);
            $_SESSION['message'] = $newId ? 'Thêm người dùng thành công' : 'Không thể thêm người dùng';
        }
    } catch (\Exception $e) {
        $_SESSION['error'] = 'Lỗi xử lý người dùng: ' . $e->getMessage();
    }
    
    header('Location: ' . ROOT_URL . 'admin/users');
    exit;
}

/**
 * Xóa user
 * URL: /admin/deleteUser/{id}
 */
public function deleteUser($id) {
    $userModel = new User();
    
    $user = $userModel->getById($id);
    if (!$user) {
        $_SESSION['error'] = 'Người dùng không tồn tại';
        header('Location: ' . ROOT_URL . 'admin/users');
        exit;
    }
    
    try {
        $success = $userModel->deleteUser($id);
        $_SESSION['message'] = $success ? 'Đã xóa người dùng' : 'Không thể xóa người dùng';
    } catch (\Exception $e) {
        $_SESSION['error'] = 'Lỗi khi xóa người dùng: ' . $e->getMessage();
    }
    
    header('Location: ' . ROOT_URL . 'admin/users');
    exit;
}

public function orders($action = null, $param = null) {
    // Nếu action là 'detail', xử lý API JSON
    if ($action === 'detail' && $param) {
        $this->orderDetail($param);
        return;
    }
    // Chuyển tiếp cập nhật trạng thái sang AdminOrderController (API JSON)
    if ($action === 'updateStatus') {
        $oc = new AdminOrderController();
        $oc->updateStatus();
        return;
    }
    // Delegate to AdminOrderController for full orders management
    $oc = new AdminOrderController();
    return $oc->index();
}

private function orderDetail($orderId) {
    // Kiểm tra admin
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    if (!$orderId) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Order ID not provided']);
        exit;
    }

    $orderModel = new \Models\Order();
    $orderDetailModel = new \Models\OrderDetail();

    $order = $orderModel->getByIdWithUser($orderId);
    if (!$order) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Order not found']);
        exit;
    }

    $orderDetails = $orderDetailModel->getByOrderIdWithProduct($orderId);

    // Tính tổng
    $total = 0;
    $detailsArray = [];
    if ($orderDetails) {
        foreach ($orderDetails as $detail) {
            $total += $detail['quantity'] * $detail['Price'];
            $detailsArray[] = $detail;
        }
    }

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'order' => $order,
        'details' => $detailsArray,
        'total' => $total
    ]);
    exit;
}

public function branch() {
    // delegate to BranchController
    $bc = new BranchController();
    return $bc->index();
}

/**
 * Hiển thị form sửa hãng
 * URL: /admin/editBranch/{id}
 */
public function editBranch($id) {
    $bc = new BranchController();
    return $bc->adminEditBranch($id);
}

/**
 * Xử lý thêm / cập nhật hãng
 * URL: /admin/saveBranch (POST)
 */
public function saveBranch() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . ROOT_URL . 'admin/branch');
        exit;
    }
    
    $bc = new BranchController();
    return $bc->adminSaveBranch();
}

/**
 * Xóa hãng
 * URL: /admin/deleteBranch/{id}
 */
public function deleteBranch($id = null) {
    $bc = new BranchController();
    return $bc->adminDeleteBranch($id);
}

public function categories() {
    $this->requireAdmin();
    $cc = new CategoryController();
    return $cc->index();
}

/**
 * Hiển thị form sửa danh mục
 * URL: /admin/editCategory/{id}
 */
public function editCategory($id = null) {
    $cc = new CategoryController();
    return $cc->adminEditCategory($id);
}

/**
 * Xử lý thêm / cập nhật danh mục
 * URL: /admin/saveCategory (POST)
 */
public function saveCategory() {
    $cc = new CategoryController();
    return $cc->adminSaveCategory();
}


/**
 * Xóa danh mục
 * URL: /admin/deleteCategory/{id}
 */
public function deleteCategory($id) {
    $cc = new CategoryController();
    return $cc->adminDeleteCategory($id);
}


    /**
     * Trang quản lý màu sắc.
     */
    public function colors() {
        $cc = new ColorController();
        return $cc->index();
    }

    public function editColor($id = null) {
        $cc = new ColorController();
        return $cc->adminEditColor($id);
    }

    public function saveColor() {
        $cc = new ColorController();
        return $cc->adminSaveColor();
    }

    public function deleteColor($id = null) {
        $cc = new ColorController();
        return $cc->adminDeleteColor($id);
    }

    /**
     * Trang quản lý kích cỡ.
     */
    public function sizes() {
        $sc = new SizeController();
        return $sc->index();
    }

    public function editSize($id = null) {
        $sc = new SizeController();
        return $sc->adminEditSize($id);
    }

    public function saveSize() {
        $sc = new SizeController();
        return $sc->adminSaveSize();
    }

    public function deleteSize($id = null) {
        $sc = new SizeController();
        return $sc->adminDeleteSize($id);
    }

    /**
     * Trang quản lý biến thể sản phẩm.
     */
    public function productVariants() {
        $mvc = new ProductVarirantController();
        return $mvc->index();
    }

    public function editProductVariant($id = null) {
        $mvc = new ProductVarirantController();
        return $mvc->adminEditVariant($id);
    }

    public function saveProductVariant() {
        $mvc = new ProductVarirantController();
        return $mvc->adminSaveVariant();
    }

    public function deleteProductVariant($id = null) {
        $mvc = new ProductVarirantController();
        return $mvc->adminDeleteVariant($id);
    }

public function comments() {
    $this->renderView('admin/comment');
}

public function stats() {
    $this->renderView('admin/stat');
}

    public function dashboard() {
        $this->requireAdmin();

        $dm = new Dashboard();
        $year = (int)date('Y');

        // get optional selectors from query
        $selectedWeek = isset($_GET['week']) ? trim($_GET['week']) : null; // format YYYY-WW
        $selectedMonth = isset($_GET['month']) ? trim($_GET['month']) : null; // format YYYY-MM
        $rangeStart = isset($_GET['start']) ? trim($_GET['start']) : null; // YYYY-MM-DD
        $rangeEnd = isset($_GET['end']) ? trim($_GET['end']) : null; // YYYY-MM-DD

        // default period-level data (yearly)
        $topCustomers = $dm->topCustomers($year, 5);
        $topProducts = $dm->topProducts($year, 5);
        $revenueByMonth = $dm->revenueByMonth($year);
        $revenueByWeek = $dm->revenueByWeek(12); // last 12 weeks
        $topWorst = $dm->topWorstProducts($year, 5);

        // per-day datasets for selected week or month
        $weeklyPerDay = [];
        $monthlyPerDay = [];
        $topProductsPeriod = $topProducts;
        $topCustomersPeriod = $topCustomers;

        if ($selectedWeek) {
            // parse selectedWeek YYYY-WW
            if (preg_match('/^(\d{4})-W?(\d{1,2})$/', $selectedWeek, $m)) {
                $wYear = (int)$m[1];
                $wNum = (int)$m[2];
                $weeklyPerDay = $dm->revenueByWeekPerDay($wYear, $wNum);

                $dt = new \DateTime();
                $dt->setISODate($wYear, $wNum);
                $start = $dt->format('Y-m-d');
                $dt->modify('+6 days');
                $end = $dt->format('Y-m-d');

                $topProductsPeriod = $dm->topProductsByRange($start, $end, 5);
                $topCustomersPeriod = $dm->topCustomersByRange($start, $end, 5);
            }
        }

        if ($selectedMonth) {
            if (preg_match('/^(\d{4})-(\d{1,2})$/', $selectedMonth, $mm)) {
                $mYear = (int)$mm[1];
                $mNum = (int)$mm[2];
                $monthlyPerDay = $dm->revenueByMonthDays($mYear, $mNum, 30);

                $start = sprintf('%04d-%02d-01', $mYear, $mNum);
                $dt2 = new \DateTime($start);
                $dt2->modify('+29 days');
                $end = $dt2->format('Y-m-d');

                $topProductsPeriod = $dm->topProductsByRange($start, $end, 5);
                $topCustomersPeriod = $dm->topCustomersByRange($start, $end, 5);
            }
        }
        
        if ($rangeStart && $rangeEnd && preg_match('/^\d{4}-\d{2}-\d{2}$/', $rangeStart) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $rangeEnd)) {
            $weeklyPerDay = $dm->revenueByRangePerDay($rangeStart, $rangeEnd);
            $topProductsPeriod = $dm->topProductsByRange($rangeStart, $rangeEnd, 5);
            $topCustomersPeriod = $dm->topCustomersByRange($rangeStart, $rangeEnd, 5);
        }

    // Build last-12 week options and last 12 months for selectors
    $weekOptions = array_keys($dm->revenueByWeek(12));
    $monthOptions = [];
    for ($i = 0; $i < 12; $i++) {
        $d = new \DateTime();
        $d->modify('-' . $i . ' months');
        $monthOptions[] = $d->format('Y-m');
    }

    $data = [
        'title' => 'Dashbroad',
        'topCustomers' => $topCustomers,
        'topProducts' => $topProducts,
        'revenueByMonth' => $revenueByMonth,
        'revenueByWeek' => $revenueByWeek,
        'topWorst' => $topWorst,
        'year' => $year
    ];

    // extra period data
    $data['weeklyPerDay'] = $weeklyPerDay;
    $data['monthlyPerDay'] = $monthlyPerDay;
        $data['topProductsPeriod'] = $topProductsPeriod;
        $data['topCustomersPeriod'] = $topCustomersPeriod;
        $data['selectedWeek'] = $selectedWeek;
        $data['selectedMonth'] = $selectedMonth;
        $data['rangeStart'] = $rangeStart;
        $data['rangeEnd'] = $rangeEnd;
        $data['weekOptions'] = $weekOptions;
        $data['monthOptions'] = $monthOptions;

        $this->renderView('admin/dashboard', $data);
    }

    /**
     * Mặc định chuyển vào quản lý sản phẩm
     * URL: /admin hoặc /admin/index
     */
    public function index() {
        $this->requireAdmin();
        $this->products();
    }

    /**
     * Trang danh sách + form thêm sản phẩm
     * URL: /admin/products
     */
    public function products() {
        // Delegate product admin pages to ProductController
        $pc = new ProductController();
        return $pc->adminProducts();
    }

    /**
     * Hiển thị form sửa sản phẩm
     * URL: /admin/editProduct/{id}
     */
    public function editProduct($id) {
        $pc = new ProductController();
        return $pc->adminEditProduct($id);
    }

    /**
     * Xử lý thêm / cập nhật sản phẩm
     * URL: /admin/saveProduct (POST)
     */
    public function saveProduct() {
        $pc = new ProductController();
        return $pc->adminSaveProduct();
    }

    /**
     * Xóa sản phẩm
     * URL: /admin/deleteProduct/{id}
     */
    public function deleteProduct($id) {
        $pc = new ProductController();
        return $pc->adminDeleteProduct($id);
    }

    /**
     * Danh sách voucher (JSON)
     * URL: /admin/voucher/list
     */
    public function voucherList() {
        $this->requireAdmin();
        header('Content-Type: application/json; charset=utf-8');
        try {
            $file = ROOT_PATH . '/public/data/vouchers.json';
            if (!file_exists($file)) {
                echo json_encode([]);
                return;
            }
            $data = json_decode(file_get_contents($file), true);
            if (!is_array($data)) { $data = []; }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            echo json_encode(['error' => 'Failed to load vouchers: ' . $e->getMessage()]);
        }
    }

    /**
     * Lưu/ cập nhật voucher (JSON)
     * URL: /admin/voucher/save (POST)
     */
    public function voucherSave() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'admin/orders');
            exit;
        }
        $this->requireAdmin();
        header('Content-Type: application/json; charset=utf-8');
        try {
            $payload = json_decode(file_get_contents('php://input'), true);
            if (!is_array($payload)) { echo json_encode(['success' => false, 'error' => 'Invalid payload']); return; }
            $code = strtoupper(trim($payload['code'] ?? ''));
            $type = trim($payload['type'] ?? 'fixed');
            $value = (float)($payload['value'] ?? 0);
            $maxDiscount = isset($payload['max_discount']) && $payload['max_discount'] !== null ? (int)$payload['max_discount'] : null;
            $minOrder = (int)($payload['min_order'] ?? 0);
            $expiry = trim((string)($payload['expiry'] ?? ''));
            $active = !!($payload['active'] ?? true);
            $scope = trim($payload['scope'] ?? 'all');
            $categories = [];
            if ($scope === 'category') {
                $categories = isset($payload['categories']) && is_array($payload['categories']) ? array_values($payload['categories']) : [];
            }
            if ($code === '' || !in_array($type, ['fixed', 'percent']) || $value <= 0) {
                echo json_encode(['success' => false, 'error' => 'Invalid voucher data']);
                return;
            }

            $file = ROOT_PATH . '/public/data/vouchers.json';
            $list = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
            if (!is_array($list)) { $list = []; }
            $found = false;
            foreach ($list as &$v) {
                if (strtoupper((string)($v['code'] ?? '')) === $code) {
                    $v['code'] = $code;
                    $v['type'] = $type;
                    $v['value'] = $value;
                    $v['max_discount'] = $maxDiscount;
                    $v['min_order'] = max(0, $minOrder);
                    $v['expiry'] = $expiry;
                    $v['active'] = $active;
                    $v['scope'] = $scope;
                    $v['categories'] = $categories;
                    $found = true;
                    break;
                }
            }
            unset($v);
            if (!$found) {
                $list[] = [
                    'code' => $code,
                    'type' => $type,
                    'value' => $value,
                    'max_discount' => $maxDiscount,
                    'min_order' => max(0, $minOrder),
                    'expiry' => $expiry,
                    'active' => $active,
                    'scope' => $scope,
                    'categories' => $categories
                ];
            }
            @file_put_contents($file, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Xóa voucher (JSON)
     * URL: /admin/voucher/delete (POST)
     */
    public function voucherDelete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'admin/orders');
            exit;
        }
        $this->requireAdmin();
        header('Content-Type: application/json; charset=utf-8');
        try {
            $payload = json_decode(file_get_contents('php://input'), true);
            $code = strtoupper(trim($payload['code'] ?? ''));
            if ($code === '') { echo json_encode(['success' => false, 'error' => 'Code is required']); return; }
            $file = ROOT_PATH . '/public/data/vouchers.json';
            $list = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
            if (!is_array($list)) { $list = []; }
            $newList = [];
            foreach ($list as $v) {
                if (strtoupper((string)($v['code'] ?? '')) !== $code) { $newList[] = $v; }
            }
            @file_put_contents($file, json_encode($newList, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Router cho voucher theo dạng /admin/voucher/{action}
     * Hỗ trợ: list, save, delete
     */
    public function voucher($action = null) {
        // Map hành động sang method tương ứng
        switch (strtolower((string)$action)) {
            case 'list':
                $this->voucherList();
                return;
            case 'save':
                $this->voucherSave();
                return;
            case 'delete':
                $this->voucherDelete();
                return;
            default:
                // Trả về lỗi JSON nếu action không hợp lệ
                $this->requireAdmin();
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['error' => 'Invalid voucher action']);
                return;
        }
    }

    /**
     * Router hoàn tiền: /admin/refund/{action}
     * Hỗ trợ: info/{orderId}, confirm (POST)
     */
    public function refund($action = null, $orderId = null) {
        switch (strtolower((string)$action)) {
            case 'info':
                $this->refundInfo($orderId);
                return;
            case 'confirm':
                $this->refundConfirm();
                return;
            case 'marktransfer':
                $this->refundMarkTransfer();
                return;
            default:
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Invalid refund action']);
                return;
        }
    }

    /**
     * Trả về thông tin hoàn tiền của đơn hàng (JSON)
     * URL: /admin/refund/info/{orderId}
     */
    private function refundInfo($orderId) {
        // Kiểm tra admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        if (!$orderId) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Order ID not provided']);
            exit;
        }
        $orderModel = new \Models\Order();
        $orderDetailModel = new \Models\OrderDetail();
        $userModel = new \Models\User();

        $order = $orderModel->getByIdWithUser($orderId);
        if (!$order) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Order not found']);
            exit;
        }
        // Xác định phương thức thanh toán là online
        $pm = $order['PaymentMethod'] ?? '';
        $rawNote = (string)($order['Note'] ?? '');
        $isOnline = ($pm === 'online') || (stripos($rawNote, 'Thanh toán Online') !== false);
        if (!$isOnline) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Order is not online payment']);
            exit;
        }
        // Chỉ hiển thị QR khi đơn đang ở trạng thái đã hủy
        if (($order['TrangThai'] ?? '') !== 'cancelled') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Order is not cancelled']);
            exit;
        }

        // Tính tổng cần hoàn giống công thức hiển thị hóa đơn
        $items = $orderDetailModel->getByOrderIdWithProduct($orderId) ?? [];
        $subtotal = 0;
        foreach ($items as $it) {
            $qty = (int)($it['quantity'] ?? $it['Quantity'] ?? 0);
            $price = (float)($it['Price'] ?? $it['price'] ?? 0);
            $subtotal += $qty * $price;
        }
        $vat = (int)round($subtotal * 0.05);
        $ship = 50000;
        $voucherDiscount = 0;
        if ($rawNote !== '' && preg_match('/Voucher:\s*([A-Z0-9_-]+)\s*-\s*(\d+)/i', $rawNote, $m)) {
            $voucherDiscount = (int)($m[2] ?? 0);
        }
        $refundAmount = max(0, $subtotal + $vat + $ship - max(0, $voucherDiscount));

        // Lấy thông tin ngân hàng khách hàng
        $userId = $order['_UserName_Id'] ?? '';
        $user = $userModel->getById($userId);
        $bankName = $user['bank_name'] ?? '';
        $accountNo = $user['bank_account_number'] ?? '';
        $accountName = strtoupper($user['name'] ?? $user['username'] ?? '');
        $bankCode = '';
        $bankCodes = \Core\PaymentHelper::getAllBankCodes();
        foreach ($bankCodes as $code => $name) {
            if (strcasecmp($name, $bankName) === 0 || strcasecmp($code, $bankName) === 0) { $bankCode = $code; break; }
        }

        $qrUrl = '';
        if ($bankCode && $accountNo && $accountName) {
            $qrUrl = \Core\PaymentHelper::buildQRUrl($bankCode, $accountNo, $accountName, $refundAmount, 'REFUND-' . $orderId, 'compact');
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'order_id' => $orderId,
            'amount' => $refundAmount,
            'bank_code' => $bankCode,
            'bank_name' => $bankName,
            'account_no' => $accountNo,
            'account_name' => $accountName,
            'qr_url' => $qrUrl
        ]);
        exit;
    }

    /**
     * Xác nhận đã chuyển hoàn tiền và cộng số dư cho khách
     * URL: /admin/refund/confirm (POST JSON)
     */
    private function refundConfirm() {
        // Chỉ cho phép POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            exit;
        }
        // Kiểm tra admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit;
        }
        $payload = json_decode(file_get_contents('php://input'), true);
        $orderId = trim($payload['order_id'] ?? '');
        if ($orderId === '') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Order ID required']);
            exit;
        }
        $orderModel = new \Models\Order();
        $orderDetailModel = new \Models\OrderDetail();
        $userModel = new \Models\User();
        $order = $orderModel->getByIdWithUser($orderId);
        if (!$order) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Order not found']);
            exit;
        }
        $pm = $order['PaymentMethod'] ?? '';
        $rawNote = (string)($order['Note'] ?? '');
        $isOnline = ($pm === 'online') || (stripos($rawNote, 'Thanh toán Online') !== false);
        if (!$isOnline) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Order is not online payment']);
            exit;
        }
        // Tính số tiền hoàn
        $items = $orderDetailModel->getByOrderIdWithProduct($orderId) ?? [];
        $subtotal = 0;
        foreach ($items as $it) {
            $qty = (int)($it['quantity'] ?? $it['Quantity'] ?? 0);
            $price = (float)($it['Price'] ?? $it['price'] ?? 0);
            $subtotal += $qty * $price;
        }
        $vat = (int)round($subtotal * 0.05);
        $ship = 50000;
        $voucherDiscount = 0;
        if ($rawNote !== '' && preg_match('/Voucher:\s*([A-Z0-9_-]+)\s*-\s*(\d+)/i', $rawNote, $m)) {
            $voucherDiscount = (int)($m[2] ?? 0);
        }
        $refundAmount = max(0, $subtotal + $vat + $ship - max(0, $voucherDiscount));

        // Cộng số dư cho khách (users.Price)
        $userId = $order['_UserName_Id'] ?? '';
        $user = $userModel->getById($userId);
        if (!$user) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'User not found']);
            exit;
        }
        $newBalance = (float)($user['price'] ?? 0) + (float)$refundAmount;
        $ok = $userModel->updateUser($userId, ['price' => $newBalance]);
        if (!$ok) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Failed to update user balance']);
            exit;
        }

        // Ghi vào note đơn hàng
        $orderModel->appendNote($orderId, 'REFUND CONFIRMED: +' . number_format($refundAmount, 0, ',', '.') . 'đ');
        // Cập nhật trạng thái sang refunded
        $orderModel->updateStatus($orderId, 'refunded');

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'amount' => $refundAmount, 'new_balance' => $newBalance]);
        exit;
    }

    /**
     * Xác nhận đã hoàn tiền qua chuyển khoản (không cộng số dư ví)
     * URL: /admin/refund/markTransfer (POST JSON)
     */
    private function refundMarkTransfer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            exit;
        }
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit;
        }
        $payload = json_decode(file_get_contents('php://input'), true);
        $orderId = trim($payload['order_id'] ?? '');
        if ($orderId === '') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Order ID required']);
            exit;
        }
        $orderModel = new \Models\Order();
        $orderDetailModel = new \Models\OrderDetail();
        $order = $orderModel->getByIdWithUser($orderId);
        if (!$order) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Order not found']);
            exit;
        }
        $pm = $order['PaymentMethod'] ?? '';
        $rawNote = (string)($order['Note'] ?? '');
        $isOnline = ($pm === 'online') || (stripos($rawNote, 'Thanh toán Online') !== false);
        if (!$isOnline) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Order is not online payment']);
            exit;
        }
        $items = $orderDetailModel->getByOrderIdWithProduct($orderId) ?? [];
        $subtotal = 0;
        foreach ($items as $it) {
            $qty = (int)($it['quantity'] ?? $it['Quantity'] ?? 0);
            $price = (float)($it['Price'] ?? $it['price'] ?? 0);
            $subtotal += $qty * $price;
        }
        $vat = (int)round($subtotal * 0.05);
        $ship = 50000;
        $voucherDiscount = 0;
        if ($rawNote !== '' && preg_match('/Voucher:\s*([A-Z0-9_-]+)\s*-\s*(\d+)/i', $rawNote, $m)) {
            $voucherDiscount = (int)($m[2] ?? 0);
        }
        $refundAmount = max(0, $subtotal + $vat + $ship - max(0, $voucherDiscount));

        // Ghi note xác nhận chuyển khoản
        $orderModel->appendNote($orderId, 'REFUND BANK TRANSFER CONFIRMED: -' . number_format($refundAmount, 0, ',', '.') . 'đ');
        // Cập nhật trạng thái sang refunded
        $orderModel->updateStatus($orderId, 'refunded');

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'amount' => $refundAmount, 'method' => 'bank_transfer']);
        exit;
    }
}

?>

