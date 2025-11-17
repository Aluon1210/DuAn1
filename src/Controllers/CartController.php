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
        
        $this->loadView('cart/index', $data);
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
}
?>

