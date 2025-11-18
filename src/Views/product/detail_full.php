<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Chi Tiết Sản Phẩm'; ?> - Luxury Fashion Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-black: #1a1a1a;
            --primary-gold: #d4af37;
            --primary-gold-light: #f4e4bc;
            --accent-gray: #f8f8f8;
            --text-dark: #2c2c2c;
            --text-light: #666666;
            --border-light: #e5e5e5;
            --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.12);
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(180deg, #fafafa 0%, #ffffff 100%);
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Header */
        header {
            background: var(--primary-black);
            color: white;
            padding: 0;
            box-shadow: var(--shadow-soft);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 3px solid var(--primary-gold);
        }

        .header-top {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 2fr 1fr;
            align-items: center;
            padding: 15px 40px;
            gap: 30px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .header-top .logo {
            justify-self: start;
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .header-top .logo a {
            color: white;
            text-decoration: none;
            transition: var(--transition-smooth);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-top .logo a:hover {
            color: var(--primary-gold);
        }

        .logo-icon {
            font-size: 32px;
            filter: drop-shadow(0 2px 4px rgba(212, 175, 55, 0.3));
        }

        .search-box {
            width: 100%;
            max-width: 100%;
            margin: 0;
            position: relative;
            justify-self: center;
        }

        .search-box input {
            width: 100%;
            padding: 14px 20px 14px 50px;
            border: 2px solid transparent;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 14px;
            transition: var(--transition-smooth);
            backdrop-filter: blur(10px);
        }

        .search-box input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .search-box input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary-gold);
        }

        .search-box::before {
            content: '🔍';
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            opacity: 0.7;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
            justify-self: end;
        }

        .login-icon,
        .user-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(212, 175, 55, 0.1);
            border: 2px solid var(--primary-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-gold);
            text-decoration: none;
            transition: var(--transition-smooth);
            font-size: 20px;
        }

        .login-icon:hover,
        .user-icon:hover {
            background: var(--primary-gold);
            color: var(--primary-black);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
        }

        .header-bottom {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 12px 40px;
            gap: 10px;
        }

        .nav-menu {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 15px;
            font-weight: 500;
            transition: var(--transition-smooth);
            position: relative;
            display: flex;
            align-items: center;
            gap: 6px;
            border: 2px solid transparent;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            background: rgba(212, 175, 55, 0.1);
            border-color: var(--primary-gold);
            color: var(--primary-gold);
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
        }

        .product-detail-container {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: var(--shadow-soft);
            margin-top: 20px;
        }

        .product-detail-image {
            height: 600px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-hover);
            position: relative;
        }

        .product-detail-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-detail-info {
            padding-left: 40px;
        }

        .product-detail-title {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 24px;
            color: var(--primary-black);
            letter-spacing: 1px;
            line-height: 1.2;
        }

        .product-detail-price {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 700;
            margin: 30px 0;
            color: var(--primary-black);
            letter-spacing: 2px;
        }

        .product-detail-price::after {
            content: ' ₫';
            font-size: 32px;
            color: var(--primary-gold);
        }

        .product-info-box {
            margin: 30px 0;
            padding: 24px;
            background: linear-gradient(135deg, var(--accent-gray) 0%, #f0f0f0 100%);
            border-radius: 12px;
            border-left: 4px solid var(--primary-gold);
        }

        .product-info-box p {
            margin-bottom: 12px;
            font-size: 16px;
            color: var(--text-dark);
        }

        .product-info-box p:last-child {
            margin-bottom: 0;
        }

        .product-info-box strong {
            color: var(--primary-black);
            font-weight: 600;
            min-width: 120px;
            display: inline-block;
        }

        .product-description-box {
            margin: 40px 0;
            padding: 30px;
            background: white;
            border: 2px solid var(--border-light);
            border-radius: 12px;
        }

        .product-description-box h3 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            margin-bottom: 20px;
            color: var(--primary-black);
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-gold-light);
        }

        .product-description-box p {
            font-size: 16px;
            line-height: 1.8;
            color: var(--text-dark);
        }

        .quantity-selector {
            display: flex;
            gap: 16px;
            align-items: center;
            margin: 30px 0;
        }

        .quantity-selector label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 16px;
        }

        .quantity-selector input {
            padding: 14px 20px;
            border: 2px solid var(--border-light);
            border-radius: 30px;
            width: 100px;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            transition: var(--transition-smooth);
        }

        .quantity-selector input:focus {
            outline: none;
            border-color: var(--primary-gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .stock-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-left: 12px;
        }

        .stock-available {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .stock-unavailable {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: var(--transition-smooth);
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .btn-primary {
            background: var(--primary-black);
            color: white;
            border: 2px solid var(--primary-black);
        }

        .btn-primary:hover {
            background: var(--primary-gold);
            border-color: var(--primary-gold);
            color: var(--primary-black);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--primary-gold) 0%, #b8941f 100%);
            color: var(--primary-black);
            font-weight: 600;
            border: 2px solid transparent;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #b8941f 0%, var(--primary-gold) 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
        }

        @media (max-width: 768px) {
            .header-top {
                grid-template-columns: 1fr;
                gap: 15px;
                padding: 15px 20px;
            }

            .header-top .logo {
                justify-self: center;
            }

            .header-top .header-actions {
                justify-self: center;
            }

            .container {
                padding: 20px;
            }

            .product-detail-container {
                padding: 20px;
            }

            .product-detail-info {
                padding-left: 0;
                margin-top: 30px;
            }

            .product-detail-container > div {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .product-detail-image {
                height: 400px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-top">
            <div class="logo">
                <a href="<?php echo ROOT_URL; ?>">
                    <span class="logo-icon">✨</span>
                    <span>Luxury Fashion</span>
                </a>
            </div>
            
            <form class="search-box" method="GET" action="<?php echo ROOT_URL; ?>product/search">
                <input type="text" name="q" placeholder="Tìm kiếm sản phẩm cao cấp..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
            </form>
            
            <div class="header-actions">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="<?php echo ROOT_URL; ?>account" class="user-icon" title="Tài khoản">
                        <span>👤</span>
                    </a>
                <?php else: ?>
                    <a href="<?php echo ROOT_URL; ?>login" class="login-icon" title="Đăng nhập">
                        <span>🔐</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="header-bottom">
            <nav class="nav-menu">
                <?php
                $currentUrl = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
                $activeClass = function($path) use ($currentUrl) {
                    if ($path === '' && $currentUrl === '') return 'active';
                    if ($path !== '' && strpos($currentUrl, $path) === 0) return 'active';
                    return '';
                };
                ?>
                <a href="<?php echo ROOT_URL; ?>" class="<?php echo $activeClass(''); ?>">
                    <span>🏠</span>
                    <span>Trang chủ</span>
                </a>
                <a href="<?php echo ROOT_URL; ?>product" class="<?php echo $activeClass('product'); ?>">
                    <span>🛍️</span>
                    <span>Sản phẩm</span>
                </a>
                <a href="<?php echo ROOT_URL; ?>home/about" class="<?php 
                    $aboutActive = ($currentUrl === 'home/about' || $currentUrl === 'about') ? 'active' : '';
                    echo $aboutActive;
                ?>">
                    <span>ℹ️</span>
                    <span>Giới thiệu</span>
                </a>
                <a href="<?php echo ROOT_URL; ?>cart" class="<?php echo $activeClass('cart'); ?>">
                    <span>🛒</span>
                    <span>Giỏ hàng</span>
                    <?php 
                        $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                        if ($cartCount > 0):
                    ?>
                        <span style="position: absolute; top: -5px; right: -5px; background: linear-gradient(135deg, var(--primary-gold) 0%, #b8941f 100%); color: var(--primary-black); border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;"><?php echo $cartCount; ?></span>
                    <?php endif; ?>
                </a>
            </nav>
        </div>
    </header>

    <div class="container">
        <?php if (isset($_SESSION['message'])): ?>
            <div style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); color: #155724; padding: 16px 24px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #28a745; box-shadow: var(--shadow-soft);">
                <?php echo htmlspecialchars($_SESSION['message']); ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div style="background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); color: #721c24; padding: 16px 24px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #dc3545; box-shadow: var(--shadow-soft);">
                <?php echo htmlspecialchars($_SESSION['error']); ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($product) && $product): ?>
            <div class="product-detail-container">
                <a href="<?php echo ROOT_URL; ?>product" class="btn btn-primary" style="margin-bottom: 30px; display: inline-flex; align-items: center; gap: 8px;">
                    <span>←</span>
                    <span>Quay lại danh sách</span>
                </a>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; margin-top: 30px;">
                    <!-- Hình ảnh -->
                    <div class="product-detail-image">
                        <?php if (!empty($product['image'])): ?>
                            <img src="<?php echo ROOT_URL; ?>public/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php else: ?>
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 150px; opacity: 0.2; background: linear-gradient(135deg, var(--accent-gray) 0%, #e8e8e8 100%);">
                                ✨
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Thông tin sản phẩm -->
                    <div class="product-detail-info">
                        <h1 class="product-detail-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                        
                        <div class="product-detail-price"><?php echo number_format($product['price'], 0, ',', '.'); ?></div>

                        <div class="product-info-box">
                            <p>
                                <strong>Danh mục:</strong> 
                                <?php 
                                if (isset($categories)) {
                                    $cat = array_filter($categories, function($c) use ($product) {
                                        return $c['id'] == $product['category_id'];
                                    });
                                    if (!empty($cat)) {
                                        echo htmlspecialchars(array_values($cat)[0]['name']);
                                    }
                                }
                                ?>
                            </p>
                            <p>
                                <strong>Kho hàng:</strong> 
                                <span style="color: <?php echo $product['quantity'] > 0 ? '#27ae60' : '#e74c3c'; ?>; font-weight: 600;">
                                    <?php echo $product['quantity']; ?> sản phẩm
                                </span>
                                <?php if ($product['quantity'] > 0): ?>
                                    <span class="stock-badge stock-available">Còn hàng</span>
                                <?php else: ?>
                                    <span class="stock-badge stock-unavailable">Hết hàng</span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="product-description-box">
                            <h3>Mô tả sản phẩm</h3>
                            <p>
                                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                            </p>
                        </div>

                        <?php if ($product['quantity'] > 0): ?>
                            <form method="POST" action="<?php echo ROOT_URL; ?>cart/add/<?php echo $product['id']; ?>">
                                <div class="quantity-selector">
                                    <label for="quantity">Số lượng:</label>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['quantity']; ?>" required>
                                    <span style="color: var(--text-light); font-size: 14px;">(Tối đa: <?php echo $product['quantity']; ?>)</span>
                                </div>
                                <button type="submit" class="btn btn-success" style="padding: 18px 50px; font-size: 18px; width: 100%; text-transform: uppercase; letter-spacing: 1.5px;">
                                    🛒 Thêm vào giỏ hàng
                                </button>
                            </form>
                        <?php else: ?>
                            <div style="padding: 24px; background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); border-radius: 12px; text-align: center; margin-top: 30px;">
                                <div style="font-size: 48px; margin-bottom: 16px;">⏳</div>
                                <div style="color: #721c24; font-weight: 600; font-size: 18px;">Sản phẩm hiện đang hết hàng</div>
                                <p style="color: #721c24; margin-top: 8px; font-size: 14px;">Vui lòng quay lại sau hoặc liên hệ với chúng tôi</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div style="background: white; padding: 60px; border-radius: 16px; text-align: center; box-shadow: var(--shadow-soft);">
                <div style="font-size: 80px; margin-bottom: 20px; opacity: 0.3;">❌</div>
                <h2 style="font-family: 'Playfair Display', serif; font-size: 32px; margin-bottom: 12px; color: var(--text-dark);">Sản phẩm không tồn tại</h2>
                <p style="color: var(--text-light); margin-bottom: 30px;">Không tìm thấy sản phẩm bạn đang tìm kiếm.</p>
                <a href="<?php echo ROOT_URL; ?>product" class="btn btn-primary" style="padding: 14px 30px;">
                    ← Quay lại danh sách sản phẩm
                </a>
            </div>
        <?php endif; ?>
    </div>

<?php require_once ROOT_PATH . '/src/Views/includes/footer.php'; ?>

