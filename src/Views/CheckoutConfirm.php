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
        <?php foreach ($items as $it):
          $p = $it['product']; ?>
          <div class="item-row">
            <div class="item-info">
              <div class="item-image">
                <?php if (!empty($p['image'])): ?>
                  <img
                    src="<?php echo ROOT_URL; ?>public/images/<?php echo isset($p['image']) ? htmlspecialchars($p['image']) : 'placeholder.jpg'; ?>"
                    alt="" style="width:100%;height:100%;object-fit:cover;">
                <?php else: ?><span>✨</span><?php endif; ?>
              </div>
              <div>
                <div style="font-weight:600;"><?php echo htmlspecialchars($p['name']); ?></div>
                <div style="color:var(--text-light);">Giá: <?php echo number_format($p['price'], 0, ',', '.'); ?> ₫</div>
              </div>
            </div>
            <div style="text-align:right;">
              <div>Số lượng: <strong><?php echo (int) $it['quantity']; ?></strong></div>
              <div>Thành tiền: <strong><?php echo number_format($it['subtotal'], 0, ',', '.'); ?> ₫</strong></div>
            </div>
            <?php $cartKey = $it['cart_key'] ?? ($p['id'] ?? ''); ?>
            <input type="hidden" name="selected[]" value="<?php echo htmlspecialchars($cartKey); ?>">
            <input type="hidden" name="quantity[<?php echo htmlspecialchars($cartKey); ?>]"
              value="<?php echo (int) $it['quantity']; ?>">
          </div>
        <?php endforeach; ?>
      </div>

      <div class="checkout-section">
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
                <span class="payment-method-text">💵 Thanh toán OPT (Tiền mặt)</span>
              </label>
            </div>
            <div class="payment-method">
              <label>
                <input type="radio" name="payment_method" value="online" required>
                <span class="payment-method-text">📱 Thanh toán Online (QR Code)</span>
              </label>
            </div>
          </div>

          <!-- Ghi chú: QR chỉ hiển thị sau khi bấm "Đặt hàng" -->
          <div class="qr-section" id="qrSection" style="display:none;">
            <p class="qr-label" style="margin:0;">Mã QR sẽ hiển thị sau khi bạn nhấn "Đặt hàng"</p>
          </div>
        </div>

        <div class="total-box">Tổng: <?php echo number_format($total, 0, ',', '.'); ?> ₫</div>
        <button type="submit" class="btn btn-success" id="placeOrderBtn"
          style="margin-top:16px; padding:14px 24px; width:100%;">Đặt hàng</button>
        <a href="<?php echo ROOT_URL; ?>cart" class="btn btn-primary"
          style="margin-top:10px; padding:12px 24px; width:100%;">Quay lại giỏ hàng</a>
      </div>
    </div>
  </form>
</div>

<!-- ===== PAYMENT MODAL ===== -->
<div class="payment-modal-overlay" id="paymentModal">
  <div class="payment-modal">
    <!-- Header -->
    <div class="payment-modal-header">💳 Thông tin thanh toán</div>

    <!-- Content Grid -->
    <div class="payment-modal-content">
      <!-- LEFT SIDE: Account Info -->
      <div class="payment-modal-left">
        <h3 style="margin-top:0; margin-bottom:16px; color:#333; font-size:16px;">Cách 1: Thanh toán chuyển khoản ngân
          hàng</h3>

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
          <div class="payment-modal-amount-value" id="modalAmount">3.200 đ</div>
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
        ✓ Đã Chuyển Khoản Rồi
      </button>
      <button type="button" class="payment-modal-btn payment-modal-btn-secondary" id="modalCancelBtn">
        ✕ Hủy
      </button>
    </div>

    <!-- Footer Info -->
    <div class="payment-modal-footer">
      ⏱️ Vui lòng chuyển khoản trong vòng 15 phút<br>
      💬 Nhập nội dung ở trên để xác nhận thanh toán
    </div>
  </div>
</div>

<script>
  // Payment Method Toggle Logic
  const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
  const qrSection = document.getElementById('qrSection');
  const totalAmount = <?php echo (int) $total; ?>;
  const checkoutForm = document.getElementById('checkoutForm');
  const paymentVerifiedInput = document.createElement('input');
  paymentVerifiedInput.type = 'hidden';
  paymentVerifiedInput.name = 'payment_verified';
  paymentVerifiedInput.value = '0'; // mặc định chưa xác thực online
  checkoutForm.appendChild(paymentVerifiedInput);

  // Lấy tên sản phẩm từ trang
  const productNames = [];
  <?php foreach ($items as $it): ?>
    productNames.push('<?php echo htmlspecialchars($it['product']['name']); ?>');
  <?php endforeach; ?>

  // ===== CẤU HÌNH NGÂN HÀNG =====
  // CÓ THỂ THAY ĐỔI TRỰC TIẾP DƯỚI ĐÂY
  const qrConfig = {
    bankId: 'MB',           // Mã ngân hàng (MB, ACB, BIDV, v.v.)
    accountNo: '0833268346', // Số tài khoản
    accountName: 'DUONG THANH CONG', // Tên chủ tài khoản
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

    // Không tải QR tại đây; QR chỉ hiển thị khi bấm "Đặt hàng"
    if (qrSection) {
      qrSection.style.display = selectedMethod === 'online' ? 'block' : 'none';
    }

    // Reset flag xác thực khi đổi phương thức
    paymentVerifiedInput.value = selectedMethod === 'online' ? '0' : '1';
  }

  // Handle form submission - Show payment modal
  checkoutForm.addEventListener('submit', async function (e) {
    const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;

    // Nếu chọn thanh toán online, hiển thị payment modal, chặn submit ngay
    if (selectedMethod === 'online') {
      e.preventDefault();
      paymentVerifiedInput.value = '0';
      showPaymentModal();
    }
    // Nếu chọn OPT, cho phép submit bình thường
  });

  // ===== PAYMENT MODAL FUNCTIONS =====
  const paymentModalOverlay = document.getElementById('paymentModal');
  const modalCheckPaymentBtn = document.getElementById('modalCheckPaymentBtn');
  const modalCancelBtn = document.getElementById('modalCancelBtn');
  const modalStatus = document.getElementById('modalStatus');
  const modalStatusText = document.getElementById('modalStatusText');

  // Variables để quản lý polling
  let paymentCheckInterval = null;
  let currentOrderId = null;

  // Hiển thị payment modal
  function showPaymentModal() {
    // Lấy tên người nhận
    const fullName = '<?php echo isset($user['name']) ? htmlspecialchars($user['name']) : (isset($user['username']) ? htmlspecialchars($user['username']) : 'KHACH HANG'); ?>';

    // Tạo description
    let description = 'Thanh toan - ' + fullName;
    if (productNames.length > 0) {
      if (productNames.length === 1) {
        description += ' - ' + productNames[0];
      } else {
        description += ' - ' + productNames[0] + ' (+' + (productNames.length - 1) + ')';
      }
    }

    // Cập nhật thông tin modal
    document.getElementById('modalBankName').textContent = 'MB Bank';
    document.getElementById('modalAccountNo').textContent = qrConfig.accountNo;
    document.getElementById('modalAccountName').textContent = qrConfig.accountName;
    document.getElementById('modalDescription').textContent = description;
    document.getElementById('modalAmount').textContent = formatCurrency(totalAmount) + ' đ';

    // Cập nhật QR
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
    modalCheckPaymentBtn.textContent = '✓ Đã Chuyển Khoản Rồi';

    // Vô hiệu hóa nút "Đặt hàng"
    document.getElementById('placeOrderBtn').disabled = true;
    document.getElementById('placeOrderBtn').style.opacity = '0.5';
    document.getElementById('placeOrderBtn').style.cursor = 'not-allowed';

    // Tạo order ID duy nhất cho session này
    currentOrderId = 'ORD_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

    // Hiển thị modal
    paymentModalOverlay.classList.add('active');

    // Bắt đầu polling kiểm tra thanh toán
    startPaymentPolling(description);
  }

  // Ẩn payment modal
  function hidePaymentModal() {
    paymentModalOverlay.classList.remove('active');

    // Dừng polling
    if (paymentCheckInterval) {
      clearInterval(paymentCheckInterval);
      paymentCheckInterval = null;
    }

    // Bật lại nút "Đặt hàng"
    document.getElementById('placeOrderBtn').disabled = false;
    document.getElementById('placeOrderBtn').style.opacity = '1';
    document.getElementById('placeOrderBtn').style.cursor = 'pointer';
  }

  // Bắt đầu polling kiểm tra thanh toán (Chạy liên tục)
  function startPaymentPolling(description) {
    // Kiểm tra mỗi 2 giây
    paymentCheckInterval = setInterval(async () => {
      try {
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

        // Try to parse JSON; if not JSON, ignore this round and continue polling
        let result = null;
        const contentType = response.headers.get('content-type') || '';
        if (contentType.indexOf('application/json') !== -1) {
          try {
            result = await response.json();
          } catch (err) {
            console.warn('Invalid JSON from check-payment:', err);
            // continue polling
            return;
          }
        } else {
          // non-json response (could be HTML or text), log and continue polling
          const txt = await response.text();
          console.warn('Non-JSON response from check-payment:', txt.slice(0, 300));
          return;
        }

        if (result && result.success) {
          // Thanh toán thành công
          clearInterval(paymentCheckInterval);
          paymentCheckInterval = null;

          modalStatus.className = 'payment-modal-status success';
          modalStatusText.innerHTML = '✓ Thanh toán thành công!<br>Đang tạo đơn hàng...';
          modalCheckPaymentBtn.disabled = true;

          // Chờ 1.5 giây rồi submit form để tạo đơn hàng
          setTimeout(() => {
            paymentVerifiedInput.value = '1';
            hidePaymentModal();
            // Submit form
            checkoutForm.submit();
          }, 1500);
        }
      } catch (error) {
        console.error('Lỗi trong polling:', error);
      }
    }, 2000);
  }

  // Tạo QR URL
  function generateQRUrl() {
    const bankId = qrConfig.bankId;
    const accountNo = qrConfig.accountNo;
    const accountName = qrConfig.accountName;
    const template = qrConfig.template;

    const fullName = '<?php echo isset($user['name']) ? htmlspecialchars($user['name']) : (isset($user['username']) ? htmlspecialchars($user['username']) : 'KHACH HANG'); ?>';
    let description = 'Thanh toan - ' + fullName;
    if (productNames.length > 0) {
      if (productNames.length === 1) {
        description += ' - ' + productNames[0];
      } else {
        description += ' - ' + productNames[0] + ' (+' + (productNames.length - 1) + ')';
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

  // Gọi API kiểm tra thanh toán (Khi nhấn nút "Đã Chuyển Khoản Rồi")
  async function checkPayment() {
    const fullName = '<?php echo isset($user['name']) ? htmlspecialchars($user['name']) : (isset($user['username']) ? htmlspecialchars($user['username']) : 'KHACH HANG'); ?>';
    let description = 'Thanh toan - ' + fullName;
    if (productNames.length > 0) {
      if (productNames.length === 1) {
        description += ' - ' + productNames[0];
      } else {
        description += ' - ' + productNames[0] + ' (+' + (productNames.length - 1) + ')';
      }
    }

    // Hiển thị status
    modalStatus.style.display = 'block';
    modalStatus.className = 'payment-modal-status pending';
    modalStatusText.innerHTML = '<span class="payment-modal-spinner"></span> <span>Đang kiểm tra thanh toán...</span>';
    modalCheckPaymentBtn.disabled = true;

    try {
      // Gọi API để xác nhận thanh toán
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
          console.warn('Invalid JSON from check-payment:', err);
          modalStatus.className = 'payment-modal-status failed';
          modalStatusText.textContent = '✕ Lỗi phản hồi API. Vui lòng thử lại.';
          modalCheckPaymentBtn.disabled = false;
          modalCheckPaymentBtn.textContent = '↻ Thử Lại';
          return;
        }
      } else {
        // Show non-JSON response to user
        const txt = await response.text();
        modalStatus.className = 'payment-modal-status failed';
        modalStatusText.textContent = '✕ Lỗi kết nối. Phản hồi API không hợp lệ.';
        console.error('Non-JSON response from check-payment. Status:', response.status, 'Content-Type:', contentType);
        console.error('Response text:', txt.slice(0, 500));
        modalCheckPaymentBtn.disabled = false;
        modalCheckPaymentBtn.textContent = '↻ Thử Lại';
        return;
      }

      if (result && result.success) {
        // Thanh toán thành công
        modalStatus.className = 'payment-modal-status success';
        modalStatusText.innerHTML = '✓ Thanh toán thành công!<br>Đang tạo đơn hàng...';
        modalCheckPaymentBtn.disabled = true;

        // Dừng polling
        if (paymentCheckInterval) {
          clearInterval(paymentCheckInterval);
          paymentCheckInterval = null;
        }

        // Chờ 1.5 giây rồi submit form để tạo đơn hàng
        setTimeout(() => {
          paymentVerifiedInput.value = '1';
          hidePaymentModal();
          // Submit form
          checkoutForm.submit();
        }, 1500);
      } else {
        // Thanh toán thất bại
        modalStatus.className = 'payment-modal-status failed';
        modalStatusText.textContent = '✕ ' + (result.message || 'Thanh toán thất bại. Vui lòng thử lại.');
        modalCheckPaymentBtn.disabled = false;
        modalCheckPaymentBtn.textContent = '↻ Thử Lại';
      }
    } catch (error) {
      console.error('Lỗi kiểm tra thanh toán:', error);
      modalStatus.className = 'payment-modal-status failed';
      modalStatusText.textContent = '✕ Lỗi kết nối. Vui lòng thử lại.';
      modalCheckPaymentBtn.disabled = false;
      modalCheckPaymentBtn.textContent = '↻ Thử Lại';
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

<?php require_once ROOT_PATH . '/src/Views/includes/footer.php'; ?>