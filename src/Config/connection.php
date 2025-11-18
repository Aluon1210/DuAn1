<?php
// File: src/Config/connection.php
// Không sử dụng namespace ở đây để dễ dàng include và sử dụng biến $conn

$host = "localhost";
$user = "root";
$database = "duan1"; // Sửa tên database theo database.sql
$pass = "";
$port = "3306";

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Không echo để tránh làm hỏng output
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}