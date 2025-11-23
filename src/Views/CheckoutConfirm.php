<?php require_once ROOT_PATH . '/src/Views/includes/header.php'; ?>

<style>
  .checkout-container { background:white; border-radius:16px; padding:40px; box-shadow: var(--shadow-soft); }
  .checkout-grid { display:grid; grid-template-columns: 2fr 1fr; gap:24px; }
  .checkout-section { background: var(--accent-gray); border-radius:12px; padding:20px; }
  .item-row { display:flex; align-items:center; justify-content:space-between; padding:12px 0; border-bottom:1px solid var(--border-light); }
  .item-row:last-child { border-bottom: none; }
  .item-info { display:flex; align-items:center; gap:12px; }
  .item-image { width:64px; height:64px; border-radius:8px; overflow:hidden; background:#eee; display:flex; align-items:center; justify-content:center; }
  .total-box { font-family:'Playfair Display', serif; font-size:28px; font-weight:700; }
</style>

<div class="checkout-container">
  <h2 style="font-family:'Playfair Display', serif; font-size:32px; margin-bottom:12px;">Xác nhận đơn hàng</h2>
  <p style="color:var(--text-light);">Kiểm tra lại sản phẩm và thông tin nhận hàng trước khi đặt</p>

  <form method="POST" action="<?php echo ROOT_URL; ?>cart/placeOrder">
    <div class="checkout-grid">
      <div class="checkout-section">
        <?php foreach ($items as $it): $p = $it['product']; ?>
          <div class="item-row">
            <div class="item-info">
              <div class="item-image">
                <?php if (!empty($p['image'])): ?>
                  <img src="<?php echo ROOT_URL; ?>public/images/<?php echo htmlspecialchars($p['image']); ?>" alt="" style="width:100%;height:100%;object-fit:cover;">
                <?php else: ?><span>✨</span><?php endif; ?>
              </div>
              <div>
                <div style="font-weight:600;"><?php echo htmlspecialchars($p['name']); ?></div>
                <div style="color:var(--text-light);">Giá: <?php echo number_format($p['price'], 0, ',', '.'); ?> ₫</div>
              </div>
            </div>
            <div style="text-align:right;">
              <div>Số lượng: <strong><?php echo (int)$it['quantity']; ?></strong></div>
              <div>Thành tiền: <strong><?php echo number_format($it['subtotal'], 0, ',', '.'); ?> ₫</strong></div>
            </div>
            <input type="hidden" name="selected[]" value="<?php echo $p['id']; ?>">
            <input type="hidden" name="quantity[<?php echo $p['id']; ?>]" value="<?php echo (int)$it['quantity']; ?>">
          </div>
        <?php endforeach; ?>
      </div>

      <div class="checkout-section">
        <div style="margin-bottom:16px;">
          <label style="font-weight:600; display:block; margin-bottom:6px;">Địa chỉ nhận hàng</label>
          <input type="text" name="address" value="<?php echo isset($user['address']) ? htmlspecialchars($user['address']) : ''; ?>" style="width:100%; padding:12px 14px; border:2px solid var(--border-light); border-radius:8px;">
        </div>
        <div style="margin-bottom:16px;">
          <label style="font-weight:600; display:block; margin-bottom:6px;">Ghi chú</label>
          <textarea name="note" rows="3" style="width:100%; padding:12px 14px; border:2px solid var(--border-light); border-radius:8px;" placeholder="Ví dụ: Giao trong giờ hành chính"></textarea>
        </div>
        <div class="total-box">Tổng: <?php echo number_format($total, 0, ',', '.'); ?> ₫</div>
        <button type="submit" class="btn btn-success" style="margin-top:16px; padding:14px 24px; width:100%;">Đặt hàng</button>
        <a href="<?php echo ROOT_URL; ?>cart" class="btn btn-primary" style="margin-top:10px; padding:12px 24px; width:100%;">Quay lại giỏ hàng</a>
      </div>
    </div>
  </form>
</div>

<?php require_once ROOT_PATH . '/src/Views/includes/footer.php'; ?>
