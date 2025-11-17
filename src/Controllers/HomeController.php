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
        
        // Tải view 'home.php' (không cần 'client/' nữa)
        $this->loadView('home', $data); 
    }
    
    /**
     * Tương ứng với URL: /home/about hoặc /about
     */
    public function about() {
         // Tải view 'about.php'
         $this->loadView('about', ['title' => 'Giới thiệu']);
    }
}
?>