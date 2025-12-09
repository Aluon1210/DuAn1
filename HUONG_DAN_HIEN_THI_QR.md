# Hướng Dẫn Hiển Thị Mã QR Động

## 📌 Cách Hoạt Động

Mã QR được tạo động từ VietQR API với các tham số bạn cung cấp.

### 🔗 URL VietQR Format:

```
https://img.vietqr.io/image/{BANK_ID}-{ACCOUNT_NO}-{TEMPLATE}.png?amount={AMOUNT}&addInfo={DESCRIPTION}&accountName={ACCOUNT_NAME}
```

### 📊 Các Tham Số:

| Tham Số        | Ví Dụ               | Mô Tả                 |
| -------------- | ------------------- | --------------------- |
| `BANK_ID`      | ACB, MB, BIDV       | Mã ngân hàng          |
| `ACCOUNT_NO`   | 123456789           | Số tài khoản          |
| `TEMPLATE`     | print, compact      | Kiểu hiển thị QR      |
| `AMOUNT`       | 500000              | Số tiền (VND)         |
| `DESCRIPTION`  | Thanh toan don hang | Nội dung chuyển khoản |
| `ACCOUNT_NAME` | NGUYEN VAN A        | Tên chủ tài khoản     |

## 🔨 Cách Sử Dụng

### Cách 1: PHP - PaymentHelper::buildQRUrl()

```php
<?php
use Core\PaymentHelper;

// Tạo QR URL với các tham số cụ thể
$qrUrl = PaymentHelper::buildQRUrl(
    'MB',                    // BANK_ID
    '0833268346',            // ACCOUNT_NO
    'DUONG THANH CONG',      // ACCOUNT_NAME
    500000,                  // AMOUNT
    'Thanh toan don hang',   // DESCRIPTION
    'print'                  // TEMPLATE
);

// Kết quả:
// https://img.vietqr.io/image/MB-0833268346-print.png?amount=500000&addInfo=Thanh+toan+don+hang&accountName=DUONG+THANH+CONG
?>
```

### Cách 2: HTML - Truyền Trực Tiếp

```html
<!-- Ví dụ: Hiển thị QR với các tham số cụ thể -->
<img
  src="https://img.vietqr.io/image/MB-0833268346-print.png?amount=500000&addInfo=Thanh+toan+don+hang&accountName=DUONG+THANH+CONG"
  alt="QR Code"
  style="width:200px; height:200px;"
/>
```

### Cách 3: JavaScript - Tạo URL Động

```javascript
// Ví dụ: Tạo URL QR từ JavaScript
function generateQRUrl(bankId, accountNo, accountName, amount, description) {
  const baseUrl = "https://img.vietqr.io/image/";
  const qrPart = `${bankId}-${accountNo}-print.png`;
  const params = new URLSearchParams({
    amount: amount,
    addInfo: description,
    accountName: accountName,
  });

  return baseUrl + qrPart + "?" + params.toString();
}

// Sử dụng
const qrUrl = generateQRUrl(
  "MB",
  "0833268346",
  "DUONG THANH CONG",
  500000,
  "Thanh toan don hang"
);
console.log(qrUrl);
// Kết quả: https://img.vietqr.io/image/MB-0833268346-print.png?amount=500000&addInfo=Thanh+toan+don+hang&accountName=DUONG+THANH+CONG

// Hiển thị ảnh
document.getElementById("qrImage").src = qrUrl;
document.getElementById("qrImage").style.display = "block";
```

## 🎯 Quy Trình Hiển Thị QR Trên Trang Thanh Toán

```
1. User truy cập trang: /cart/checkout
   ↓
2. Form hiển thị với 2 option thanh toán:
   - Thanh toán OPT (Tiền mặt) ← Mặc định
   - Thanh toán Online (QR Code) ← Ẩn mặc định
   ↓
3. User chọn "Thanh toán Online (QR Code)"
   ↓
4. JavaScript gọi hàm: updatePaymentDisplay()
   ↓
5. Section QR hiển thị, gọi hàm: loadQRCode()
   ↓
6. loadQRCode() fetch API: /payment/get-qr-code?amount={totalAmount}&description=...
   ↓
7. Server trả về JSON:
   {
     "success": true,
     "qr_url": "https://img.vietqr.io/image/MB-0833268346-print.png?amount=500000&...",
     "bank_id": "MB",
     "account_no": "0833268346",
     "account_name": "DUONG THANH CONG",
     "amount": 500000
   }
   ↓
8. JavaScript set src của <img id="qrImage"> = qr_url
   ↓
9. Browser tải ảnh từ VietQR API
   ↓
10. Hiển thị mã QR trên trang (như hình trong request)
```

## 📄 File Liên Quan

| File                                    | Chức Năng             |
| --------------------------------------- | --------------------- |
| `src/Views/CheckoutConfirm.php`         | Giao diện hiển thị QR |
| `src/Core/PaymentHelper.php`            | Hàm tạo QR URL        |
| `src/Controllers/PaymentController.php` | API trả về QR URL     |
| `src/Config/payment.php`                | Cấu hình ngân hàng    |

## 🔧 Cấu Hình (src/Config/payment.php)

```php
'qr' => [
    'bank_id' => 'MB',                    // Mã ngân hàng
    'account_no' => '0833268346',         // Số tài khoản
    'account_name' => 'DUONG THANH CONG', // Tên tài khoản
    'template' => 'print',                // Loại template
    'enabled' => true,                    // Bật/tắt QR
],
```

## 🌐 API Endpoints

### GET /payment/get-qr-code

Lấy mã QR URL

**Parameters:**

- `amount` (int) - Số tiền
- `description` (string) - Nội dung

**Response:**

```json
{
  "success": true,
  "qr_url": "https://img.vietqr.io/image/...",
  "bank_id": "MB",
  "account_no": "0833268346",
  "account_name": "DUONG THANH CONG",
  "amount": 500000
}
```

## 💡 Ví Dụ Thực Tế

### Ví dụ 1: QR với số tiền cụ thể

```
https://img.vietqr.io/image/MB-0833268346-print.png?amount=500000&addInfo=Thanh+toan+don+hang&accountName=DUONG+THANH+CONG
```

### Ví dụ 2: QR không có số tiền

```
https://img.vietqr.io/image/MB-0833268346-print.png?accountName=DUONG+THANH+CONG
```

### Ví dụ 3: QR compact

```
https://img.vietqr.io/image/MB-0833268346-compact.png?amount=500000&addInfo=Test&accountName=DUONG+THANH+CONG
```

## ✅ Kiểm Tra Hoạt Động

1. Truy cập trang: `http://yourdomain.com/cart/checkout`
2. Click "Thanh toán Online (QR Code)"
3. Mã QR sẽ hiển thị động
4. Thử scan bằng điện thoại để test

## 🐛 Xử Lý Sự Cố

### QR không hiển thị?

1. Kiểm tra Console (F12) → Network
2. Xác nhận API `/payment/get-qr-code` trả về đúng
3. Kiểm tra cấu hình `bank_id` và `account_no` trong `src/Config/payment.php`

### URL QR sai?

1. Kiểm tra các tham số: `BANK_ID`, `ACCOUNT_NO`, `ACCOUNT_NAME`
2. Đảm bảo `ACCOUNT_NAME` không có dấu và IN HOA
3. Xem console.log output để debug

### Số tiền (AMOUNT) không đúng?

1. Kiểm tra biến `totalAmount` trong JavaScript
2. Xác nhận dữ liệu từ server (PHP)

## 📚 Tham Khảo Thêm

- VietQR Documentation: https://vietqr.io/
- VietQR Image API: https://img.vietqr.io/
- Danh sách ngân hàng: https://vietqr.io/

---

**Lưu ý:** Mã QR được tạo bởi VietQR API, tất cả dữ liệu được truyền qua URL parameters.
