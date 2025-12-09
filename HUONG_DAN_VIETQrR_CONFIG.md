# Hướng Dẫn Cấu Hình Thanh Toán VietQR

## 📌 Tổng Quan

Hệ thống thanh toán QR đã được tích hợp đầy đủ với VietQR. Bạn có thể dễ dàng cấu hình thông tin ngân hàng mà không cần sửa code.

## 🚀 Cách Cấu Hình

### Cách 1: Sử Dụng Trang Admin (Khuyên Dùng)

1. **Truy cập Trang Cấu Hình:**
   - URL: `http://yourdomain.com/admin/payment-config` 
   - Hoặc thêm link vào admin menu: `admin/payment-config.php`

2. **Nhập Thông Tin:**
   - **Mã Ngân Hàng**: Chọn từ dropdown (ACB, VIETCOMBANK, BIDV, v.v.)
   - **Số Tài Khoản**: Số tài khoản ngân hàng
   - **Tên Chủ Tài Khoản**: Tên chủ tài khoản (IN HOA, không dấu)
   - **Template QR**: print hoặc compact

3. **Lưu Cấu Hình:**
   - Click nút "Lưu Cấu Hình"
   - Xem trước mã QR ngay trên trang
   - Dữ liệu tự động lưu vào file config

### Cách 2: Chỉnh Sửa File Config Trực Tiếp

1. **Mở file:** `src/Config/payment.php`

2. **Cập nhật thông tin:**
   ```php
   'qr' => [
       'bank_id' => 'ACB',           // Thay đổi thành mã ngân hàng của bạn
       'account_no' => '123456789',  // Thay đổi thành số tài khoản
       'account_name' => 'NGUYEN VAN A',  // Tên chủ tài khoản
       'template' => 'print',        // print hoặc compact
       'enabled' => true,            // Bật/tắt QR
   ],
   ```

3. **Lưu file**

## 📋 Danh Sách Mã Ngân Hàng

Các mã ngân hàng phổ biến:

| Mã | Tên Ngân Hàng |
|---|---|
| ACB | Asia Commercial Bank |
| VIETCOMBANK | Vietcombank |
| BIDV | BIDV |
| TECHCOMBANK | Techcombank |
| SACOMBANK | Sacombank |
| VPBANK | VPBank |
| TPBANK | TPBank |
| AGRIBANK | AgriBank |
| VIETINBANK | Vietinbank |
| MB | MB Bank |
| MSBANK | Maritime Bank |
| OCB | OCB |
| SHINHAN | Shinhan Bank |
| SCB | SCB |
| SHB | Shinhan Bank |

> Để xem danh sách đầy đủ, truy cập: https://vietqr.io/

## 🔧 API Endpoints

### 1. Lấy Mã QR Code

**Endpoint:** `GET /payment/get-qr-code`

**Parameters:**
- `amount` (optional): Số tiền (VND)
- `description` (optional): Nội dung chuyển khoản

**Response:**
```json
{
  "success": true,
  "qr_url": "https://img.vietqr.io/image/ACB-123456789-print.png?amount=100000&...",
  "bank_id": "ACB",
  "account_no": "123456789",
  "account_name": "NGUYEN VAN A",
  "amount": 100000
}
```

### 2. Lấy Cấu Hình (Admin Only)

**Endpoint:** `GET /payment/config`

**Response:**
```json
{
  "success": true,
  "config": {
    "bank_id": "ACB",
    "account_no": "123456789",
    "account_name": "NGUYEN VAN A",
    "template": "print",
    "enabled": true
  },
  "banks": { /* danh sách ngân hàng */ }
}
```

### 3. Cập Nhật Cấu Hình (Admin Only)

**Endpoint:** `POST /payment/update-config`

**POST Parameters:**
```
bank_id=ACB
account_no=123456789
account_name=NGUYEN VAN A
template=print
```

**Response:**
```json
{
  "success": true,
  "message": "Configuration updated successfully",
  "config": { /* cấu hình mới */ }
}
```

## 💡 Cách Hoạt Động

### Trên Trang Checkout

1. **Mặc định:** Radio button "OPT (Tiền Mặt)" được chọn
   - Section QR bị ẩn

2. **Khi Chọn "Thanh Toán Online (QR Code)":**
   - Section QR hiển thị
   - JavaScript gọi API `/payment/get-qr-code`
   - Mã QR được tải động dựa trên:
     - Tổng tiền đơn hàng
     - Thông tin từ file config

### URL QR Code

Mã QR được sinh từ VietQR API:

```
https://img.vietqr.io/image/{BANK_ID}-{ACCOUNT_NO}-{TEMPLATE}.png?amount={AMOUNT}&addInfo={DESCRIPTION}&accountName={ACCOUNT_NAME}
```

**Ví dụ:**
```
https://img.vietqr.io/image/ACB-123456789-print.png?amount=500000&addInfo=Thanh%20toan%20don%20hang&accountName=NGUYEN%20VAN%20A
```

## 📁 Cấu Trúc File

```
src/
├── Config/
│   └── payment.php                    # File cấu hình
├── Core/
│   └── PaymentHelper.php              # Helper class
├── Controllers/
│   └── PaymentController.php          # API endpoints
└── Views/
    ├── CheckoutConfirm.php            # Trang thanh toán (cập nhật)
    └── admin/
        └── payment-config.php         # Trang cấu hình admin
```

## 🔐 Bảo Mật

- **API `/payment/get-qr-code`**: Công khai (ai cũng dùng được)
- **API `/payment/config`**: Chỉ admin
- **API `/payment/update-config`**: Chỉ admin

## 🐛 Xử Lý Sự Cố

### QR Code không hiển thị

1. Kiểm tra file config: `src/Config/payment.php`
2. Đảm bảo `bank_id` và `account_no` không rỗng
3. Kiểm tra Console (F12) xem có lỗi API không
4. Thử reload lại trang

### Lỗi "Không thể lưu cấu hình"

1. Kiểm tra quyền ghi file: `src/Config/payment.php`
2. Đảm bảo user đã login và có role 'admin'
3. Kiểm tra PHP error log

### QR hiển thị nhưng không đúng

1. Kiểm tra số tài khoản có đúng không
2. Kiểm tra mã ngân hàng có hợp lệ không
3. Thử xem trước trên trang cấu hình

## 📞 Liên Hệ & Hỗ Trợ

- VietQR Documentation: https://vietqr.io/
- VietQR API: https://img.vietqr.io/

## ✅ Checklist Hoàn Tất

- [ ] Truy cập trang admin: `/admin/payment-config.php`
- [ ] Chọn ngân hàng
- [ ] Nhập số tài khoản
- [ ] Nhập tên chủ tài khoản
- [ ] Xem trước mã QR
- [ ] Lưu cấu hình
- [ ] Test trên trang checkout
- [ ] Chọn "Thanh toán Online" và xem mã QR
