<?php
// File: src/Controllers/HomeController.php
namespace Controllers;

use Core\Controller; // Sử dụng Controller cơ sở

class HomeController extends Controller {
    
    /**
     * Tương ứng với URL: /duan1/
     */
    public function index() {
        $data = [
            'title' => 'Trang chủ FashionStore'
        ];
        
        // Render view đầy đủ HTML, không dùng layout
        $this->renderView('home_full', $data); 
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