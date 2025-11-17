<?php
// File: src/Core/Model.php
namespace Core;

use PDO;
use PDOException;

class Model {
    
    /**
     * @var PDO Kết nối database
     */
    protected $db;
    
    /**
     * @var string Tên bảng trong database
     */
    protected $table;
    
    /**
     * Constructor - Khởi tạo kết nối database
     */
    public function __construct() {
        $this->db = $this->getConnection();
    }
    
    /**
     * Lấy kết nối PDO
     * @return PDO
     */
    protected function getConnection() {
        // Include connection file để lấy biến $conn
        require ROOT_PATH . '/src/Config/connection.php';
        // Biến $conn được tạo trong connection.php
        return $conn;
    }
    
    /**
     * Lấy tất cả records
     * @param array $conditions Điều kiện WHERE
     * @return array
     */
    public function getAll($conditions = []) {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = :$key";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy một record theo ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy một record theo điều kiện
     * @param array $conditions
     * @return array|false
     */
    public function getOne($conditions = []) {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = :$key";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $sql .= " LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Thêm record mới
     * @param array $data
     * @return int|false ID của record mới hoặc false nếu lỗi
     */
    public function create($data) {
        try {
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));
            
            $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$values})";
            $stmt = $this->db->prepare($sql);
            
            if ($stmt->execute($data)) {
                // Với bảng users, lastInsertId() có thể không hoạt động vì primary key là string
                // Trả về true nếu insert thành công
                return true;
            }
            return false;
        } catch (\PDOException $e) {
            error_log("SQL Insert Error in {$this->table}: " . $e->getMessage());
            error_log("SQL: INSERT INTO {$this->table} ({$columns}) VALUES ({$values})");
            error_log("Data: " . print_r($data, true));
            throw $e; // Re-throw để catch ở level cao hơn
        }
    }
    
    /**
     * Cập nhật record
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $setClause = implode(", ", $set);
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = :id";
        $data['id'] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    /**
     * Xóa record
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Thực thi query tùy chỉnh
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $results !== false ? $results : [];
        } catch (\PDOException $e) {
            error_log("SQL Query Error: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));
            throw $e; // Re-throw để catch ở level cao hơn
        }
    }
    
    /**
     * Đếm số lượng records
     * @param array $conditions
     * @return int
     */
    public function count($conditions = []) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = :$key";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }
}
?>

