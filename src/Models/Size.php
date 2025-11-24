<?php

namespace Models;

use Core\Model;
class Size extends Model {

    protected $table = 'sizes';

    /**
     * Lấy tất cả size
     * @return array
     */
    public function getAllSizes() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY Size_Value ASC");
        $stmt->execute();
        return $this->getAll();
    }

    /**
     * Lấy size theo ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        return $this->getOne(['Size_Id' => $id]);
    }
}