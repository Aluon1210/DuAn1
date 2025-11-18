# 🛍️ HƯỚNG DẪN CÀI ĐẶT SHOP THỜI TRANG

## 1️⃣ Chuẩn bị cơ sở dữ liệu

### Bước 1: Mở phpMyAdmin

- Truy cập: `http://localhost/phpmyadmin/`
- Đăng nhập bằng tài khoản mặc định (root, không có mật khẩu nếu là XAMPP)

### Bước 2: Tạo cơ sở dữ liệu

1. Click vào tab **SQL**
2. Chép toàn bộ nội dung file `database.sql`
3. Dán vào ô SQL và click **Go**

### Bước 3: Cập nhật `config/Database.php` (nếu cần)

```php
private $host = 'localhost';        // Host mặc định
private $db_name = 'du_an_1';      // Tên database
private $username = 'root';        // Username
private $password = '';            // Password (nếu có)
```

---

## 2️⃣ Cấu trúc thư mục

```
DuAn1/
├── config/
│   └── Database.php                # Kết nối CSDL
├── controllers/
│   ├── HomeController.php
│   ├── ProductController.php       # Quản lý sản phẩm
│   └── CartController.php          # Quản lý giỏ hàng
├── models/
│   ├── User.php
│   ├── Product.php                 # Model sản phẩm
│   ├── Category.php                # Model danh mục
│   └── Cart.php                    # Model giỏ hàng
├── views/
│   ├── header.php                  # Header chung
│   ├── product/
│   │   ├── list.php                # Danh sách sản phẩm
│   │   ├── detail.php              # Chi tiết sản phẩm
│   │   ├── category.php            # Theo danh mục
│   │   └── search.php              # Kết quả tìm kiếm
│   └── cart/
│       └── index.php               # Giỏ hàng
├── public/
│   ├── css/                        # File CSS
│   └── images/                     # Ảnh sản phẩm
├── index.php                       # File chính (Router)
├── .htaccess                       # Rewrite URL
├── database.sql                    # Script SQL
└── README.md
```

---

## 3️⃣ Các tính năng

### 🏠 Trang chủ

- Hiển thị tất cả sản phẩm
- Danh mục bên trái
- Tìm kiếm nhanh

### 📦 Sản phẩm

- ✅ Xem danh sách tất cả sản phẩm
- ✅ Xem chi tiết từng sản phẩm
- ✅ Lọc theo danh mục
- ✅ Tìm kiếm theo từ khóa
- ✅ Hiển thị giá, mô tả, số lượng tồn kho

### 🛒 Giỏ hàng

- ✅ Thêm sản phẩm vào giỏ
- ✅ Cập nhật số lượng
- ✅ Xóa sản phẩm
- ✅ Xóa toàn bộ giỏ
- ✅ Tính tổng tiền

---

## 4️⃣ URL các trang

```
Trang chủ:              http://localhost/DuAn1/
Danh sách sản phẩm:     http://localhost/DuAn1/?url=product
Chi tiết sản phẩm:      http://localhost/DuAn1/?url=product/detail/1
Danh mục:               http://localhost/DuAn1/?url=product/category/1
Tìm kiếm:               http://localhost/DuAn1/?url=product/search?q=áo
Giỏ hàng:               http://localhost/DuAn1/?url=cart
```

---

## 5️⃣ Cách sử dụng

### Thêm sản phẩm vào giỏ

1. Chọn số lượng
2. Click **"Thêm giỏ"**

### Xem giỏ hàng

1. Click icon 🛒 ở góc trên phải
2. Cập nhật số lượng nếu cần
3. Click **"Cập nhật giỏ"**

### Tìm kiếm sản phẩm

1. Nhập từ khóa vào ô tìm kiếm
2. Click Enter hoặc nút tìm

---

## 6️⃣ Dữ liệu mẫu

Database đã có sẵn dữ liệu mẫu:

- 6 danh mục (Áo thun, Áo sơ mi, Quần, Váy, Giày dép, Phụ kiện)
- 10 sản phẩm mẫu với giá, mô tả, số lượng

---

## 7️⃣ Mở rộng (Tính năng tiếp theo)

- [ ] Đăng ký / Đăng nhập người dùng
- [ ] Thanh toán trực tuyến
- [ ] Admin quản lý sản phẩm
- [ ] Đánh giá sản phẩm
- [ ] Yêu thích sản phẩm
- [ ] Lịch sử mua hàng

---

## 🆘 Xử lý lỗi

**Lỗi: "Controller không tồn tại"**

- Kiểm tra tên file controller (phải theo chuẩn: `TênController.php`)

**Lỗi: "Database connection failed"**

- Kiểm tra config Database.php
- Đảm bảo MySQL đang chạy
- Kiểm tra tên database có đúng không

**Lỗi: ".htaccess không hoạt động"**

- Bật `mod_rewrite` trong Apache
- Hoặc copy toàn bộ thư mục vào `htdocs` (XAMPP)

---

## 📞 Cần giúp?

- Kiểm tra Console của trình duyệt (F12)
- Xem lỗi trong file `config/Database.php`
- Chạy lệnh `php -S localhost:8000` trong thư mục project

**Chúc bạn thành công! 🚀**
