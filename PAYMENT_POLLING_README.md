# Payment Polling System - Hệ Thống Kiểm Tra Thanh Toán Tự Động

## 🎯 Mục Đích

Tự động kiểm tra thanh toán QR từ Google Apps Script API liên tục, so sánh với thông tin hệ thống tạo, và tự động tạo đơn hàng nếu thanh toán khớp.

## 📁 Các File Đã Tạo/Cập Nhật

### Backend (PHP)

1. **`src/Controllers/PaymentController.php`** - Thêm các method mới:
   - `pollLatestPayment()` - Endpoint polling chính
   - `getLatestPaymentFromAPI()` - Lấy thanh toán từ API
   - `isPaymentProcessed()` - Kiểm tra xem đã xử lý chưa
   - `comparePaymentInfo()` - So sánh thông tin
   - `autoCreateOrderFromPayment()` - Tự động tạo đơn hàng
   - `markPaymentAsProcessed()` - Đánh dấu đã xử lý
   - Và các method phụ trợ khác

### Frontend (JavaScript)

2. **`asset/js/payment-polling.js`** - Thư viện polling chính
   - Class `PaymentPoller` - Quản lý polling
   - Auto polling mỗi 2 giây
   - Event callbacks: onSuccess, onError, onPaymentDetected
   - Static methods: create(), startPolling()

### Storage Files

3. **`storage/payment_polling_state.json`** - Lưu trạng thái:

   - Last payment ID
   - Last payment timestamp
   - Created orders
   - Failed payments

4. **`storage/pending_payments.json`** - Lưu thanh toán pending

### Documentation

5. **`HUONG_DAN_POLLING_THANH_TOAN.md`** - Hướng dẫn chi tiết
6. **`demo_payment_polling.html`** - Demo interactive

## 🚀 Cách Sử Dụng

### 1. Trang Checkout

```html
<!-- Thêm thư viện -->
<script src="/asset/js/payment-polling.js"></script>

<script>
  // Bắt đầu polling
  const poller = PaymentPoller.startPolling({
    orderId: "ORD001", // Mã đơn hàng
    pollingInterval: 2000, // 2 giây kiểm tra
    maxAttempts: 600, // 20 phút
    autoCreateOrder: true, // Tự động tạo đơn hàng

    onSuccess: (data) => {
      console.log("Đơn hàng tạo thành công:", data.orderId);
      window.location.href = `/order/${data.orderId}`;
    },

    onError: (data) => {
      console.error("Lỗi:", data.message);
    },
  });

  // Dừng polling nếu cần
  // poller.stop();
</script>
```

### 2. API Endpoint

**URL:** `POST /payment/poll-latest-payment`

**Request:**

```json
{
  "order_id": "ORD001",
  "user_id": "123"
}
```

**Response (Thành công):**

```json
{
  "success": true,
  "message": "Thanh toán khớp - Đơn hàng đã được tạo",
  "payment": {...},
  "order_id": "ORD001",
  "order_data": {...}
}
```

## 📊 Luồng Xử Lý

```
┌─────────────────────────────────────────────┐
│ 1. User thanh toán QR Code                  │
└──────────────────┬──────────────────────────┘
                   ↓
┌─────────────────────────────────────────────┐
│ 2. JavaScript polling API liên tục          │
│    (mỗi 2 giây)                             │
└──────────────────┬──────────────────────────┘
                   ↓
┌─────────────────────────────────────────────┐
│ 3. Lấy thanh toán mới nhất từ GAS API       │
└──────────────────┬──────────────────────────┘
                   ↓
┌─────────────────────────────────────────────┐
│ 4. So sánh: Số tiền, nội dung, tài khoản    │
└──────┬──────────────────────────────┬───────┘
       │                              │
       ↓                              ↓
   ✓ Khớp                        ✗ Không khớp
       │                              │
       ↓                              ↓
┌──────────────────────┐   ┌──────────────────────┐
│ Tạo đơn hàng         │   │ Thông báo lỗi        │
│ Xóa giỏ hàng         │   │ Yêu cầu thử lại      │
│ Báo thành công       │   │ Tiếp tục polling     │
└──────────────────────┘   └──────────────────────┘
```

## ✅ Các Tính Năng

- ✓ Polling liên tục từ API
- ✓ So sánh thông tin thanh toán tự động
- ✓ Tạo đơn hàng tự động khi thanh toán khớp
- ✓ Tránh trùng lặp giao dịch
- ✓ Xóa giỏ hàng sau tạo đơn
- ✓ Log activity chi tiết
- ✓ Error handling toàn diện
- ✓ Timeout & retry logic

## 🔧 Cấu Hình

### Google Apps Script API URL

Trong `src/Config/payment.php`:

```php
'google_apps_script' => [
    'payment_api_url' => 'https://script.google.com/macros/s/YOUR_ID/exec',
    'enabled' => true,
],
```

### Polling Parameters

```javascript
{
  orderId: 'ORD001',              // Mã đơn hàng
  pollingInterval: 2000,          // ms - 2 giây
  maxAttempts: 600,               // ~20 phút
  autoCreateOrder: true,          // Tự động tạo
  apiUrl: '/payment/poll-latest-payment'
}
```

## 📝 Log Files

- **`storage/payment_polling.log`** - Polling requests
- **`storage/payment_polling_state.json`** - Current state
- **`storage/check_payment_requests.log`** - Check requests

## 🧪 Test

### Demo Page

```
http://localhost/DuAn1/demo_payment_polling.html
```

Tính năng:

- Generate QR Code
- Start/Stop Polling
- Xem log activity real-time
- Kiểm tra stats

### Manual Testing

```bash
curl -X POST http://localhost/DuAn1/payment/poll-latest-payment \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": "ORD001",
    "user_id": "123"
  }'
```

## 🐛 Troubleshooting

### Polling không hoạt động

- Kiểm tra console log
- Verify API URL
- Check session/authentication

### Payment không được detect

- Verify số tiền
- Check nội dung description
- Kiểm tra số tài khoản

### Order không được tạo

- Kiểm tra giỏ hàng có sản phẩm
- Verify tồn kho
- Check database connection

## 📚 Documentation

- **Hướng dẫn chi tiết:** `HUONG_DAN_POLLING_THANH_TOAN.md`
- **API Documentation:** Trong file controller
- **Demo Page:** `demo_payment_polling.html`

## 🎓 Ví Dụ Sử Dụng

### Checkout Flow Hoàn Chỉnh

```javascript
// 1. Generate order & QR
generateOrder(); // Create ORD001
generateQRCode(); // Create QR with amount

// 2. Start polling
PaymentPoller.startPolling({
  orderId: "ORD001",
  autoCreateOrder: true,

  onSuccess: (data) => {
    // Order created, redirect
    location.href = `/order/${data.orderId}`;
  },
});

// 3. User quét QR thanh toán
// 4. Hệ thống detect & tạo đơn hàng tự động
// 5. User redirect để xem đơn hàng
```

## 🔐 Security

- ✓ Session check
- ✓ User authentication
- ✓ CSRF token support
- ✓ SQL injection prevention
- ✓ Input validation

## ⚡ Performance

- Polling interval: 2 giây (customizable)
- Max attempts: 600 (~20 phút)
- API timeout: 5 giây
- Async request handling

## 📞 Support

Nếu có vấn đề:

1. Kiểm tra log files
2. Xem console browser
3. Test demo page
4. Verify API endpoint

---

**Version:** 1.0  
**Last Updated:** 2025-12-09  
**Status:** ✓ Ready to Use
