<?php
// File: src/Controllers/AdminController.php
namespace Controllers;

use Core\Controller;
use Models\Product;
use Models\Category;
use Models\Branch;
use Models\User;

class AdminController extends Controller {

    // Trong AdminController

public function users() {
    // load view: quản lý user
    $this->renderView('admin/user');
}

public function orders() {
    $this->renderView('admin/order');
}

public function branch() {
    $branchModel = new Branch();
    $branches = $branchModel->getAll();
    
    $data = [
        'title' => 'Quản lý hãng',
        'branches' => $branches,
        'totalBranches' => count($branches),
        'editing' => false
    ];
    
    $this->renderView('admin/branch', $data);
}

/**
 * Hiển thị form sửa hãng
 * URL: /admin/editBranch/{id}
 */
public function editBranch($id) {
    $branchModel = new Branch();
    $branch = $branchModel->getById($id);
    
    if (!$branch) {
        $_SESSION['error'] = 'Hãng không tồn tại';
        header('Location: ' . ROOT_URL . 'admin/branch');
        exit;
    }
    
    $branches = $branchModel->getAll();
    
    $data = [
        'title' => 'Sửa hãng',
        'branch' => $branch,
        'branches' => $branches,
        'totalBranches' => count($branches),
        'editing' => true
    ];
    
    $this->renderView('admin/branch', $data);
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
    
    $branchModel = new Branch();
    
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    
    if ($name === '') {
        $_SESSION['error'] = 'Tên hãng không được để trống';
        header('Location: ' . ROOT_URL . 'admin/branch');
        exit;
    }
    
    try {
        if ($id) {
            // Cập nhật
            $success = $branchModel->updateBranch($id, ['name' => $name]);
            $_SESSION['message'] = $success ? 'Cập nhật hãng thành công' : 'Không thể cập nhật hãng';
        } else {
            // Thêm mới
            $newId = $branchModel->createBranch(['name' => $name]);
            $_SESSION['message'] = $newId ? 'Thêm hãng thành công' : 'Không thể thêm hãng';
        }
    } catch (\Exception $e) {
        $_SESSION['error'] = 'Lỗi xử lý hãng: ' . $e->getMessage();
    }
    
    header('Location: ' . ROOT_URL . 'admin/branch');
    exit;
}

/**
 * Xóa hãng
 * URL: /admin/deleteBranch/{id}
 */
public function deleteBranch($id) {
    $branchModel = new Branch();
    
    $branch = $branchModel->getById($id);
    if (!$branch) {
        $_SESSION['error'] = 'Hãng không tồn tại';
        header('Location: ' . ROOT_URL . 'admin/branch');
        exit;
    }
    
    try {
        $success = $branchModel->deleteBranch($id);
        $_SESSION['message'] = $success ? 'Đã xóa hãng' : 'Không thể xóa hãng';
    } catch (\Exception $e) {
        $_SESSION['error'] = 'Lỗi khi xóa hãng: ' . $e->getMessage();
    }
    
    header('Location: ' . ROOT_URL . 'admin/branch');
    exit;
}

public function categories() {
    $categoryModel = new Category();
    $categories = $categoryModel->getAll();
    
    $data = [
        'title' => 'Quản lý danh mục',
        'categories' => $categories,
        'totalCategories' => count($categories),
        'editing' => false
    ];
    
    $this->renderView('admin/category', $data);
}

/**
 * Hiển thị form sửa danh mục
 * URL: /admin/editCategory/{id}
 */
public function editCategory($id) {
    $categoryModel = new Category();
    $category = $categoryModel->getById($id);
    
    if (!$category) {
        $_SESSION['error'] = 'Danh mục không tồn tại';
        header('Location: ' . ROOT_URL . 'admin/categories');
        exit;
    }
    
    $categories = $categoryModel->getAll();
    
    $data = [
        'title' => 'Sửa danh mục',
        'category' => $category,
        'categories' => $categories,
        'totalCategories' => count($categories),
        'editing' => true
    ];
    
    $this->renderView('admin/category', $data);
}

/**
 * Xử lý thêm / cập nhật danh mục
 * URL: /admin/saveCategory (POST)
 */
public function saveCategory() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . ROOT_URL . 'admin/categories');
        exit;
    }
    
    $categoryModel = new Category();
    
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    
    if ($name === '') {
        $_SESSION['error'] = 'Tên danh mục không được để trống';
        header('Location: ' . ROOT_URL . 'admin/categories');
        exit;
    }
    
    try {
        if ($id) {
            // Cập nhật
            $success = $categoryModel->updateCategory($id, [
                'name' => $name,
                'description' => $description
            ]);
            $_SESSION['message'] = $success ? 'Cập nhật danh mục thành công' : 'Không thể cập nhật danh mục';
        } else {
            // Thêm mới
            $newId = $categoryModel->createCategory([
                'name' => $name,
                'description' => $description
            ]);
            $_SESSION['message'] = $newId ? 'Thêm danh mục thành công' : 'Không thể thêm danh mục';
        }
    } catch (\Exception $e) {
        $_SESSION['error'] = 'Lỗi xử lý danh mục: ' . $e->getMessage();
    }
    
    header('Location: ' . ROOT_URL . 'admin/categories');
    exit;
}

/**
 * Xóa danh mục
 * URL: /admin/deleteCategory/{id}
 */
public function deleteCategory($id) {
    $categoryModel = new Category();
    
    $category = $categoryModel->getById($id);
    if (!$category) {
        $_SESSION['error'] = 'Danh mục không tồn tại';
        header('Location: ' . ROOT_URL . 'admin/categories');
        exit;
    }
    
    try {
        $success = $categoryModel->deleteCategory($id);
        $_SESSION['message'] = $success ? 'Đã xóa danh mục' : 'Không thể xóa danh mục';
    } catch (\Exception $e) {
        $_SESSION['error'] = 'Lỗi khi xóa danh mục: ' . $e->getMessage();
    }
    
    header('Location: ' . ROOT_URL . 'admin/categories');
    exit;
}

public function comments() {
    $this->renderView('admin/comment');
}

public function stats() {
    $this->renderView('admin/stat');
}

public function dashboard() {
    $this->renderView('admin/dashboard');
}

    /**
     * Mặc định chuyển vào quản lý sản phẩm
     * URL: /admin hoặc /admin/index
     */
    public function index() {
        $this->products();
    }

    /**
     * Trang danh sách + form thêm sản phẩm
     * URL: /admin/products
     */
    public function products() {
        $productModel = new Product();
        $categoryModel = new Category();
        $branchModel = new Branch();
        $UserModel = new \Models\User();

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
     * Hiển thị form sửa sản phẩm
     * URL: /admin/editProduct/{id}
     */
    public function editProduct($id) {
        $productModel = new Product();
        $categoryModel = new Category();
        $branchModel = new Branch();

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
     * Xử lý thêm / cập nhật sản phẩm
     * URL: /admin/saveProduct (POST)
     */
    public function saveProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'admin/products');
            exit;
        }

        $productModel = new Product();

        $id = isset($_POST['id']) ? trim($_POST['id']) : '';
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $price = isset($_POST['price']) ? (int)$_POST['price'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
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
                // Cập nhật
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
                // Thêm mới
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
     * Xóa sản phẩm
     * URL: /admin/deleteProduct/{id}
     */
    public function deleteProduct($id) {
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
}

?>


