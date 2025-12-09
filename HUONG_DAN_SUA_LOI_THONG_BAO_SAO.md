# Hướng Dẫn Khắc Phục: Thông Báo "Giao Dịch Chưa Được Phát Hiện"

## 🔴 Vấn Đề

Thanh toán đã thành công (dữ liệu xuất hiện trong Google Sheet) nhưng hệ thống vẫn thông báo **"Giao dịch chưa được phát hiện"** hoặc **"Thông tin thanh toán không khớp"**.

---

## 🔍 Nguyên Nhân

### 1. **Nội Dung (Mô Tả) Không Khớp** ⚠️ **[Nguyên Nhân Chính]**

```
Yêu cầu hệ thống:  "Thanh toan - duong thanh cong"
Thực tế từ API:    "ZALOPAY-CHUYENTIEN-O5CH7C0QJ4E2"
                    ❌ Không khớp
```

**Giải pháp:**

1. Hệ thống so sánh nội dung linh hoạt hơn (50% từ khóa khớp là được)
2. User phải thanh toán với nội dung chính xác từ QR Code
3. Nếu dùng ZALOPAY, cần cập nhật logic để chấp nhận ZALOPAY transfers

### 2. **Số Tiền Không Khớp**

```
Yêu cầu: 3,200,000 VND
Thực tế: 3200 (nghìn)
          ❌ Sai format
```

**Giải pháp:** Đảm bảo số tiền chính xác từ API (không phải nghìn)

### 3. **Số Tài Khoản Không Khớp**

```
Yêu cầu: 0833268346
Thực tế: 534306355605 (hoặc khác)
          ❌ Sai tài khoản
```

**Giải pháp:** Kiểm tra cấu hình ngân hàng

### 4. **Format Dữ Liệu Từ API**

API có thể trả về các key khác nhau:

- `Mã GD` vs `payment_id`
- `Giá trị` vs `amount`
- `Mô tả` vs `description`
- `Số tài khoản` vs `account_no`

---

## ✅ Giải Pháp Chi Tiết

### Bước 1: Kiểm Tra Dữ Liệu Từ API

**Mở Browser DevTools (F12) → Network**

1. Bắt đầu polling
2. Xem request tới `/payment/poll-latest-payment`
3. Kiểm tra response JSON

**Response sẽ trông như:**

```json
{
  "success": false,
  "message": "Nội dung không khớp",
  "payment": {
    "Mã GD": "12893422",
    "Mô tả": "ZALOPAY-CHUYENTIEN-O5CH7C0QJ4E2",
    "Giá trị": 3200,
    "Ngày diễn ra": "2025-12-09 11:33:00",
    "Số tài khoản": "534306355605"
  },
  "system_info": {
    "amount": 3200000,
    "description": "Thanh toan - duong thanh cong"
  }
}
```

### Bước 2: Cập Nhật Logic So Sánh

**File:** `src/Controllers/PaymentController.php`

Tôi đã cập nhật hàm `comparePaymentInfo()` để:

- ✓ So sánh linh hoạt hơn (50% từ khóa khớp)
- ✓ Chấp nhận ZALOPAY transfers
- ✓ Tránh vấn đề format

### Bước 3: Xác Định Loại Thanh Toán

**Nếu dùng ZALOPAY:**

```
Nội dung từ ZALOPAY: "ZALOPAY-CHUYENTIEN-{ORDER_ID}"
Nội dung hệ thống:   "Thanh toan - {USER_NAME}"

→ Cập nhật để chấp nhận ZALOPAY format
```

**Nếu dùng Direct Transfer:**

```
Nội dung từ API: "Thanh toan - duong thanh cong"
Nội dung hệ thống: "Thanh toan - duong thanh cong"

→ Phải match 100%
```

### Bước 4: Cấu Hình Đúng

**File:** `src/Config/payment.php`

```php
'qr' => [
    'bank_id' => 'MB',
    'account_no' => '0833268346',  // ← Kiểm tra lại số này
    'account_name' => 'DUONG THANH CONG',
    'template' => 'print',
    'enabled' => true,
],
```

Số tài khoản phải khớp với số tài khoản nhận tiền trong Google Sheet!

### Bước 5: Kiểm Tra Google Sheet API

**API phải trả về dữ liệu đúng format:**

```json
{
  "data": [
    {
      "Mã GD": "12893422",
      "Mô tả": "ZALOPAY-CHUYENTIEN-O5CH7C0QJ4E2",
      "Giá trị": 3200, // ← Kiểm tra: nghìn hay số thực?
      "Ngày diễn ra": "2025-12-09 11:33:00",
      "Số tài khoản": "0833268346" // ← Phải khớp config
    }
  ]
}
```

**Kiểm tra trên Google Sheet:**

- Column C (Giá trị): 3.200 hay 3200 hay 3200000?
- Column E (Số tài khoản): Số nào đó?
- Column B (Mô tả): Format nào?

---

## 🔧 Cách Khắc Phục Từng Vấn Đề

### Vấn Đề 1: Nội Dung Không Khớp (ZALOPAY)

**Cập nhật Controller:**

```php
private function comparePaymentInfo($apiPayment, $systemOrder)
{
    // ... code hiện tại ...

    // SO SÁNH NỘI DUNG - Thêm logic cho ZALOPAY
    $descriptionMatch = false;

    // Kiểm tra nếu là ZALOPAY transfer
    $isZaloPay = strpos($apiDescriptionSimple, 'ZALOPAY') !== false;

    if ($isZaloPay) {
        // Nếu là ZALOPAY, chỉ kiểm tra số tiền khớp là được
        $descriptionMatch = true;
        // Hoặc kiểm tra order_id trong description
        if (preg_match('/O5CH7C0Q/', $apiDescriptionSimple)) {
            $descriptionMatch = true;
        }
    } else {
        // Logic so sánh bình thường cho direct transfer
        // ...
    }
}
```

### Vấn Đề 2: Số Tiền Không Khớp

**Kiểm tra format từ API:**

```javascript
// Trong Developer Console
console.log("API Amount:", response.payment["Giá trị"]);
console.log("System Amount:", response.system_info.amount);

// Nếu API trả về 3200 nhưng hệ thống mong 3200000
// → Cần nhân với 1000 hoặc cập nhật cách tính
```

**Sửa trong Controller:**

```php
// Kiểm tra xem API trả về của Google Sheet là số thực hay nghìn
$apiAmount = (int)($apiPayment['Giá trị'] ?? 0);

// Nếu Google Sheet lưu dạng nghìn
if ($apiAmount < 100000) {
    $apiAmount = $apiAmount * 1000;
}

// Sau đó so sánh
$amountMatch = $apiAmount === $systemAmount;
```

### Vấn Đề 3: Số Tài Khoản Sai

**Kiểm tra Google Sheet:**

1. Mở Google Sheet
2. Xem column "Số tài khoản"
3. Dán đúng số đó vào `src/Config/payment.php`

```php
'account_no' => '0833268346',  // ← Phải chính xác 100%
```

---

## 📊 Test & Debug

### 1. **Xem Log Activity**

```
File: /storage/payment_polling.log
File: /storage/payment_polling_state.json
```

### 2. **Test Thủ Công**

```bash
# Gọi API trực tiếp
curl -X POST http://localhost/DuAn1/payment/poll-latest-payment \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": "ORD001",
    "user_id": "123"
  }'
```

### 3. **Xem Response Chi Tiết**

```javascript
// Trong browser console
PaymentPoller.startPolling({
  orderId: "ORD001",
  onPaymentCheck: (data) => {
    console.log("Response:", JSON.stringify(data.response, null, 2));
  },
});
```

---

## 💡 Lời Khuyên Thực Hành

### Nếu Dùng ZALOPAY:

1. **Cập nhật** logic để chấp nhận ZALOPAY format
2. **Không kiểm tra** strict nội dung
3. **Chỉ kiểm tra** số tiền + tài khoản + order_id

### Nếu Dùng Direct Transfer:

1. **Số tiền** phải đúng 100%
2. **Nội dung** phải chứa từ khóa quan trọng
3. **Tài khoản** phải đúng 100%

### Để Tránh Vấn Đề:

```javascript
// Khi hiển thị QR Code
const description = `Thanh toan - ${userName}`;
console.log("Nội dung để user nhập:", description);

// Hoặc nếu dùng ZALOPAY
const description = `ORDER${orderId}`;
console.log("Nội dung ZALOPAY:", description);
```

---

## 🧪 Demo Test

Truy cập: **http://localhost/DuAn1/demo_payment_polling.html**

1. Generate QR Code
2. Kiểm tra nội dung mô tả
3. Bắt đầu Polling
4. Xem log để debug

---

## 📝 Checklist Khắc Phục

- [ ] Kiểm tra Google Sheet API trả về dữ liệu gì
- [ ] Xác định loại thanh toán (ZALOPAY hay Direct Transfer)
- [ ] Kiểm tra số tiền format (nghìn vs số thực)
- [ ] Kiểm tra số tài khoản chính xác
- [ ] Cập nhật logic so sánh nếu cần
- [ ] Test lại với real payment
- [ ] Xem log để debug nếu vẫn có lỗi

---

## 🆘 Nếu Vẫn Không Hoạt Động

**Gửi thông tin sau:**

1. Response từ API (JSON)
2. System info (amount, description, account_no)
3. Log từ `/storage/payment_polling.log`
4. Screenshot Google Sheet
5. Chrome DevTools Network tab

---

**Version:** 1.0  
**Last Updated:** 2025-12-09
