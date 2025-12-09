# API Tạo Đơn Hàng Khi Thanh Toán QR Thành Công

## Tổng Quan

API này cho phép tự động tạo đơn hàng khi thanh toán QR thành công. Sau khi xác nhận giao dịch, hệ thống sẽ:

1. Lấy giỏ hàng của user
2. Tạo đơn hàng với tất cả chi tiết sản phẩm
3. Xóa giỏ hàng sau khi đơn hàng được tạo thành công

---

## Endpoint

**URL:** `/payment/create-order-on-payment`

**Method:** `POST`

**Content-Type:** `application/json`

---

## Authentication

Yêu cầu user phải **đã đăng nhập** (có session user).

---

## Request Body (JSON)

```json
{
  "amount": 1500000,
  "description": "Thanh toán đơn hàng",
  "address": "123 Đường ABC, Quận 1, TP.HCM",
  "note": "Giao hàng vào chiều, không rung lắc"
}
```

### Các tham số:

| Tham số       | Kiểu   | Bắt buộc | Mô tả                                                   |
| ------------- | ------ | -------- | ------------------------------------------------------- |
| `amount`      | number | ✓        | Tổng tiền thanh toán (VND). Phải khớp với tổng giỏ hàng |
| `description` | string | ✗        | Mô tả giao dịch (tùy chọn)                              |
| `address`     | string | ✓        | Địa chỉ giao hàng                                       |
| `note`        | string | ✗        | Ghi chú cho đơn hàng (tùy chọn)                         |

---

## Response Success (201 Created)

```json
{
  "success": true,
  "message": "Đơn hàng đã được tạo thành công",
  "order_id": "Ord0000000001",
  "order_data": {
    "Order_Id": "Ord0000000001",
    "Order_date": "2025-12-09",
    "Adress": "123 Đường ABC, Quận 1, TP.HCM",
    "Note": "Giao hàng vào chiều",
    "TrangThai": "pending",
    "_UserName_Id": "user123",
    "user_name": "Nguyễn Văn A",
    "user_email": "nguyenvana@email.com"
  },
  "items_count": 3,
  "total_amount": 1500000
}
```

---

## Response Errors

### 400 - Bad Request

**Khi amount bị thiếu:**

```json
{
  "success": false,
  "message": "amount là bắt buộc"
}
```

**Khi address bị thiếu:**

```json
{
  "success": false,
  "message": "address là bắt buộc"
}
```

**Khi giỏ hàng trống:**

```json
{
  "success": false,
  "message": "Giỏ hàng trống - không thể tạo đơn hàng"
}
```

**Khi tổng tiền không khớp:**

```json
{
  "success": false,
  "message": "Tổng tiền không khớp. Giỏ hàng: 1500000 VND, thanh toán: 1400000 VND",
  "cart_total": 1500000,
  "payment_amount": 1400000
}
```

### 401 - Unauthorized

```json
{
  "success": false,
  "message": "Unauthorized - Vui lòng đăng nhập"
}
```

### 405 - Method Not Allowed

```json
{
  "success": false,
  "message": "Method not allowed"
}
```

### 500 - Server Error

```json
{
  "success": false,
  "message": "Lỗi server - không thể tạo đơn hàng",
  "error": "Chi tiết lỗi"
}
```

---

## Luồng Xử Lý Chi Tiết

### 1. Kiểm Tra Yêu Cầu

- Kiểm tra method là POST
- Kiểm tra user đã login
- Kiểm tra dữ liệu JSON hợp lệ

### 2. Validate Dữ Liệu

- `amount` bắt buộc và phải > 0
- `address` bắt buộc

### 3. Lấy Giỏ Hàng

- Lấy tất cả items từ database theo userId
- Nếu giỏ hàng trống → trả về error

### 4. Tính Toán Tổng Tiền

- Lặp qua từng item trong giỏ hàng
- Kiểm tra tồn kho của mỗi variant
- Tính tổng tiền từ tất cả items
- Nếu tổng tiền ≠ amount → trả về error

### 5. Tạo Đơn Hàng

- Tạo bản ghi đơn hàng với status = 'pending'
- Tạo chi tiết từng sản phẩm trong đơn hàng
- Nếu thất bại → trả về error

### 6. Dọn Dẹp

- Xóa tất cả items khỏi giỏ hàng
- Lấy thông tin đơn hàng vừa tạo

### 7. Return Response

- Trả về order_id, order_data, và thông tin thêm

---

## Ví Dụ Sử Dụng

### JavaScript/Fetch

```javascript
// Sau khi xác nhận thanh toán QR thành công
async function createOrderAfterPayment() {
  const cartItems = getCartItems(); // Lấy items từ giỏ hàng
  const totalAmount = calculateTotal(cartItems); // Tính tổng tiền

  const response = await fetch("/payment/create-order-on-payment", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      amount: totalAmount,
      description: "Thanh toán đơn hàng",
      address: document.getElementById("addressInput").value,
      note: document.getElementById("noteInput").value,
    }),
  });

  const result = await response.json();

  if (result.success) {
    console.log("Đơn hàng tạo thành công:", result.order_id);
    // Chuyển hướng đến trang xem chi tiết đơn hàng
    window.location.href = `/order/${result.order_id}`;
  } else {
    console.error("Lỗi:", result.message);
    alert("Tạo đơn hàng thất bại: " + result.message);
  }
}
```

### jQuery

```javascript
$.ajax({
  type: "POST",
  url: "/payment/create-order-on-payment",
  contentType: "application/json",
  data: JSON.stringify({
    amount: 1500000,
    description: "Thanh toán đơn hàng",
    address: "123 Đường ABC, Q.1, TP.HCM",
    note: "Giao chiều",
  }),
  success: function (result) {
    if (result.success) {
      alert("Đơn hàng " + result.order_id + " đã được tạo");
      // Redirect hoặc cập nhật UI
    }
  },
  error: function (error) {
    console.error("Lỗi:", error);
  },
});
```

### cURL

```bash
curl -X POST http://localhost/DuAn1/payment/create-order-on-payment \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 1500000,
    "description": "Thanh toán đơn hàng",
    "address": "123 Đường ABC, Q.1, TP.HCM",
    "note": "Giao chiều"
  }' \
  -b "PHPSESSID=your_session_id"
```

---

## Quy Trình Thanh Toán QR Hoàn Chỉnh

### 1. User Chọn Phương Thức Thanh Toán QR

```
[Giỏ Hàng] → [Chọn QR] → API `/payment/get-qr-code` → Hiển thị QR Code
```

### 2. User Quét QR và Thanh Toán

```
[Quét QR] → [Nhập số tiền] → [Xác nhận] → [Ngân hàng xử lý]
```

### 3. Kiểm Tra Giao Dịch

```
[Click "Kiểm Tra Thanh Toán"] → API `/payment/check-payment` → Gọi Google Apps Script
```

### 4. Tạo Đơn Hàng (NEW!)

```
[Thanh Toán Thành Công] → API `/payment/create-order-on-payment` → [Đơn Hàng Được Tạo]
```

### 5. Xác Nhận

```
[Hiển Thị Đơn Hàng] → [Chuyển Hướng Trang Chi Tiết]
```

---

## Trạng Thái Đơn Hàng

Khi đơn hàng được tạo, trạng thái mặc định là: **`pending`** (Chờ xác nhận)

```
pending (Chờ xác nhận)
  → confirmed (Chờ giao hàng)
  → shipping (Vận chuyển)
  → delivered (Hoàn thành)

hoặc:
  → cancelled (Đã hủy)
  → return (Trả hàng)
```

---

## Xử Lý Lỗi Thường Gặp

| Lỗi                             | Nguyên Nhân                     | Giải Pháp                       |
| ------------------------------- | ------------------------------- | ------------------------------- |
| "Giỏ hàng trống"                | Người dùng chưa thêm sản phẩm   | Thêm sản phẩm vào giỏ           |
| "Tổng tiền không khớp"          | Amount không bằng tổng giỏ hàng | Kiểm tra lại tính toán giỏ hàng |
| "Unauthorized"                  | User chưa login                 | Login trước khi thanh toán      |
| "Không tìm thấy thông tin user" | Session bị mất                  | Login lại                       |

---

## Notes & Best Practices

1. **Kiểm tra tổng tiền**: Luôn kiểm tra amount từ client vs. server để tránh gian lận
2. **Validate dữ liệu**: Server sẽ kiểm tra hết, nhưng client cũng nên validate
3. **Xóa giỏ hàng**: Giỏ hàng sẽ tự động xóa sau khi đơn hàng được tạo
4. **Transaction Safety**: Hệ thống không dùng transaction, nên nếu lỗi giữa chừng có thể cần xử lý thủ công
5. **Logging**: Tất cả lỗi sẽ được ghi vào error log

---

## Changelog

**v1.0** - Initial Release

- Tạo API create-order-on-payment
- Auto delete cart sau khi tạo đơn hàng
- Validate tổng tiền
