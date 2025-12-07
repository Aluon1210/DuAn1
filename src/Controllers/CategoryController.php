<?php

namespace Controllers;
use Core\Controller;
use Models\Category;

class CategoryController extends Controller {

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new CategoryController();
        }
        return self::$instance;
    }
    function index() {
        $categoryModel = new Category();
        $categories = $categoryModel->getAllWithProductCount();

        $data = [
            'title' => 'Danh mục sản phẩm',
            'categories' => $categories,
            'totalCategories' => count($categories),
            'editing' => false
        ];

        $this->renderView('admin/category', $data);
    }

    /**
     * ADMIN: Hiển thị form sửa danh mục
     */
    public function adminEditCategory($id = null) {
        $this->requireAdmin();
        if (!$id) {
            $_SESSION['error'] = 'ID danh mục không được bỏ trống';
            header('Location: ' . ROOT_URL . 'admin/categories');
            exit;
        }

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
     * ADMIN: Lưu danh mục (thêm/cập nhật)
     */
    public function adminSaveCategory() {
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
                $success = $categoryModel->updateCategory($id, [
                    'name' => $name,
                    'description' => $description
                ]);
                $_SESSION['message'] = $success ? 'Cập nhật danh mục thành công' : 'Không thể cập nhật danh mục';
            } else {
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
     * ADMIN: Xóa danh mục
     */
    public function adminDeleteCategory($id) {
        $this->requireAdmin();
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
}
