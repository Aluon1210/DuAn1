<?php
// File: src/Controllers/HomeController.php
namespace Controllers;

use Core\Controller; // Sử dụng Controller cơ sở

class HomeController extends Controller {
    
    /**
     * Tương ứng với URL: /duan1/
     */
    public function index() {
        $productModel = new \Models\Product();
        $categoryModel = new \Models\Category();

        $limit = 15;
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $total = $productModel->countAll();
        $products = $productModel->getAllWithCategoryPaginated($limit, $offset);
        $categories = $categoryModel->getAll();

        $totalPages = $total > 0 ? (int) ceil($total / $limit) : 1;

        $data = [
            'title' => 'Trang chủ - Danh sách sản phẩm',
            'products' => $products,
            'categories' => $categories,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'totalPages' => $totalPages
            ],
            'baseUrl' => ROOT_URL
        ];

        $this->renderView('HomeProduct', $data);
    }
    
    /**
     * Tương ứng với URL: /home/about hoặc /about
     */
    public function about() {
         // Render view đầy đủ HTML, không dùng layout
         $data = [
             'title' => 'Giới thiệu - Luxury Fashion Store'
         ];
         $this->renderView('about_full', $data);
    }
}
?>
