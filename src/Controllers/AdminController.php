<?php
// File: src/Controllers/AdminController.php
namespace Controllers;

use Core\Controller;
use Models\Product;
use Models\Category;
use Models\Branch;

class AdminController extends Controller {

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


