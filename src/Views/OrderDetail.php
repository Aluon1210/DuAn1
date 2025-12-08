<?php require_once ROOT_PATH . '/src/Views/includes/header.php'; ?>

<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    background: #f5f5f5;
    font-family: 'Arial', sans-serif;
  }

  .receipt-container {
    max-width: 400px;
    margin: 20px auto;
    background: white;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }

  .receipt-header {
    text-align: center;
    margin-bottom: 20px;
    border-bottom: 1px dashed #333;
    padding-bottom: 16px;
  }

  .store-name {
    font-size: 16px;
    font-weight: 700;
    color: #000;
    margin-bottom: 4px;
  }

  .store-address {
    font-size: 11px;
    color: #666;
    line-height: 1.4;
    margin-bottom: 8px;
  }

  .store-name-vn {
    font-size: 14px;
    font-weight: 700;
    color: #000;
    margin: 8px 0 4px 0;
  }

  .order-id {
    font-size: 13px;
    color: #666;
    margin-bottom: 4px;
  }

  .receipt-info {
    font-size: 11px;
    color: #666;
    margin-bottom: 8px;
    line-height: 1.3;
  }

  .receipt-body {
    margin-bottom: 16px;
  }

  .receipt-section {
    margin-bottom: 12px;
  }

  .section-label {
    font-size: 11px;
    font-weight: 700;
    color: #333;
    margin-bottom: 6px;
  }

  .info-row {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #333;
    margin-bottom: 4px;
    padding: 2px 0;
  }

  .info-label {
    flex-grow: 1;
  }

  .info-value {
    text-align: right;
    min-width: 80px;
  }

  .items-list {
    border-top: 1px dashed #333;
    border-bottom: 1px dashed #333;
    padding: 12px 0;
    margin: 16px 0;
  }

  .item-row {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #333;
    margin-bottom: 8px;
    line-height: 1.3;
  }

  .item-name {
    flex-grow: 1;
    max-width: 60%;
  }

  .item-qty {
    text-align: right;
    min-width: 40px;
    padding: 0 8px;
  }

  .item-price {
    text-align: right;
    min-width: 60px;
  }

  .item-variant {
    font-size: 10px;
    color: #999;
    padding-left: 0;
    margin-top: 2px;
  }

  .receipt-total {
    text-align: right;
    font-size: 18px;
    font-weight: 700;
    color: #000;
    margin: 16px 0;
    padding-top: 12px;
    border-top: 1px dashed #333;
  }

  .total-amount {
    font-size: 20px;
    font-weight: 700;
  }

  .receipt-footer {
    text-align: center;
    font-size: 11px;
    color: #666;
    padding-top: 12px;
    border-top: 1px dashed #333;
    margin-top: 12px;
    line-height: 1.4;
  }

  .receipt-actions {
    text-align: center;
    margin-top: 20px;
    display: flex;
    gap: 12px;
    justify-content: center;
  }

  .receipt-actions button,
  .receipt-actions a {
    padding: 10px 20px;
    font-size: 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: 0.3s;
  }

  .btn-print {
    background: #000;
    color: white;
  }

  .btn-print:hover {
    background: #333;
  }

  .btn-back {
    background: #ddd;
    color: #333;
  }

  .btn-back:hover {
    background: #ccc;
  }

  @media print {
    body {
      background: white;
    }
    .receipt-container {
      max-width: 100%;
      margin: 0;
      box-shadow: none;
      padding: 0;
    }
    .receipt-actions {
      display: none;
    }
  }
</style>

<div class="receipt-container">
  <div class="receipt-header">
    <div class="store-name">QUÁN KHỎI</div>
    <div class="store-address">06 Tân Kỳ Tân Quý, P.15, Q. Tân Bình, HCM</div>
    <div class="store-name-vn">PHIẾU THANH TOÁN</div>
    <div class="order-id"><?php echo htmlspecialchars($order['Order_Id']); ?></div>
  </div>

  <div class="receipt-body">
    <div class="receipt-section">
      <div class="info-row">
        <span class="info-label">Khu:</span>
        <span class="info-value">A</span>
      </div>
      <div class="info-row">
        <span class="info-label">Bàn:</span>
        <span class="info-value">1</span>
      </div>
      <div class="info-row">
        <span class="info-label">Tên môn</span>
        <span class="info-value">SL</span>
      </div>
    </div>

    <div class="receipt-section" style="margin-top: 8px;">
      <div class="info-row" style="margin-bottom: 8px;">
        <span class="info-label">Giờ vào: <?php echo date('d.m.Y H:i', strtotime($order['Order_date'])); ?></span>
      </div>
      <div class="info-row">
        <span class="info-label">Giờ ra: <?php echo date('d.m.Y H:i', strtotime($order['Order_date'])); ?></span>
      </div>
    </div>
  </div>

  <div class="items-list">
    <?php foreach ($orderDetails as $detail): ?>
      <div class="item-row">
        <div class="item-name"><?php echo htmlspecialchars($detail['product_name']); ?></div>
        <div class="item-qty"><?php echo (int)$detail['quantity']; ?></div>
        <div class="item-price"><?php echo number_format($detail['Price'], 0, ',', '.'); ?></div>
      </div>
      <?php if ($detail['color_name'] || $detail['size_name']): ?>
        <div class="item-variant">
          <?php if ($detail['color_name']): ?>
            <span><?php echo htmlspecialchars($detail['color_name']); ?></span>
          <?php endif; ?>
          <?php if ($detail['size_name']): ?>
            <span><?php echo htmlspecialchars($detail['size_name']); ?></span>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <div class="receipt-total">
    <div style="margin-bottom: 4px;">Tổng cộng</div>
    <div class="total-amount"><?php 
      $totalAmount = 0;
      foreach ($orderDetails as $detail) {
        $totalAmount += $detail['quantity'] * $detail['Price'];
      }
      echo number_format($totalAmount, 0, ',', '.');
    ?></div>
  </div>

  <div class="receipt-footer">
    Chúc quý khách vui vẻ, hẹn gặp lại!
  </div>

  <div class="receipt-actions">
    <button class="btn-print" onclick="window.print()">🖨️ In</button>
    <a href="<?php echo ROOT_URL; ?>cart" class="btn-back">← Quay lại</a>
  </div>
</div>

<?php require_once ROOT_PATH . '/src/Views/includes/footer.php'; ?>
