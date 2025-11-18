<?php
<<<<<<< HEAD
// File chính - Route xử lý URL
session_start();

// Lấy URL
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = explode('/', filter_var($url, FILTER_SANITIZE_URL));

// Tên controller và action
$controller_name = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) : 'Home';
$action_name = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';
$params = array_slice($url, 2);

// Đường dẫn file controller
$controller_file = 'controllers/' . $controller_name . 'Controller.php';

// Kiểm tra xem controller có tồn tại hay không
if (file_exists($controller_file)) {
    require_once $controller_file;
    $controller_class = $controller_name . 'Controller';
    $controller = new $controller_class();

    // Kiểm tra xem action có tồn tại hay không
    if (method_exists($controller, $action_name)) {
        call_user_func_array([$controller, $action_name], $params);
    } else {
        echo "Action '$action_name' không tồn tại trong controller '$controller_class'";
    }
} else {
    echo "Controller '$controller_file' không tồn tại";
}
?>
=======
// File: index.php (Thư mục gốc)

// Bật error reporting để debug
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();

// 1. Tải file khởi động (autoloader, constants)
require_once 'app.php';

// 2. Khởi chạy Bộ định tuyến (Router)
// Class này nằm trong 'src/Core/App.php'
try {
    $app = new \Core\App();
} catch (\Exception $e) {
    die("Lỗi: " . $e->getMessage() . "<br>File: " . $e->getFile() . "<br>Line: " . $e->getLine());
}
?>
>>>>>>> cong
