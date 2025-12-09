# Hướng Dẫn Cấu Hình Thanh Toán QR Code

## Tổng Quan

Tính năng thanh toán đã được tích hợp với 2 phương thức:

- **OPT (Tiền mặt)**: Thanh toán khi nhận hàng
- **Online (QR Code)**: Thanh toán qua mã QR Code

## Cấu Trúc

### 1. Giao Diện (Frontend)

**File**: `src/Views/CheckoutConfirm.php`

#### Thành phần:

- Radio button chọn phương thức thanh toán
- Section hiển thị mã QR (ẩn mặc định, hiện khi chọn "Thanh toán Online")
- Placeholder để chứa ảnh QR code

#### JavaScript Logic:

```javascript
- Khi chọn "OPT": Ẩn section QR
- Khi chọn "Online": Hiển thị section QR
```

### 2. Backend

**File**: `src/Controllers/CartController.php` - Hàm `placeOrder()`

#### Xử lý:

- Nhận giá trị `payment_method` từ form POST
- Validate phương thức (chỉ cho phép 'opt' hoặc 'online')
- Lưu vào database trong trường `payment_method` của Order
- Tạo message phản hồi phù hợp

## Cấu Hình Mã QR

### Cách 1: Sử dụng File Hình Ảnh (Tĩnh)

1. Tạo mã QR code từ các trang web miễn phí:

   - https://qr-code-generator.com/
   - https://www.qr-code-generator.de/

2. Đặt ảnh QR vào: `public/images/qr-code.jpg`

3. Cập nhật đường dẫn trong `CheckoutConfirm.php`:

   ```php
   <img src="<?php echo ROOT_URL; ?>public/images/qr-code.jpg" alt="QR Code thanh toán" id="qrImage">
   ```

4. Bỏ comment dòng này trong JavaScript:
   ```javascript
   qrImage.style.display = "block";
   qrPlaceholder.style.display = "none";
   ```

### Cách 2: Tạo API Động (Nâng Cao)

1. Tạo file controller mới: `src/Controllers/PaymentController.php`

2. Tạo hàm API để lấy QR:

   ```php
   public function getQRCode() {
       // Trả về URL hoặc base64 của mã QR
       echo json_encode([
           'qr_url' => ROOT_URL . 'public/images/qr-code.jpg',
           'qr_base64' => base64_encode(file_get_contents(...))
       ]);
   }
   ```

3. Bỏ comment dòng này trong `CheckoutConfirm.php`:
   ```javascript
   loadQRCode(); // Uncomment khi sẵn sàng
   ```

### Cách 3: Tích Hợp API Thanh Toán (Nếu cần)

Nếu muốn tích hợp với cổng thanh toán (Momo, Viettel Pay, v.v.):

1. Tạo QR động từ API cổng thanh toán
2. Cập nhật endpoint trong hàm `loadQRCode()`
3. Xử lý callback từ cổng thanh toán để cập nhật trạng thái đơn hàng

## Database Update

Thêm trường `payment_method` vào bảng `Order` nếu chưa có:

```sql
ALTER TABLE `Order` ADD COLUMN `payment_method` VARCHAR(20) DEFAULT 'opt' AFTER `status`;
```

Hoặc nếu bạn muốn tạo bảng riêng để quản lý thanh toán:

```sql
CREATE TABLE `Payment` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `order_id` VARCHAR(50),
    `method` VARCHAR(20), -- 'opt' hoặc 'online'
    `amount` DECIMAL(10, 2),
    `status` VARCHAR(20) DEFAULT 'pending', -- 'pending', 'paid', 'failed'
    `qr_data` LONGTEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`order_id`) REFERENCES `Order`(`id`)
);
```

## Kiểm Tra Hoạt Động

1. Truy cập trang thanh toán: `/cart/checkout`
2. Chọn "Thanh toán OPT" → Section QR bị ẩn ✓
3. Chọn "Thanh toán Online" → Section QR hiển thị ✓
4. Đặt hàng → Kiểm tra database `payment_method` đã được lưu ✓

## Tài Nguyên Bổ Sung

- **Thư viện QR Code PHP**: `php-qrcode` (phpqrcode.sourceforge.net)
- **Thư viện QR Code JS**: `qrcode.js`, `jsqrcode`

## Tiếp Theo

Để hoàn thiện tính năng:

1. Cấu hình mã QR thực tế (hình ảnh hoặc API)
2. Cập nhật database schema nếu cần
3. Thêm xác nhận thanh toán/xử lý callback
4. Hiển thị trạng thái thanh toán trong chi tiết đơn hàng
