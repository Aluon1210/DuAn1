<?php require_once ROOT_PATH . '/src/Views/includes/header.php'; ?>

<style>
  .checkout-container {
    background: white;
    border-radius: 16px;
    padding: 40px;
    box-shadow: var(--shadow-soft);
  }

  .checkout-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
  }

  .checkout-section {
    background: var(--accent-gray);
    border-radius: 12px;
    padding: 20px;
  }

  .item-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid var(--border-light);
  }

  .item-row:last-child {
    border-bottom: none;
  }

  .item-info {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .item-image {
    width: 64px;
    height: 64px;
    border-radius: 8px;
    overflow: hidden;
    background: #eee;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .total-box {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    font-weight: 700;
  }

  /* Payment Method Styles */
  .payment-methods {
    margin-bottom: 20px;
  }

  .payment-method {
    margin-bottom: 12px;
  }

  .payment-method label {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 12px;
    border: 2px solid var(--border-light);
    border-radius: 8px;
    transition: all 0.3s ease;
  }

  .payment-method label:hover {
    border-color: var(--primary);
    background: rgba(0, 0, 0, 0.02);
  }

  .payment-method input[type="radio"] {
    margin-right: 12px;
    cursor: pointer;
    width: 18px;
    height: 18px;
  }

  .payment-method-text {
    font-weight: 500;
  }

  /* QR Code Styles */
  .qr-section {
    display: none;
    margin-top: 20px;
    padding: 20px;
    background: white;
    border: 2px solid #f0f0f0;
    border-radius: 8px;
    text-align: center;
  }

  .qr-section.active {
    display: block;
    animation: slideDown 0.3s ease;
  }

  .qr-image {
    width: 200px;
    height: 200px;
    margin: 0 auto 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 5px;
    background: white;
  }

  .qr-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .qr-label {
    color: var(--text-light);
    font-size: 12px;
  }

  /* Payment Modal Styles */
  .payment-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
  }

  .payment-modal-overlay.active {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .payment-modal {
    background: white;
    border-radius: 16px;
    padding: 40px;
    max-width: 900px;
    width: 90%;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.4s ease;
  }

  .payment-modal-header {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 30px;
    color: #333;
    text-align: center;
  }

  .payment-modal-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-bottom: 30px;
  }

  .payment-modal-left {}

  .payment-modal-right {
    text-align: center;
  }

  .payment-modal-info {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
  }

  .payment-modal-info-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #ddd;
  }

  .payment-modal-info-row:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
  }

  .payment-modal-info-label {
    color: #666;
    font-size: 13px;
    font-weight: 500;
  }

  .payment-modal-info-value {
    font-weight: 700;
    color: #333;
    word-break: break-all;
    text-align: right;
  }

  .payment-modal-amount {
    background: #fff3cd;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
  }

  .payment-modal-amount-label {
    color: #666;
    font-size: 12px;
    margin-bottom: 8px;
  }

  .payment-modal-amount-value {
    font-size: 32px;
    font-weight: bold;
    color: #d39e00;
  }

  .payment-modal-qr {}

  .payment-modal-qr p {
    color: #666;
    font-size: 14px;
    margin-bottom: 12px;
    font-weight: 600;
  }

  .payment-modal-qr img {
    max-width: 260px;
    width: 100%;
    border: 2px solid #ddd;
    border-radius: 8px;
    padding: 8px;
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  .payment-modal-status {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: 600;
    grid-column: 1 / -1;
  }

  .payment-modal-status.pending {
    background: #e3f2fd;
    color: #1976d2;
  }

  .payment-modal-status.success {
    background: #e8f5e9;
    color: #388e3c;
  }

  .payment-modal-status.failed {
    background: #ffebee;
    color: #d32f2f;
  }

  .payment-modal-buttons {
    display: flex;
    gap: 12px;
    margin-top: 20px;
  }

  .payment-modal-btn {
    flex: 1;
    padding: 14px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 16px;
  }

  .payment-modal-btn-primary {
    background: #ff6b6b;
    color: white;
  }

  .payment-modal-btn-primary:hover {
    background: #ee5a52;
  }

  .payment-modal-btn-secondary {
    background: #6c757d;
    color: white;
  }

  .payment-modal-btn-secondary:hover {
    background: #545b62;
  }

  .payment-modal-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  .payment-modal-footer {
    padding-top: 20px;
    border-top: 1px solid #ddd;
    color: #666;
    font-size: 12px;
    text-align: center;
    line-height: 1.6;
  }

  .payment-modal-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 10px;
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }

  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>

<div class="checkout-container">
  <h2 style="font-family:'Playfair Display', serif; font-size:32px; margin-bottom:12px;">Xác nhận đơn hàng</h2>
  <p style="color:var(--text-light);">Kiểm tra lại sản phẩm và thông tin nhận hàng trước khi đặt</p>

  <form id="checkoutForm" method="POST" action="<?php echo ROOT_URL; ?>cart/placeOrder">
    <div class="checkout-grid">
      <div class="checkout-section">
        <?php
          $orderSubtotal = isset($total) ? (int)$total : 0;
          $serviceFee = (int)round($orderSubtotal * 0.05);
          $shippingFee = 50000;
          foreach ($items as $it):
          $p = $it['product']; ?>
          <div class="item-row">
            <div class="item-info">
              <div class="item-image">
                <?php if (!empty($p['image'])): ?>
                  <img
                    src="<?php echo ROOT_URL; ?>public/images/<?php echo isset($p['image']) ? htmlspecialchars($p['image']) : 'placeholder.jpg'; ?>"
                    alt="" style="width:100%;height:100%;object-fit:cover;">
                <?php else: ?><span>*</span><?php endif; ?>
              </div>
              <div>
                <div style="font-weight:600;"><?php echo htmlspecialchars($p['name']); ?></div>
                <div style="color:var(--text-light);">Giá: <?php echo number_format($p['price'], 0, ',', '.'); ?> ₫</div>
              </div>
            </div>
            <div style="text-align:right;">
              <div>Số lượng: <strong><?php echo (int) $it['quantity']; ?></strong></div>
              <?php
                $base = (int)$it['subtotal'];
                $weight = $orderSubtotal > 0 ? ($base / $orderSubtotal) : 1;
                $allocService = (int)round($serviceFee * $weight);
                $allocShip = (int)round($shippingFee * $weight);
                $lineTotal = $base + $allocService + $allocShip;
              ?>
              <div>Thành tiền: <strong><?php echo number_format($lineTotal, 0, ',', '.'); ?> ₫</strong></div>
            </div>
            <?php $cartKey = $it['cart_key'] ?? ($p['id'] ?? ''); ?>
            <input type="hidden" name="selected[]" value="<?php echo htmlspecialchars($cartKey); ?>">
            <input type="hidden" name="quantity[<?php echo htmlspecialchars($cartKey); ?>]"
              value="<?php echo (int) $it['quantity']; ?>">
          </div>
        <?php endforeach; ?>

        <?php /* Khối tính tiền và voucher được chuyển xuống dưới grid */ ?>
      </div>

      <div class="checkout-section">
        <div style="margin-bottom:16px;">
          <label style="font-weight:600; display:block; margin-bottom:6px;">Tên người nhận <span style="color:red;">*</span></label>
          <input type="text" name="recipient_name"
            value="<?php echo isset($user['name']) ? htmlspecialchars($user['name']) : (isset($user['username']) ? htmlspecialchars($user['username']) : ''); ?>" required
            placeholder="Nhập tên người nhận"
            style="width:100%; padding:12px 14px; border:2px solid var(--border-light); border-radius:8px;">
        </div>
        <div style="margin-bottom:16px;">
          <label style="font-weight:600; display:block; margin-bottom:6px;">Số điện thoại <span style="color:red;">*</span></label>
          <input type="tel" name="recipient_phone"
            value="<?php echo isset($user['phone']) ? htmlspecialchars($user['phone']) : ''; ?>" required
            placeholder="Nhập số điện thoại người nhận"
            style="width:100%; padding:12px 14px; border:2px solid var(--border-light); border-radius:8px;">
        </div>
        <div style="margin-bottom:16px;">
          <label style="font-weight:600; display:block; margin-bottom:6px;">Địa chỉ nhận hàng <span
              style="color:red;">*</span></label>
          <input type="text" name="address"
            value="<?php echo isset($user['address']) ? htmlspecialchars($user['address']) : ''; ?>" required
            placeholder="Nhập địa chỉ nhận hàng"
            style="width:100%; padding:12px 14px; border:2px solid var(--border-light); border-radius:8px;">
        </div>
        <div style="margin-bottom:16px;">
          <label style="font-weight:600; display:block; margin-bottom:6px;">Ghi chú</label>
          <textarea name="note" rows="3"
            style="width:100%; padding:12px 14px; border:2px solid var(--border-light); border-radius:8px;"
            placeholder="Ví dụ: Giao trong giờ hành chính"></textarea>
        </div>

        <!-- Payment Method Selection -->
        <div style="margin-bottom:16px;">
          <label style="font-weight:600; display:block; margin-bottom:12px;">Phương thức thanh toán <span
              style="color:red;">*</span></label>
          <div class="payment-methods">
            <div class="payment-method">
              <label>
                <input type="radio" name="payment_method" value="opt" checked required>
                <span class="payment-method-text">Thanh toán OPT (Tiền mặt)</span>
              </label>
            </div>
            <div class="payment-method">
              <label>
                <input type="radio" name="payment_method" value="online" required>
                <span class="payment-method-text">Thanh toán Online (QR Code)</span>
              </label>
            </div>
          </div>

          <!-- Ghi chú: QR chỉ hiển thị sau khi bấm "Đặt hàng" -->
          <div class="qr-section" id="qrSection" style="display:none;">
            <p class="qr-label" style="margin:0;">Mã QR sẽ hiển thị sau khi bạn nhấn "Đặt hàng"</p>
          </div>
        </div>

        <button type="submit" class="btn btn-success" id="placeOrderBtn"
          style="margin-top:16px; padding:14px 24px; width:100%;">Đặt hàng</button>
        <a href="<?php echo ROOT_URL; ?>cart" class="btn btn-primary"
          style="margin-top:10px; padding:12px 24px; width:100%;">Quay lại giỏ hàng</a>
      </div>
    </div>

    <?php
      $vatAmount = (int)round($orderSubtotal * 0.05);
      $voucherDiscount = 0;
      $grandTotal = $orderSubtotal + $vatAmount + $shippingFee - $voucherDiscount;
    ?>

    <div class="checkout-section" style="grid-column: 1 / -1; margin-top: 16px;">
      <div style="margin-top:0;">
        <label style="font-weight:600; display:block; margin-bottom:6px;">Mã Voucher</label>
        <div style="display:flex; gap:8px;">
          <input type="text" id="voucherCodeInput" placeholder="Nhập mã voucher" style="flex:1; padding:10px 12px; border:2px solid var(--border-light); border-radius:8px;">
          <button type="button" id="applyVoucherBtn" class="btn btn-primary" style="padding:10px 16px;">Áp dụng</button>
        </div>
        <div id="voucherStatus" style="margin-top:8px; font-size:13px; color:#666;"></div>
        <input type="hidden" name="voucher_code" id="voucherCodeHidden" value="">
        <input type="hidden" name="voucher_discount" id="voucherDiscountHidden" value="0">
      </div>

      <div style="background:#fff; border:1px solid var(--border-light); border-radius:8px; padding:14px; margin-top:10px;">
        <div style="display:flex; justify-content:space-between; margin-bottom:8px; color:#555;">
          <span>Tổng tiền hàng</span><span id="orderSubtotalValue"><?php echo number_format($orderSubtotal, 0, ',', '.'); ?> ₫</span>
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom:8px; color:#555;">
          <span>VAT (5%)</span><span id="vatValue"><?php echo number_format($vatAmount, 0, ',', '.'); ?> ₫</span>
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom:8px; color:#555;">
          <span>Tổng tiền phí vận chuyển</span><span id="shippingFeeValue"><?php echo number_format($shippingFee, 0, ',', '.'); ?> ₫</span>
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom:10px; color:#555;">
          <span>Tổng cộng Voucher giảm giá</span><span id="voucherDiscountValue">- 0 ₫</span>
        </div>
        <div style="display:flex; justify-content:space-between; font-weight:700; font-size:18px;">
          <span>Tổng thanh toán</span><span id="grandTotalValue"><?php echo number_format($grandTotal, 0, ',', '.'); ?> ₫</span>
        </div>
      </div>
    </div>

  </form>
</div>

<!-- ===== PAYMENT MODAL ===== -->
<div class="payment-modal-overlay" id="paymentModal">
  <div class="payment-modal">
    <!-- Header -->
    <div class="payment-modal-header">Thông tin thanh toán</div>

    <!-- Content Grid -->
    <div class="payment-modal-content">
      <!-- LEFT SIDE: Account Info -->
      <div class="payment-modal-left">
        <h3 style="margin-top:0; margin-bottom:16px; color:#333; font-size:16px;">Cách 1: Thanh toán chuyển khoản ngân hàng</h3>

        <div class="payment-modal-info">
          <div class="payment-modal-info-row">
            <span class="payment-modal-info-label">Ngân hàng</span>
            <span class="payment-modal-info-value" id="modalBankName">MB Bank</span>
          </div>
          <div class="payment-modal-info-row">
            <span class="payment-modal-info-label">Số tài khoản</span>
            <span class="payment-modal-info-value" id="modalAccountNo">0833268346</span>
          </div>
          <div class="payment-modal-info-row">
            <span class="payment-modal-info-label">Tên tài khoản</span>
            <span class="payment-modal-info-value" id="modalAccountName">DUONG THANH CONG</span>
          </div>
          <div class="payment-modal-info-row">
            <span class="payment-modal-info-label">Nội dung</span>
            <span class="payment-modal-info-value" id="modalDescription">Thanh toan don hang</span>
          </div>
        </div>

        <div class="payment-modal-amount">
          <div class="payment-modal-amount-label">Số tiền cần thanh toán</div>
          <div class="payment-modal-amount-value" id="modalAmount">3.200 ₫</div>
        </div>
      </div>

      <!-- RIGHT SIDE: QR Code -->
      <div class="payment-modal-right">
        <div class="payment-modal-qr">
          <p>Cách 2: Hình thức thanh toán quét QR Code</p>
          <img id="modalQRImage" src="" alt="QR Code" style="display:none; max-width:100%;">
          <div id="qrLoadingPlaceholder"
            style="display:flex; align-items:center; justify-content:center; width:260px; height:260px; background:#f0f0f0; border-radius:8px; margin:0 auto; color:#999; font-size:14px;">
            Đang tải mã QR...
          </div>
        </div>
      </div>

      <!-- Status Message (spans both columns) -->
      <div class="payment-modal-status pending" id="modalStatus" style="display:none;">
        <span class="payment-modal-spinner"></span>
        <span id="modalStatusText">Đang kiểm tra thanh toán...</span>
      </div>
    </div>

    <!-- Buttons -->
    <div class="payment-modal-buttons">
      <button type="button" class="payment-modal-btn payment-modal-btn-primary" id="modalCheckPaymentBtn">
        Đã chuyển khoản rồi
      </button>
      <button type="button" class="payment-modal-btn payment-modal-btn-secondary" id="modalCancelBtn">
        Hủy
      </button>
    </div>

    <!-- Footer Info -->
    <div class="payment-modal-footer">
      Vui lòng chuyển khoản trong vòng 15 phút<br>
      Nhập nội dung ở trên để xác nhận thanh toán
    </div>
  </div>
</div>

<script>
  // Payment Method Toggle Logic
  const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
  const qrSection = document.getElementById('qrSection');
  let totalAmount = <?php echo (int) $grandTotal; ?>;
  const checkoutForm = document.getElementById('checkoutForm');
  const paymentVerifiedInput = document.createElement('input');
  paymentVerifiedInput.type = 'hidden';
  paymentVerifiedInput.name = 'payment_verified';
  paymentVerifiedInput.value = '0'; // máº·c Ä‘á»‹nh chÆ°a xÃ¡c thá»±c online
  checkoutForm.appendChild(paymentVerifiedInput);

  // Láº¥y tÃªn sáº£n pháº©m tá»« trang
  const productNames = [];
  <?php foreach ($items as $it): ?>
    productNames.push('<?php echo htmlspecialchars($it['product']['name']); ?>');
  <?php endforeach; ?>

  const orderItems = [];
  <?php foreach ($items as $it): ?>
    orderItems.push({
      cat: '<?php echo htmlspecialchars($it['product']['category_id'] ?? ''); ?>',
      subtotal: <?php echo (int)($it['subtotal'] ?? 0); ?>
    });
  <?php endforeach; ?>

  // ===== Cáº¤U HÃŒNH NGÃ‚N HÃ€NG =====
  // CÃ“ THá»‚ THAY Äá»”I TRá»°C TIáº¾P DÆ¯á»šI ÄÃ‚Y
  const qrConfig = {
    bankId: 'MB',           // MÃ£ ngÃ¢n hÃ ng (MB, ACB, BIDV, v.v.)
    accountNo: '0833268346', // Sá»‘ tÃ i khoáº£n
    accountName: 'DUONG THANH CONG', // TÃªn chá»§ tÃ i khoáº£n
    template: 'print'       // Template (print, compact)
  };

  // Initialize on page load
  updatePaymentDisplay();

  // Listen for payment method changes
  paymentRadios.forEach(radio => {
    radio.addEventListener('change', updatePaymentDisplay);
  });

  function updatePaymentDisplay() {
    const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;

    // KhÃ´ng táº£i QR táº¡i Ä‘Ã¢y; QR chá»‰ hiá»ƒn thá»‹ khi báº¥m "Äáº·t hÃ ng"
    if (qrSection) {
      qrSection.style.display = selectedMethod === 'online' ? 'block' : 'none';
    }

    // Reset flag xÃ¡c thá»±c khi Ä‘á»•i phÆ°Æ¡ng thá»©c
    paymentVerifiedInput.value = selectedMethod === 'online' ? '0' : '1';
  }

  let voucherList = [];
  let appliedVoucher = null;
  const orderSubtotal = <?php echo (int)$orderSubtotal; ?>;
  let voucherDiscount = 0;

  async function loadVouchers(){
    if(voucherList.length) return voucherList;
    try{
      const resp = await fetch('<?php echo ROOT_URL; ?>public/data/vouchers.json');
      const data = await resp.json();
      voucherList = Array.isArray(data) ? data : [];
    }catch(e){ voucherList = []; }
    return voucherList;
  }

  function formatNumber(n){ return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }

  function setTotals(){
    const vat = <?php echo (int)$vatAmount; ?>;
    const ship = <?php echo (int)$shippingFee; ?>;
    const grand = orderSubtotal + vat + ship - voucherDiscount;
    totalAmount = grand;
    document.getElementById('voucherDiscountValue').textContent = '- ' + formatNumber(voucherDiscount) + ' ₫';
    document.getElementById('grandTotalValue').textContent = formatNumber(grand) + ' ₫';
    const modalAmountEl = document.getElementById('modalAmount');
    if(modalAmountEl){ modalAmountEl.textContent = formatNumber(grand) + ' ₫'; }
    document.getElementById('voucherDiscountHidden').value = voucherDiscount;
  }

  function subtotalForCategories(catIds){
    if(!Array.isArray(catIds) || !catIds.length) return 0;
    const set = catIds.map(String);
    return orderItems.filter(it => set.includes(String(it.cat))).reduce((s,it)=>s+ (parseInt(it.subtotal||0,10)||0),0);
  }

  function computeDiscount(v, baseAmount){
    if(!v) return 0;
    const base = Math.max(0, parseInt(baseAmount||0,10));
    if(base <= 0) return 0;
    if(v.type === 'fixed'){
      const val = Math.max(0, parseInt(v.value||0,10));
      return Math.min(val, base);
    }
    if(v.type === 'percent'){
      const pct = Math.max(0, parseFloat(v.value||0));
      let d = Math.round((pct/100) * base);
      const maxD = v.max_discount ? parseInt(v.max_discount,10) : null;
      if(maxD && d > maxD) d = maxD;
      return Math.max(0, d);
    }
    return 0;
  }

  document.getElementById('applyVoucherBtn').addEventListener('click', async function(){
    const code = (document.getElementById('voucherCodeInput').value||'').trim();
    const statusEl = document.getElementById('voucherStatus');
    if(!code){ statusEl.textContent = 'Vui lòng nhập mã voucher'; return; }
    await loadVouchers();
    const cUpper = code.toUpperCase();
    const today = new Date();
    const found = voucherList.find(v => String(v.code||'').toUpperCase() === cUpper && v.active !== false);
    if(!found){ statusEl.textContent = 'Mã voucher không hợp lệ'; return; }
    const exp = new Date(found.expiry||found.expired||today.toISOString().slice(0,10));
    if(isNaN(exp.getTime()) || exp < today){ statusEl.textContent = 'Voucher đã hết hạn'; return; }
    const minOrder = parseInt(found.min_order||0,10);
    if(orderSubtotal < minOrder){ statusEl.textContent = 'Chưa đạt giá trị tối thiểu'; return; }
    let base = orderSubtotal;
    if((found.scope||'all') === 'category'){
      const cats = Array.isArray(found.categories) ? found.categories : [];
      base = subtotalForCategories(cats);
      if(base <= 0){ statusEl.textContent = 'Voucher không áp dụng cho danh mục trong đơn'; return; }
    }
    appliedVoucher = found;
    voucherDiscount = computeDiscount(found, base);
    document.getElementById('voucherCodeHidden').value = found.code;
    statusEl.textContent = 'Đã áp dụng: ' + found.code + ' (-' + formatNumber(voucherDiscount) + ' ₫)';
    setTotals();
  });

  // Handle form submission - Show payment modal
  checkoutForm.addEventListener('submit', async function (e) {
    const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;

    // Nếu chọn thanh toán online, hiển thị payment modal, chặn submit ngay
    if (selectedMethod === 'online') {
      e.preventDefault();
      paymentVerifiedInput.value = '0';
      showPaymentModal();
    } else {
      checkoutForm.action = '<?php echo ROOT_URL; ?>cart/placeOrderCOD';
    }
    // Nếu chọn OPT, cho phép submit bình thường
  });

  // ===== PAYMENT MODAL FUNCTIONS =====
  const paymentModalOverlay = document.getElementById('paymentModal');
  const modalCheckPaymentBtn = document.getElementById('modalCheckPaymentBtn');
  const modalCancelBtn = document.getElementById('modalCancelBtn');
  const modalStatus = document.getElementById('modalStatus');
  const modalStatusText = document.getElementById('modalStatusText');

  // Variables Ä‘á»ƒ quáº£n lÃ½ polling
  let paymentCheckInterval = null;
  let currentOrderId = null;
  let paymentAttempts = 0;
  const pollingIntervalMs = 2500;
  let isChecking = false;
  const maxAttempts = 600;
  let creatingOrder = false;

  // Hiá»ƒn thá»‹ payment modal
  function showPaymentModal() {
    // Táº¡o order ID duy nháº¥t cho session nÃ y (pháº£i táº¡o trÆ°á»›c khi táº¡o mÃ´ táº£/QR)
    currentOrderId = 'ORD' + Date.now() + '' + Math.random().toString(36).substr(2, 9);

    // Láº¥y tÃªn ngÆ°á»i nháº­n
    const fullName = '<?php echo isset($user['name']) ? htmlspecialchars($user['name']) : (isset($user['username']) ? htmlspecialchars($user['username']) : 'KHACH HANG'); ?>';

    // Táº¡o description chá»‰ chá»©a order id
    let description = currentOrderId;
    if (productNames.length > 0) {
      if (productNames.length === 1) {
        // náº¿u muá»‘n váº«n kÃ¨m tÃªn sp sau order id, bá» comment phÃ­a dÆ°á»›i
        // description += ' - ' + productNames[0];
      } else {
        // description += ' - ' + productNames[0] + ' (+' + (productNames.length - 1) + ')';
      }
    }

    // Cáº­p nháº­t thÃ´ng tin modal
    document.getElementById('modalBankName').textContent = 'MB Bank';
    document.getElementById('modalAccountNo').textContent = qrConfig.accountNo;
    document.getElementById('modalAccountName').textContent = qrConfig.accountName;
    document.getElementById('modalDescription').textContent = description;
    document.getElementById('modalAmount').textContent = formatCurrency(totalAmount) + ' ₫';

    // Cáº­p nháº­t QR
    const qrUrl = generateQRUrl();
    const qrImage = document.getElementById('modalQRImage');
    const qrPlaceholder = document.getElementById('qrLoadingPlaceholder');

    qrImage.src = qrUrl;
    qrImage.onload = function () {
      qrImage.style.display = 'block';
      qrPlaceholder.style.display = 'none';
    };
    qrImage.onerror = function () {
      qrPlaceholder.style.display = 'flex';
      qrPlaceholder.textContent = 'Lỗi tải mã QR';
    };

    // Reset status
    modalStatus.style.display = 'block';
    modalStatus.className = 'payment-modal-status pending';
    modalStatusText.innerHTML = '<span class="payment-modal-spinner"></span> <span>Đang chờ thanh toán...</span>';
    modalCheckPaymentBtn.disabled = false;
    modalCheckPaymentBtn.textContent = 'Đã chuyển khoản rồi';

    // VÃ´ hiá»‡u hÃ³a nÃºt "Äáº·t hÃ ng"
    document.getElementById('placeOrderBtn').disabled = true;
    document.getElementById('placeOrderBtn').style.opacity = '0.5';
    document.getElementById('placeOrderBtn').style.cursor = 'not-allowed';

    // order id Ä‘Ã£ Ä‘Æ°á»£c táº¡o á»Ÿ trÃªn

    // Hiá»ƒn thá»‹ modal
    paymentModalOverlay.classList.add('active');

    // Báº¯t Ä‘áº§u polling kiá»ƒm tra thanh toÃ¡n
    console.log('[Payment] Open QR modal', { orderId: currentOrderId, amount: totalAmount });
    startPaymentPolling(description);
  }

  // áº¨n payment modal
  function hidePaymentModal() {
    paymentModalOverlay.classList.remove('active');

    // Dá»«ng polling
    if (paymentCheckInterval) {
      clearInterval(paymentCheckInterval);
      paymentCheckInterval = null;
    }

    // Báº­t láº¡i nÃºt "Äáº·t hÃ ng"
    document.getElementById('placeOrderBtn').disabled = false;
    document.getElementById('placeOrderBtn').style.opacity = '1';
    document.getElementById('placeOrderBtn').style.cursor = 'pointer';
  }

  // Kiểm tra thanh toán liên tục và tạo đơn khi phát hiện giao dịch
  function startPaymentPolling(description) {
    if (paymentCheckInterval) {
      clearInterval(paymentCheckInterval);
      paymentCheckInterval = null;
    }
    paymentAttempts = 0;

    const pollOnce = async () => {
      if (creatingOrder || isChecking) return;
      isChecking = true;
      try {
        console.log('[Polling] Calling /payment/check-payment');
        const checkResp = await fetch('<?php echo ROOT_URL; ?>payment/check-payment', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            order_id: currentOrderId,
            amount: totalAmount,
            description: description,
            account_no: qrConfig.accountNo,
            bank_id: qrConfig.bankId
          })
        });

        let checkResult = null;
        const ct1 = checkResp.headers.get('content-type') || '';
        if (ct1.indexOf('application/json') !== -1) {
          checkResult = await checkResp.json();
        } else {
          // Không phải JSON, cứ tiếp tục thử lại ở lần kế tiếp
          paymentAttempts++;
          return;
        }

        if (checkResult && checkResult.success) {
          console.log('[Polling] check-payment success', checkResult);
          creatingOrder = true;
          if (paymentCheckInterval) {
            clearInterval(paymentCheckInterval);
            paymentCheckInterval = null;
          }
          modalStatus.className = 'payment-modal-status pending';
          modalStatusText.innerHTML = '<span class="payment-modal-spinner"></span> <span>Đang tạo đơn hàng...</span>';
          const addressVal = checkoutForm.querySelector('input[name="address"]').value.trim();
          const recipientName = (checkoutForm.querySelector('input[name="recipient_name"]')?.value || '').trim();
          const recipientPhone = (checkoutForm.querySelector('input[name="recipient_phone"]')?.value || '').trim();
          const noteVal = checkoutForm.querySelector('textarea[name="note"]').value.trim();

          const selectedInputs = Array.from(checkoutForm.querySelectorAll('input[name="selected[]"]'));
          const selectedIds = selectedInputs.map(i => i.value);
          const qtyInputs = Array.from(checkoutForm.querySelectorAll('input[name^="quantity["]'));
          const quantities = {};
          qtyInputs.forEach(inp => {
            const m = inp.name.match(/^quantity\[(.+)\]$/);
            if (m) { quantities[m[1]] = parseInt(inp.value || '1', 10); }
          });

          console.log('[Polling] Calling /payment/create-order-on-payment');
          const createResp = await fetch('<?php echo ROOT_URL; ?>payment/create-order-on-payment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              amount: totalAmount,
              description: currentOrderId,
              address: addressVal,
              note: noteVal,
              recipient_name: recipientName,
              recipient_phone: recipientPhone,
              voucher_code: (document.getElementById('voucherCodeHidden')?.value||'').toString().trim(),
              voucher_discount: parseInt(document.getElementById('voucherDiscountHidden')?.value||'0',10)||0,
              selected: selectedIds,
              quantities: quantities
            })
          });

          const ct2 = createResp.headers.get('content-type') || '';
          if (ct2.indexOf('application/json') !== -1) {
            const createResult = await createResp.json();
            console.log('[Polling] create-order-on-payment response', createResult);
            if (createResult && createResult.success) {
              paymentVerifiedInput.value = '1';
              clearInterval(paymentCheckInterval);
              paymentCheckInterval = null;
              modalStatus.className = 'payment-modal-status success';
              modalStatusText.innerHTML = 'Thanh toán thành công!<br>Đơn hàng đã được tạo.<br>Mã đơn: <strong>' + (createResult.order_id || '') + '</strong>';
              modalCheckPaymentBtn.disabled = true;
              setTimeout(() => {
                hidePaymentModal();
                window.location = '<?php echo ROOT_URL; ?>cart?invoice=' + encodeURIComponent(createResult.order_id || '') + '&print=1';
               }, 300);
            } else {
              // Nếu tạo đơn thất bại, tiếp tục polling để tránh tạo trùng
              modalStatus.className = 'payment-modal-status failed';
              modalStatusText.textContent = (createResult && createResult.message) ? createResult.message : 'Không thể tạo đơn hàng. Vui lòng thử lại.';
              modalCheckPaymentBtn.disabled = false;
              creatingOrder = false;
              // Khởi động lại polling sau 2 giây
              setTimeout(() => {
                if (!paymentCheckInterval) {
                  paymentCheckInterval = setInterval(pollOnce, pollingIntervalMs);
                }
              }, 2000);
            }
          } else {
            // Phản hồi không hợp lệ, tiếp tục thử lại ở lần kế tiếp
            paymentAttempts++;
            creatingOrder = false;
          }
        } else {
          console.log('[Polling] check-payment pending', checkResult);
          // Chưa phát hiện giao dịch
          modalStatus.className = 'payment-modal-status pending';
          modalStatusText.innerHTML = '<span class="payment-modal-spinner"></span> <span>' + (checkResult && checkResult.message ? checkResult.message : 'Đang chờ thanh toán...') + '</span>';
          modalCheckPaymentBtn.disabled = false;
          paymentAttempts++;
        }

        if (paymentAttempts >= maxAttempts) {
          clearInterval(paymentCheckInterval);
          paymentCheckInterval = null;
          modalStatus.className = 'payment-modal-status failed';
          modalStatusText.textContent = 'Quá thời gian chờ - Vui lòng thử lại.';
          modalCheckPaymentBtn.disabled = false;
        }
      } catch (error) {
        // Lỗi mạng, tiếp tục thử ở lần sau
        paymentAttempts++;
        if (paymentAttempts >= maxAttempts) {
          clearInterval(paymentCheckInterval);
          paymentCheckInterval = null;
          modalStatus.className = 'payment-modal-status failed';
          modalStatusText.textContent = 'Quá thời gian chờ - Vui lòng thử lại.';
          modalCheckPaymentBtn.disabled = false;
        }
      }
      isChecking = false;
    };

    // Gọi ngay một lần và sau đó lặp lại
    pollOnce();
    paymentCheckInterval = setInterval(pollOnce, pollingIntervalMs);
  }

  // Táº¡o QR URL
  function generateQRUrl() {
    const bankId = qrConfig.bankId;
    const accountNo = qrConfig.accountNo;
    const accountName = qrConfig.accountName;
    const template = qrConfig.template;
    const fullName = '<?php echo isset($user['name']) ? htmlspecialchars($user['name']) : (isset($user['username']) ? htmlspecialchars($user['username']) : 'KHACH HANG'); ?>';
    // Sá»­ dá»¥ng order id hiá»‡n táº¡i Ä‘á»ƒ lÃ m mÃ´ táº£; náº¿u chÆ°a cÃ³ thÃ¬ táº¡o táº¡m
    const orderIdForDesc = currentOrderId || ('ORD' + Date.now() + '' + Math.random().toString(36).substr(2, 9));
    let description = orderIdForDesc;
    if (productNames.length > 0) {
      if (productNames.length === 1) {
        // description += ' - ' + productNames[0];
      } else {
        // description += ' - ' + productNames[0] + ' (+' + (productNames.length - 1) + ')';
      }
    }

    const qrBaseUrl = 'https://img.vietqr.io/image/';
    const qrCode = bankId + '-' + accountNo + '-' + template + '.png';

    const params = new URLSearchParams();
    params.append('amount', totalAmount);
    params.append('addInfo', description);
    params.append('accountName', accountName);

    return qrBaseUrl + qrCode + '?' + params.toString();
  }

  // Format currency
  function formatCurrency(amount) {
    return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  // Gọi API kiểm tra thanh toán (Khi nhấn nút "Đã chuyển khoản rồi")
  async function checkPayment() {
    // Mô tả khi chủ động check: chỉ gửi order id
    const description = currentOrderId || ('ORD' + Date.now() + '' + Math.random().toString(36).substr(2, 9));
    if (productNames.length > 0) {
      if (productNames.length === 1) {
        // description += ' - ' + productNames[0];
      } else {
        // description += ' - ' + productNames[0] + ' (+' + (productNames.length - 1) + ')';
      }
    }

    // Hiển thị status
    modalStatus.style.display = 'block';
    modalStatus.className = 'payment-modal-status pending';
    modalStatusText.innerHTML = '<span class="payment-modal-spinner"></span> <span>Đang kiểm tra thanh toán...</span>';
    modalCheckPaymentBtn.disabled = true;

    try {
      // 1) Kiểm tra thanh toán
      const response = await fetch('<?php echo ROOT_URL; ?>payment/check-payment', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          order_id: currentOrderId,
          amount: totalAmount,
          description: description,
          account_no: qrConfig.accountNo,
          bank_id: qrConfig.bankId
        })
      });

      // Parse response safely
      let result = null;
      const contentType = response.headers.get('content-type') || '';
      if (contentType.indexOf('application/json') !== -1) {
        try {
          result = await response.json();
        } catch (err) {
          console.warn('Invalid JSON từ check-payment:', err);
          modalStatus.className = 'payment-modal-status failed';
          modalStatusText.textContent = 'Lỗi phản hồi API. Vui lòng thử lại.';
          modalCheckPaymentBtn.disabled = false;
          modalCheckPaymentBtn.textContent = 'Thử lại';
          return;
        }
      } else {
        // Hiển thị non-JSON response
        const txt = await response.text();
        modalStatus.className = 'payment-modal-status failed';
        modalStatusText.textContent = 'Lỗi kết nối. Phản hồi API không hợp lệ.';
        console.error('Non-JSON response from check-payment. Status:', response.status, 'Content-Type:', contentType);
        console.error('Response text:', txt.slice(0, 500));
        modalCheckPaymentBtn.disabled = false;
        modalCheckPaymentBtn.textContent = 'Thử lại';
        return;
      }

      if (result && result.success) {
        // 2) Tạo đơn hàng
        const addressVal = checkoutForm.querySelector('input[name="address"]').value.trim();
        const noteVal = checkoutForm.querySelector('textarea[name="note"]').value.trim();

        try {
          const selectedInputs = Array.from(checkoutForm.querySelectorAll('input[name="selected[]"]'));
          const selectedIds = selectedInputs.map(i => i.value);
          const qtyInputs = Array.from(checkoutForm.querySelectorAll('input[name^="quantity["]'));
          const quantities = {};
          qtyInputs.forEach(inp => {
            const m = inp.name.match(/^quantity\[(.+)\]$/);
            if (m) { quantities[m[1]] = parseInt(inp.value || '1', 10); }
          });

          const createResp = await fetch('<?php echo ROOT_URL; ?>payment/create-order-on-payment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              amount: totalAmount,
              description: currentOrderId,
              address: addressVal,
              note: noteVal,
              voucher_code: (document.getElementById('voucherCodeHidden')?.value||'').toString().trim(),
              voucher_discount: parseInt(document.getElementById('voucherDiscountHidden')?.value||'0',10)||0,
              selected: selectedIds,
              quantities: quantities
            })
          });

          const createCT = createResp.headers.get('content-type') || '';
          if (createCT.indexOf('application/json') !== -1) {
            const createResult = await createResp.json();
            if (createResult && createResult.success) {
              paymentVerifiedInput.value = '1';
              modalStatus.className = 'payment-modal-status success';
              modalStatusText.innerHTML = 'Thanh toán thành công!<br>Đơn hàng đã được tạo.<br>Mã đơn: <strong>' + (createResult.order_id || '') + '</strong>';
              modalCheckPaymentBtn.disabled = true;
              setTimeout(() => {
                hidePaymentModal();
                window.location = '<?php echo ROOT_URL; ?>cart?invoice=' + encodeURIComponent(createResult.order_id || '') + '&print=1';
              }, 300);
            } else {
              modalStatus.className = 'payment-modal-status failed';
              modalStatusText.textContent = (createResult && createResult.message) ? createResult.message : 'Không thể tạo đơn hàng. Vui lòng thử lại.';
              modalCheckPaymentBtn.disabled = false;
              modalCheckPaymentBtn.textContent = 'Thử lại';
            }
          } else {
            const txt2 = await createResp.text();
            console.warn('Non-JSON response từ create-order-on-payment:', txt2.slice(0, 500));
            modalStatus.className = 'payment-modal-status failed';
            modalStatusText.textContent = 'Phản hồi tạo đơn không hợp lệ. Vui lòng thử lại.';
            modalCheckPaymentBtn.disabled = false;
            modalCheckPaymentBtn.textContent = 'Thử lại';
          }
        } catch (errCreate) {
          console.error('Lỗi tạo đơn hàng:', errCreate);
          modalStatus.className = 'payment-modal-status failed';
          modalStatusText.textContent = 'Lỗi tạo đơn hàng. Vui lòng thử lại.';
          modalCheckPaymentBtn.disabled = false;
          modalCheckPaymentBtn.textContent = 'Thử lại';
        }
      } else {
        modalStatus.className = 'payment-modal-status failed';
        modalStatusText.textContent = (result && result.message) ? result.message : 'Thanh toán thất bại. Vui lòng thử lại.';
        modalCheckPaymentBtn.disabled = false;
        modalCheckPaymentBtn.textContent = 'Thử lại';
      }
    } catch (error) {
      console.error('Lỗi kiểm tra thanh toán:', error);
      modalStatus.className = 'payment-modal-status failed';
      modalStatusText.textContent = 'Lỗi kết nối. Vui lòng thử lại.';
      modalCheckPaymentBtn.disabled = false;
      modalCheckPaymentBtn.textContent = 'Thử lại';
    }
  }

  // Button event listeners
  modalCheckPaymentBtn.addEventListener('click', checkPayment);
  modalCancelBtn.addEventListener('click', hidePaymentModal);

  // Close modal when clicking overlay
  paymentModalOverlay.addEventListener('click', function (e) {
    if (e.target === paymentModalOverlay) {
      hidePaymentModal();
    }
  });
</script>

<style>
  .invoice-modal-overlay{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:9999}
  .invoice-modal-overlay.active{display:flex;align-items:center;justify-content:center}
  .invoice-modal{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,0.3);width:90%;max-width:960px;max-height:85vh;display:flex;flex-direction:column}
  .invoice-modal-header{display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-bottom:1px solid #eee}
  .invoice-modal-title{font-weight:700}
  .invoice-modal-close{border:none;background:#e74c3c;color:#fff;padding:8px 12px;border-radius:8px;cursor:pointer}
  .invoice-modal-body{flex:1}
  .invoice-iframe{width:100%;height:70vh;border:0}
  .invoice-modal-footer{padding:10px 16px;border-top:1px solid #eee;text-align:right}
</style>

<div id="invoiceModal" class="invoice-modal-overlay" aria-hidden="true">
  <div class="invoice-modal" role="dialog" aria-modal="true">
    <div class="invoice-modal-header">
      <div class="invoice-modal-title">Hóa đơn A4</div>
      <button class="invoice-modal-close" type="button" onclick="closeInvoiceModal()">Đóng</button>
    </div>
    <div class="invoice-modal-body">
      <iframe id="invoiceIframe" class="invoice-iframe"></iframe>
    </div>
    <div class="invoice-modal-footer">
      <button id="invoicePrintBtn" class="btn btn-primary" type="button">In hóa đơn</button>
    </div>
  </div>
 </div>

<script>
  function openInvoiceModal(orderId){
    var url = '<?php echo ROOT_URL; ?>cart/invoice/' + encodeURIComponent(orderId);
    var m = document.getElementById('invoiceModal');
    var f = document.getElementById('invoiceIframe');
    var btn = document.getElementById('invoicePrintBtn');
    f.src = url; m.classList.add('active');
    if(btn){ btn.onclick = function(){ try{ f.contentWindow.focus(); f.contentWindow.print(); }catch(e){} }; }
  }
  function closeInvoiceModal(){
    var m = document.getElementById('invoiceModal');
    var f = document.getElementById('invoiceIframe');
    f.src = 'about:blank'; m.classList.remove('active');
  }
</script>

<?php require_once ROOT_PATH . '/src/Views/includes/footer.php'; ?>

