<?php
$hot = "localhost";
$user = "root";
$database = "DuAn1";
$pass = "";
$port="3306";
try {
    $conn = new PDO("mysql:host=$hot;dbname=$database", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($conn) {
        
    }
} catch (PDOException $e) {
    echo "Kết nối thất bại: " . $e->getMessage();
}