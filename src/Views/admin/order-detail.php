
<style>
  /* E-invoice style (A4-friendly) */
  .ei-container { max-width: 900px; margin: 20px auto; background: #fff; padding: 24px; font-family: Arial, Helvetica, sans-serif; color: #222; border: 1px solid #e6e6e6; }
  .ei-top { display:flex; justify-content:space-between; align-items:flex-start; }
  .ei-company { max-width:60%; }
  .ei-company h2 { margin:0; font-size:18px; color:#0b4ea2; }
  .ei-company .small { font-size:12px; color:#555; }
  .ei-title { text-align:center; width:40%; }
  .ei-title h1 { margin:0; font-size:20px; color:#b30000; }
  .ei-meta { margin-top:10px; display:flex; justify-content:space-between; gap:8px; }
  .ei-meta .box { font-size:12px; color:#333; }

  .ei-section { margin-top:18px; display:flex; gap:16px; }
  .ei-section .left, .ei-section .right { flex:1; }
  .ei-section .box { background:#fafafa; padding:10px; border:1px solid #eee; font-size:13px; }

  table.ei-table { width:100%; border-collapse:collapse; margin-top:18px; font-size:13px; }
  table.ei-table th, table.ei-table td { border:1px solid #ddd; padding:8px; }
  table.ei-table th { background:#f2f2f2; font-weight:700; text-align:left; }

  .ei-totals { margin-top:12px; display:flex; justify-content:flex-end; }
  .ei-totals .inner { width:360px; }
  .ei-totals .row { display:flex; justify-content:space-between; padding:6px 8px; border-bottom:1px dashed #e6e6e6; font-size:13px; }
  .ei-totals .row.total { font-size:18px; font-weight:700; color:#000; border-top:2px solid #ccc; }

  .ei-footer { margin-top:18px; display:flex; justify-content:space-between; font-size:12px; }
  .ei-bank { background:#f8f8f8; padding:12px; border:1px solid #eee; font-size:12px; }

  @media print {
    .ei-container { box-shadow:none; margin:0; border:none; }
  }
</style>

<?php
  $order = isset($order) ? $order : [];
  $items = isset($items) ? $items : [];
  $subtotal = 0;
  foreach ($items as $it) {
    $subtotal += (float)($it['Price'] ?? 0) * (int)($it['quantity'] ?? 0);
  }
  $serviceFeeRate = 0.05;
  $serviceFee = round($subtotal * $serviceFeeRate);
  $shippingFee = 50000;
  $voucherCode = '';
  $voucherDiscount = (int)($order['Voucher_Discount'] ?? ($order['voucher_discount'] ?? 0));
  if ($voucherDiscount <= 0) {
    $noteStr = (string)($order['Note'] ?? '');
    if ($noteStr !== '') {
      if (preg_match('/Voucher:\s*([A-Z0-9_-]+)\s*-\s*(\d+)/i', $noteStr, $m)) {
        $voucherCode = strtoupper($m[1] ?? '');
        $voucherDiscount = (int)($m[2] ?? 0);
      }
    }
  } else {
    $voucherCode = strtoupper((string)($order['Voucher_Code'] ?? ($order['voucher_code'] ?? '')));
  }
  // Nếu vẫn chưa có số tiền giảm nhưng có mã voucher, thử tra cứu từ file vouchers.json
  if ($voucherDiscount <= 0 && $voucherCode !== '') {
    try {
      $file = ROOT_PATH . '/public/data/vouchers.json';
      if (file_exists($file)) {
        $list = json_decode(file_get_contents($file), true) ?: [];
        foreach ($list as $v) {
          if (strtoupper((string)($v['code'] ?? '')) === $voucherCode) {
            $type = $v['type'] ?? 'fixed';
            $value = (float)($v['value'] ?? 0);
            $maxD = isset($v['max_discount']) ? (int)$v['max_discount'] : null;
            if ($type === 'fixed') {
              $voucherDiscount = (int)min(max(0, $value), max(0, $subtotal));
            } else {
              $calc = (int)round((max(0, $value) / 100) * max(0, $subtotal));
              if ($maxD && $calc > $maxD) { $calc = $maxD; }
              $voucherDiscount = max(0, $calc);
            }
            break;
          }
        }
      }
    } catch (\Exception $e) { /* ignore */ }
  }
  $grandTotal = $subtotal + $serviceFee + $shippingFee - max(0, $voucherDiscount);

  // Xác định hình thức thanh toán hiển thị
  $pmRaw = isset($order['PaymentMethod']) ? strtolower($order['PaymentMethod']) : strtolower($order['payment_method'] ?? '');
  $paymentText = '';
  if ($pmRaw === 'online') { $paymentText = 'Online (QR Code)'; }
  elseif ($pmRaw === 'opt') { $paymentText = 'Tiền mặt'; }
  else {
    $noteStr = (string)($order['Note'] ?? '');
    $paymentText = (stripos($noteStr, 'online') !== false) ? 'Online (QR Code)' : 'Tiền mặt';
  }
  $qrCfg = \Core\PaymentHelper::getQRConfig();
  $bankCodes = \Core\PaymentHelper::getAllBankCodes();
  $bankName = isset($bankCodes[$qrCfg['bank_id']]) ? $bankCodes[$qrCfg['bank_id']] : $qrCfg['bank_id'];
?>

<div class="ei-container">
  <div class="ei-top">
    <div class="ei-company">
      <h2>Luxury Fashion</h2>
      <div class="small">Địa chỉ: 06 Tân Kỳ Tân Quý, P.15, Q. Tân Bình, HCM</div>
      <div class="small">MST: 030xxxxx • Điện thoại: 028 xxxxxx</div>
    </div>
    <div class="ei-title">
      <h1>HÓA ĐƠN GIÁ TRỊ GIA TĂNG</h1>
      <div style="font-size:12px;color:#333;margin-top:6px;">(Mẫu số: 01GTKT0/001)</div>
      <div style="margin-top:8px;font-weight:700;">Số: <?php echo htmlspecialchars($order['Order_Id'] ?? ''); ?></div>
    </div>
  </div>

  <div class="ei-meta">
    <div class="box">Người bán: <strong><?php echo htmlspecialchars($order['seller_name'] ?? 'Cửa hàng'); ?></strong></div>
    <div class="box">Người mua: <strong><?php echo htmlspecialchars($order['user_name'] ?? 'Khách hàng'); ?></strong></div>
    <div class="box">Ngày: <strong><?php echo date('H:i:s d/m/Y', strtotime($order['Order_date'] ?? date('Y-m-d H:i:s'))); ?></strong></div>
  </div>

  <div class="ei-section">
    <div class="left">
      <div class="box">
        <div><strong>Địa chỉ người bán:</strong> <?php echo htmlspecialchars($order['seller_address'] ?? ''); ?></div>
        <div><strong>Địa chỉ người nhận:</strong> <?php echo htmlspecialchars($order['Adress'] ?? ''); ?></div>
        <div><strong>Hình thức thanh toán:</strong> <?php echo htmlspecialchars($paymentText); ?></div>
      </div>
    </div>
    <div class="right">
      <div class="box">
        <div><strong>MST người mua:</strong> <?php echo htmlspecialchars($order['user_tax_code'] ?? '-'); ?></div>
        <div><strong>Số hóa đơn:</strong> <?php echo htmlspecialchars($order['InvoiceNumber'] ?? ''); ?></div>
      </div>
    </div>
  </div>

  <table class="ei-table">
    <thead>
      <tr>
        <th style="width:6%">STT</th>
        <th style="width:46%">Tên hàng hóa, dịch vụ</th>
        <th style="width:10%">ĐVT</th>
        <th style="width:8%;text-align:right">Số lượng</th>
        <th style="width:15%;text-align:right">Đơn giá</th>
        <th style="width:15%;text-align:right">Thành tiền</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; foreach ($items as $it):
        $qty = (int)($it['quantity'] ?? 0);
        $price = (float)($it['Price'] ?? 0);
        $lineBase = $qty * $price;
        $weight = $subtotal > 0 ? ($lineBase / $subtotal) : 1;
        $allocService = (int)round($serviceFee * $weight);
        $allocShip = (int)round($shippingFee * $weight);
        $lineTotal = $lineBase + $allocService + $allocShip;
      ?>
      <tr>
        <td><?php echo $i++; ?></td>
        <td>
          <?php echo htmlspecialchars($it['product_name'] ?? ''); ?>
          <?php if (!empty($it['color_name']) || !empty($it['size_name'])): ?>
            <div style="font-size:11px;color:#666;margin-top:4px;">
              <?php echo !empty($it['color_name']) ? 'Màu: '.htmlspecialchars($it['color_name']) : ''; ?>
              <?php echo (!empty($it['color_name']) && !empty($it['size_name'])) ? ' • ' : ''; ?>
              <?php echo !empty($it['size_name']) ? 'Size: '.htmlspecialchars($it['size_name']) : ''; ?>
            </div>
          <?php endif; ?>
        </td>
        <td>cái</td>
        <td style="text-align:right"><?php echo $qty; ?></td>
        <td style="text-align:right"><?php echo number_format($price,0,',','.'); ?></td>
        <td style="text-align:right"><?php echo number_format($lineTotal,0,',','.'); ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="ei-totals">
    <div class="inner">
      <div class="row"><span>VAT (5%):</span><span><?php echo number_format($serviceFee,0,',','.'); ?> ₫</span></div>
      <div class="row"><span>Phí vận chuyển:</span><span><?php echo number_format($shippingFee,0,',','.'); ?> ₫</span></div>
      <?php if ($voucherDiscount > 0): ?>
      <div class="row"><span>Giảm voucher<?php echo $voucherCode ? ' ('.$voucherCode.')' : ''; ?>:</span><span>- <?php echo number_format($voucherDiscount,0,',','.'); ?> ₫</span></div>
      <?php endif; ?>
      <div class="row total"><span>Tổng thanh toán:</span><span><?php echo number_format($grandTotal,0,',','.'); ?> ₫</span></div>
    </div>
  </div>


  <div class="ei-footer">
    <?php if ($pmRaw === 'online'): ?>
    <div class="ei-bank">
      <div><strong>Ngân hàng:</strong> <?php echo htmlspecialchars($bankName); ?></div>
      <div><strong>STK:</strong> <?php echo htmlspecialchars($qrCfg['account_no']); ?></div>
      <div><strong>Tên TK:</strong> <?php echo htmlspecialchars($qrCfg['account_name']); ?></div>
    </div>
    <?php endif; ?>
    <div style="text-align:center;">
      <div style="font-weight:700;margin-bottom:8px;">Người bán</div>
      <div style="height:48px;"></div>
      <div style="font-size:12px;color:#666;">(Ký, ghi rõ họ tên)</div>
    </div>
    <div style="text-align:center;">
      <div style="font-weight:700;margin-bottom:8px;">Người mua</div>
      <div style="height:48px;"></div>
      <div style="font-size:12px;color:#666;">(Ký, ghi rõ họ tên)</div>
    </div>
  </div>

</div>
