# Hướng Dẫn Sử Dụng Hệ Thống Polling Thanh Toán Tự Động

## Tổng Quan

Hệ thống này tự động kiểm tra dữ liệu thanh toán mới từ Google Apps Script API liên tục, so sánh với thông tin thanh toán hệ thống tạo, và tự động tạo đơn hàng nếu thanh toán khớp.

### Luồng Xử Lý

```
1. User thanh toán QR Code
   ↓
2. Hệ thống ghi lại thông tin thanh toán dự kiến
   ↓
3. JavaScript polling API liên tục (mỗi 2 giây)
   ↓
4. Lấy thanh toán mới nhất từ Google Apps Script API
   ↓
5. So sánh: Số tiền, nội dung, số tài khoản
   ↓
6. Nếu khớp → Tạo đơn hàng + Xóa giỏ hàng
   Nếu không khớp → Thông báo lỗi
```

## Cấu Hình

### 1. API Endpoint Google Apps Script

Đảm bảo cấu hình đúng trong `src/Config/payment.php`:

```php
'google_apps_script' => [
    'payment_api_url' => 'https://script.google.com/macros/s/YOUR_SCRIPT_ID/exec',
    'enabled' => true,
],
```

API này phải trả về JSON với cấu trúc:

```json
{
  "data": [
    {
      "Mã GD": "GD001",
      "Giá trị": 3200000,
      "Mô tả": "Thanh toan - Ten Khach Hang",
      "Ngày diễn ra": "2025-12-09 08:18:54",
      "Số tài khoản": "0833268346"
    }
  ]
}
```

### 2. Số Tài Khoản Ngân Hàng

Cấu hình trong `src/Config/payment.php`:

```php
'qr' => [
    'bank_id' => 'MB',
    'account_no' => '0833268346',
    'account_name' => 'DUONG THANH CONG',
    'template' => 'print',
    'enabled' => true,
],
```

## Sử Dụng

### 1. Trang Checkout (HTML)

```html
<!-- Thêm thư viện polling -->
<script src="/asset/js/payment-polling.js"></script>

<!-- QR Code Display -->
<div id="qrContainer">
  <img id="qrCode" src="" alt="QR Code" />
</div>

<!-- Lưu thông tin user -->
<div data-user-id="USER_ID" style="display: none;"></div>

<script>
  // Sau khi generate QR code
  const poller = PaymentPoller.startPolling({
    orderId: "ORD001", // Optional - để enable auto create order
    pollingInterval: 2000, // 2 giây kiểm tra một lần
    maxAttempts: 600, // 20 phút
    autoCreateOrder: true, // Tự động tạo đơn hàng

    onPaymentDetected: (data) => {
      console.log("Phát hiện thanh toán:", data.payment);
      showNotification("Phát hiện thanh toán", "info");
    },

    onSuccess: (data) => {
      console.log("Đơn hàng đã được tạo:", data.orderId);
      showNotification("Đơn hàng đã được tạo!", "success");
      setTimeout(() => {
        window.location.href = `/order/${data.orderId}`;
      }, 2000);
    },

    onError: (data) => {
      console.error("Lỗi:", data.message);
      showNotification("Lỗi: " + data.message, "error");
    },

    onPollingStart: (data) => {
      document.getElementById("statusText").textContent =
        "Đang kiểm tra thanh toán...";
    },

    onPollingStop: (data) => {
      document.getElementById("statusText").textContent = "Dừng kiểm tra";
    },
  });

  // Dừng polling nếu cần
  // poller.stop();
</script>
```

### 2. PHP - Gọi API Polling

```php
// POST /payment/poll-latest-payment
$response = file_get_contents('http://localhost/DuAn1/payment/poll-latest-payment', false,
    stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => json_encode([
                'order_id' => 'ORD001',
                'user_id' => '123'
            ])
        ]
    ])
);

$result = json_decode($response, true);

// Kết quả trả về:
// {
//   "success": true/false,
//   "message": "...",
//   "payment": {...},
//   "order_id": "ORD001" (nếu auto create)
// }
```

## API Endpoints

### 1. `/payment/poll-latest-payment` (POST)

Kiểm tra và xử lý thanh toán mới nhất

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
  "message": "Thanh toán khớp - Đơn hàng đã được tạo thành công",
  "payment": {
    "Mã GD": "GD001",
    "Giá trị": 3200000,
    "Mô tả": "Thanh toan - Customer Name",
    "Ngày diễn ra": "2025-12-09 08:18:54",
    "Số tài khoản": "0833268346"
  },
  "order_id": "ORD001",
  "order_data": {...}
}
```

**Response (Lỗi):**

```json
{
  "success": false,
  "message": "Lỗi chi tiết",
  "payment": {...}
}
```

### 2. `/payment/check-payment` (POST)

Kiểm tra một giao dịch cụ thể (một lần)

**Request:**

```json
{
  "order_id": "ORD001",
  "amount": 3200000,
  "description": "Thanh toan - Customer",
  "account_no": "0833268346",
  "bank_id": "MB"
}
```

## Lưu Trữ & Trạng Thái

### 1. `storage/payment_polling_state.json`

Lưu trạng thái polling:

```json
{
  "last_checked_payment_id": "GD001",
  "last_payment_timestamp": "2025-12-09 08:18:54",
  "polling_enabled": true,
  "polling_interval_seconds": 2,
  "last_polling_timestamp": "2025-12-09 08:20:00",
  "created_orders": [
    {
      "payment_id": "GD001",
      "payment_amount": 3200000,
      "processed_at": "2025-12-09 08:18:54"
    }
  ],
  "failed_payments": []
}
```

### 2. `storage/payment_polling.log`

Log các lần kiểm tra:

```
---
TIMESTAMP: 2025-12-09 08:18:54
API_URL: https://script.google.com/macros/...
HTTP_CODE: 200
CURL_ERROR:
RESPONSE: {"data":[...]}
---
```

## Tính Năng

### 1. So Sánh Thông Tin Thanh Toán

```
Kiểm tra:
✓ Số tiền chính xác
✓ Nội dung chuyển khoản chứa từ khóa
✓ Số tài khoản nhận (nếu có)
```

### 2. Xử Lý Lỗi

```
Nếu không khớp:
✗ Báo lỗi chi tiết (tiền, nội dung, tài khoản)
✗ Không tạo đơn hàng
✗ Yêu cầu user thử lại
```

### 3. Tránh Trùng Lặp

```
Kiểm tra:
✓ Payment ID không trùng
✓ Timestamp không trùng
✓ Chỉ tạo đơn hàng một lần
```

### 4. Auto Create Order

```
Khi enabled:
1. Lấy items từ giỏ hàng
2. Kiểm tra tồn kho
3. Tính tổng tiền so với thanh toán
4. Tạo order + order_details
5. Xóa giỏ hàng
6. Trả về order_id
```

## Các Điểm Lưu Ý

### 1. Session & Authentication

- Polling API yêu cầu user phải đăng nhập
- Check session `$_SESSION['user']`

### 2. Timeout API

- Google Apps Script timeout: 5 giây
- Nếu timeout, tiếp tục polling

### 3. Polling Interval

- Mặc định: 2 giây
- Có thể custom: `pollingInterval: 3000`
- Không quá nhanh (tránh quá tải API)

### 4. Max Attempts

- Mặc định: 600 lần (20 phút)
- Có thể custom: `maxAttempts: 1800` (60 phút)

### 5. Order ID Generation

- Có thể tạo trước hoặc server tạo
- Format: `ORD` + timestamp + random

## Troubleshooting

### 1. Polling không hoạt động

```javascript
// Kiểm tra console log
console.log("Polling active:", poller.pollingActive);
console.log("Attempts:", poller.attempts);
```

### 2. Payment không được detect

```
Kiểm tra:
- API URL đúng?
- Số tiền có khớp?
- Nội dung có chứa từ khóa?
- Số tài khoản có khớp?
- Browser console có lỗi?
```

### 3. Order không được tạo

```
Kiểm tra:
- Giỏ hàng có sản phẩm?
- Có tồn kho?
- Database kết nối ok?
- Địa chỉ được set?
```

### 4. Xem Log

```
File log: /storage/payment_polling.log
File state: /storage/payment_polling_state.json
Check requests: /storage/check_payment_requests.log
```

## Ví Dụ Thực Tế

### Scenario 1: Checkout Hoàn Chỉnh

```javascript
// 1. User chọn QR payment
const amount = 3200000;
const address = "Hà Nội, Việt Nam";

// 2. Generate QR code
const qr = generateQR(amount, "Thanh toan - Customer");

// 3. Bắt đầu polling
const poller = PaymentPoller.startPolling({
  orderId: generateOrderId(),
  pollingInterval: 2000,
  maxAttempts: 600,

  onSuccess: (data) => {
    console.log("✓ Đơn hàng tạo thành công:", data.orderId);
    // Redirect hoặc show success
  },

  onError: (data) => {
    console.log("✗ Lỗi:", data.message);
    // Show error modal
  },
});

// 4. User quét QR và thanh toán
// 5. Hệ thống tự động detect và tạo đơn hàng
```

### Scenario 2: Manual Check

```javascript
// User click "Check Payment" button
const response = await fetch("/payment/check-payment", {
  method: "POST",
  body: JSON.stringify({
    order_id: "ORD001",
    amount: 3200000,
    description: "Thanh toan don hang",
    account_no: "0833268346",
  }),
});

const result = await response.json();
if (result.success) {
  // Thanh toán được xác nhận
  // User có thể click "Create Order"
}
```

---

**Phiên bản:** 1.0  
**Cập nhật lần cuối:** 2025-12-09  
**Liên hệ:** Dự án DuAn1
