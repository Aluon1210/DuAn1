# Hướng Dẫn Tích Hợp API Tạo Đơn Hàng Vào Frontend

## 1. Quy Trình Thanh Toán QR Hoàn Chỉnh

```
┌─────────────────────────────────────────────────────────┐
│ 1. User chọn phương thức thanh toán QR                 │
└─────────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────────┐
│ 2. Gọi API /payment/get-qr-code để lấy QR Code         │
└─────────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────────┐
│ 3. Hiển thị QR Code cho user quét và thanh toán        │
└─────────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────────┐
│ 4. User quét QR, nhập số tiền và thanh toán ngân hàng  │
└─────────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────────┐
│ 5. Click "Kiểm Tra Thanh Toán" → API /payment/check-payment
│    Gọi Google Apps Script để kiểm tra giao dịch        │
└─────────────────────────────────────────────────────────┘
                        ↓
              Giao dịch được xác nhận?
              /         \
           YES            NO
           /                \
          ↓                  ↓
    ┌──────────────┐   ┌─────────────┐
    │ Thành công   │   │ Thất bại    │
    └──────────────┘   │ Thử lại     │
          ↓            └─────────────┘
┌─────────────────────────────────────────────────────────┐
│ 6. Click "Tạo Đơn Hàng"                                 │
│    → API /payment/create-order-on-payment              │
│    → Tự động tạo đơn hàng từ giỏ hàng                   │
└─────────────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────────────┐
│ 7. Đơn hàng được tạo thành công                        │
│    → Chuyển hướng đến trang chi tiết đơn hàng          │
└─────────────────────────────────────────────────────────┘
```

---

## 2. Thêm Script Vào CheckoutConfirm.php

### Option 1: Thêm Script Inline (Nhanh)

Thêm vào cuối file `CheckoutConfirm.php`:

```php
<!-- Thêm trước closing tag </body> -->

<!-- Payment Integration Script -->
<script src="<?php echo ROOT_URL; ?>asset/js/payment-integration.js"></script>
```

### Option 2: Viết Script Inline (Nếu không muốn file riêng)

```php
<!-- Thêm vào CheckoutConfirm.php -->
<script>
class PaymentIntegration {
  // ... copy nội dung từ payment-integration.js vào đây ...
}

document.addEventListener('DOMContentLoaded', () => {
  const paymentIntegration = new PaymentIntegration();
  paymentIntegration.init();
});
</script>
```

---

## 3. Cập Nhật HTML Structure Cho CheckoutConfirm.php

### Đảm bảo có các phần tử sau:

```html
<!-- Hiển thị tổng tiền -->
<div class="total-section">
  <div data-cart-total>1500000</div>
  <!-- Data attribute để script lấy -->
</div>

<!-- Địa chỉ giao hàng -->
<div class="form-group">
  <label for="addressInput">Địa chỉ giao hàng *</label>
  <input
    type="text"
    id="addressInput"
    placeholder="Nhập địa chỉ giao hàng"
    required
  />
</div>

<!-- Ghi chú -->
<div class="form-group">
  <label for="noteInput">Ghi chú</label>
  <textarea
    id="noteInput"
    placeholder="Ghi chú cho người bán (tùy chọn)"
  ></textarea>
</div>

<!-- Phương thức thanh toán (QR) -->
<div class="payment-method">
  <label>
    <input type="radio" name="payment_method" value="qr" checked />
    Thanh toán QR
  </label>
</div>

<!-- Nút kiểm tra thanh toán -->
<button id="checkPaymentBtn" class="btn btn-primary">
  Kiểm Tra Thanh Toán
</button>

<!-- Nút tạo đơn hàng -->
<button id="createOrderBtn" class="btn btn-success" style="display: none;">
  Tạo Đơn Hàng
</button>

<!-- Thông tin ngân hàng (để script lấy) -->
<div data-account-no hidden>0123456789</div>
<div data-bank-id hidden>ACB</div>
<div data-user-name hidden><?php echo $_SESSION['user']['name'] ?? ''; ?></div>
```

---

## 4. JavaScript Flow Chi Tiết

### A. Kiểm tra thanh toán (checkPayment)

```javascript
// 1. Lấy thông tin từ form
const amount = document.querySelector("[data-cart-total]").textContent;
const description = "Thanh toan don hang - Nguyen Van A";

// 2. Gọi API /payment/check-payment
fetch("/payment/check-payment", {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify({
    order_id: "TMP" + Date.now(),
    amount: 1500000,
    description: description,
    account_no: "0123456789",
    bank_id: "ACB",
  }),
})
  // 3. Xử lý response
  .then((res) => res.json())
  .then((result) => {
    if (result.success) {
      // Giao dịch được xác nhận
      console.log("Thanh toán thành công:", result.transaction);

      // Hiển thị nút "Tạo Đơn Hàng"
      document.getElementById("createOrderBtn").style.display = "block";
    } else {
      // Giao dịch chưa được phát hiện
      alert(result.message);
    }
  });
```

### B. Tạo đơn hàng (createOrderAfterPayment)

```javascript
// 1. Validate dữ liệu
const amount = 1500000;
const address = document.getElementById("addressInput").value;
const note = document.getElementById("noteInput").value;

if (!address) {
  alert("Vui lòng nhập địa chỉ giao hàng");
  return;
}

// 2. Gọi API /payment/create-order-on-payment
fetch("/payment/create-order-on-payment", {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify({
    amount: amount,
    description: "Thanh toán đơn hàng",
    address: address,
    note: note,
  }),
})
  // 3. Xử lý response
  .then((res) => res.json())
  .then((result) => {
    if (result.success) {
      // Đơn hàng được tạo thành công
      console.log("Order ID:", result.order_id);

      // Hiển thị modal thành công
      alert("Đơn hàng " + result.order_id + " đã được tạo!");

      // Chuyển hướng đến trang chi tiết đơn hàng
      window.location.href = "/order/" + result.order_id;
    } else {
      alert("Lỗi: " + result.message);
    }
  });
```

---

## 5. Test API Bằng cURL

### Kiểm Tra Thanh Toán

```bash
curl -X POST http://localhost/DuAn1/payment/check-payment \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": "TMP123456",
    "amount": 1500000,
    "description": "Thanh toan don hang - Nguyen Van A",
    "account_no": "0123456789",
    "bank_id": "ACB"
  }' \
  -b "PHPSESSID=your_session_id"
```

### Tạo Đơn Hàng

```bash
curl -X POST http://localhost/DuAn1/payment/create-order-on-payment \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 1500000,
    "description": "Thanh toán đơn hàng",
    "address": "123 Đường ABC, Q.1, TP.HCM",
    "note": "Giao vào chiều"
  }' \
  -b "PHPSESSID=your_session_id"
```

---

## 6. Xử Lý Lỗi Thường Gặp

### Lỗi: "Giỏ hàng trống"

**Nguyên nhân**: User chưa thêm sản phẩm hoặc giỏ hàng đã xóa

**Giải pháp**:

```javascript
if (cartItems.length === 0) {
  alert("Giỏ hàng trống. Vui lòng thêm sản phẩm");
  window.location.href = "/product";
}
```

### Lỗi: "Tổng tiền không khớp"

**Nguyên nhân**: Tính toán tiền ở client khác server

**Giải pháp**:

```javascript
// Luôn lấy tổng tiền từ server, không tính ở client
fetch("/cart/get-total")
  .then((res) => res.json())
  .then((data) => {
    const amount = data.total; // Lấy từ server
  });
```

### Lỗi: "Unauthorized"

**Nguyên nhân**: User chưa login hoặc session hết hạn

**Giải pháp**:

```javascript
if (result.message.includes("Unauthorized")) {
  window.location.href = "/login";
}
```

### Lỗi: "Không tìm thấy thông tin user"

**Nguyên nhân**: Session user bị mất

**Giải pháp**: Login lại

---

## 7. Best Practices

### 1. Validate Dữ Liệu 2 Chiều

- **Client**: Validate trước gửi request
- **Server**: Validate lại dữ liệu nhận được

```javascript
// Client-side
if (!address || address.trim() === '') {
  alert('Địa chỉ không được để trống');
  return;
}

// Server-side (PHP)
if (empty($address)) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'address là bắt buộc']);
  return;
}
```

### 2. Kiểm Tra Tổng Tiền

```javascript
// So sánh giỏ hàng hiện tại với amount gửi lên
const expectedTotal = calculateCartTotal();
const submittedAmount = parseInt(document.getElementById("amountInput").value);

if (expectedTotal !== submittedAmount) {
  alert("Tổng tiền không khớp!");
  return;
}
```

### 3. Loading State

```javascript
function setLoading(button, isLoading) {
  if (isLoading) {
    button.disabled = true;
    button.innerHTML = '<i class="spinner"></i> Đang xử lý...';
  } else {
    button.disabled = false;
    button.innerHTML = "Tạo Đơn Hàng";
  }
}
```

### 4. Error Handling

```javascript
.catch(error => {
  console.error('Request failed:', error);
  alert('Lỗi kết nối: ' + error.message);
})
```

### 5. Redirect Sau Thành Công

```javascript
if (result.success) {
  // Hiển thị message
  alert("Đơn hàng tạo thành công!");

  // Delay 2 giây rồi redirect
  setTimeout(() => {
    window.location.href = `/order/${result.order_id}`;
  }, 2000);
}
```

---

## 8. Testing Checklist

- [ ] User có thể nhập địa chỉ giao hàng
- [ ] Nút "Kiểm Tra Thanh Toán" hoạt động
- [ ] API `/payment/check-payment` trả về kết quả đúng
- [ ] Nút "Tạo Đơn Hàng" xuất hiện khi thanh toán thành công
- [ ] API `/payment/create-order-on-payment` tạo đơn hàng thành công
- [ ] Giỏ hàng tự động xóa sau khi tạo đơn hàng
- [ ] Chuyển hướng đúng đến trang chi tiết đơn hàng
- [ ] Xử lý lỗi validation đúng
- [ ] Xử lý session timeout đúng
- [ ] Loading state hiển thị đúng

---

## 9. File Reference

```
DuAn1/
├── src/
│   └── Controllers/
│       └── PaymentController.php (Phương thức: createOrderOnPayment)
├── asset/
│   └── js/
│       └── payment-integration.js (Script frontend)
├── src/
│   └── Views/
│       └── CheckoutConfirm.php (Giao diện thanh toán)
└── API_CREATE_ORDER_ON_PAYMENT.md (Tài liệu API)
```

---

## 10. Troubleshooting

**Q: API trả về lỗi "Giỏ hàng trống"?**
A: Kiểm tra xem user đã thêm sản phẩm vào giỏ trong database chưa.

**Q: Giỏ hàng không tự động xóa?**
A: Kiểm tra xem database connection có ok không, xem lại `CartModel::deleteCart()`.

**Q: Order không được tạo nhưng không có lỗi?**
A: Kiểm tra logs trong `/storage/` folder để xem lỗi chi tiết.

**Q: Redirect không hoạt động?**
A: Kiểm tra xem `/order/{order_id}` route đã được định nghĩa chưa.
