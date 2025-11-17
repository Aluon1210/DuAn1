<?php
// File: src/Models/User.php
namespace Models;

use Core\Model;

class User extends Model {
    
    protected $table = 'users';
    
    /**
     * Lấy user theo email
     * @param string $email
     * @return array|false
     */
    public function getByEmail($email) {
        return $this->getOne(['email' => $email]);
    }
    
    /**
     * Tạo user mới với password đã hash
     * @param array $data
     * @return int|false
     */
    public function createUser($data) {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $this->create($data);
    }
    
    /**
     * Xác thực user
     * @param string $email
     * @param string $password
     * @return array|false
     */
    public function authenticate($email, $password) {
        $user = $this->getByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // Không trả về password
            return $user;
        }
        return false;
    }
}
?>

