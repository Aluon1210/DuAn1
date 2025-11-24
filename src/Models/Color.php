<?php

namespace Models;

use Core\Model;
use Core\IdGenerator;

class Color extends Model
{
    protected $table = 'colors';

    /**
     * Lấy danh sách màu cùng alias thân thiện.
     */
    public function getAll($conditions = [])
    {
        $sql = "SELECT Color_Id as id,
                   Name as name,
                   Hex_Code as hex_code
            FROM {$this->table}";

        $params = [];
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $dbKey = $this->mapColumnName($key);
                $where[] = "{$dbKey} = :{$key}";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY Name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Lấy một màu theo Color_Id.
     */
    public function getById($id)
    {
        $sql = "SELECT Color_Id as id,
                   Name as name,
                   Hex_Code as hex_code
            FROM {$this->table}
            WHERE Color_Id = :id
            LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Tạo màu sắc mới.
     */
    public function createColor(array $data)
    {
        $colorId = IdGenerator::generate('CL+', $this->table, 'Color_Id', 10);

        $payload = [
            'Color_Id' => $colorId,
            'Name' => trim($data['name'] ?? ''),
            'Hex_Code' => strtoupper(trim($data['hex_code'] ?? '#000000'))
        ];

        if (empty($payload['Name'])) {
            return false;
        }

        return $this->create($payload);
    }

    /**
     * Cập nhật màu sắc.
     */
    public function updateColor(string $id, array $data)
    {
        $sql = "UPDATE {$this->table}
                   SET Name = :name,
                       Hex_Code = :hex
                 WHERE Color_Id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => trim($data['name'] ?? ''),
            ':hex' => strtoupper(trim($data['hex_code'] ?? '#000000')),
            ':id' => $id
        ]);
    }

    /**
     * Xóa màu.
     */
    public function deleteColor(string $id)
    {
        $sql = "DELETE FROM {$this->table} WHERE Color_Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    private function mapColumnName(string $key): string
    {
        $map = [
            'id' => 'Color_Id',
            'name' => 'Name',
            'hex_code' => 'Hex_Code'
        ];

        return $map[$key] ?? $key;
    }
}

?>
