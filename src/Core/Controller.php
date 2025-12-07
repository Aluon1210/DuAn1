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
        
        if (!file_exists($viewFile)) {
            die("Không tìm thấy View: " . $viewFile);
        }
        
        // $content chính là đường dẫn đến file view con
        $content = $viewFile; 
        
        // 2. Tải layout chung dựa trên thuộc tính $layout
        $layoutFile = ROOT_PATH . '/src/Views/layouts/' . $this->layout . '.php';
        
        if (!file_exists($layoutFile)) {
            die("Không tìm thấy layout: " . $layoutFile);
        }
        
        // Tải file layout (layout sẽ require $content)
        require_once $layoutFile;
    }

    /**
     * Render view không dùng layout (trang đầy đủ HTML)
     * @param string $view Tên file view (ví dụ: 'home', 'products/detail')
     * @param array $data Dữ liệu truyền cho view
     */
    public function renderView($view, $data = []) {
        // Biến mảng $data thành các biến riêng lẻ
        extract($data);
        
        // Đường dẫn đến file view
        $viewFile = ROOT_PATH . '/src/Views/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            die("Không tìm thấy View: " . $viewFile);
        }
        
        // Tải view trực tiếp (view phải có HTML đầy đủ)
        require_once $viewFile;
    }

    /**
     * Kiểm tra xem user có phải admin không
     * Nếu không phải admin, redirect về trang chủ
     * @return bool|void
     */
    protected function requireAdmin() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: ' . ROOT_URL);
            exit;
        }
    }

    /**
     * Kiểm tra xem user đã đăng nhập chưa
     * Nếu chưa, redirect tới login
     * @return bool|void
     */
    protected function requireLogin() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }
        // If the logged-in account has role 'forbident', show blocked page and destroy session
        if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'forbident') {
            // clear session user
            unset($_SESSION['user']);
            $data = ['message' => 'Tài khoản của bạn đã bị chặn và không thể truy cập hệ thống. Vui lòng liên hệ quản trị.'];
            // Render a simple forbidden view
            $this->renderView('auth/forbidden', $data);
            exit;
        }
    }
}
?>