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
            if (!is_array($cartData) || !isset($cartData['variant_id']) || !isset($cartData['product_id'])) {
                // Bỏ qua entry không hợp lệ theo chuẩn mới
                continue;
            }

            $productId = $cartData['product_id'];
            $variantId = $cartData['variant_id'];
            $quantity = (int)($cartData['quantity'] ?? 1);

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

            // Variant có thể không tồn tại (product đơn lẻ). Nếu có thì dùng giá và tồn kho của variant,
            // nếu không thì fallback về dữ liệu của product (dành cho sản phẩm không có variants).
            $variant = $variantModel->getById($variantId);
            if ($variant && ($variant['product_id'] == $productId || ($variant['Product_Id'] ?? null) == $productId)) {
                $price = $variant['price'] ?? $price;
                $maxQuantity = $variant['stock'] ?? $maxQuantity;

                // Lấy thông tin color và size
                if (!empty($variant['color_id'])) {
                    $color = $colorModel->getById($variant['color_id']);
                }
                if (!empty($variant['size_id'])) {
                    $size = $sizeModel->getById($variant['size_id']);
                }
            } else {
                // Nếu không có variant nhưng variant_id == 0 => coi như sản phẩm đơn lẻ, dùng thông tin product
                if ((int)$variantId === 0) {
                    $price = $product['price'];
                    $maxQuantity = $product['quantity'];
                    $variant = null;
                    $color = null;
                    $size = null;
                } else {
                    // Nếu variant_id != 0 nhưng variant không hợp lệ thì bỏ qua entry
                    continue;
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
     * Thêm sản phẩm (biến thể) vào giỏ hàng
     * URL: /cart/add/{productId}
     */
    public function add($id)
    {
        $this->initCart();

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

            // Nếu không gửi variant_id, cố gắng chọn variant đầu tiên còn hàng
            if ($variantId <= 0) {
                $variants = $variantModel->getByProductId($id);
                if (!empty($variants)) {
                    $first = $variants[0];
                    $variantId = (int)($first['Variant_Id'] ?? $first['id'] ?? 0);
                } else {
                    // Không có variant trong DB: đánh dấu là sản phẩm đơn lẻ bằng variant_id = 0
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

            // Key giỏ hàng mới: luôn theo variant (với sản phẩm đơn lẻ sẽ là 'variant_0')
            $cartKey = 'variant_' . $variantId;

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

        $found = false;
        // Trong chuẩn mới, key luôn là 'variant_{id}'.
        if (isset($_SESSION['cart'][$key])) {
            unset($_SESSION['cart'][$key]);
            $found = true;
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
                    // Tìm item trong cart (chỉ hỗ trợ format mới)
                    $cartData = $_SESSION['cart'][$cartKey] ?? null;
                    if (!is_array($cartData) || !isset($cartData['product_id'], $cartData['variant_id'])) {
                        continue;
                    }

                    $productId = $cartData['product_id'];
                    $variantId = $cartData['variant_id'];

                    $product = $productModel->getById($productId);
                    if (!$product) {
                        unset($_SESSION['cart'][$cartKey]);
                        $hasUpdate = true;
                        continue;
                    }

                    // Với chuẩn mới, tồn kho lấy từ variant
                    $maxQuantity = $product['quantity'];
                    $variant = $variantModel->getById($variantId);
                    if ($variant && ($variant['product_id'] == $productId || ($variant['Product_Id'] ?? null) == $productId)) {
                        $maxQuantity = (int)($variant['stock'] ?? $variant['Quantity_In_Stock'] ?? 0);
                    }

                    // Giới hạn số lượng theo tồn kho
                    $quantity = min($quantity, $maxQuantity);

                    if ($quantity > 0) {
                        $_SESSION['cart'][$cartKey]['quantity'] = $quantity;
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

        // Selected checkboxes trong giỏ dùng cart key dạng 'variant_{id}'.
        $selected = isset($_POST['selected']) ? (array) $_POST['selected'] : [];
        $quantities = isset($_POST['quantity']) ? (array) $_POST['quantity'] : [];

        if (empty($selected)) {
            $_SESSION['error'] = 'Vui lòng chọn sản phẩm để thanh toán';
            header('Location: ' . ROOT_URL . 'cart');
            exit;
        }

        $productModel = new \Models\Product();
        $variantModel = new \Models\Product_Varirant();
        $items = [];
        $total = 0;

        foreach ($selected as $cartKey) {
            $cartData = $_SESSION['cart'][$cartKey] ?? null;
            if (!is_array($cartData) || !isset($cartData['product_id'], $cartData['variant_id'])) {
                $_SESSION['error'] = 'Sản phẩm không tồn tại trong giỏ hàng';
                header('Location: ' . ROOT_URL . 'cart');
                exit;
            }

            $productId = $cartData['product_id'];
            $variantId = $cartData['variant_id'];
            $qty = isset($quantities[$cartKey]) ? (int)$quantities[$cartKey] : ($cartData['quantity'] ?? 1);

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

            $price = $product['price'];
            $maxQuantity = $product['quantity'];

            // Variant có thể là product-level (variant_id == 0). Nếu variant tồn tại thì dùng variant,
            // nếu không và variant_id == 0 thì dùng giá/tồn kho của product.
            $variant = $variantModel->getById($variantId);
            if ($variant && ($variant['product_id'] == $productId || ($variant['Product_Id'] ?? null) == $productId)) {
                $price = $variant['price'] ?? $price;
                $maxQuantity = $variant['stock'] ?? $variant['Quantity_In_Stock'] ?? $maxQuantity;
            } else {
                if ((int)$variantId === 0) {
                    $price = $product['price'];
                    $maxQuantity = $product['quantity'];
                } else {
                    $_SESSION['error'] = 'Biến thể không hợp lệ';
                    header('Location: ' . ROOT_URL . 'cart');
                    exit;
                }
            }

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
                'cart_key' => $cartKey
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

        $variantModel = new \Models\Product_Varirant();
        foreach ($selected as $cartKey) {
            // Resolve cart entry from session (format mới)
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

            $variant = $variantModel->getById($variantId);
            if ($variant && ($variant['product_id'] == $productId || ($variant['Product_Id'] ?? null) == $productId)) {
                $price = $variant['price'] ?? $price;
                $maxQuantity = (int)($variant['stock'] ?? $variant['Quantity_In_Stock'] ?? 0);
            } else {
                if ((int)$variantId === 0) {
                    $price = $product['price'] ?? $price;
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

        $user = $_SESSION['user'];
        $orderModel = new \Models\Order();
        $orderId = $orderModel->createWithDetails([
            'user_id' => $user['id'] ?? $user['username'] ?? '',
            'address' => $_POST['address'] ?? ($user['address'] ?? ''),
            'note' => $_POST['note'] ?? 'Đặt hàng',
            'status' => 'completed'
        ], $orderDetails);

        if ($orderId) {
            foreach ($selected as $cartKey) {
                unset($_SESSION['cart'][$cartKey]);
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