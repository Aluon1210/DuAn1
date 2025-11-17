<?php
// File: src/Core/App.php
namespace Core;

class App {
    
    protected $controller = 'HomeController'; // Controller mặc định
    protected $method = 'index'; // Method mặc định
    protected $params = []; // Tham số

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Xử lý Controller
        // Kiểm tra xem controller có tồn tại không
        // ví dụ: /duan1/products -> $url[0] = 'products'
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller'; // 'Products' . 'Controller'
            $controllerFile = ROOT_PATH . '/src/Controllers/' . $controllerName . '.php';
            
            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }
        
        // Thêm namespace đầy đủ cho controller
        $this->controller = 'Controllers\\' . $this->controller;
        
        // Khởi tạo controller: new Controllers\HomeController()
        $controllerInstance = new $this->controller; 

        // 2. Xử lý Method
        if (isset($url[1])) {
            if (method_exists($controllerInstance, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. Xử lý Params (Các tham số còn lại)
        $this->params = $url ? array_values($url) : [];

        // Gọi hàm của controller với các tham số
        call_user_func_array([$controllerInstance, $this->method], $this->params);
    }

    /**
     * Lấy URL và tách thành mảng
     * @return array
     */
    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
?>