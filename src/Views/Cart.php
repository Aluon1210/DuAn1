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
        border-bottom: 2px solid var(--border-light);
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
    <div style="display:flex; justify-content:flex-start; margin-bottom:16px;">
        <a href="<?php echo ROOT_URL; ?>product" class="btn btn-primary" style="padding: 12px 24px; display:inline-flex; align-items:center; gap:8px;">
            <span>←</span>
            <span>Tiếp tục mua sắm</span>
        </a>
    </div>
<?php endif; ?>

<?php if (!empty($cartItems)): ?>
    <div class="cart-table-container">
        <form method="POST" action="<?php echo ROOT_URL; ?>cart/update" id="cartForm">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th style="width:40px; text-align:center;">Chọn</th>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr <?php echo ($item['variant'] ? ($item['variant']['stock'] ?? 0) : ($item['product']['quantity'] ?? 0)) <= 0 ? 'style="opacity:0.5;"' : ''; ?> >
                            <td style="text-align:center;">
                                <input type="checkbox" name="selected[]" value="<?php echo htmlspecialchars($item['cart_id']); ?>" <?php echo ($item['variant'] ? ($item['variant']['stock'] ?? 0) : ($item['product']['quantity'] ?? 0)) <= 0 ? 'disabled' : ''; ?> class="cart-select">
                            </td>
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
                                        <?php if ($item['color'] || $item['size']): ?>
                                            <div style="margin-top: 8px; display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                                                <?php if ($item['color']): ?>
                                                    <div style="display: flex; align-items: center; gap: 6px;">
                                                        <span style="font-size: 12px; color: var(--text-light);">Màu:</span>
                                                        <div style="width: 20px; height: 20px; border-radius: 50%; background-color: <?php echo htmlspecialchars($item['color']['hex_code']); ?>; border: 2px solid #ddd; box-shadow: 0 1px 3px rgba(0,0,0,0.2);" title="<?php echo htmlspecialchars($item['color']['name']); ?>"></div>
                                                        <span style="font-size: 13px; color: var(--text-dark);"><?php echo htmlspecialchars($item['color']['name']); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($item['size']): ?>
                                                    <div style="display: flex; align-items: center; gap: 6px;">
                                                        <span style="font-size: 12px; color: var(--text-light);">Size:</span>
                                                        <span style="font-size: 13px; color: var(--text-dark); font-weight: 600;"><?php echo htmlspecialchars($item['size']['value']); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        <a href="<?php echo ROOT_URL; ?>product/detail/<?php echo $item['product']['id']; ?>" class="cart-item-link" style="margin-top: 8px; display: inline-block;">
                                            Xem chi tiết →
                                        </a>
                                        <?php if ($item['product']['quantity'] <= 0): ?>
                                            <div style="margin-top:8px; color:#e74c3c; font-weight:600;">Hết hàng</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <div class="cart-price"><?php echo number_format($item['price'], 0, ',', '.'); ?> ₫</div>
                            </td>
                            <td style="text-align: center;">
                                <div style="display:inline-flex; align-items:center; gap:8px; justify-content:center;">
                                    <button type="button" class="btn btn-primary qty-minus" data-id="<?php echo htmlspecialchars($item['cart_id']); ?>" <?php echo ($item['variant'] ? ($item['variant']['stock'] ?? 0) : ($item['product']['quantity'] ?? 0)) <= 0 ? 'disabled' : ''; ?>>-</button>
                                    <input type="number" 
                                           name="quantity[<?php echo htmlspecialchars($item['cart_id']); ?>]" 
                                           value="<?php echo $item['quantity']; ?>" 
                                           min="1" 
                                           max="<?php echo $item['variant'] ? ($item['variant']['stock'] ?? 0) : ($item['product']['quantity'] ?? 0); ?>" 
                                           class="cart-quantity-input" 
                                           data-price="<?php echo $item['price']; ?>"
                                           data-id="<?php echo htmlspecialchars($item['cart_id']); ?>"
                                           <?php echo ($item['variant'] ? ($item['variant']['stock'] ?? 0) : ($item['product']['quantity'] ?? 0)) <= 0 ? 'disabled' : ''; ?>
                                           required>
                                    <button type="button" class="btn btn-primary qty-plus" data-id="<?php echo htmlspecialchars($item['cart_id']); ?>" <?php echo ($item['variant'] ? ($item['variant']['stock'] ?? 0) : ($item['product']['quantity'] ?? 0)) <= 0 ? 'disabled' : ''; ?>>+</button>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <div class="cart-subtotal" data-id="<?php echo htmlspecialchars($item['cart_id']); ?>"><?php echo number_format($item['subtotal'], 0, ',', '.'); ?> ₫</div>
                            </td>
                            <td style="text-align: center;">
                                <a href="<?php echo ROOT_URL; ?>cart/remove/<?php echo htmlspecialchars($item['cart_id']); ?>" 
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
                    
                </div>
                <div class="cart-total">
                    <div class="cart-total-label">Tổng tiền hàng đã chọn</div>
                    <div class="cart-total-amount" id="selectedTotal">0</div>
                    <div style="margin-top:8px; color: var(--text-light);">Đã chọn: <span id="selectedCount">0</span> sản phẩm</div>
                </div>
            </div>
        </form>

        <div style="margin-top: 30px; padding-top: 30px; border-top: 2px solid var(--border-light); display:flex; justify-content:center;">
            <button id="paySelectedBtn" type="submit" form="cartForm" formaction="<?php echo ROOT_URL; ?>cart/confirm" class="btn btn-success" style="padding: 18px 60px; font-size: 18px; text-transform: uppercase; letter-spacing: 1.5px;">
                ✓ Thanh toán
            </button>
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

<script>
const fmt = v => new Intl.NumberFormat('vi-VN').format(v);
function recalc() {
  let total = 0; let count = 0;
  document.querySelectorAll('.cart-select').forEach(chk => {
    if (chk.checked && !chk.disabled) {
      const id = chk.value;
      const qtyInput = document.querySelector(`input[data-id="${id}"]`);
      const price = parseInt(qtyInput.getAttribute('data-price')) || 0;
      const qty = parseInt(qtyInput.value) || 0;
      total += price * qty; count += 1;
    }
  });
  document.getElementById('selectedTotal').textContent = fmt(total);
  document.getElementById('selectedCount').textContent = count;
}
function clamp(input) {
  const min = parseInt(input.min) || 1;
  const max = parseInt(input.max) || 1;
  let val = parseInt(input.value) || min;
  if (val < min) val = min;
  if (val > max) val = max;
  input.value = val;
}
async function syncQty(id, qty) {
  try {
    const resp = await fetch('<?php echo ROOT_URL; ?>cart/update-quantity', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ cart_id: id, quantity: String(qty) })
    });
    const data = await resp.json();
    const input = document.querySelector(`input[data-id="${id}"]`);
    if (!data || !input) return;
    if (data.deleted) {
      const tr = input.closest('tr');
      if (tr) tr.remove();
      recalc();
      return;
    }
    if (typeof data.max !== 'undefined') {
      input.max = String(data.max);
    }
    const newQty = parseInt(data.quantity || qty) || qty;
    if ((parseInt(input.value) || 0) !== newQty) {
      input.value = newQty;
    }
    const subtotal = document.querySelector(`.cart-subtotal[data-id="${id}"]`);
    if (subtotal && typeof data.subtotal !== 'undefined') {
      subtotal.textContent = fmt(data.subtotal) + ' ₫';
    }
    recalc();
  } catch (e) {}
}
document.querySelectorAll('.qty-minus').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.getAttribute('data-id');
    const input = document.querySelector(`input[data-id="${id}"]`);
    input.value = Math.max((parseInt(input.value)||1)-1, parseInt(input.min)||1);
    clamp(input);
    const price = parseInt(input.getAttribute('data-price'))||0;
    const subtotal = document.querySelector(`.cart-subtotal[data-id="${id}"]`);
    subtotal.textContent = fmt(price * parseInt(input.value)) + ' ₫';
    recalc();
    syncQty(id, parseInt(input.value));
  });
});
document.querySelectorAll('.qty-plus').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.getAttribute('data-id');
    const input = document.querySelector(`input[data-id="${id}"]`);
    input.value = (parseInt(input.value)||1)+1;
    clamp(input);
    const price = parseInt(input.getAttribute('data-price'))||0;
    const subtotal = document.querySelector(`.cart-subtotal[data-id="${id}"]`);
    subtotal.textContent = fmt(price * parseInt(input.value)) + ' ₫';
    recalc();
    syncQty(id, parseInt(input.value));
  });
});
document.querySelectorAll('.cart-quantity-input').forEach(inp => {
  inp.addEventListener('change', () => {
    clamp(inp);
    const id = inp.getAttribute('data-id');
    const price = parseInt(inp.getAttribute('data-price'))||0;
    const subtotal = document.querySelector(`.cart-subtotal[data-id="${id}"]`);
    subtotal.textContent = fmt(price * parseInt(inp.value)) + ' ₫';
    recalc();
    syncQty(id, parseInt(inp.value));
  });
});
document.querySelectorAll('.cart-select').forEach(chk => {
  chk.addEventListener('change', recalc);
});
recalc();

// Yêu cầu phải chọn ít nhất 1 sản phẩm trước khi thanh toán
const payBtn = document.getElementById('paySelectedBtn');
if (payBtn) {
  payBtn.addEventListener('click', function(e) {
    const anySelected = Array.from(document.querySelectorAll('.cart-select')).some(chk => chk.checked && !chk.disabled);
    if (!anySelected) {
      e.preventDefault();
      alert('Vui lòng chọn ít nhất 1 sản phẩm để thanh toán');
    }
  });
}
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
    if(btn){
      btn.onclick = function(){ try{ f.contentWindow.focus(); f.contentWindow.print(); }catch(e){} };
    }
    try {
      var qp = new URLSearchParams(window.location.search);
      var shouldPrint = qp.get('print') === '1';
      f.onload = function(){
        if(shouldPrint){
          try{ f.contentWindow.focus(); f.contentWindow.print(); }catch(e){}
          try{
            qp.delete('print');
            var base = window.location.pathname + '?' + qp.toString();
            if(!qp.toString()){ base = window.location.pathname; }
            window.history.replaceState(null, document.title, base);
          }catch(e){}
        }
      };
    } catch(e) {}
  }
  function closeInvoiceModal(){
    var m = document.getElementById('invoiceModal');
    var f = document.getElementById('invoiceIframe');
    f.src = 'about:blank'; m.classList.remove('active');
  }
  (function(){
    var qp = new URLSearchParams(window.location.search);
    var inv = qp.get('invoice');
    if(inv){
      try{
        var up = String(inv||'').toUpperCase();
        var m = up.match(/\bORD[0-9]+\b/);
        if(m && m[0]){ inv = m[0]; }
      }catch(e){}
      openInvoiceModal(inv);
    }
  })();
</script>

 


