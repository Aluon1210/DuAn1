<?php
require_once 'config/Database.php';

class Product {
    private $db;
    private $table = 'products';

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Lấy tất cả sản phẩm
    public function getAll() {
        $query = 'SELECT * FROM ' . $this->table . ' ORDER BY id DESC';
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm theo ID
    public function getById($id) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm theo danh mục
    public function getByCategory($category_id) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE category_id = :category_id ORDER BY id DESC';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm kiếm sản phẩm
    public function search($keyword) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE name LIKE :keyword OR description LIKE :keyword ORDER BY id DESC';
        $stmt = $this->db->prepare($query);
        $keyword = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm sản phẩm
    public function create($name, $price, $quantity, $description, $category_id, $image) {
        $query = 'INSERT INTO ' . $this->table . ' (name, price, quantity, description, category_id, image) 
                  VALUES (:name, :price, :quantity, :description, :category_id, :image)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);
        return $stmt->execute();
    }

    // Cập nhật sản phẩm
    public function update($id, $name, $price, $quantity, $description, $category_id, $image = null) {
        if ($image) {
            $query = 'UPDATE ' . $this->table . ' SET name = :name, price = :price, quantity = :quantity, 
                      description = :description, category_id = :category_id, image = :image WHERE id = :id';
        } else {
            $query = 'UPDATE ' . $this->table . ' SET name = :name, price = :price, quantity = :quantity, 
                      description = :description, category_id = :category_id WHERE id = :id';
        }
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category_id', $category_id);
        if ($image) {
            $stmt->bindParam(':image', $image);
        }
        return $stmt->execute();
    }

    // Xóa sản phẩm
    public function delete($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Lấy sản phẩm bán chạy
    public function getTopSelling($limit = 5) {
        $query = 'SELECT * FROM ' . $this->table . ' ORDER BY RAND() LIMIT ' . $limit;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
