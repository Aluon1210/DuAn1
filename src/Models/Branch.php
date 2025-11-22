<?php
// File: src/Models/Branch.php
namespace Models;

use Core\Model;

class Branch extends Model {

    protected $table = 'branch';

    /**
     * Lấy danh sách hãng (branch) với alias id, name
     * @param array $conditions
     * @return array
     */
    public function getAll($conditions = []) {
        $sql = "SELECT Branch_Id as id, Name as name FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $dbKey = $this->mapColumnName($key);
                $where[] = "$dbKey = :$key";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY Name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Lấy branch theo ID (Branch_Id)
     */
    public function getById($id) {
        return $this->getOne(['Branch_Id' => $id]);
    }

    /**
     * Map tên cột từ code sang database
     */
    private function mapColumnName($key) {
        $map = [
            'id' => 'Branch_Id',
            'name' => 'Name',
        ];
        return $map[$key] ?? ucfirst($key);
    }

    /**
     * Tạo hãng mới
     * @param array $data ['name' => '...']
     * @return string|false Trả về Branch_Id hoặc false
     */
    public function createBranch($data) {
        try {
            $dbData = [
                'Name' => trim($data['name'] ?? '')
            ];
            
            if (empty($dbData['Name'])) {
                error_log("Branch creation failed: Name is required");
                return false;
            }
            
            return $this->create($dbData);
        } catch (\PDOException $e) {
            error_log("Branch creation SQL Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cập nhật hãng
     * @param string $id Branch_Id
     * @param array $data ['name' => '...']
     * @return bool
     */
    public function updateBranch($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET Name = :name WHERE Branch_Id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':name' => trim($data['name'] ?? ''),
                ':id' => $id
            ]);
        } catch (\PDOException $e) {
            error_log("Branch update SQL Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Xóa hãng
     * @param string $id Branch_Id
     * @return bool
     */
    public function deleteBranch($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE Branch_Id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            error_log("Branch delete SQL Error: " . $e->getMessage());
            throw $e;
        }
    }
}

?>


