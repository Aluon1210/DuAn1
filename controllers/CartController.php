<?php
require_once 'models/Cart.php';
require_once 'models/Product.php';

class CartController {
    private $cartModel;
    private $productModel;

    public function __construct() {
        $this->cartModel = new Cart();
        $this->productModel = new Product();
    }

    // Hiển thị giỏ hàng
    public function index() {
        $cart = $this->cartModel->getCart();
        $cartItems = [];
        $total = 0;

        foreach ($cart as $product_id => $item) {
            $product = $this->productModel->getById($product_id);
            if ($product) {
                $item['product'] = $product;
                $item['subtotal'] = $product['price'] * $item['quantity'];
                $total += $item['subtotal'];
                $cartItems[] = $item;
            }
        }

        require_once 'views/cart/index.php';
    }

    // Thêm vào giỏ
    public function add($product_id = null) {
        if ($product_id === null || !is_numeric($product_id)) {
            header('Location: ?url=product');
            return;
        }

        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        if ($quantity < 1) $quantity = 1;

        $this->cartModel->add($product_id, $quantity);
        $_SESSION['message'] = 'Đã thêm sản phẩm vào giỏ hàng';
        
        header('Location: ?url=cart');
    }

    // Xóa khỏi giỏ
    public function remove($product_id = null) {
        if ($product_id !== null) {
            $this->cartModel->remove($product_id);
        }
        header('Location: ?url=cart');
    }

    // Cập nhật giỏ
    public function update() {
        $quantities = isset($_POST['quantity']) ? $_POST['quantity'] : [];
        foreach ($quantities as $product_id => $quantity) {
            $quantity = (int)$quantity;
            if ($quantity <= 0) {
                $this->cartModel->remove($product_id);
            } else {
                $this->cartModel->update($product_id, $quantity);
            }
        }
        header('Location: ?url=cart');
    }

    // Làm trống giỏ
    public function clear() {
        $this->cartModel->clear();
        header('Location: ?url=cart');
    }
}
?>
