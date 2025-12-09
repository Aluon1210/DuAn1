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
        // ví dụ: /duan1/home/about -> $url[0] = 'home', $url[1] = 'about'
        // ví dụ: /duan1/login -> $url[0] = 'login' -> AuthController
        // ví dụ: /duan1/register -> $url[0] = 'register' -> AuthController
        if (isset($url[0]) && !empty($url[0])) {
            // Map các route đặc biệt
            $routeMap = [
                'login' => 'AuthController',
                'register' => 'AuthController',
                'logout' => 'AuthController'
            ];
            
            if (isset($routeMap[$url[0]])) {
                // Route đặc biệt: login, register, logout -> AuthController
                $this->controller = $routeMap[$url[0]];
                $route = $url[0];
                unset($url[0]);
                $url = array_values($url);
                
                // Nếu không có method tiếp theo, set method mặc định
                // Nếu có method (ví dụ: process), sẽ được xử lý ở phần 2
                if (empty($url[0])) {
                    if ($route === 'login') {
                        $this->method = 'index';
                    } elseif ($route === 'register') {
                        $this->method = 'register';
                    } elseif ($route === 'logout') {
                        $this->method = 'logout';
                    }
                }
            } else {
                // Route thông thường
                $controllerName = ucfirst(strtolower($url[0])) . 'Controller';
                $controllerFile = ROOT_PATH . '/src/Controllers/' . $controllerName . '.php';
                
                if (file_exists($controllerFile)) {
                    $this->controller = $controllerName;
                    unset($url[0]);
                    // Reindex mảng sau khi unset
                    $url = array_values($url);
                }
            }
        }
        
        // Thêm namespace đầy đủ cho controller
        $fullControllerName = 'Controllers\\' . $this->controller;
        
        // Kiểm tra xem class có tồn tại không
        if (!class_exists($fullControllerName)) {
            die("Controller không tồn tại: " . $fullControllerName);
        }
        
        // Khởi tạo controller: new Controllers\HomeController()
        $controllerInstance = new $fullControllerName; 

        // 2. Xử lý Method
        // Nếu method chưa được set từ route đặc biệt và còn phần tử trong $url
        if (isset($url[0]) && !empty($url[0])) {
            // Convert dashes to camelCase for method names (check-payment -> checkPayment)
            $methodName = $url[0];
            if (strpos($methodName, '-') !== false) {
                $parts = explode('-', $methodName);
                $methodName = array_shift($parts);
                foreach ($parts as $part) {
                    $methodName .= ucfirst($part);
                }
            }
            
            // Kiểm tra method có tồn tại không
            if (method_exists($controllerInstance, $methodName)) {
                $this->method = $methodName;
                unset($url[0]);
                // Reindex lại
                $url = array_values($url);
            }
        }

        // 3. Xử lý Params (Các tham số còn lại)
        $this->params = $url ? array_values($url) : [];

        // Gọi hàm của controller với các tham số
        if (!method_exists($controllerInstance, $this->method)) {
            die("Method không tồn tại: " . $this->method . " trong " . $fullControllerName);
        }
        
        call_user_func_array([$controllerInstance, $this->method], $this->params);
    }

    /**
     * Lấy URL và tách thành mảng
     * @return array
     */
    public function parseUrl() {
        if (isset($_GET['url'])) {
            $url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            // Loại bỏ các phần tử rỗng
            $url = array_filter($url, function($value) {
                return !empty($value);
            });
            return array_values($url);
        }
        return [];
    }
}
?>