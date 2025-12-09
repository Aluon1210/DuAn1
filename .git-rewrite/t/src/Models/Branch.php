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
}

?>


