<?php require_once ROOT_PATH . '/src/Views/includes/header.php'; ?>

<style>
    .cart-header {
        margin-bottom: 40px;
    }

    .cart-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 42px;
        font-weight: 700;
        margin-bottom: 12px;
        letter-spacing: 1px;
    }

    .cart-header p {
        color: var(--text-light);
        font-size: 16px;
    }

    .cart-table-container {
        background: white;
        border-radius: 16px;
        padding: 40px;
        box-shadow: var(--shadow-soft);
        margin-top: 30px;
    }

    .cart-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .cart-table thead tr {
        background: linear-gradient(135deg, var(--primary-black) 0%, #2c2c2c 100%);
        color: white;
    }

    .cart-table thead th {
        padding: 20px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 3px solid var(--primary-gold);
    }

    .cart-table thead th:nth-child(2),
    .cart-table thead th:nth-child(3),
    .cart-table thead th:nth-child(4),
    .cart-table thead th:nth-child(5) {
        text-align: center;
    }

    .cart-table tbody tr {
        border-bottom: 1px solid var(--border-light);
        transition: var(--transition-smooth);
    }

    .cart-table tbody tr:hover {
        background: var(--accent-gray);
    }

    .cart-table tbody td {
        padding: 24px 20px;
        vertical-align: middle;
    }

    .cart-item-image {
        width: 100px;
        height: 100px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--accent-gray);
    }

    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-item-info {
        padding-left: 20px;
    }

    .cart-item-name {
        font-weight: 600;
        font-size: 16px;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .cart-item-link {
        color: var(--primary-gold);
        text-decoration: none;
        font-size: 13px;
        transition: var(--transition-smooth);
    }

    .cart-item-link:hover {
        color: var(--primary-black);
        text-decoration: underline;
    }

    .cart-quantity-input {
        width: 80px;
        padding: 10px 14px;
        border: 2px solid var(--border-light);
        border-radius: 30px;
        text-align: center;
        font-weight: 600;
        transition: var(--transition-smooth);
    }

    .cart-quantity-input:focus {
        outline: none;
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    .cart-price {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-black);
        font-family: 'Playfair Display', serif;
    }

    .cart-subtotal {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary-black);
        font-family: 'Playfair Display', serif;
    }

    .cart-actions {
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid var(--border-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .cart-total {
        text-align: right;
    }

    .cart-total-label {
        font-size: 14px;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .cart-total-amount {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 700;
        color: var(--primary-black);
        letter-spacing: 1px;
    }

    .cart-total-amount::after {
        content: ' ₫';
        font-size: 24px;
        color: var(--primary-gold);
    }

    .cart-buttons {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .cart-empty {
        background: white;
        border-radius: 16px;
        padding: 80px 40px;
        text-align: center;
        box-shadow: var(--shadow-soft);
        margin-top: 30px;
    }

    .cart-empty-icon {
        font-size: 120px;
        margin-bottom: 30px;
        opacity: 0.3;
    }

    .cart-empty-title {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 16px;
    }

    .cart-empty-text {
        color: var(--text-light);
        font-size: 16px;
        margin-bottom: 40px;
    }
</style>

<div class="cart-header">
    <h2>🛒 Giỏ Hàng Của Bạn</h2>
    <p><?php echo !empty($cartItems) ? count($cartItems) . ' sản phẩm trong giỏ hàng' : 'Giỏ hàng trống'; ?></p>
</div>

<?php if (!empty($cartItems)): ?>
    <div class="cart-table-container">
        <form method="POST" action="<?php echo ROOT_URL; ?>cart/update">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <div class="cart-item-image">
                                        <?php if (!empty($item['product']['image'])): ?>
                                            <img src="<?php echo ROOT_URL; ?>public/images/<?php echo htmlspecialchars($item['product']['image']); ?>" alt="<?php echo htmlspecialchars($item['product']['name']); ?>">
                                        <?php else: ?>
                                            <span style="font-size: 50px; opacity: 0.3;">✨</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="cart-item-info">
                                        <div class="cart-item-name"><?php echo htmlspecialchars($item['product']['name']); ?></div>
                                        <a href="<?php echo ROOT_URL; ?>product/detail/<?php echo $item['product']['id']; ?>" class="cart-item-link">
                                            Xem chi tiết →
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <div class="cart-price"><?php echo number_format($item['product']['price'], 0, ',', '.'); ?> ₫</div>
                            </td>
                            <td style="text-align: center;">
                                <input type="number" 
                                       name="quantity[<?php echo $item['product']['id']; ?>]" 
                                       value="<?php echo $item['quantity']; ?>" 
                                       min="1" 
                                       max="<?php echo $item['product']['quantity']; ?>" 
                                       class="cart-quantity-input" 
                                       required>
                            </td>
                            <td style="text-align: right;">
                                <div class="cart-subtotal"><?php echo number_format($item['subtotal'], 0, ',', '.'); ?> ₫</div>
                            </td>
                            <td style="text-align: center;">
                                <a href="<?php echo ROOT_URL; ?>cart/remove/<?php echo $item['product']['id']; ?>" 
                                   class="btn btn-danger" 
                                   style="padding: 10px 20px; font-size: 13px;">
                                    Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-actions">
                <div class="cart-buttons">
                    <button type="submit" class="btn btn-primary" style="padding: 14px 30px;">
                        💾 Cập nhật giỏ hàng
                    </button>
                    <a href="<?php echo ROOT_URL; ?>product" class="btn btn-primary" style="padding: 14px 30px;">
                        ← Tiếp tục mua hàng
                    </a>
                    <a href="<?php echo ROOT_URL; ?>cart/clear" class="btn btn-danger" style="padding: 14px 30px;">
                        🗑️ Xóa tất cả
                    </a>
                </div>
                <div class="cart-total">
                    <div class="cart-total-label">Tổng cộng</div>
                    <div class="cart-total-amount"><?php echo number_format($total, 0, ',', '.'); ?></div>
                </div>
            </div>
        </form>

        <div style="margin-top: 30px; padding-top: 30px; border-top: 2px solid var(--border-light); text-align: center;">
            <a href="#" class="btn btn-success" style="padding: 18px 60px; font-size: 18px; text-transform: uppercase; letter-spacing: 1.5px;">
                ✓ Tiến hành thanh toán
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="cart-empty">
        <div class="cart-empty-icon">🛒</div>
        <h3 class="cart-empty-title">Giỏ hàng của bạn trống</h3>
        <p class="cart-empty-text">Hãy khám phá bộ sưu tập của chúng tôi và thêm các sản phẩm yêu thích vào giỏ hàng</p>
        <a href="<?php echo ROOT_URL; ?>product" class="btn btn-success" style="padding: 16px 40px; font-size: 16px; text-transform: uppercase; letter-spacing: 1.5px;">
            🛍️ Khám phá ngay
        </a>
    </div>
<?php endif; ?>

<?php require_once ROOT_PATH . '/src/Views/includes/footer.php'; ?>

