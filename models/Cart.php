<?php
class Cart {
    private $cart = [];

    public function __construct() {
        if (isset($_SESSION['cart'])) {
            $this->cart = $_SESSION['cart'];
        }
    }

    // Thêm sản phẩm vào giỏ
    public function add($product_id, $quantity = 1) {
        if (isset($this->cart[$product_id])) {
            $this->cart[$product_id]['quantity'] += $quantity;
        } else {
            $this->cart[$product_id] = [
                'quantity' => $quantity
            ];
        }
        $_SESSION['cart'] = $this->cart;
    }

    // Xóa sản phẩm khỏi giỏ
    public function remove($product_id) {
        if (isset($this->cart[$product_id])) {
            unset($this->cart[$product_id]);
        }
        $_SESSION['cart'] = $this->cart;
    }

    // Cập nhật số lượng
    public function update($product_id, $quantity) {
        if (isset($this->cart[$product_id])) {
            $this->cart[$product_id]['quantity'] = $quantity;
        }
        $_SESSION['cart'] = $this->cart;
    }

    // Lấy giỏ hàng
    public function getCart() {
        return $this->cart;
    }

    // Làm trống giỏ
    public function clear() {
        $this->cart = [];
        $_SESSION['cart'] = [];
    }

    // Đếm số sản phẩm
    public function count() {
        return count($this->cart);
    }
}
?>
