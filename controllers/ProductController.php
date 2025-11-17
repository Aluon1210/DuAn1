<?php
require_once 'models/Product.php';
require_once 'models/Category.php';

class ProductController {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    // Hiển thị danh sách sản phẩm
    public function index() {
        $products = $this->productModel->getAll();
        $categories = $this->categoryModel->getAll();
        require_once 'views/product/list.php';
    }

    // Hiển thị chi tiết sản phẩm
    public function detail($id = null) {
        if ($id === null) {
            header('Location: ?url=product');
            return;
        }
        $product = $this->productModel->getById($id);
        $categories = $this->categoryModel->getAll();
        if (!$product) {
            echo "Sản phẩm không tồn tại";
            return;
        }
        require_once 'views/product/detail.php';
    }

    // Hiển thị sản phẩm theo danh mục
    public function category($category_id = null) {
        if ($category_id === null) {
            header('Location: ?url=product');
            return;
        }
        $category = $this->categoryModel->getById($category_id);
        $products = $this->productModel->getByCategory($category_id);
        $categories = $this->categoryModel->getAll();
        require_once 'views/product/category.php';
    }

    // Tìm kiếm sản phẩm
    public function search() {
        $keyword = isset($_GET['q']) ? $_GET['q'] : '';
        $products = [];
        $categories = $this->categoryModel->getAll();
        
        if (!empty($keyword)) {
            $products = $this->productModel->search($keyword);
        }
        
        require_once 'views/product/search.php';
    }
}
?>
