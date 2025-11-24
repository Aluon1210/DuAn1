<?php

namespace Models;

use Core\Model;
use Core\IdGenerator;

class Size extends Model
{
    protected $table = 'sizes';

    /**
     * Lấy tất cả size với alias.
     */
    public function getAll($conditions = [])
    {
        $sql = "SELECT Size_Id as id,
                       Name as value,
                       Type as description
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
     * Lấy size theo Size_Id.
     */
    public function getById($id)
    {
        $sql = "SELECT Size_Id as id,
                       Name as value,
                       Type as description
                FROM {$this->table}
                WHERE Size_Id = :id LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Tạo size mới.
     */
    public function createSize(array $data)
    {
        $sizeId = IdGenerator::generate('SZ+', $this->table, 'Size_Id', 10);

        $payload = [
            'Size_Id' => $sizeId,
            'Name' => trim($data['value'] ?? ''),
            'Type' => trim($data['description'] ?? '')
        ];

        if (empty($payload['Name'])) {
            return false;
        }

        return $this->create($payload);
    }

    /**
     * Cập nhật size.
     */
    public function updateSize(string $id, array $data)
    {
        $sql = "UPDATE {$this->table}
                   SET Name = :value,
                       Type = :description
                 WHERE Size_Id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':value' => trim($data['value'] ?? ''),
            ':description' => trim($data['description'] ?? ''),
            ':id' => $id
        ]);
    }

    /**
     * Xóa size.
     */
    public function deleteSize(string $id)
    {
        $sql = "DELETE FROM {$this->table} WHERE Size_Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    private function mapColumnName(string $key): string
    {
        $map = [
            'id' => 'Size_Id',
            'value' => 'Name',
            'description' => 'Type'
        ];

        return $map[$key] ?? $key;
    }
}