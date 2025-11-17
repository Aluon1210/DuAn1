<?php
// File: src/Core/Controller.php
namespace Core;

class Controller {
    
    /**
     * @var string Tên của file layout (ví dụ: 'client_layout' hoặc 'admin_layout')
     * Mặc định là layout của client.
     */
    protected $layout = 'client_layout'; 

    /**
     * Tải View và truyền dữ liệu
     * @param string $view Tên file view (ví dụ: 'home', 'products')
     * @param array $data Dữ liệu truyền cho view
     */
    public function loadView($view, $data = []) {
        // Biến mảng $data thành các biến riêng lẻ
        // $data['title'] sẽ trở thành biến $title
        extract($data);
        
        // 1. Đường dẫn đến file view con (đã bỏ 'client/')
        $viewFile = ROOT_PATH . '/src/Views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            // $content chính là đường dẫn đến file view con
            $content = $viewFile; 
            
            // 2. Tải layout chung dựa trên thuộc tính $layout
            $layoutFile = ROOT_PATH . '/src/Views/layouts/' . $this->layout . '.php';
            
            if (file_exists($layoutFile)) {
                // Tải file layout
                require_once $layoutFile;
            } else {
                die("Không tìm thấy layout: " . $layoutFile);
            }
        } else {
            die("Không tìm thấy View: " . $viewFile);
        }
    }
}
?>