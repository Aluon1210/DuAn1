<?php
// File: src/Controllers/ProductController.php
namespace Controllers;

use Core\Controller;
use Models\Product;
use Models\Category;
use Models\Comment;

class ProductController extends Controller
{
    /**
     * ADMIN: Trang danh sách + form thêm sản phẩm (admin)
     * URL: /admin/products (delegated from AdminController)
     */
    public function adminProducts()
    {
        $this->requireAdmin();
        $productModel = new Product();
        $categoryModel = new Category();
        $branchModel = new \Models\Branch();

        $products = $productModel->getAllWithCategory();
        $categories = $categoryModel->getAll();
        $branches = $branchModel->getAll();

        $data = [
            'title' => 'Quản lý sản phẩm',
            'products' => $products,
            'categories' => $categories,
            'branches' => $branches,
            'totalProducts' => count($products),
            'editing' => false
        ];

        $this->renderView('admin/product', $data);
    }

    /**
     * ADMIN: Hiển thị form sửa sản phẩm
     */
    public function adminEditProduct($id)
    {
        $this->requireAdmin();
        $productModel = new Product();
        $categoryModel = new Category();
        $branchModel = new \Models\Branch();

        $product = $productModel->getById($id);
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại';
            header('Location: ' . ROOT_URL . 'admin/products');
            exit;
        }

        $products = $productModel->getAllWithCategory();
        $categories = $categoryModel->getAll();
        $branches = $branchModel->getAll();

        $data = [
            'title' => 'Sửa sản phẩm',
            'product' => $product,
            'products' => $products,
            'categories' => $categories,
            'branches' => $branches,
            'totalProducts' => count($products),
            'editing' => true
        ];

        $this->renderView('admin/product', $data);
    }

    /**
     * ADMIN: Xử lý thêm / cập nhật sản phẩm
     */
    public function adminSaveProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'admin/products');
            exit;
        }

        $productModel = new Product();

        $id = isset($_POST['id']) ? trim($_POST['id']) : '';
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $price = isset($_POST['price']) ? (int) $_POST['price'] : 0;
        $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 0;
        $categoryId = isset($_POST['category_id']) ? trim($_POST['category_id']) : '';
        $branchId = isset($_POST['branch_id']) ? trim($_POST['branch_id']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';

        if ($name === '' || $price < 0 || $quantity < 0 || $categoryId === '' || $branchId === '') {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin hợp lệ (tên, giá, kho, danh mục, hãng)';
            header('Location: ' . ROOT_URL . 'admin/products');
            exit;
        }

        // Xử lý ảnh upload đơn giản (nếu có)
        $imageName = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = ROOT_PATH . '/public/images/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid('prod_') . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
        }

        try {
            if ($id) {
                $updateData = [
                    'name' => $name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'category_id' => $categoryId,
                    'branch_id' => $branchId,
                    'description' => $description
                ];
                if ($imageName !== '') {
                    $updateData['image'] = $imageName;
                }
                $success = $productModel->update($id, $updateData, 'Product_Id');
                $_SESSION['message'] = $success ? 'Cập nhật sản phẩm thành công' : 'Không thể cập nhật sản phẩm';
            } else {
                $createData = [
                    'name' => $name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'category_id' => $categoryId,
                    'branch_id' => $branchId,
                    'description' => $description,
                    'image' => $imageName
                ];
                $newId = $productModel->createProduct($createData);
                $_SESSION['message'] = $newId ? 'Thêm sản phẩm thành công' : 'Không thể thêm sản phẩm';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi xử lý sản phẩm: ' . $e->getMessage();
        }

        header('Location: ' . ROOT_URL . 'admin/products');
        exit;
    }

    /**
     * ADMIN: Xóa sản phẩm theo Product_Id
     */
    public function adminDeleteProduct($id)
    {
        $this->requireAdmin();
        $productModel = new Product();

        $product = $productModel->getById($id);
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại';
            header('Location: ' . ROOT_URL . 'admin/products');
            exit;
        }

        try {
            $success = $productModel->deleteById($id);
            $_SESSION['message'] = $success ? 'Đã xóa sản phẩm' : 'Không thể xóa sản phẩm';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi khi xóa sản phẩm: ' . $e->getMessage();
        }

        header('Location: ' . ROOT_URL . 'admin/products');
        exit;
    }

    /**
     * Hiển thị danh sách tất cả sản phẩm
     * URL: /product hoặc /product/index
     */
    public function index()
    {
        $productModel = new Product();
        $categoryModel = new Category();

        $limit = 15;
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $total = $productModel->countAll();
        $products = $productModel->getAllWithCategoryPaginated($limit, $offset);
        $categories = $categoryModel->getAll();

        $totalPages = $total > 0 ? (int) ceil($total / $limit) : 1;

        $data = [
            'title' => 'Sản phẩm',
            'products' => $products,
            'categories' => $categories,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'totalPages' => $totalPages
            ],
            'baseUrl' => ROOT_URL . 'product'
        ];

        $this->renderView('HomeProduct', $data);
    }

    /**
     * Hiển thị sản phẩm theo danh mục
     * URL: /product/category/{id}
     */
    public function category($id)
    {
        $productModel = new Product();
        $categoryModel = new Category();

        $category = $categoryModel->getById($id);
        if (!$category) {
            $_SESSION['error'] = 'Danh mục không tồn tại';
            header('Location: ' . ROOT_URL . 'product');
            exit;
        }

        $limit = 15;
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $total = $productModel->countByCategory($id);
        $products = $productModel->getByCategoryPaginated($id, $limit, $offset);
        $categories = $categoryModel->getAll();
        $totalPages = $total > 0 ? (int) ceil($total / $limit) : 1;

        $data = [
            'title' => $category['name'],
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $category,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'totalPages' => $totalPages
            ],
            'baseUrl' => ROOT_URL . 'product/category/' . $category['id']
        ];

        $this->renderView('product/category_full', $data);
    }

    /**
     * Hiển thị chi tiết sản phẩm
     * URL: /product/detail/{id}
     */
    public function detail($id)
    {
        $productModel = new Product();
        $categoryModel = new Category();
        $commentModel = new Comment();
        $variantModel = new \Models\Product_Varirant();

        // Lấy sản phẩm
        $product = $productModel->getByIdWithCategory($id);
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại';
            header('Location: ' . ROOT_URL . 'product');
            exit;
        }

        $categories = $categoryModel->getAll();
        $comments = $commentModel->getByProductId($id);

        // Lấy dữ liệu chi tiết variant: variants, colors, sizes
        // Chỉ cần 1 query tối ưu với LEFT JOIN
        $variantData = $variantModel->getProductVariantData($id);

        // Kiểm tra user đã nhận hàng sản phẩm này chưa (để cho phép bình luận)
        $canComment = false;
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'] ?? $_SESSION['user']['username'] ?? '';
            $orderModel = new \Models\Order();
            $canComment = $orderModel->hasUserReceivedProduct($userId, $id);
        }

        $data = [
            'title' => $product['name'],
            'product' => $product,
            'categories' => $categories,
            'comments' => $comments,
            'variants' => $variantData['variants'],          // Tất cả variants
            'availableColors' => $variantData['colors'],     // Danh sách colors
            'availableSizes' => $variantData['sizes'],       // Danh sách sizes
            'canComment' => $canComment                       // Có thể bình luận không
        ];

        // Render view ProductDetail với dữ liệu đã cấu trúc
        $this->renderView('ProductDetail', $data);
    }

    /**
     * Xử lý post comment
     * URL: /product/postComment/{productId} (POST)
     */
    public function postComment($productId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'product/detail/' . $productId);
            exit;
        }

        // Kiểm tra user đã đăng nhập
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để bình luận';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        $content = isset($_POST['content']) ? trim($_POST['content']) : '';

        if (empty($content)) {
            $_SESSION['error'] = 'Nội dung bình luận không được để trống';
            header('Location: ' . ROOT_URL . 'product/detail/' . $productId);
            exit;
        }

        $productModel = new Product();
        $product = $productModel->getById($productId);
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại';
            header('Location: ' . ROOT_URL . 'product');
            exit;
        }

        // Kiểm tra user đã nhận hàng sản phẩm này chưa
        $userId = $_SESSION['user']['id'] ?? $_SESSION['user']['username'] ?? '';
        $orderModel = new \Models\Order();

        if (!$orderModel->hasUserReceivedProduct($userId, $productId)) {
            $_SESSION['error'] = 'Bạn chỉ có thể bình luận sau khi đã nhận hàng sản phẩm này';
            header('Location: ' . ROOT_URL . 'product/detail/' . $productId);
            exit;
        }

        try {
            $commentModel = new Comment();
            $commentId = $commentModel->createComment([
                'content' => $content,
                'user_id' => $userId,
                'product_id' => $productId
            ]);

            if ($commentId) {
                // If AJAX request, return JSON with comment data
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    $userName = $_SESSION['user']['FullName'] ?? ($_SESSION['user']['name'] ?? '');
                    $comment = [
                        'Comment_Id' => $commentId,
                        'Content' => $content,
                        'Create_at' => date('Y-m-d'),
                        '_UserName_Id' => $userId,
                        'user_name' => $userName
                    ];
                    header('Content-Type: application/json');
                    echo json_encode(['ok' => true, 'comment' => $comment]);
                    return;
                }
                $_SESSION['message'] = 'Bình luận của bạn đã được đăng';
            } else {
                $_SESSION['error'] = 'Không thể đăng bình luận';
            }
        } catch (\Exception $e) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['ok' => false, 'error' => 'server', 'message' => $e->getMessage()]);
                return;
            }
            $_SESSION['error'] = 'Lỗi khi đăng bình luận: ' . $e->getMessage();
        }

        header('Location: ' . ROOT_URL . 'product/detail/' . $productId);
        exit;
    }

    /**
     * AJAX: Kiểm tra xem user hiện tại đã comment product chưa
     * URL: /product/ajaxHasComment/{productId}
     */
    public function ajaxHasComment($productId)
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user'])) {
            echo json_encode(['ok' => false, 'error' => 'unauth']);
            return;
        }

        $userId = $_SESSION['user']['id'] ?? '';
        $commentModel = new Comment();
        try {
            $has = $commentModel->hasUserCommented($userId, $productId);
            echo json_encode(['ok' => true, 'hasComment' => (bool)$has]);
        } catch (\Exception $e) {
            echo json_encode(['ok' => false, 'error' => 'server']);
        }
    }

    /**
     * Tìm kiếm sản phẩm
     * URL: /product/search?q=keyword
     */
    public function search()
    {
        $productModel = new Product();
        $categoryModel = new Category();

        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        $limit = 15;
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        if (!empty($keyword)) {
            $total = $productModel->countSearch($keyword);
            $products = $productModel->searchPaginated($keyword, $limit, $offset);
        } else {
            $total = $productModel->countAll();
            $products = $productModel->getAllWithCategoryPaginated($limit, $offset);
        }

        $categories = $categoryModel->getAll();
        $totalPages = $total > 0 ? (int) ceil($total / $limit) : 1;

        $data = [
            'title' => 'Tìm kiếm: ' . htmlspecialchars($keyword),
            'products' => $products,
            'categories' => $categories,
            'keyword' => $keyword,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'totalPages' => $totalPages
            ],
            'baseUrl' => ROOT_URL . 'product/search?q=' . urlencode($keyword)
        ];

        $this->renderView('product/search_full', $data);
    }
}
?>
