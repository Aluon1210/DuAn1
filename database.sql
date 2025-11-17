-- Tạo cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS du_an_1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE du_an_1;

-- Tạo bảng danh mục
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tạo bảng sản phẩm
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    description LONGTEXT NOT NULL,
    category_id INT NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Tạo bảng người dùng
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tạo bảng đơn hàng
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Tạo bảng chi tiết đơn hàng
CREATE TABLE IF NOT EXISTS order_details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Chèn dữ liệu mẫu - Danh mục
INSERT INTO categories (name) VALUES 
('Áo thun'),
('Áo sơ mi'),
('Quần'),
('Váy'),
('Giày dép'),
('Phụ kiện');

-- Chèn dữ liệu mẫu - Sản phẩm
INSERT INTO products (name, price, quantity, description, category_id, image) VALUES
('Áo thun cơ bản Nam', 99000, 50, 'Áo thun 100% cotton, thoáng mát, phù hợp với mọi lứa tuổi.', 1, NULL),
('Áo thun nữ tay ngắn', 89000, 45, 'Áo thun nữ form fitting, chất liệu cotton cao cấp.', 1, NULL),
('Áo sơ mi nam trắng', 199000, 30, 'Áo sơ mi nam chất liệu linen thoáng mát, phù hợp công sở.', 2, NULL),
('Áo sơ mi nữ màu hồng', 179000, 25, 'Áo sơ mi nữ kiểu dáng cổ điển, thoải mái suốt ngày.', 2, NULL),
('Quần jean nam xanh', 249000, 40, 'Quần jean nam màu xanh đen, bền bỉ, phong cách.', 3, NULL),
('Quần kaki nữ', 229000, 35, 'Quần kaki nữ form thon gọn, thoải mái và sang trọng.', 3, NULL),
('Váy xòe nữ', 199000, 20, 'Váy xòe nữ màu đen thanh lịch, phù hợp dạo phố.', 4, NULL),
('Váy chữ A nữ', 179000, 15, 'Váy chữ A nữ hoa văn họa tiết, nữ tính và thoải mái.', 4, NULL),
('Giày thể thao nam', 299000, 25, 'Giày thể thao nam đế cao su bền bỉ, êm chân.', 5, NULL),
('Giày cao gót nữ', 349000, 18, 'Giày cao gót nữ màu đen kinh điển, phù hợp công sở.', 5, NULL);
