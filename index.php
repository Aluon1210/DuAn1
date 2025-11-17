<?php
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