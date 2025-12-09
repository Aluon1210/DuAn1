<?php
// File: app.php (Thư mục gốc)

// 1. Định nghĩa các hằng số
// URL gốc của website (rất quan trọng cho link CSS, JS)
// Tự động xác định theo thư mục hiện tại để tránh sai khi đổi tên project
$scriptName = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
$baseDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
$rootUrl = ($baseDir === '/' || $baseDir === '.') ? '/' : ($baseDir . '/');
define('ROOT_URL', $rootUrl);
define('ROOT_PATH', __DIR__); // Đường dẫn thư mục gốc

// 2. Autoloader đơn giản cho cấu trúc /src
// 2a. Load environment variables từ .env file
if (file_exists(ROOT_PATH . '/.env')) {
    $lines = file(ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Bỏ qua comment lines
        if (strpos(trim($line), '#') === 0) continue;
        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            // Set vào $_ENV và putenv
            $_ENV[$key] = $value;
            putenv("{$key}={$value}");
        }
    }
}

// 2b. Autoloader đơn giản cho cấu trúc /src
// Tự động 'require' file khi bạn 'new' một class
spl_autoload_register(function ($className) {
    // Ví dụ: new Core\App()
    // $className sẽ là 'Core\App'
    
    // Đổi 'Core\App' thành 'Core/App'
    $className = str_replace('\\', '/', $className);
    
    // Tạo đường dẫn: 'src/Core/App.php'
    $file = ROOT_PATH . '/src/' . $className . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});
?>
