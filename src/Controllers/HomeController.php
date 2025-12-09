<?php
// File: src/Controllers/HomeController.php
namespace Controllers;

use Core\Controller; // Sử dụng Controller cơ sở

class HomeController extends Controller {
    
    /**
     * Tương ứng với URL: /duan1/
     */
    public function index() {
        // Trang chủ hiển thị danh sách sản phẩm (HomeProduct)
        $productModel = new \Models\Product();
        $categoryModel = new \Models\Category();

        $products = $productModel->getAllWithCategory();
        $categories = $categoryModel->getAll();

        $data = [
            'title' => 'Trang chủ - Danh sách sản phẩm',
            'products' => $products,
            'categories' => $categories
        ];

        // Sử dụng view HomeProduct duy nhất cho danh sách sản phẩm
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