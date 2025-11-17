<?php
// File: index.php (Thư mục gốc)

session_start();

// 1. Tải file khởi động (autoloader, constants)
require_once 'app.php';

// 2. Khởi chạy Bộ định tuyến (Router)
// Class này nằm trong 'src/Core/App.php'
$app = new \Core\App();
?>