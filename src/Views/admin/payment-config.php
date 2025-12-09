<?php 
// File: src/Views/admin/payment-config.php
require_once ROOT_PATH . '/src/Views/includes/header.php';

use Core\PaymentHelper;

// Kiểm tra quyền admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo '<div style="padding: 20px; color: red;">Bạn không có quyền truy cập trang này</div>';
    exit;
}

$currentConfig = PaymentHelper::getQRConfig();
$bankCodes = PaymentHelper::getAllBankCodes();
?>

<style>
  .payment-config-container {
    background: white;
    border-radius: 12px;
    padding: 30px;
    max-width: 600px;
    margin: 20px auto;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }
  
  .config-header {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333;
  }
  
  .config-subtitle {
    color: #666;
    margin-bottom: 25px;
    font-size: 14px;
  }
  
  .form-group {
    margin-bottom: 20px;
  }
  
  .form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
  }
  
  .form-group input,
  .form-group select {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
    transition: border-color 0.3s;
  }
  
  .form-group input:focus,
  .form-group select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
  }
  
  .form-group .help-text {
    font-size: 12px;
    color: #999;
    margin-top: 5px;
  }
  
  .form-group small {
    display: block;
    margin-top: 5px;
    color: #999;
    font-size: 12px;
  }
  
  .button-group {
    display: flex;
    gap: 10px;
    margin-top: 30px;
  }
  
  .btn {
    flex: 1;
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
  }
  
  .btn-primary {
    background: #007bff;
    color: white;
  }
  
  .btn-primary:hover {
    background: #0056b3;
  }
  
  .btn-secondary {
    background: #6c757d;
    color: white;
  }
  
  .btn-secondary:hover {
    background: #545b62;
  }
  
  .alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
    font-size: 14px;
  }
  
  .alert-success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
  }
  
  .alert-error {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
  }
  
  .info-box {
    background: #e7f3ff;
    border-left: 4px solid #007bff;
    padding: 15px;
    margin: 20px 0;
    border-radius: 4px;
    font-size: 13px;
    color: #004085;
  }
  
  .qr-preview {
    margin-top: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 6px;
    text-align: center;
  }
  
  .qr-preview h4 {
    margin-top: 0;
    color: #333;
  }
  
  .qr-preview img {
    max-width: 250px;
    margin-top: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    background: white;
  }
</style>

<div class="payment-config-container">
  <div class="config-header">⚙️ Cấu Hình Thanh Toán QR</div>
  <p class="config-subtitle">Quản lý thông tin thanh toán VietQR Code</p>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <div class="info-box">
    📌 <strong>Thông tin VietQR:</strong> Sử dụng VietQR để tạo mã QR thanh toán ngân hàng. 
    Mã QR sẽ được sinh tự động dựa trên thông tin bạn cấu hình ở đây.
  </div>

  <form method="POST" action="<?php echo ROOT_URL; ?>payment/update-config" id="configForm">
    
    <div class="form-group">
      <label for="bank_id">Mã Ngân Hàng <span style="color:red;">*</span></label>
      <select id="bank_id" name="bank_id" required>
        <option value="">-- Chọn ngân hàng --</option>
        <?php foreach ($bankCodes as $code => $name): ?>
          <option value="<?php echo htmlspecialchars($code); ?>" 
                  <?php echo ($code === $currentConfig['bank_id']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($name) . ' (' . $code . ')'; ?>
          </option>
        <?php endforeach; ?>
      </select>
      <small>Ví dụ: ACB, VIETCOMBANK, BIDV, TECHCOMBANK, v.v.</small>
    </div>

    <div class="form-group">
      <label for="account_no">Số Tài Khoản <span style="color:red;">*</span></label>
      <input type="text" id="account_no" name="account_no" 
             value="<?php echo htmlspecialchars($currentConfig['account_no']); ?>" 
             required placeholder="VD: 123456789">
      <small>Nhập số tài khoản ngân hàng của bạn</small>
    </div>

    <div class="form-group">
      <label for="account_name">Tên Chủ Tài Khoản <span style="color:red;">*</span></label>
      <input type="text" id="account_name" name="account_name" 
             value="<?php echo htmlspecialchars($currentConfig['account_name']); ?>" 
             required placeholder="VD: NGUYEN VAN A">
      <small>Tên chủ tài khoản (IN HOA, không dấu)</small>
    </div>

    <div class="form-group">
      <label for="template">Template QR</label>
      <select id="template" name="template">
        <option value="print" <?php echo ($currentConfig['template'] === 'print') ? 'selected' : ''; ?>>Print</option>
        <option value="compact" <?php echo ($currentConfig['template'] === 'compact') ? 'selected' : ''; ?>>Compact</option>
      </select>
      <small>Kiểu hiển thị mã QR</small>
    </div>

    <div class="button-group">
      <button type="submit" class="btn btn-primary">💾 Lưu Cấu Hình</button>
      <button type="reset" class="btn btn-secondary">🔄 Đặt Lại</button>
    </div>
  </form>

  <!-- QR Preview -->
  <div class="qr-preview" id="qrPreview" style="display:none;">
    <h4>Xem Trước Mã QR</h4>
    <p style="font-size:12px; color:#666;">Mã QR sẽ hiển thị như sau (với số tiền 100,000 VND):</p>
    <img id="previewImage" src="" alt="QR Preview">
  </div>
</div>

<script>
  // Generate QR preview khi thay đổi form
  const form = document.getElementById('configForm');
  const bankSelect = document.getElementById('bank_id');
  const accountInput = document.getElementById('account_no');
  const nameInput = document.getElementById('account_name');
  const templateSelect = document.getElementById('template');
  const qrPreview = document.getElementById('qrPreview');
  const previewImage = document.getElementById('previewImage');

  function updatePreview() {
    const bankId = bankSelect.value;
    const accountNo = accountInput.value;
    const template = templateSelect.value;

    if (bankId && accountNo) {
      const qrUrl = `https://img.vietqr.io/image/${bankId}-${accountNo}-${template}.png?amount=100000&addInfo=Xem%20Truoc&accountName=TEST`;
      previewImage.src = qrUrl;
      qrPreview.style.display = 'block';
    } else {
      qrPreview.style.display = 'none';
    }
  }

  // Listen to changes
  bankSelect.addEventListener('change', updatePreview);
  accountInput.addEventListener('input', updatePreview);
  templateSelect.addEventListener('change', updatePreview);

  // Initial preview
  updatePreview();

  // Form submission
  form.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(form);
    
    fetch('<?php echo ROOT_URL; ?>payment/update-config', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('✅ Cấu hình đã được lưu thành công!');
        location.reload();
      } else {
        alert('❌ Lỗi: ' + (data.message || 'Không thể lưu cấu hình'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('❌ Lỗi kết nối');
    });
  });
</script>

<?php require_once ROOT_PATH . '/src/Views/includes/footer.php'; ?>
