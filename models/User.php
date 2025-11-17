<?php
require_once 'config/Database.php';

class User {
    private $db;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Lấy tất cả người dùng
    public function getAll() {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy người dùng theo ID
    public function getById($id) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm người dùng
    public function create($name, $email) {
        $query = 'INSERT INTO ' . $this->table . ' (name, email) VALUES (:name, :email)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    // Cập nhật người dùng
    public function update($id, $name, $email) {
        $query = 'UPDATE ' . $this->table . ' SET name = :name, email = :email WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    // Xóa người dùng
    public function delete($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
