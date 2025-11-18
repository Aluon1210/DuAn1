<?php
// File: src/Core/IdGenerator.php
namespace Core;

use PDO;
use PDOException;

class IdGenerator {
    
    /**
     * Generate ID theo format: PREFIX + 0000000001
     * @param string $prefix Prefix (ví dụ: 'kh+', 'sp+', 'or+', 'cm+')
     * @param string $table Tên bảng
     * @param string $idColumn Tên cột ID
     * @param int $length Độ dài số (mặc định 10 chữ số)
     * @return string ID mới (ví dụ: kh+0000000001)
     */
    public static function generate($prefix, $table, $idColumn, $length = 9) {
        try {
            require ROOT_PATH . '/src/Config/connection.php';
            
            // Lấy ID lớn nhất hiện tại - tìm số sau dấu +
            // Sử dụng backtick cho tên cột và bảng để tránh lỗi SQL
            $idColumnEscaped = "`{$idColumn}`";
            $tableEscaped = "`{$table}`";
            
            $sql = "SELECT {$idColumnEscaped} FROM {$tableEscaped} WHERE {$idColumnEscaped} LIKE :pattern 
                    ORDER BY 
                        CASE 
                            WHEN LOCATE('+', {$idColumnEscaped}) > 0 
                            THEN CAST(SUBSTRING({$idColumnEscaped}, LOCATE('+', {$idColumnEscaped}) + 1) AS UNSIGNED)
                            ELSE CAST(SUBSTRING({$idColumnEscaped}, " . (strlen($prefix) + 1) . ") AS UNSIGNED)
                        END DESC 
                    LIMIT 1";
            $pattern = $prefix . '%';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['pattern' => $pattern]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $nextNumber = 1;
            
            if ($result && isset($result[$idColumn])) {
                // Lấy số từ ID hiện tại (tìm vị trí dấu + và lấy phần sau)
                $currentId = $result[$idColumn];
                $plusPos = strpos($currentId, '+');
                if ($plusPos !== false) {
                    // Lấy phần số sau dấu +
                    $numberPart = substr($currentId, $plusPos + 1);
                    $currentNumber = (int)$numberPart;
                    if ($currentNumber > 0) {
                        $nextNumber = $currentNumber + 1;
                    }
                } else {
                    // Nếu không có dấu +, thử lấy số sau prefix
                    $numberPart = substr($currentId, strlen($prefix));
                    $currentNumber = (int)$numberPart;
                    if ($currentNumber > 0) {
                        $nextNumber = $currentNumber + 1;
                    }
                }
            }
            
            // Format số với độ dài cố định
            $formattedNumber = str_pad($nextNumber, $length, '0', STR_PAD_LEFT);
            
            return $prefix . $formattedNumber;
            
        } catch (PDOException $e) {
            error_log("IdGenerator Error: " . $e->getMessage());
            error_log("Table: {$table}, Column: {$idColumn}, Prefix: {$prefix}");
            // Fallback: dùng timestamp nếu lỗi
            return $prefix . str_pad(time() % pow(10, $length), $length, '0', STR_PAD_LEFT);
        }
    }
    
    /**
     * Generate Cart ID đơn giản (chỉ số tăng dần: 1, 2, 3...)
     * @param string $table Tên bảng (cart)
     * @param string $idColumn Tên cột ID (_Cart_Id)
     * @return string ID mới (ví dụ: 1, 2, 3...)
     */
    public static function generateCartId($table = 'cart', $idColumn = '_Cart_Id') {
        try {
            require ROOT_PATH . '/src/Config/connection.php';
            
            // Lấy ID lớn nhất hiện tại (chuyển sang số)
            $sql = "SELECT CAST({$idColumn} AS UNSIGNED) as max_id FROM {$table} ORDER BY max_id DESC LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $nextNumber = 1;
            
            if ($result && isset($result['max_id'])) {
                $nextNumber = (int)$result['max_id'] + 1;
            }
            
            return (string)$nextNumber;
            
        } catch (PDOException $e) {
            error_log("CartIdGenerator Error: " . $e->getMessage());
            // Fallback: dùng timestamp
            return (string)time();
        }
    }
}
?>

