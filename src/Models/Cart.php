<?php
// File: src/Models/Cart.php
namespace Models;

use Core\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $primaryKey = '_Cart_Id';

    /**
     * Thêm sản phẩm vào giỏ hàng DB (dựa vào Variant_Id)
     */
    public function addToCart($userId, $productId, $variantId, $quantity)
    {
        // Nếu không có variant, không thể thêm vào giỏ
        if ($variantId <= 0) {
            throw new \Exception("Không thể thêm sản phẩm không có biến thể vào giỏ hàng");
        }

        // Kiểm tra đã có sản phẩm này trong giỏ chưa (dựa vào Variant_Id)
        $sql = "SELECT * FROM {$this->table} WHERE _UserName_Id = :user_id AND Variant_Id = :variant_id";
        $row = $this->query($sql, [
            'user_id' => $userId,
            'variant_id' => $variantId
        ]);
        if ($row && isset($row[0])) {
            // Nếu đã có thì cập nhật số lượng
            $newQty = $row[0]['Quantity'] + $quantity;
            $updateSql = "UPDATE {$this->table} SET Quantity = :quantity, Update_at = NOW() WHERE _Cart_Id = :id";
            $this->db->prepare($updateSql)->execute([
                'quantity' => $newQty,
                'id' => $row[0]['_Cart_Id']
            ]);
        } else {
            // Nếu chưa có thì thêm mới
            $cartId = \Core\IdGenerator::generateCartId('cart', '_Cart_Id');
            $insertSql = "INSERT INTO {$this->table} (_Cart_Id, _UserName_Id, Variant_Id, Quantity, Update_at) VALUES (:cart_id, :user_id, :variant_id, :quantity, NOW())";
            $this->db->prepare($insertSql)->execute([
                'cart_id' => $cartId,
                'user_id' => $userId,
                'variant_id' => $variantId,
                'quantity' => $quantity
            ]);
        }
    }

    /**
     * Lấy tất cả sản phẩm trong giỏ của một user
     */
    public function getCartByUserId($userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE _UserName_Id = :user_id ORDER BY Update_at DESC";
        return $this->query($sql, ['user_id' => $userId]) ?? [];
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ
     */
    public function updateCartQuantity($cartId, $quantity)
    {
        if ($quantity <= 0) {
            return $this->deleteCart($cartId);
        }
        $sql = "UPDATE {$this->table} SET Quantity = :quantity, Update_at = NOW() WHERE _Cart_Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'quantity' => $quantity,
            'id' => $cartId
        ]);
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function deleteCart($cartId)
    {
        $sql = "DELETE FROM {$this->table} WHERE _Cart_Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $cartId]);
    }

    /**
     * Xóa toàn bộ giỏ hàng của user
     */
    public function clearCart($userId)
    {
        $sql = "DELETE FROM {$this->table} WHERE _UserName_Id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['user_id' => $userId]);
    }
}


