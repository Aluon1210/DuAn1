
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
  $order = $data['order'] ?? [];
  $items = $data['items'] ?? [];
  $subtotal = 0;
  foreach ($items as $it) {
    $subtotal += (float)($it['Price'] ?? 0) * (int)($it['quantity'] ?? 0);
  }
  $taxRate = 0.10; // 10% VAT for example
  $taxAmount = round($subtotal * $taxRate);
  $grandTotal = $subtotal + $taxAmount;
?>

<div class="ei-container">
  <div class="ei-top">
    <div class="ei-company">
      <h2>NGÂN HÀNG THƯƠNG MẠI CỔ PHẦN A CHÂU (ACB)</h2>
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
    <div class="box">Ngày: <strong><?php echo date('d/m/Y', strtotime($order['Order_date'] ?? date('Y-m-d'))); ?></strong></div>
  </div>

  <div class="ei-section">
    <div class="left">
      <div class="box">
        <div><strong>Địa chỉ người bán:</strong> <?php echo htmlspecialchars($order['seller_address'] ?? ''); ?></div>
        <div><strong>Địa chỉ người nhận:</strong> <?php echo htmlspecialchars($order['Adress'] ?? ''); ?></div>
        <div><strong>Hình thức thanh toán:</strong> <?php echo htmlspecialchars($order['PaymentMethod'] ?? ''); ?></div>
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
        $line = $qty * $price;
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
        <td style="text-align:right"><?php echo number_format($line,0,',','.'); ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="ei-totals">
    <div class="inner">
      <div class="row"><span>Tổng cộng hàng hóa, dịch vụ:</span><span><?php echo number_format($subtotal,0,',','.'); ?> ₫</span></div>
      <div class="row"><span>Thuế suất GTGT:</span><span><?php echo ($taxRate*100); ?>%</span></div>
      <div class="row"><span>Tiền thuế GTGT:</span><span><?php echo number_format($taxAmount,0,',','.'); ?> ₫</span></div>
      <div class="row total"><span>Tổng thanh toán:</span><span><?php echo number_format($grandTotal,0,',','.'); ?> ₫</span></div>
    </div>
  </div>

  <div class="ei-footer">
    <div class="ei-bank">
      <div><strong>Ngân hàng:</strong> ACB - Chi nhánh Tân Bình</div>
      <div><strong>STK:</strong> 123456789</div>
    </div>
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

<?php require_once ROOT_PATH . '/src/Views/includes/footer.php'; ?>
