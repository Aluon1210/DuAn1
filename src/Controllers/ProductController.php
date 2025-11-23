<?php
// File: src/Controllers/ProductController.php
namespace Controllers;

use Core\Controller;
use Models\Product;
use Models\Category;
use Models\Comment;

class ProductController extends Controller {
    
    /**
     * Hiển thị danh sách tất cả sản phẩm
     * URL: /product hoặc /product/index
     */
    public function index() {
        $productModel = new Product();
        $categoryModel = new Category();

        $products = $productModel->getAllWithCategory();
        $categories = $categoryModel->getAll();
        $name = isset($_GET['name']) ? trim($_GET['name']) : '';

        $data = [
            'title' => 'Sản phẩm',
            'products' => $products,
            'categories' => $categories
        ];

        // Dùng chung HomeProduct cho danh sách sản phẩm
        $this->renderView('HomeProduct', $data);
    }
    
    /**
     * Hiển thị sản phẩm theo danh mục
     * URL: /product/category/{id}
     */
    public function category($id) {
        $productModel = new Product();
        $categoryModel = new Category();
        
        $category = $categoryModel->getById($id);
        if (!$category) {
            $_SESSION['error'] = 'Danh mục không tồn tại';
            header('Location: ' . ROOT_URL . 'product');
            exit;
        }
        
        $products = $productModel->getByCategory($id);
        $categories = $categoryModel->getAll();
        
        $data = [
            'title' => $category['name'],
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $category
        ];
        
        // Render view đầy đủ HTML, không dùng layout
        $this->renderView('product/category_full', $data);
    }
    
    /**
     * Hiển thị chi tiết sản phẩm
     * URL: /product/detail/{id}
     */
    public function detail($id) {
        $productModel = new Product();
        $categoryModel = new Category();
        $commentModel = new Comment();

        $product = $productModel->getByIdWithCategory($id);
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại';
            header('Location: ' . ROOT_URL . 'product');
            exit;
        }

        $categories = $categoryModel->getAll();
        $comments = $commentModel->getByProductId($id);

        $data = [
            'title' => $product['name'],
            'product' => $product,
            'categories' => $categories,
            'comments' => $comments
        ];

        // Dùng view ProductDetail duy nhất cho chi tiết sản phẩm
        $this->renderView('ProductDetail', $data);
    }
    
    /**
     * Xử lý post comment
     * URL: /product/postComment/{productId} (POST)
     */
    public function postComment($productId) {
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
        
        try {
            $commentModel = new Comment();
            $commentId = $commentModel->createComment([
                'content' => $content,
                'user_id' => $_SESSION['user']['id'],
                'product_id' => $productId
            ]);
            
            if ($commentId) {
                $_SESSION['message'] = 'Bình luận của bạn đã được đăng';
            } else {
                $_SESSION['error'] = 'Không thể đăng bình luận';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi khi đăng bình luận: ' . $e->getMessage();
        }
        
        header('Location: ' . ROOT_URL . 'product/detail/' . $productId);
        exit;
    }
    
    /**
     * Tìm kiếm sản phẩm
     * URL: /product/search?q=keyword
     */
    public function search() {
        $productModel = new Product();
        $categoryModel = new Category();
        
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        $products = [];
        
        if (!empty($keyword)) {
            $products = $productModel->search($keyword);
        } else {
            $products = $productModel->getAllWithCategory();
        }
        
        $categories = $categoryModel->getAll();
        
        $data = [
            'title' => 'Tìm kiếm: ' . htmlspecialchars($keyword),
            'products' => $products,
            'categories' => $categories,
            'keyword' => $keyword
        ];
        
        // Render view đầy đủ HTML, không dùng layout
        $this->renderView('product/search_full', $data);
    }
}
?>

