<?php
// File: src/Controllers/CartController.php
namespace Controllers;

use Core\Controller;
use Models\Product;

class CartController extends Controller
{

    /**
     * Khởi tạo giỏ hàng trong session nếu chưa có
     */
    private function initCart()
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    /**
     * Hiển thị giỏ hàng
     * URL: /cart hoặc /cart/index
     */
    public function index()
    {
        $this->initCart();

        $productModel = new Product();
        $variantModel = new \Models\Product_Varirant();
        $colorModel = new \Models\Color();
        $sizeModel = new \Models\Size();

        $cartItems = [];
        $total = 0;

        foreach ($_SESSION['cart'] as $cartKey => $cartData) {
            // Hỗ trợ cả cấu trúc cũ (chỉ quantity) và mới (array)
            if (!is_array($cartData)) {
                // Cấu trúc cũ: $cartKey là product_id, $cartData là quantity
                $productId = $cartKey;
                $quantity = $cartData;
                $variantId = null;
            } else {
                // Cấu trúc mới
                $productId = $cartData['product_id'] ?? $cartKey;
                $quantity = $cartData['quantity'] ?? 1;
                $variantId = $cartData['variant_id'] ?? null;
            }

            $product = $productModel->getById($productId);
            if (!$product) {
                // Xóa sản phẩm không tồn tại khỏi giỏ hàng
                unset($_SESSION['cart'][$cartKey]);
                continue;
            }

            $price = $product['price'];
            $maxQuantity = $product['quantity'];
            $variant = null;
            $color = null;
            $size = null;

            // Nếu có variant, lấy thông tin variant
            if ($variantId) {
                $variant = $variantModel->getById($variantId);
                if ($variant && $variant['product_id'] == $productId) {
                    $price = $variant['price'] ?? $price;
                    $maxQuantity = $variant['stock'] ?? $maxQuantity;

                    // Lấy thông tin color và size
                    if ($variant['color_id']) {
                        $color = $colorModel->getById($variant['color_id']);
                    }
                    if ($variant['size_id']) {
                        $size = $sizeModel->getById($variant['size_id']);
                    }
                }
            }

            // Đảm bảo không vượt quá số lượng tồn kho
            $quantity = min($quantity, $maxQuantity);
            $subtotal = $price * $quantity;

            $cartItems[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'price' => $price,
                'variant' => $variant,
                'color' => $color,
                'size' => $size,
                'cart_key' => $cartKey
            ];

            $total += $subtotal;
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
    public function add($id)
    {
        $this->initCart();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productModel = new Product();
            $product = $productModel->getById($id);

            if (!$product) {
                $_SESSION['error'] = 'Sản phẩm không tồn tại';
                header('Location: ' . ROOT_URL . 'product');
                exit;
            }

            $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;
            $variantId = isset($_POST['variant_id']) ? trim($_POST['variant_id']) : null;

            if ($quantity <= 0) {
                $_SESSION['error'] = 'Số lượng phải lớn hơn 0';
                header('Location: ' . ROOT_URL . 'product/detail/' . $id);
                exit;
            }

            // Nếu có variant_id, kiểm tra variant
            $maxQuantity = $product['quantity'];
            if ($variantId) {
                $variantModel = new \Models\Product_Varirant();
                $variant = $variantModel->getById($variantId);
                if ($variant && $variant['product_id'] == $id) {
                    $maxQuantity = $variant['stock'];
                } else {
                    $_SESSION['error'] = 'Biến thể không hợp lệ';
                    header('Location: ' . ROOT_URL . 'product/detail/' . $id);
                    exit;
                }
            }

            if ($quantity > $maxQuantity) {
                $_SESSION['error'] = 'Số lượng vượt quá tồn kho';
                header('Location: ' . ROOT_URL . 'product/detail/' . $id);
                exit;
            }

            // Tạo key cho giỏ hàng: dùng variant_id nếu có, nếu không dùng product_id
            $cartKey = $variantId ? 'variant_' . $variantId : 'product_' . $id;

            // Thêm vào giỏ hàng hoặc cập nhật số lượng
            if (isset($_SESSION['cart'][$cartKey])) {
                $newQuantity = $_SESSION['cart'][$cartKey]['quantity'] + $quantity;
                if ($newQuantity > $maxQuantity) {
                    $_SESSION['error'] = 'Tổng số lượng vượt quá tồn kho';
                } else {
                    $_SESSION['cart'][$cartKey]['quantity'] = $newQuantity;
                    $_SESSION['message'] = 'Đã cập nhật số lượng sản phẩm trong giỏ hàng';
                }
            } else {
                $_SESSION['cart'][$cartKey] = [
                    'product_id' => $id,
                    'variant_id' => $variantId,
                    'quantity' => $quantity
                ];
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
     * URL: /cart/remove/{key}
     */
    public function remove($key)
    {
        $this->initCart();

        // Hỗ trợ cả key cũ (product_id) và key mới (variant_xxx hoặc product_xxx)
        $found = false;
        if (isset($_SESSION['cart'][$key])) {
            unset($_SESSION['cart'][$key]);
            $found = true;
        } else {
            // Tìm theo product_id hoặc variant_id
            foreach ($_SESSION['cart'] as $cartKey => $cartData) {
                if (is_array($cartData)) {
                    if ($cartData['product_id'] == $key || $cartData['variant_id'] == $key) {
                        unset($_SESSION['cart'][$cartKey]);
                        $found = true;
                        break;
                    }
                } elseif ($cartKey == $key) {
                    unset($_SESSION['cart'][$cartKey]);
                    $found = true;
                    break;
                }
            }
        }

        if ($found) {
            $_SESSION['message'] = 'Đã xóa sản phẩm khỏi giỏ hàng';
        }

        header('Location: ' . ROOT_URL . 'cart');
        exit;
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     * URL: /cart/update
     */
    public function update()
    {
        $this->initCart();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantity'])) {
            $productModel = new Product();
            $variantModel = new \Models\Product_Varirant();
            $hasUpdate = false;

            foreach ($_POST['quantity'] as $cartKey => $quantity) {
                $quantity = (int) $quantity;

                if ($quantity <= 0) {
                    if (isset($_SESSION['cart'][$cartKey])) {
                        unset($_SESSION['cart'][$cartKey]);
                        $hasUpdate = true;
                    }
                } else {
                    // Tìm item trong cart
                    $cartData = $_SESSION['cart'][$cartKey] ?? null;

                    if (!$cartData) {
                        continue;
                    }

                    // Hỗ trợ cả cấu trúc cũ và mới
                    if (is_array($cartData)) {
                        $productId = $cartData['product_id'];
                        $variantId = $cartData['variant_id'] ?? null;
                    } else {
                        $productId = $cartKey;
                        $variantId = null;
                    }

                    $product = $productModel->getById($productId);
                    if (!$product) {
                        unset($_SESSION['cart'][$cartKey]);
                        $hasUpdate = true;
                        continue;
                    }

                    $maxQuantity = $product['quantity'];

                    // Nếu có variant, kiểm tra stock của variant
                    if ($variantId) {
                        $variant = $variantModel->getById($variantId);
                        if ($variant && $variant['product_id'] == $productId) {
                            $maxQuantity = $variant['stock'] ?? 0;
                        }
                    }

                    // Giới hạn số lượng theo tồn kho
                    $quantity = min($quantity, $maxQuantity);

                    if ($quantity > 0) {
                        if (is_array($cartData)) {
                            $_SESSION['cart'][$cartKey]['quantity'] = $quantity;
                        } else {
                            $_SESSION['cart'][$cartKey] = $quantity;
                        }
                        $hasUpdate = true;
                    } else {
                        unset($_SESSION['cart'][$cartKey]);
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
    public function clear()
    {
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
    public function confirm()
    {
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

        $selected = isset($_POST['selected']) ? (array) $_POST['selected'] : [];
        $quantities = isset($_POST['quantity']) ? (array) $_POST['quantity'] : [];

        if (empty($selected)) {
            $_SESSION['error'] = 'Vui lòng chọn sản phẩm để thanh toán';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $productModel = new \Models\Product();
        $items = [];
        $total = 0;

        foreach ($selected as $productId) {
            $productId = (string) $productId;
            $product = $productModel->getById($productId);
            if (!$product) {
                $_SESSION['error'] = 'Sản phẩm không tồn tại';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }
            $qty = isset($quantities[$productId]) ? (int) $quantities[$productId] : 0;
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
    public function checkout()
    {
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

        $selected = isset($_POST['selected']) ? (array) $_POST['selected'] : [];
        $quantities = isset($_POST['quantity']) ? (array) $_POST['quantity'] : [];

        if (empty($selected)) {
            $_SESSION['error'] = 'Vui lòng chọn sản phẩm để thanh toán';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $productModel = new \Models\Product();
        $variantModel = new \Models\Product_Varirant();
        $orderDetails = [];
        $totalAmount = 0;

        foreach ($selected as $cartKey) {
            // Lấy thông tin từ cart
            $cartData = $_SESSION['cart'][$cartKey] ?? null;
            if (!$cartData) {
                continue;
            }

            // Hỗ trợ cả cấu trúc cũ và mới
            if (is_array($cartData)) {
                $productId = $cartData['product_id'];
                $variantId = $cartData['variant_id'] ?? null;
                $qty = isset($quantities[$cartKey]) ? (int) $quantities[$cartKey] : $cartData['quantity'];
            } else {
                $productId = $cartKey;
                $variantId = null;
                $qty = isset($quantities[$cartKey]) ? (int) $quantities[$cartKey] : $cartData;
            }

            $product = $productModel->getById($productId);
            if (!$product) {
                $_SESSION['error'] = 'Sản phẩm không tồn tại';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }

            if ($qty <= 0) {
                $_SESSION['error'] = 'Số lượng không hợp lệ cho sản phẩm đã chọn';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }

            $price = $product['price'];
            $maxQuantity = $product['quantity'];

            // Nếu có variant, kiểm tra variant
            if ($variantId) {
                $variant = $variantModel->getById($variantId);
                if ($variant && $variant['product_id'] == $productId) {
                    $price = $variant['price'] ?? $price;
                    $maxQuantity = $variant['stock'] ?? 0;
                } else {
                    $_SESSION['error'] = 'Biến thể không hợp lệ';
                    header('Location: ' . ROOT_URL . 'cart');
                    exit;
                }
            }

            if ($maxQuantity < $qty) {
                $_SESSION['error'] = 'Sản phẩm ' . $product['name'] . ' không đủ tồn kho';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }

            $orderDetails[] = [
                'Product_Id' => $productId,
                'Variant_Id' => $variantId,
                'quantity' => $qty
            ];
            $totalAmount += $price * $qty;
        }

        if (empty($orderDetails)) {
            $_SESSION['error'] = 'Không có sản phẩm hợp lệ để thanh toán';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $user = $_SESSION['user'];
        $orderModel = new \Models\Order();

        // Trạng thái mặc định là 'pending' (chờ xác nhận)
        $orderId = $orderModel->createWithDetails([
            'user_id' => $user['id'] ?? $user['username'] ?? '',
            'address' => $user['address'] ?? '',
            'note' => 'Thanh toán giỏ hàng',
            'status' => 'pending'
        ], $orderDetails);

        if ($orderId) {
            // Xóa các sản phẩm đã thanh toán khỏi giỏ hàng
            foreach ($selected as $cartKey) {
                unset($_SESSION['cart'][$cartKey]);
            }
            $_SESSION['message'] = 'Đặt hàng thành công! Mã đơn: ' . $orderId . '. Tổng tiền: ' . number_format($totalAmount, 0, ',', '.') . ' ₫. Đơn hàng đang chờ xác nhận.';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        } else {
            $_SESSION['error'] = 'Đặt hàng thất bại. Vui lòng thử lại';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }
    }

    /**
     * Đặt hàng (bước 2 xác nhận cuối)
     * URL: /cart/placeOrder (POST)
     */
    public function placeOrder()
    {
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

        $selected = isset($_POST['selected']) ? (array) $_POST['selected'] : [];
        $quantities = isset($_POST['quantity']) ? (array) $_POST['quantity'] : [];
        if (empty($selected)) {
            $_SESSION['error'] = 'Vui lòng chọn sản phẩm để đặt hàng';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $productModel = new \Models\Product();
        $orderDetails = [];
        $totalAmount = 0;

        foreach ($selected as $productId) {
            $productId = (string) $productId;
            $product = $productModel->getById($productId);
            if (!$product) {
                $_SESSION['error'] = 'Sản phẩm không tồn tại';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }
            $qty = isset($quantities[$productId]) ? (int) $quantities[$productId] : 0;
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
            foreach ($selected as $pid) {
                unset($_SESSION['cart'][$pid]);
            }
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