<?php
// File: src/Views/cart/index.php
?>
<h2 style="color: #2c3e50; margin-bottom: 20px;">🛒 Giỏ Hàng Của Bạn</h2>

<?php if (!empty($cartItems)): ?>
    <div style="background-color: white; border-radius: 8px; padding: 20px; margin-top: 20px;">
        <form method="POST" action="<?php echo ROOT_URL; ?>cart/update">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #ecf0f1;">
                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #bdc3c7;">Sản phẩm</th>
                        <th style="padding: 12px; text-align: center; border-bottom: 2px solid #bdc3c7;">Giá</th>
                        <th style="padding: 12px; text-align: center; border-bottom: 2px solid #bdc3c7;">Số lượng</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #bdc3c7;">Thành tiền</th>
                        <th style="padding: 12px; text-align: center; border-bottom: 2px solid #bdc3c7;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr style="border-bottom: 1px solid #ecf0f1;">
                            <td style="padding: 12px;">
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <div style="width: 60px; height: 60px; background-color: #ecf0f1; border-radius: 4px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        <?php if (!empty($item['product']['image'])): ?>
                                            <img src="<?php echo ROOT_URL; ?>public/images/<?php echo htmlspecialchars($item['product']['image']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <span style="font-size: 30px;">👕</span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div style="font-weight: bold;"><?php echo htmlspecialchars($item['product']['name']); ?></div>
                                        <a href="<?php echo ROOT_URL; ?>product/detail/<?php echo $item['product']['id']; ?>" style="color: #3498db; text-decoration: none; font-size: 12px;">Xem chi tiết</a>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <?php echo number_format($item['product']['price'], 0, ',', '.'); ?> ₫
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <input type="number" name="quantity[<?php echo $item['product']['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['product']['quantity']; ?>" style="width: 60px; padding: 6px; border: 1px solid #bdc3c7; border-radius: 4px; text-align: center;">
                            </td>
                            <td style="padding: 12px; text-align: right; font-weight: bold; color: #e74c3c;">
                                <?php echo number_format($item['subtotal'], 0, ',', '.'); ?> ₫
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <a href="<?php echo ROOT_URL; ?>cart/remove/<?php echo $item['product']['id']; ?>" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">💾 Cập nhật giỏ</button>
                <div style="font-size: 20px; font-weight: bold; color: #e74c3c;">
                    Tổng cộng: <?php echo number_format($total, 0, ',', '.'); ?> ₫
                </div>
            </div>
        </form>

        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <a href="<?php echo ROOT_URL; ?>product" class="btn btn-primary" style="padding: 12px 20px;">← Tiếp tục mua hàng</a>
            <a href="<?php echo ROOT_URL; ?>cart/clear" class="btn btn-danger" style="padding: 12px 20px;">🗑️ Xóa tất cả</a>
            <a href="#" class="btn" style="padding: 12px 20px; background-color: #27ae60; color: white; margin-left: auto;">✓ Thanh toán</a>
        </div>
    </div>
<?php else: ?>
    <div style="background-color: white; border-radius: 8px; padding: 40px; margin-top: 20px; text-align: center;">
        <div style="font-size: 60px; margin-bottom: 20px;">🛒</div>
        <p style="color: #7f8c8d; margin-bottom: 20px;">Giỏ hàng của bạn trống</p>
        <a href="<?php echo ROOT_URL; ?>product" class="btn btn-primary" style="padding: 12px 30px;">Tiếp tục mua hàng</a>
    </div>
<?php endif; ?>

