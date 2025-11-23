<?php
// File: src/Controllers/CartController.php
namespace Controllers;

use Core\Controller;
use Models\Product;

class CartController extends Controller {
    
    /**
     * Khởi tạo giỏ hàng trong session nếu chưa có
     */
    private function initCart() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }
    
    /**
     * Hiển thị giỏ hàng
     * URL: /cart hoặc /cart/index
     */
    public function index() {
        $this->initCart();

        $productModel = new Product();
        $cartItems = [];
        $total = 0;

        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $productModel->getById($productId);
            if ($product) {
                // Đảm bảo không vượt quá số lượng tồn kho
                $quantity = min($quantity, $product['quantity']);
                $subtotal = $product['price'] * $quantity;

                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];

                $total += $subtotal;
            } else {
                // Xóa sản phẩm không tồn tại khỏi giỏ hàng
                unset($_SESSION['cart'][$productId]);
            }
        }

        $data = [
            'title' => 'Giỏ Hàng',
            'cartItems' => $cartItems,
            'total' => $total
        ];

        // Dùng view Cart duy nhất cho giỏ hàng
        $this->renderView('Cart', $data);
    }
    
    /**
     * Thêm sản phẩm vào giỏ hàng
     * URL: /cart/add/{id}
     */
    public function add($id) {
        $this->initCart();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productModel = new Product();
            $product = $productModel->getById($id);
            
            if (!$product) {
                $_SESSION['error'] = 'Sản phẩm không tồn tại';
                header('Location: ' . ROOT_URL . 'product');
                exit;
            }
            
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            if ($quantity <= 0) {
                $_SESSION['error'] = 'Số lượng phải lớn hơn 0';
                header('Location: ' . ROOT_URL . 'product/detail/' . $id);
                exit;
            }
            
            if ($quantity > $product['quantity']) {
                $_SESSION['error'] = 'Số lượng vượt quá tồn kho';
                header('Location: ' . ROOT_URL . 'product/detail/' . $id);
                exit;
            }
            
            // Thêm vào giỏ hàng hoặc cập nhật số lượng
            if (isset($_SESSION['cart'][$id])) {
                $newQuantity = $_SESSION['cart'][$id] + $quantity;
                if ($newQuantity > $product['quantity']) {
                    $_SESSION['error'] = 'Tổng số lượng vượt quá tồn kho';
                } else {
                    $_SESSION['cart'][$id] = $newQuantity;
                    $_SESSION['message'] = 'Đã cập nhật số lượng sản phẩm trong giỏ hàng';
                }
            } else {
                $_SESSION['cart'][$id] = $quantity;
                $_SESSION['message'] = 'Đã thêm sản phẩm vào giỏ hàng';
            }
            
            header('Location: ' . ROOT_URL . 'product/detail/' . $id);
            exit;
        }
        
        header('Location: ' . ROOT_URL . 'product');
        exit;
    }
    
    /**
     * Xóa sản phẩm khỏi giỏ hàng
     * URL: /cart/remove/{id}
     */
    public function remove($id) {
        $this->initCart();
        
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
            $_SESSION['message'] = 'Đã xóa sản phẩm khỏi giỏ hàng';
        }
        
        header('Location: ' . ROOT_URL . 'cart');
        exit;
    }
    
    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     * URL: /cart/update
     */
    public function update() {
        $this->initCart();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantity'])) {
            $productModel = new Product();
            $hasUpdate = false;
            
            foreach ($_POST['quantity'] as $productId => $quantity) {
                $quantity = (int)$quantity;
                $productId = (int)$productId;
                
                if ($quantity <= 0) {
                    unset($_SESSION['cart'][$productId]);
                    $hasUpdate = true;
                } else {
                    $product = $productModel->getById($productId);
                    if ($product) {
                        // Giới hạn số lượng theo tồn kho
                        $quantity = min($quantity, $product['quantity']);
                        if ($quantity > 0) {
                            $_SESSION['cart'][$productId] = $quantity;
                            $hasUpdate = true;
                        } else {
                            unset($_SESSION['cart'][$productId]);
                            $hasUpdate = true;
                        }
                    } else {
                        unset($_SESSION['cart'][$productId]);
                        $hasUpdate = true;
                    }
                }
            }
            
            if ($hasUpdate) {
                $_SESSION['message'] = 'Đã cập nhật giỏ hàng';
            }
        }
        
        header('Location: ' . ROOT_URL . 'cart');
        exit;
    }
    
    /**
     * Xóa tất cả sản phẩm trong giỏ hàng
     * URL: /cart/clear
     */
    public function clear() {
        $this->initCart();
        
        $_SESSION['cart'] = [];
        $_SESSION['message'] = 'Đã xóa tất cả sản phẩm trong giỏ hàng';
        
        header('Location: ' . ROOT_URL . 'cart');
        exit;
    }
    
    /**
     * Xác nhận thanh toán (bước 1 giống Shopee)
     * URL: /cart/confirm (POST)
     */
    public function confirm() {
        $this->initCart();

        if (!isset($_SESSION['user'])) {
            $redirect = ROOT_URL . 'cart';
            header('Location: ' . ROOT_URL . 'login?redirect=' . urlencode($redirect));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $selected = isset($_POST['selected']) ? (array)$_POST['selected'] : [];
        $quantities = isset($_POST['quantity']) ? (array)$_POST['quantity'] : [];

        if (empty($selected)) {
            $_SESSION['error'] = 'Vui lòng chọn sản phẩm để thanh toán';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $productModel = new \Models\Product();
        $items = [];
        $total = 0;

        foreach ($selected as $productId) {
            $productId = (string)$productId;
            $product = $productModel->getById($productId);
            if (!$product) {
                $_SESSION['error'] = 'Sản phẩm không tồn tại';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }
            $qty = isset($quantities[$productId]) ? (int)$quantities[$productId] : 0;
            if ($qty <= 0) {
                $_SESSION['error'] = 'Số lượng không hợp lệ';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }
            if ($product['quantity'] < $qty) {
                $qty = $product['quantity'];
                if ($qty <= 0) {
                    $_SESSION['error'] = 'Sản phẩm ' . $product['name'] . ' đã hết hàng';
                    header('Location: ' . ROOT_URL . 'cart');
                    exit;
                }
                $_SESSION['error'] = 'Một số sản phẩm đã điều chỉnh theo tồn kho hiện tại';
            }
            $items[] = [
                'product' => $product,
                'quantity' => $qty,
                'subtotal' => $product['price'] * $qty
            ];
            $total += $product['price'] * $qty;
        }

        $data = [
            'title' => 'Xác nhận thanh toán',
            'items' => $items,
            'total' => $total,
            'user' => $_SESSION['user']
        ];

        $this->renderView('CheckoutConfirm', $data);
    }
    
    /**
     * Thanh toán các sản phẩm đã chọn
     * URL: /cart/checkout
     */
    public function checkout() {
        $this->initCart();

        if (!isset($_SESSION['user'])) {
            $redirect = ROOT_URL . 'cart';
            header('Location: ' . ROOT_URL . 'login?redirect=' . urlencode($redirect));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $selected = isset($_POST['selected']) ? (array)$_POST['selected'] : [];
        $quantities = isset($_POST['quantity']) ? (array)$_POST['quantity'] : [];

        if (empty($selected)) {
            $_SESSION['error'] = 'Vui lòng chọn sản phẩm để thanh toán';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $productModel = new \Models\Product();
        $orderDetails = [];
        $totalAmount = 0;

        foreach ($selected as $productId) {
            $productId = (string)$productId;
            $product = $productModel->getById($productId);
            if (!$product) {
                $_SESSION['error'] = 'Sản phẩm không tồn tại';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }

            $qty = isset($quantities[$productId]) ? (int)$quantities[$productId] : 0;
            if ($qty <= 0) {
                $_SESSION['error'] = 'Số lượng không hợp lệ cho sản phẩm đã chọn';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }

            if ($product['quantity'] < $qty) {
                $_SESSION['error'] = 'Sản phẩm ' . $product['name'] . ' không đủ tồn kho';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }

            $orderDetails[] = [
                'Product_Id' => $productId,
                'quantity' => $qty
            ];
            $totalAmount += $product['price'] * $qty;
        }

        $user = $_SESSION['user'];
        $orderModel = new \Models\Order();

        $orderId = $orderModel->createWithDetails([
            'user_id' => $user['id'] ?? $user['username'] ?? '',
            'address' => $user['address'] ?? '',
            'note' => 'Thanh toán giỏ hàng',
            'status' => 'completed'
        ], $orderDetails);

        if ($orderId) {
            foreach ($selected as $pid) {
                unset($_SESSION['cart'][$pid]);
            }
            $_SESSION['message'] = 'Thanh toán thành công. Mã đơn: ' . $orderId . '. Tổng tiền: ' . number_format($totalAmount, 0, ',', '.') . ' ₫';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        } else {
            $_SESSION['error'] = 'Thanh toán thất bại. Vui lòng thử lại';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }
    }
    
    /**
     * Đặt hàng (bước 2 xác nhận cuối)
     * URL: /cart/placeOrder (POST)
     */
    public function placeOrder() {
        $this->initCart();
        if (!isset($_SESSION['user'])) {
            $redirect = ROOT_URL . 'cart';
            header('Location: ' . ROOT_URL . 'login?redirect=' . urlencode($redirect));
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $selected = isset($_POST['selected']) ? (array)$_POST['selected'] : [];
        $quantities = isset($_POST['quantity']) ? (array)$_POST['quantity'] : [];
        if (empty($selected)) {
            $_SESSION['error'] = 'Vui lòng chọn sản phẩm để đặt hàng';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $productModel = new \Models\Product();
        $orderDetails = [];
        $totalAmount = 0;

        foreach ($selected as $productId) {
            $productId = (string)$productId;
            $product = $productModel->getById($productId);
            if (!$product) {
                $_SESSION['error'] = 'Sản phẩm không tồn tại';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }
            $qty = isset($quantities[$productId]) ? (int)$quantities[$productId] : 0;
            if ($qty <= 0 || $product['quantity'] < $qty) {
                $_SESSION['error'] = 'Tồn kho thay đổi. Vui lòng kiểm tra lại';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }
            $orderDetails[] = ['Product_Id' => $productId, 'quantity' => $qty];
            $totalAmount += $product['price'] * $qty;
        }

        $user = $_SESSION['user'];
        $orderModel = new \Models\Order();
        $orderId = $orderModel->createWithDetails([
            'user_id' => $user['id'] ?? $user['username'] ?? '',
            'address' => $_POST['address'] ?? ($user['address'] ?? ''),
            'note' => $_POST['note'] ?? 'Đặt hàng',
            'status' => 'completed'
        ], $orderDetails);

        if ($orderId) {
            foreach ($selected as $pid) { unset($_SESSION['cart'][$pid]); }
            $_SESSION['message'] = 'Đặt hàng thành công. Mã đơn: ' . $orderId . '. Tổng tiền: ' . number_format($totalAmount, 0, ',', '.') . ' ₫';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        } else {
            $_SESSION['error'] = 'Đặt hàng thất bại. Vui lòng thử lại';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }
    }
}
?>

