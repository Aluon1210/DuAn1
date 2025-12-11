<?php
// File: src/Models/User.php
namespace Models;

use Core\Model;
use Core\IdGenerator;

class User extends Model {
    
    protected $table = 'users';
    
    /**
     * Lấy user theo email
     * @param string $email
     * @return array|false
     */
    public function getByEmail($email) {
        $user = $this->getOne(['Email' => $email]);
        return $user ? $this->normalizeUser($user) : false;
    }

    public function getByPhone($phone) {
        if ($phone === '' || $phone === null) {
            return false;
        }
        $user = $this->getOne(['Phone' => $phone]);
        return $user ? $this->normalizeUser($user) : false;
    }
    
    /**
     * Lấy user theo username
     * @param string $username
     * @return array|false
     */
    public function getByUsername($username) {
        $user = $this->getOne(['_UserName_Id' => $username]);
        return $user ? $this->normalizeUser($user) : false;
    }
    
    /**
     * Override getById để dùng _UserName_Id
     */
    public function getById($id) {
        $user = $this->getOne(['_UserName_Id' => $id]);
        return $user ? $this->normalizeUser($user) : false;
    }
    
    /**
     * Tạo user mới với password đã hash
     * @param array $data
     * @return string|false Username hoặc false nếu lỗi
     */
    public function createUser($data) {
        $dbData = []; // Khai báo trước để dùng trong catch
        try {
            // Generate username theo format kh+0000000001
            $username = IdGenerator::generate('Us', $this->table, '_UserName_Id', 10);
            
            // Map dữ liệu từ code sang database
            $dbData = [
                '_UserName_Id' => $username,
                'Email' => trim($data['email'] ?? $data['Email'] ?? ''),
                'FullName' => trim($data['name'] ?? $data['full_name'] ?? $data['FullName'] ?? ''),
                '__PassWord' => isset($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : '',
                'Phone' => trim($data['phone'] ?? $data['Phone'] ?? ''),
                'Role' => trim($data['role'] ?? $data['Role'] ?? 'user'),
                'Address' => trim($data['address'] ?? $data['Address'] ?? ''),
                'Price' => (float)($data['price'] ?? 0),
                'BankAccountNumber' => trim($data['bank_account_number'] ?? ''),
                'BankName' => trim($data['bank_name'] ?? '')
            ];
            
            // Validate dữ liệu bắt buộc
            if (empty($dbData['_UserName_Id']) || empty($dbData['Email']) || empty($dbData['__PassWord']) || empty($dbData['FullName'])) {
                error_log("User creation failed: Missing required fields");
                error_log("User Data: " . print_r($dbData, true));
                return false;
            }
            
            if ($this->create($dbData)) {
                return $dbData['_UserName_Id'];
            }
            return false;
        } catch (\PDOException $e) {
            error_log("User creation SQL Error: " . $e->getMessage());
            error_log("SQL Data: " . print_r($dbData, true));
            throw $e;
        } catch (\Exception $e) {
            error_log("User creation Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xác thực user
     * @param string $email
     * @param string $passwordGI
     * @return array|false
     */
    public function authenticate($email, $password) {
        $user = $this->getOne(['Email' => $email]);
        
        if ($user && isset($user['__PassWord']) && password_verify($password, $user['__PassWord'])) {
            $normalized = $this->normalizeUser($user);
            unset($normalized['password']); // Không trả về password
            return $normalized;
        }
        return false;
    }
    
    /**
     * Chuẩn hóa dữ liệu user từ DB sang format code
     */
    private function normalizeUser($user) {
        // Nếu đã normalize rồi thì return luôn
        if (isset($user['id']) || isset($user['username'])) {
            return $user;
        }
        
        return [
            'id' => $user['_UserName_Id'] ?? '',
            'username' => $user['_UserName_Id'] ?? '',
            'email' => $user['Email'] ?? '',
            'name' => $user['FullName'] ?? '',
            'full_name' => $user['FullName'] ?? '',
            'phone' => $user['Phone'] ?? '',
            // Normalize role to lowercase to avoid case-sensitivity issues
            'role' => strtolower($user['Role'] ?? 'user'),
            'address' => $user['Address'] ?? '',
            'password' => $user['__PassWord'] ?? '',
            'price' => isset($user['Price']) ? (float)$user['Price'] : (isset($user['price']) ? (float)$user['price'] : 0.0),
            'bank_account_number' => $user['BankAccountNumber'] ?? ($user['bank_account_number'] ?? ''),
            'bank_name' => $user['BankName'] ?? ($user['bank_name'] ?? '')
        ];
    }

    /**
     * Lấy tất cả user (cho admin)
     * @return array
     */
    public function getAllUsers() {
        try {
            // Exclude users with role 'forbident' from admin listings
            $sql = "SELECT _UserName_Id as id, Email as email, FullName as name, Phone as phone, Role as role, Address as address, Price as price, BankAccountNumber as bank_account_number, BankName as bank_name FROM {$this->table} WHERE Role != 'forbident' ORDER BY _UserName_Id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Get all users SQL Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cập nhật user (cho admin)
     * @param string $id Username
     * @param array $data
     * @return bool
     */
    public function updateUser($id, $data) {
        try {
            $map = [
                'email' => 'Email',
                'name' => 'FullName',
                'phone' => 'Phone',
                'role' => 'Role',
                'address' => 'Address',
                'price' => 'Price',
                'bank_account_number' => 'BankAccountNumber',
                'bank_name' => 'BankName'
            ];
            $set = [];
            $params = [':id' => $id];
            foreach ($map as $k => $col) {
                if (array_key_exists($k, $data)) {
                    $set[] = "$col = :$k";
                    $params[":$k"] = is_string($data[$k]) ? trim($data[$k]) : $data[$k];
                }
            }
            if (!empty($data['password'])) {
                $set[] = "__PassWord = :password";
                $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            if (empty($set)) {
                return false;
            }
            $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE _UserName_Id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (\PDOException $e) {
            error_log("User update SQL Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Xóa user
     * @param string $id Username
     * @return bool
     */
    public function deleteUser($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE _UserName_Id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            error_log("User delete SQL Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function increaseBalance($id, $amount) {
        try {
            $sql = "UPDATE {$this->table} SET Price = COALESCE(Price, 0) + :amount WHERE _UserName_Id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':amount' => (float)$amount, ':id' => $id]);
        } catch (\PDOException $e) {
            error_log("User increaseBalance SQL Error: " . $e->getMessage());
            throw $e;
        }
    }
}
?>

