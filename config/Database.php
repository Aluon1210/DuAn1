<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'du_an_1'; // Tên cơ sở dữ liệu
    private $username = 'root';   // Tên người dùng phpMyAdmin
    private $password = '';       // Mật khẩu (thường trống nếu là localhost)
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Lỗi kết nối: ' . $e->getMessage();
        }

        return $this->conn;
    }
}
?>
