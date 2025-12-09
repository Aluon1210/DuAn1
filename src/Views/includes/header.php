<?php
// File: src/Views/includes/header.php
// Header chung cho tất cả các trang
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Luxury Fashion Store'; ?></title>
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

        .cart-icon-header {
            position: relative;
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

        .cart-icon-header:hover {
            background: var(--primary-gold);
            color: var(--primary-black);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
        }

        .cart-badge-header {
            position: absolute;
            top: -5px;
            right: -5px;
            background: linear-gradient(135deg, var(--primary-gold) 0%, #b8941f 100%);
            color: var(--primary-black);
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            border: 2px solid var(--primary-black);
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
            cursor: pointer;
        }

        .login-icon:hover,
        .user-icon:hover {
            background: var(--primary-gold);
            color: var(--primary-black);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
        }

        .user-dropdown {
            position: relative;
        }

        .user-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            box-shadow: var(--shadow-hover);
            min-width: 250px;
            margin-top: 10px;
            display: none;
            z-index: 999;
            overflow: hidden;
        }

        .user-menu.active {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .user-info {
            padding: 20px;
            border-bottom: 1px solid var(--border-light);
            background: var(--accent-gray);
        }

        .user-info .name {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .user-info .email {
            font-size: 12px;
            color: var(--text-light);
        }

        .user-menu-items {
            list-style: none;
        }

        .user-menu-items li {
            border-bottom: 1px solid var(--border-light);
        }

        .user-menu-items li:last-child {
            border-bottom: none;
        }

        .user-menu-items a {
            display: block;
            padding: 15px 20px;
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition-smooth);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-menu-items a:hover {
            background: var(--accent-gray);
            color: var(--primary-gold);
            padding-left: 25px;
        }

        .user-menu-items a.logout:hover {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
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

        /* Buttons */
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

        .btn-danger {
            background: transparent;
            color: #dc3545;
            border: 2px solid #dc3545;
        }

        .btn-danger:hover {
            background: #dc3545;
            color: white;
            transform: translateY(-2px);
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
                <a href="<?php echo ROOT_URL; ?>cart" class="cart-icon-header" title="Giỏ hàng">
                    <span>🛒</span>
                    <?php 
                        $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                        if ($cartCount > 0):
                    ?>
                        <span class="cart-badge-header"><?php echo $cartCount; ?></span>
                    <?php endif; ?>
                </a>
                
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="user-dropdown">
                        <button class="user-icon" onclick="toggleUserMenu()" title="Menu tài khoản" style="border: none; background: rgba(212, 175, 55, 0.1);">
                            👤
                        </button>
                        <div class="user-menu" id="userMenu">
                            <div class="user-info">
                                <div class="name"><?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'Người dùng'); ?></div>
                                <div class="email"><?php echo htmlspecialchars($_SESSION['user']['email'] ?? ''); ?></div>
                            </div>
                            <ul class="user-menu-items">
                                <li><a href="<?php echo ROOT_URL; ?>account">👤 Thông tin cá nhân</a></li>
                                <li><a href="<?php echo ROOT_URL; ?>account/orders">📦 Lịch sử đơn hàng</a></li>
                                <li><a href="<?php echo ROOT_URL; ?>logout" class="logout">🚪 Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo ROOT_URL; ?>login" class="login-icon" title="Đăng nhập">
                        🔐
                    </a>
                    <a href="<?php echo ROOT_URL; ?>register" class="btn btn-success" style="padding: 10px 20px; font-size: 13px;" title="Đăng ký">
                        Đăng ký
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="header-bottom">
            <nav class="nav-menu">
                <?php
                $currentUrl = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
                
                // Logic active class: chỉ một nút active tại một thời điểm
                $homeActive = '';
                $productActive = '';
                $aboutActive = '';
                $cartActive = '';
                
                // Kiểm tra từng trường hợp cụ thể
                if ($currentUrl === '' || $currentUrl === 'home') {
                    // Chỉ active khi không phải là home/about
                    if ($currentUrl !== 'home/about') {
                        $homeActive = 'active';
                    }
                } elseif (strpos($currentUrl, 'product') === 0) {
                    $productActive = 'active';
                } elseif ($currentUrl === 'home/about' || $currentUrl === 'about') {
                    $aboutActive = 'active';
                } elseif (strpos($currentUrl, 'cart') === 0) {
                    $cartActive = 'active';
                }
                ?>
                <a href="<?php echo ROOT_URL; ?>" class="<?php echo $homeActive; ?>">
                    <span>🏠</span>
                    <span>Trang chủ</span>
                </a>
                <a href="<?php echo ROOT_URL; ?>product" class="<?php echo $productActive; ?>">
                    <span>🛍️</span>
                    <span>Sản phẩm</span>
                </a>
                <a href="<?php echo ROOT_URL; ?>home/about" class="<?php echo $aboutActive; ?>">
                    <span>ℹ️</span>
                    <span>Giới thiệu</span>
                </a>
                <a href="<?php echo ROOT_URL; ?>cart" class="<?php echo $cartActive; ?>">
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

    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('active');
        }

        // Đóng menu khi nhấp ra ngoài
        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.user-dropdown');
            if (dropdown && !dropdown.contains(event.target)) {
                document.getElementById('userMenu')?.classList.remove('active');
            }
        });
    </script>

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

