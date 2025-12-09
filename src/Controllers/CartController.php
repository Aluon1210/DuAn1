<?php
// File: src/Controllers/CartController.php
namespace Controllers;

use Core\Controller;
use Models\Product;

class CartController extends Controller
{

    /**
     * Khởi tạo giỏ hàng trong session nếu chưa có.
     *
     * Chuẩn hóa về cấu trúc DUY NHẤT theo variant:
     * $_SESSION['cart'][\"variant_{Variant_Id}\"] = [
     *     'product_id' => 'Pxxx',
     *     'variant_id' => 29,
     *     'quantity'   => 2
     * ];
     */
    private function initCart()
    {
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
            return;
        }

        $normalized = [];
        $variantModel = new \Models\Product_Varirant();

        foreach ($_SESSION['cart'] as $key => $value) {
            // Đã đúng format variant-based mới
            if (is_array($value) && isset($value['variant_id']) && isset($value['product_id'])) {
                $vId = (int)$value['variant_id'];
                if ($vId < 0) {
                    // Bỏ qua entry không hợp lệ
                    continue;
                }
                $cartKey = 'variant_' . $vId;
                $qty = max(1, (int)($value['quantity'] ?? 1));

                if (!isset($normalized[$cartKey])) {
                    $normalized[$cartKey] = [
                        'product_id' => $value['product_id'],
                        'variant_id' => $vId,
                        'quantity' => $qty
                    ];
                } else {
                    $normalized[$cartKey]['quantity'] += $qty;
                }
                continue;
            }

            // Legacy format: key là product_id hoặc product_/variant_..., value là quantity
            $qty = is_array($value) ? (int)($value['quantity'] ?? 0) : (int)$value;
            if ($qty <= 0) {
                continue;
            }

            $productId = null;
            $variantId = null;

            if (is_array($value)) {
                $productId = $value['product_id'] ?? null;
                $variantId = isset($value['variant_id']) ? (int)$value['variant_id'] : null;
            }

            // Nếu chưa có variant_id, thử parse từ key dạng variant_{id}
            if ($variantId === null && str_starts_with((string)$key, 'variant_')) {
                $variantId = (int)substr((string)$key, 8);
            }

            // Nếu vẫn không có variant_id nhưng có product_id, chọn variant đầu tiên trong DB
            if ($variantId === null && $productId) {
                $variants = $variantModel->getByProductId($productId);
                if (!empty($variants)) {
                    $first = $variants[0];
                    $variantId = (int)($first['Variant_Id'] ?? $first['id'] ?? 0);
                }
            }
            
            // Nếu vẫn không tìm được variant hợp lệ thì bỏ qua entry
            if ($variantId === null) {
                continue;
            }

            // Lấy lại product_id chuẩn từ variant nếu thiếu
            if (!$productId) {
                $variant = $variantModel->getById($variantId);
                if ($variant && !empty($variant['product_id'])) {
                    $productId = $variant['product_id'];
                }
            }

            if (!$productId) {
                continue;
            }

            $cartKey = 'variant_' . $variantId;
            if (!isset($normalized[$cartKey])) {
                $normalized[$cartKey] = [
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'quantity' => $qty
                ];
            } else {
                $normalized[$cartKey]['quantity'] += $qty;
            }
        }

        $_SESSION['cart'] = $normalized;
    }

    /**
     * Hiển thị giỏ hàng từ database
     * URL: /cart hoặc /cart/index
     */
    public function index()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        $productModel = new Product();
        $variantModel = new \Models\Product_Varirant();
        $colorModel = new \Models\Color();
        $sizeModel = new \Models\Size();
        $cartModel = new \Models\Cart();
        $orderModel = new \Models\Order();

        $userId = $_SESSION['user']['id'] ?? $_SESSION['user']['username'] ?? '';
        $dbCartItems = $cartModel->getCartByUserId($userId);

        $cartItems = [];
        $total = 0;

        foreach ($dbCartItems as $cartRow) {
            $variantId = (int)$cartRow['Variant_Id'];
            $quantity = (int)$cartRow['Quantity'];
            $cartId = $cartRow['_Cart_Id'];

            // Lấy thông tin variant để tìm product
            $variant = $variantModel->getById($variantId);
            if (!$variant) {
                // Xóa variant không tồn tại khỏi giỏ hàng
                $cartModel->deleteCart($cartId);
                continue;
            }

            $productId = $variant['product_id'] ?? $variant['Product_Id'] ?? null;
            if (!$productId) {
                $cartModel->deleteCart($cartId);
                continue;
            }

            $product = $productModel->getById($productId);
            if (!$product) {
                // Xóa sản phẩm không tồn tại khỏi giỏ hàng
                $cartModel->deleteCart($cartId);
                continue;
            }

            $price = $variant['price'] ?? $product['price'];
            $maxQuantity = $variant['stock'] ?? $variant['Quantity_In_Stock'] ?? 0;
            $color = null;
            $size = null;

            // Lấy thông tin color và size
            if (!empty($variant['color_id'])) {
                $color = $colorModel->getById($variant['color_id']);
            }
            if (!empty($variant['size_id'])) {
                $size = $sizeModel->getById($variant['size_id']);
            }

            // Đảm bảo không vượt quá tồn kho
            if ($quantity > $maxQuantity) {
                $quantity = $maxQuantity;
                // Cập nhật lại số lượng trong DB
                $cartModel->updateCartQuantity($cartId, $quantity);
            }

            $subtotal = $price * $quantity;

            $cartItems[] = [
                'cart_id' => $cartId,
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'price' => $price,
                'variant' => $variant,
                'color' => $color,
                'size' => $size
            ];

            $total += $subtotal;
        }

        // Lấy lịch sử đơn hàng
        $orders = $orderModel->getByUserId($userId);

        $data = [
            'title' => 'Giỏ Hàng',
            'cartItems' => $cartItems,
            'total' => $total,
            'orders' => $orders ?? []
        ];

        $this->renderView('Cart', $data);
    }

    /**
     * Thêm sản phẩm (biến thể) vào giỏ hàng
     * URL: /cart/add/{productId}
     */
    public function add($id)
    {
        // Kiểm tra đăng nhập trước
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'product');
            exit;
        }

        $productModel = new Product();
        $variantModel = new \Models\Product_Varirant();
        $cartModel = new \Models\Cart();

        $product = $productModel->getById($id);
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại';
            header('Location: ' . ROOT_URL . 'product');
            exit;
        }

        $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;
        $variantId = isset($_POST['variant_id']) ? (int) trim($_POST['variant_id']) : 0;

        if ($quantity <= 0) {
            $_SESSION['error'] = 'Số lượng phải lớn hơn 0';
            header('Location: ' . ROOT_URL . 'product/detail/' . $id);
            exit;
        }

        // Nếu không gửi variant_id, cố gắng chọn variant đầu tiên còn hàng
        if ($variantId <= 0) {
            $variants = $variantModel->getByProductId($id);
            if (!empty($variants)) {
                $first = $variants[0];
                $variantId = (int)($first['Variant_Id'] ?? $first['id'] ?? 0);
            } else {
                $variantId = 0;
            }
        }

        // Kiểm tra variant hợp lệ nếu variant_id > 0, ngược lại dùng tồn kho/giá trên product
        $maxQuantity = (int)($product['quantity'] ?? 0);
        if ($variantId > 0) {
            $variant = $variantModel->getById($variantId);
            if (!$variant || ($variant['product_id'] ?? $variant['Product_Id'] ?? null) !== $id) {
                $_SESSION['error'] = 'Biến thể không hợp lệ';
                header('Location: ' . ROOT_URL . 'product/detail/' . $id);
                exit;
            }
            $maxQuantity = (int)($variant['stock'] ?? $variant['Quantity_In_Stock'] ?? 0);
        }

        if ($quantity > $maxQuantity) {
            $_SESSION['error'] = 'Số lượng vượt quá tồn kho';
            header('Location: ' . ROOT_URL . 'product/detail/' . $id);
            exit;
        }

        // Lưu vào database
        $userId = $_SESSION['user']['id'] ?? $_SESSION['user']['username'] ?? '';
        try {
            $cartModel->addToCart($userId, $id, $variantId, $quantity);
            $_SESSION['message'] = 'Đã thêm sản phẩm vào giỏ hàng';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi khi thêm sản phẩm: ' . $e->getMessage();
        }

        header('Location: ' . ROOT_URL . 'product/detail/' . $id);
        exit;
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productModel = new Product();
            $variantModel = new \Models\Product_Varirant();
            $product = $productModel->getById($id);
            if (!$product) {
                $_SESSION['error'] = 'Sản phẩm không tồn tại';
                header('Location: ' . ROOT_URL . 'product');
                exit;
            }
            $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;
            $variantId = isset($_POST['variant_id']) ? (int) trim($_POST['variant_id']) : 0;
            if ($quantity <= 0) {
                $_SESSION['error'] = 'Số lượng phải lớn hơn 0';
                header('Location: ' . ROOT_URL . 'product/detail/' . $id);
                exit;
            }
            if ($variantId <= 0) {
                $variants = $variantModel->getByProductId($id);
                if (!empty($variants)) {
                    $first = $variants[0];
                    $variantId = (int)($first['Variant_Id'] ?? $first['id'] ?? 0);
                } else {
                    $variantId = 0;
                }
            }
            $maxQuantity = (int)($product['quantity'] ?? 0);
            if ($variantId > 0) {
                $variant = $variantModel->getById($variantId);
                if (!$variant || ($variant['product_id'] ?? $variant['Product_Id'] ?? null) !== $id) {
                    $_SESSION['error'] = 'Biến thể không hợp lệ';
                    header('Location: ' . ROOT_URL . 'product/detail/' . $id);
                    exit;
                }
                $maxQuantity = (int)($variant['stock'] ?? $variant['Quantity_In_Stock'] ?? 0);
            }
            if ($quantity > $maxQuantity) {
                $_SESSION['error'] = 'Số lượng vượt quá tồn kho';
                header('Location: ' . ROOT_URL . 'product/detail/' . $id);
                exit;
            }
            // Lưu giỏ hàng vào DB
            $userId = $_SESSION['user']['id'] ?? $_SESSION['user']['username'] ?? '';
            $cartModel = new \Models\Cart();
            $cartModel->addToCart($userId, $id, $variantId, $quantity);
            $_SESSION['message'] = 'Đã thêm sản phẩm vào giỏ hàng';
            header('Location: ' . ROOT_URL . 'product/detail/' . $id);
            exit;
        }
        header('Location: ' . ROOT_URL . 'product');
        exit;
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

        $isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
        // Selected checkboxes là cart_id từ database (nếu POST)
        $selected = $isPost ? (array)($_POST['selected'] ?? []) : [];
        $quantities = $isPost ? (array)($_POST['quantity'] ?? []) : [];

        $productModel = new \Models\Product();
        $variantModel = new \Models\Product_Varirant();
        $cartModel = new \Models\Cart();
        $items = [];
        $total = 0;

        if (empty($selected)) {
            $_SESSION['error'] = 'Vui lòng chọn sản phẩm để xác nhận thanh toán';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        foreach ($selected as $cartId) {
            // Lấy thông tin từ giỏ hàng trong database
            $cartItems = $cartModel->getCartByUserId($_SESSION['user']['id'] ?? $_SESSION['user']['username'] ?? '');
            $cartData = null;
            foreach ($cartItems as $item) {
                if ($item['_Cart_Id'] === $cartId) {
                    $cartData = $item;
                    break;
                }
            }

            if (!$cartData) {
                $_SESSION['error'] = 'Sản phẩm không tồn tại trong giỏ hàng';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }

            $variantId = $cartData['Variant_Id'];
            $qty = isset($quantities[$cartId]) ? (int)$quantities[$cartId] : ($cartData['Quantity'] ?? 1);
            
            // Lấy product_id từ variant
            $variant = $variantModel->getById($variantId);
            if (!$variant) {
                $_SESSION['error'] = 'Biến thể không tồn tại';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }
            $productId = $variant['product_id'] ?? $variant['Product_Id'] ?? null;

            
            $product = $productModel->getById($productId);
            if (!$product) {
                $_SESSION['error'] = 'Sản phẩm không tồn tại';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }

            if ($qty <= 0) {
                $_SESSION['error'] = 'Số lượng không hợp lệ';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }

            $price = $variant['price'] ?? $product['price'];
            $maxQuantity = $variant['stock'] ?? $variant['Quantity_In_Stock'] ?? 0;

            if ($maxQuantity < $qty) {
                $qty = $maxQuantity;
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
                'subtotal' => $price * $qty,
                'cart_key' => $cartId
            ];
            $total += $price * $qty;
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
            // Lấy thông tin từ cart (format mới)
            $cartData = $_SESSION['cart'][$cartKey] ?? null;
            if (!is_array($cartData) || !isset($cartData['product_id'], $cartData['variant_id'])) {
                continue;
            }

            $productId = $cartData['product_id'];
            $variantId = $cartData['variant_id'];
            $qty = isset($quantities[$cartKey]) ? (int)$quantities[$cartKey] : (int)($cartData['quantity'] ?? 1);

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

            // Variant có thể là product-level (variant_id == 0). Nếu variant tồn tại thì dùng variant,
            // nếu không và variant_id == 0 thì dùng giá/tồn kho của product.
            $variant = $variantModel->getById($variantId);
            if ($variant && ($variant['product_id'] == $productId || ($variant['Product_Id'] ?? null) == $productId)) {
                $price = $variant['price'] ?? $price;
                $maxQuantity = (int)($variant['stock'] ?? $variant['Quantity_In_Stock'] ?? 0);
            } else {
                if ((int)$variantId === 0) {
                    $price = $product['price'];
                    $maxQuantity = (int)$product['quantity'];
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
     * Đặt hàng COD: tạo đơn trực tiếp từ giỏ trong DB, không dùng API
     * URL: /cart/placeOrderCOD (POST)
     */
    public function placeOrderCOD()
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

        $address = isset($_POST['address']) ? trim($_POST['address']) : '';
        if ($address === '') {
            $_SESSION['error'] = 'Vui lòng nhập địa chỉ nhận hàng';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $productModel = new \Models\Product();
        $variantModel = new \Models\Product_Varirant();
        $cartModel = new \Models\Cart();
        $orderModel = new \Models\Order();

        $userId = $_SESSION['user']['id'] ?? $_SESSION['user']['username'] ?? '';
        $cartItems = $cartModel->getCartByUserId($userId);
        if (empty($cartItems)) {
            $_SESSION['error'] = 'Giỏ hàng trống - không thể đặt hàng';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $orderDetails = [];
        $totalAmount = 0;
        $processedCartIds = [];

        foreach ($cartItems as $item) {
            $variantId = $item['Variant_Id'];
            $qty = (int)($item['Quantity'] ?? 1);
            if ($qty <= 0) { continue; }

            $variant = $variantModel->getById($variantId);
            if (!$variant) { continue; }

            $productId = $variant['product_id'] ?? $variant['Product_Id'] ?? null;
            if (!$productId) { continue; }

            $product = $productModel->getById($productId);
            if (!$product) { continue; }

            $price = (int)($variant['price'] ?? $product['price'] ?? 0);
            $stock = (int)($variant['stock'] ?? $variant['Quantity_In_Stock'] ?? 0);
            if ($stock <= 0) { continue; }
            if ($qty > $stock) { $qty = $stock; }
            if ($qty <= 0) { continue; }

            $orderDetails[] = [
                'Product_Id' => $productId,
                'Variant_Id' => $variantId,
                'quantity' => $qty
            ];
            $totalAmount += $price * $qty;
            if (!empty($item['_Cart_Id'])) { $processedCartIds[] = $item['_Cart_Id']; }
        }

        if (empty($orderDetails)) {
            $_SESSION['error'] = 'Không có sản phẩm hợp lệ để đặt hàng (hết hàng hoặc số lượng không hợp lệ)';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $user = $_SESSION['user'];
        $orderId = $orderModel->createWithDetails([
            'user_id' => $user['id'] ?? $user['username'] ?? '',
            'address' => $address,
            'note' => $_POST['note'] ?? 'Đặt hàng',
            'status' => 'pending'
        ], $orderDetails);

        if ($orderId) {
            foreach ($processedCartIds as $cid) { $cartModel->deleteCart($cid); }
            $_SESSION['message'] = 'Đặt hàng thành công. Mã đơn: ' . $orderId . '. Tổng tiền: ' . number_format($totalAmount, 0, ',', '.') . ' ₫. Phương thức: OPT (Tiền mặt)';
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
        $paymentMethod = $_POST['payment_method'] ?? 'opt';
        $paymentVerified = $_POST['payment_verified'] ?? '0';
        if (empty($selected)) {
            $_SESSION['error'] = 'Vui lòng chọn sản phẩm để đặt hàng';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        // Nếu là thanh toán online mà chưa xác thực thì chặn tạo đơn
        if ($paymentMethod === 'online' && $paymentVerified !== '1') {
            $_SESSION['error'] = 'Thanh toán online chưa được xác nhận. Vui lòng hoàn tất thanh toán trước.';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $address = isset($_POST['address']) ? trim($_POST['address']) : '';
        if (empty($address)) {
            $_SESSION['error'] = 'Vui lòng nhập địa chỉ nhận hàng';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $productModel = new \Models\Product();
        $orderDetails = [];
        $totalAmount = 0;
        $variantModel = new \Models\Product_Varirant();
        $cartModel = new \Models\Cart();

        foreach ($selected as $cartId) {
            // Lấy thông tin từ giỏ hàng trong database
            $cartItems = $cartModel->getCartByUserId($_SESSION['user']['id'] ?? $_SESSION['user']['username'] ?? '');
            $cartData = null;
            foreach ($cartItems as $item) {
                if ($item['_Cart_Id'] === $cartId) {
                    $cartData = $item;
                    break;
                }
            }

            if (!$cartData) {
                $_SESSION['error'] = 'Sản phẩm không tồn tại trong giỏ hàng';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }

            $variantId = $cartData['Variant_Id'];
            $qty = isset($quantities[$cartId]) ? (int)$quantities[$cartId] : ($cartData['Quantity'] ?? 1);

            // Lấy product_id từ variant
            $variant = $variantModel->getById($variantId);
            if (!$variant) {
                $_SESSION['error'] = 'Biến thể không tồn tại';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }
            $productId = $variant['product_id'] ?? $variant['Product_Id'] ?? null;

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

            $price = $variant['price'] ?? $product['price'];
            $maxQuantity = $variant['stock'] ?? $variant['Quantity_In_Stock'] ?? 0;

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

        $user = $_SESSION['user'];
        $orderModel = new \Models\Order();
        
        // Lấy phương thức thanh toán từ form
        $paymentMethod = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : 'opt';
        if (!in_array($paymentMethod, ['opt', 'online'])) {
            $paymentMethod = 'opt'; // Mặc định nếu không hợp lệ
        }
        
        $orderId = $orderModel->createWithDetails([
            'user_id' => $user['id'] ?? $user['username'] ?? '',
            'address' => $_POST['address'] ?? ($user['address'] ?? ''),
            'note' => $_POST['note'] ?? 'Đặt hàng',
            'status' => ($paymentMethod === 'online') ? 'pending' : 'completed', // online: chờ xác nhận
            'payment_method' => $paymentMethod
        ], $orderDetails);

        if ($orderId) {
            // Xóa các sản phẩm đã đặt hàng khỏi DB cart
            foreach ($selected as $cartId) {
                $cartModel->deleteCart($cartId);
            }
            
            // Tạo message thanh toán phù hợp
            $paymentText = ($paymentMethod === 'online') ? 'Online (QR Code)' : 'OPT (Tiền mặt)';
            $_SESSION['message'] = 'Đặt hàng thành công. Mã đơn: ' . $orderId . '. Tổng tiền: ' . number_format($totalAmount, 0, ',', '.') . ' ₫. Phương thức: ' . $paymentText;
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        } else {
            $_SESSION['error'] = 'Đặt hàng thất bại. Vui lòng thử lại';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }
    }

    public function updateQuantity()
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'unauthorized']);
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'method_not_allowed']);
            return;
        }

        $cartId = isset($_POST['cart_id']) ? trim($_POST['cart_id']) : null;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : null;
        if (!$cartId || $quantity === null) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'bad_request']);
            return;
        }

        $userId = $_SESSION['user']['id'] ?? $_SESSION['user']['username'] ?? '';
        $cartModel = new \Models\Cart();
        $variantModel = new \Models\Product_Varirant();
        $productModel = new \Models\Product();

        $dbCartItems = $cartModel->getCartByUserId($userId);
        $cartRow = null;
        foreach ($dbCartItems as $row) {
            if ($row['_Cart_Id'] == $cartId) {
                $cartRow = $row;
                break;
            }
        }

        if (!$cartRow) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'not_found']);
            return;
        }

        $variantId = (int)$cartRow['Variant_Id'];
        $variant = $variantModel->getById($variantId);
        if (!$variant) {
            $cartModel->deleteCart($cartId);
            echo json_encode(['success' => true, 'deleted' => true, 'cart_id' => $cartId]);
            return;
        }
        $productId = $variant['product_id'] ?? ($variant['Product_Id'] ?? null);
        $product = $productModel->getById($productId);
        if (!$product) {
            $cartModel->deleteCart($cartId);
            echo json_encode(['success' => true, 'deleted' => true, 'cart_id' => $cartId]);
            return;
        }

        $price = $variant['price'] ?? $product['price'];
        $max = (int)($variant['stock'] ?? ($variant['Quantity_In_Stock'] ?? 0));
        if ($max <= 0) {
            $cartModel->deleteCart($cartId);
            echo json_encode(['success' => true, 'deleted' => true, 'cart_id' => $cartId]);
            return;
        }

        if ($quantity > $max) {
            $quantity = $max;
        }
        if ($quantity <= 0) {
            $cartModel->deleteCart($cartId);
            echo json_encode(['success' => true, 'deleted' => true, 'cart_id' => $cartId]);
            return;
        }

        $cartModel->updateCartQuantity($cartId, $quantity);
        $subtotal = $price * $quantity;
        echo json_encode([
            'success' => true,
            'cart_id' => $cartId,
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $subtotal,
            'max' => $max
        ]);
    }

    /**
     * Xem chi tiết đơn hàng
     * URL: /cart/orderDetail/{orderId}
     */
    public function orderDetail($orderId = null)
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để xem chi tiết đơn hàng';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        if (!$orderId) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $orderModel = new \Models\Order();
        $orderDetailModel = new \Models\OrderDetail();
        
        $order = $orderModel->getByIdWithUser($orderId);
        if (!$order) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        // Kiểm tra quyền: chỉ user sở hữu đơn hàng hoặc admin mới được xem
        if ($order['_UserName_Id'] !== $_SESSION['user']['username'] && !isset($_SESSION['user']['is_admin'])) {
            $_SESSION['error'] = 'Bạn không có quyền xem đơn hàng này';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $orderDetails = $orderDetailModel->getByOrderIdWithProduct($orderId);

        $data = [
            'title' => 'Chi tiết đơn hàng',
            'order' => $order,
            'orderDetails' => $orderDetails
        ];

        $this->renderView('OrderDetail', $data);
    }
}
?>
