# Dự án MVC PHP

## Cấu trúc thư mục

```
DuAn1/
├── config/              # Cấu hình (kết nối database)
│   └── Database.php     # Lớp kết nối CSDL
├── controllers/         # Controller (xử lý logic)
│   └── HomeController.php
├── models/              # Model (tương tác CSDL)
│   └── User.php
├── views/               # View (giao diện người dùng)
│   ├── home.php
│   └── about.php
├── public/              # File tĩnh (CSS, JS, ảnh)
├── index.php            # File chính
├── .htaccess            # Rewrite URL
└── README.md            # Tài liệu
```

## Cách cài đặt

1. **Tạo cơ sở dữ liệu trong phpMyAdmin:**
   - Tên database: `du_an_1`
   - Chạy SQL này để tạo bảng users:
   
   ```sql
   CREATE TABLE users (
       id INT PRIMARY KEY AUTO_INCREMENT,
       name VARCHAR(100) NOT NULL,
       email VARCHAR(100) NOT NULL
   );
   ```

2. **Cập nhật thông tin kết nối trong `config/Database.php`:**
   - `$host`: localhost
   - `$db_name`: du_an_1
   - `$username`: root (hoặc tên người dùng của bạn)
   - `$password`: (mật khẩu nếu có)

3. **Chạy trên máy chủ Apache:**
   - Đặt thư mục vào `htdocs` (nếu dùng XAMPP)
   - Truy cập: `http://localhost/DuAn1/`

## Cách sử dụng

- **Trang chủ:** `http://localhost/DuAn1/?url=home`
- **Trang Về chúng tôi:** `http://localhost/DuAn1/?url=home/about`
- **Lấy người dùng theo ID:** `http://localhost/DuAn1/?url=home/getUserById/1`

## File chính

- **index.php**: Route chính, xử lý URL
- **config/Database.php**: Kết nối cơ sở dữ liệu
- **controllers/HomeController.php**: Xử lý logic
- **models/User.php**: Tương tác CSDL
- **views/*.php**: Giao diện
