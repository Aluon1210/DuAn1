<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['title'] ?? 'Tài Khoản - Luxury Fashion Store'); ?></title>
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

        .header-top .logo a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .header-top .logo a:hover {
            color: var(--primary-gold);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
            justify-self: end;
        }

        .user-dropdown {
            position: relative;
            display: inline-block;
        }

        .user-toggle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(212, 175, 55, 0.1);
            border: 2px solid var(--primary-gold);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-gold);
            cursor: pointer;
            transition: var(--transition-smooth);
            font-size: 20px;
        }

        .user-toggle:hover {
            background: var(--primary-gold);
            color: var(--primary-black);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
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

        .user-menu-items a.logout {
            color: #dc3545;
        }

        .user-menu-items a.logout:hover {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
        }

        .account-section {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 30px;
        }

        .account-sidebar {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .account-nav {
            list-style: none;
        }

        .account-nav li a {
            display: block;
            padding: 15px 20px;
            background: white;
            border-left: 4px solid transparent;
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition-smooth);
            border-radius: 8px;
            box-shadow: var(--shadow-soft);
        }

        .account-nav li a:hover,
        .account-nav li a.active {
            border-left-color: var(--primary-gold);
            background: linear-gradient(90deg, rgba(212, 175, 55, 0.1), transparent);
            color: var(--primary-gold);
            padding-left: 25px;
        }

        .account-content {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-soft);
            padding: 40px;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .form-group input,
        .form-group textarea {
            padding: 12px;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            font-family: inherit;
            transition: var(--transition-smooth);
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition-smooth);
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-gold) 0%, #b8941f 100%);
            color: var(--primary-black);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
        }

        .btn-secondary {
            background: var(--accent-gray);
            color: var(--text-dark);
            border: 1px solid var(--border-light);
        }

        .btn-secondary:hover {
            background: var(--border-light);
        }

        .order-list {
            list-style: none;
        }

        .order-item {
            background: var(--accent-gray);
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            border-left: 4px solid var(--primary-gold);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-info h3 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .order-info p {
            font-size: 13px;
            color: var(--text-light);
            margin-bottom: 3px;
        }

        .order-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-shipping {
            background: #cce5ff;
            color: #004085;
        }

        .status-delivered {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .alert {
            padding: 16px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            border-left: 4px solid;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-light);
        }

        .empty-state .icon {
            font-size: 64px;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            margin-bottom: 10px;
            color: var(--text-dark);
        }

        @media (max-width: 768px) {
            .header-top {
                grid-template-columns: 1fr;
                padding: 15px 20px;
            }

            .account-section {
                grid-template-columns: 1fr;
            }

            .account-sidebar {
                flex-direction: row;
                gap: 0;
                margin-bottom: 20px;
            }

            .account-nav li a {
                flex: 1;
                text-align: center;
            }

            .profile-grid {
                grid-template-columns: 1fr;
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
                    <span>✨</span>
                    <span>Luxury Fashion</span>
                </a>
            </div>
            
            <form class="search-box" method="GET" action="<?php echo ROOT_URL; ?>product/search" style="position: relative; width: 100%; max-width: 100%; justify-self: center;">
                <input type="text" name="q" placeholder="Tìm kiếm sản phẩm cao cấp..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" style="width: 100%; padding: 14px 20px 14px 50px; border: 2px solid transparent; border-radius: 50px; background: rgba(255, 255, 255, 0.1); color: white; font-size: 14px; transition: all 0.3s; border-color: transparent;">
            </form>
            
            <div class="header-actions">
                <a href="<?php echo ROOT_URL; ?>cart" style="width: 45px; height: 45px; border-radius: 50%; background: rgba(212, 175, 55, 0.1); border: 2px solid var(--primary-gold); display: flex; align-items: center; justify-content: center; color: var(--primary-gold); text-decoration: none; transition: all 0.3s; font-size: 20px;" title="Giỏ hàng">
                    🛒
                </a>
                
                <div class="user-dropdown">
                    <button class="user-toggle" onclick="toggleUserMenu()" title="Menu tài khoản">
                        👤
                    </button>
                    <div class="user-menu" id="userMenu">
                        <div class="user-info">
                            <div class="name"><?php echo htmlspecialchars($data['user']['name'] ?? 'Người dùng'); ?></div>
                            <div class="email"><?php echo htmlspecialchars($data['user']['email'] ?? ''); ?></div>
                        </div>
                        <ul class="user-menu-items">
                            <li><a href="<?php echo ROOT_URL; ?>account">👤 Thông tin cá nhân</a></li>
                            <li><a href="<?php echo ROOT_URL; ?>account/orders">📦 Lịch sử đơn hàng</a></li>
                            <li><a href="<?php echo ROOT_URL; ?>cart">🛒 Giỏ hàng</a></li>
                            <li><a href="<?php echo ROOT_URL; ?>logout" class="logout">🚪 Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="account-section">
            <!-- Sidebar -->
            <aside class="account-sidebar">
                <ul class="account-nav">
                    <li><a href="<?php echo ROOT_URL; ?>account" class="<?php echo ($data['activeTab'] ?? 'profile') === 'profile' ? 'active' : ''; ?>">👤 Thông tin</a></li>
                    <li><a href="<?php echo ROOT_URL; ?>account/orders" class="<?php echo ($data['activeTab'] ?? 'profile') === 'orders' ? 'active' : ''; ?>">📦 Đơn hàng</a></li>
                </ul>
            </aside>

            <!-- Main Content -->
            <main class="account-content">
                <!-- Tab: Profile -->
                <div id="profileTab" class="tab-content <?php echo ($data['activeTab'] ?? 'profile') === 'profile' ? 'active' : ''; ?>">
                    <h2 style="margin-bottom: 30px; font-family: 'Playfair Display', serif; font-size: 32px;">Thông Tin Cá Nhân</h2>
                    
                    <form method="POST" action="<?php echo ROOT_URL; ?>account/updateProfile">
                        <div class="profile-grid">
                            <div class="form-group">
                                <label>Tên người dùng</label>
                                <input type="text" value="<?php echo htmlspecialchars($data['user']['id'] ?? ''); ?>" disabled style="background: var(--accent-gray); cursor: not-allowed;">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" value="<?php echo htmlspecialchars($data['user']['email'] ?? ''); ?>" disabled style="background: var(--accent-gray); cursor: not-allowed;">
                            </div>
                        </div>

                        <div class="profile-grid">
                            <div class="form-group">
                                <label for="name">Họ tên *</label>
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($data['user']['name'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($data['user']['phone'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <textarea id="address" name="address"><?php echo htmlspecialchars($data['user']['address'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="password">Đổi mật khẩu (để trống nếu không thay đổi)</label>
                            <input type="password" id="password" name="password" placeholder="Nhập mật khẩu mới">
                        </div>

                        <div style="display: flex; gap: 15px; margin-top: 30px;">
                            <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
                            <a href="<?php echo ROOT_URL; ?>" class="btn btn-secondary">← Quay lại trang chủ</a>
                        </div>
                    </form>
                </div>

                <!-- Tab: Orders -->
                <div id="ordersTab" class="tab-content <?php echo ($data['activeTab'] ?? 'profile') === 'orders' ? 'active' : ''; ?>">
                    <h2 style="margin-bottom: 30px; font-family: 'Playfair Display', serif; font-size: 32px;">Lịch Sử Đơn Hàng</h2>
                    
                    <!-- Order Filter Tabs -->
                    <div style="display: flex; gap: 0; margin-bottom: 30px; border-bottom: 2px solid var(--border-light); overflow-x: auto;">
                        <button class="order-tab-btn active" onclick="filterOrders('all')" style="padding: 15px 20px; border: none; background: none; color: var(--text-dark); font-weight: 600; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s; white-space: nowrap;">
                            Tất cả
                        </button>
                        <button class="order-tab-btn" onclick="filterOrders('pending')" style="padding: 15px 20px; border: none; background: none; color: var(--text-dark); font-weight: 600; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s; white-space: nowrap;">
                            Chờ xác nhận
                        </button>
                        <button class="order-tab-btn" onclick="filterOrders('confirmed')" style="padding: 15px 20px; border: none; background: none; color: var(--text-dark); font-weight: 600; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s; white-space: nowrap;">
                            Chờ giao hàng
                        </button>
                        <button class="order-tab-btn" onclick="filterOrders('shipping')" style="padding: 15px 20px; border: none; background: none; color: var(--text-dark); font-weight: 600; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s; white-space: nowrap;">
                            Vận chuyển
                        </button>
                        <button class="order-tab-btn" onclick="filterOrders('delivered')" style="padding: 15px 20px; border: none; background: none; color: var(--text-dark); font-weight: 600; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s; white-space: nowrap;">
                            Hoàn thành
                        </button>
                        <button class="order-tab-btn" onclick="filterOrders('cancelled')" style="padding: 15px 20px; border: none; background: none; color: var(--text-dark); font-weight: 600; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s; white-space: nowrap;">
                            Đã hủy
                        </button>
                        <button class="order-tab-btn" onclick="filterOrders('return')" style="padding: 15px 20px; border: none; background: none; color: var(--text-dark); font-weight: 600; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s; white-space: nowrap;">
                            Trả hàng/Hoàn tiền
                        </button>
                    </div>

                    <style>
                        .order-tab-btn.active {
                            color: var(--primary-gold);
                            border-bottom-color: var(--primary-gold) !important;
                        }

                        .order-item-shopee {
                            background: white;
                            border: 1px solid var(--border-light);
                            border-radius: 8px;
                            margin-bottom: 20px;
                            overflow: hidden;
                            box-shadow: var(--shadow-soft);
                        }

                        .order-item-header {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            padding: 15px 20px;
                            border-bottom: 1px solid var(--border-light);
                            background: var(--accent-gray);
                        }

                        .order-item-header-left {
                            display: flex;
                            gap: 15px;
                            align-items: center;
                        }

                        .order-item-header-right {
                            display: flex;
                            gap: 15px;
                            align-items: center;
                        }

                        .order-status-badge {
                            padding: 6px 12px;
                            border-radius: 20px;
                            font-size: 12px;
                            font-weight: 600;
                            text-transform: uppercase;
                            white-space: nowrap;
                        }

                        .status-pending {
                            background: #fff3cd;
                            color: #856404;
                        }

                        .status-confirmed {
                            background: #fff3cd;
                            color: #856404;
                        }

                        .status-shipping {
                            background: #cce5ff;
                            color: #004085;
                        }

                        .status-delivered {
                            background: #d4edda;
                            color: #155724;
                        }

                        .status-cancelled {
                            background: #f8d7da;
                            color: #721c24;
                        }

                        .status-return {
                            background: #d1ecf1;
                            color: #0c5460;
                        }

                        .order-item-body {
                            padding: 20px;
                        }

                        .order-product {
                            display: flex;
                            gap: 15px;
                            margin-bottom: 20px;
                            padding-bottom: 20px;
                            border-bottom: 1px solid var(--border-light);
                        }

                        .order-product:last-child {
                            margin-bottom: 0;
                            padding-bottom: 0;
                            border-bottom: none;
                        }

                        .order-product-image {
                            width: 80px;
                            height: 80px;
                            border-radius: 8px;
                            object-fit: cover;
                            background: var(--accent-gray);
                            border: 1px solid var(--border-light);
                        }

                        .order-product-info {
                            flex: 1;
                        }

                        .order-product-name {
                            font-weight: 600;
                            color: var(--text-dark);
                            margin-bottom: 5px;
                            display: -webkit-box;
                            -webkit-line-clamp: 2;
                            -webkit-box-orient: vertical;
                            overflow: hidden;
                        }

                        .order-product-variant {
                            font-size: 12px;
                            color: var(--text-light);
                            margin-bottom: 8px;
                        }

                        .order-product-price {
                            color: var(--primary-gold);
                            font-weight: 600;
                        }

                        .order-item-footer {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            padding: 15px 20px;
                            border-top: 1px solid var(--border-light);
                            background: white;
                        }

                        .order-total-price {
                            text-align: right;
                        }

                        .order-total-price-label {
                            font-size: 12px;
                            color: var(--text-light);
                        }

                        .order-total-price-value {
                            font-size: 18px;
                            font-weight: 700;
                            color: var(--primary-gold);
                        }

                        .order-actions {
                            display: flex;
                            gap: 10px;
                        }

                        .order-btn {
                            padding: 8px 16px;
                            border: 1px solid;
                            border-radius: 4px;
                            cursor: pointer;
                            font-size: 13px;
                            font-weight: 600;
                            transition: all 0.3s;
                            text-decoration: none;
                            display: inline-block;
                        }

                        .order-btn-primary {
                            border-color: var(--primary-gold);
                            color: var(--primary-gold);
                            background: white;
                        }

                        .order-btn-primary:hover {
                            background: var(--accent-gray);
                        }

                        .order-btn-secondary {
                            border-color: var(--border-light);
                            color: var(--text-dark);
                            background: white;
                        }

                        .order-btn-secondary:hover {
                            border-color: var(--text-dark);
                        }

                        .empty-state {
                            text-align: center;
                            padding: 60px 20px;
                            background: white;
                            border-radius: 8px;
                            border: 1px solid var(--border-light);
                        }

                        .empty-state .icon {
                            font-size: 64px;
                            margin-bottom: 20px;
                        }

                        .empty-state h3 {
                            margin-bottom: 10px;
                            color: var(--text-dark);
                        }

                        .empty-state p {
                            color: var(--text-light);
                        }
                    </style>

                    <!-- Orders List -->
                    <div id="ordersContainer">
                        <?php if (!empty($data['orders'])): ?>
                            <?php foreach ($data['orders'] as $order): 
                                $status = $order['TrangThai'] ?? 'pending';
                                $statusClass = 'status-' . $status;
                                $statusText = [
                                    'pending' => 'Chờ xác nhận',
                                    'confirmed' => 'Chờ giao hàng',
                                    'shipping' => 'Vận chuyển',
                                    'delivered' => 'Hoàn thành',
                                    'cancelled' => 'Đã hủy',
                                    'return' => 'Trả hàng'
                                ][$status] ?? 'Chưa rõ';
                            ?>
                                <div class="order-item-shopee" data-status="<?php echo $status; ?>" data-order-id="<?php echo htmlspecialchars($order['Order_Id'] ?? ''); ?>" data-order-date="<?php echo htmlspecialchars($order['Order_date'] ?? ''); ?>">
                                    <div class="order-item-header">
                                        <div class="order-item-header-left">
                                            <div style="font-weight: 600;"><?php echo htmlspecialchars('Đơn hàng #' . ($order['Order_Id'] ?? 'N/A')); ?></div>
                                            <span class="order-status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                        </div>
                                        <div class="order-item-header-right">
                                            <div style="text-align: right; font-size: 12px; color: var(--text-light);">
                                                <div><?php echo htmlspecialchars($order['Order_date'] ?? 'N/A'); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="order-item-body">
                                        <?php if (!empty($order['items'])): ?>
                                            <?php foreach ($order['items'] as $item): ?>
                                                <?php
                                                    $productIdAttr = htmlspecialchars($item['Product_Id'] ?? $item['product_id'] ?? '');
                                                    $productNameAttr = htmlspecialchars($item['product_name'] ?? $item['ProductName'] ?? 'Sản phẩm');
                                                    $qtyAttr = (int)($item['quantity'] ?? 0);
                                                    $unitPriceAttr = (float)($item['Price'] ?? $item['price'] ?? 0);
                                                    $totalAttr = $qtyAttr * $unitPriceAttr;
                                                ?>
                                                <div class="order-product" data-product-id="<?php echo $productIdAttr; ?>" data-product-name="<?php echo $productNameAttr; ?>" data-qty="<?php echo $qtyAttr; ?>" data-unit-price="<?php echo $unitPriceAttr; ?>" data-total="<?php echo $totalAttr; ?>">
                                                    <?php
                                                        // Get image URL - use product_image field
                                                        $img = $item['product_image'] ?? null;
                                                        if ($img) {
                                                            $imgUrl = ROOT_URL . 'public/images/' . htmlspecialchars($img);
                                                        } else {
                                                            $imgUrl = ROOT_URL . 'public/images/placeholder.jpg';
                                                        }
                                                        
                                                        $variantLabel = '';
                                                        if (!empty($item['color_name'])) {
                                                            $variantLabel .= 'Màu: ' . htmlspecialchars($item['color_name']);
                                                        }
                                                        if (!empty($item['size_name'])) {
                                                            $variantLabel .= ($variantLabel ? ' • ' : '') . 'Size: ' . htmlspecialchars($item['size_name']);
                                                        }
                                                        
                                                        $productName = $item['product_name'] ?? 'Sản phẩm';
                                                        $price = (float)($item['Price'] ?? 0);
                                                        $qty = (int)($item['quantity'] ?? 0);
                                                    ?>
                                                    <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo htmlspecialchars($productName); ?>" class="order-product-image" style="object-fit: cover;">
                                                    <div class="order-product-info">
                                                        <div class="order-product-name"><?php echo htmlspecialchars($productName); ?></div>
                                                        <div class="order-product-variant"><?php echo $variantLabel ?: '—'; ?></div>
                                                        <div class="order-product-price">₫<?php echo number_format($price, 0, ',', '.'); ?> x <?php echo $qty; ?></div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="empty-state">
                                                <div class="icon">📦</div>
                                                <p>Không có sản phẩm trong đơn này.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="order-item-footer">
                                        <div class="order-total-price">
                                            <div class="order-total-price-label">Thành tiền:</div>
                                            <div class="order-total-price-value">₫<?php echo number_format((float)($order['total'] ?? 0), 0, ',', '.'); ?></div>
                                        </div>
                                        <div class="order-actions">
                                            <a href="<?php echo ROOT_URL; ?>account/order/<?php echo urlencode($order['Order_Id']); ?>" class="order-btn order-btn-primary">Xem Chi Tiết</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="icon">📭</div>
                                <h3>Chưa có đơn hàng nào</h3>
                                <p>Hãy <a href="<?php echo ROOT_URL; ?>product" style="color: var(--primary-gold); text-decoration: none; font-weight: 600;">mua sắm ngay</a> để có đơn hàng đầu tiên!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('active');
        }

        // Đóng menu khi nhấp ra ngoài
        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.user-dropdown');
            if (!dropdown.contains(event.target)) {
                document.getElementById('userMenu').classList.remove('active');
            }
        });

        // Filter orders by status
        function filterOrders(status) {
            const orders = document.querySelectorAll('.order-item-shopee');
            const buttons = document.querySelectorAll('.order-tab-btn');
            
            // Update active button
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Filter orders
            orders.forEach(order => {
                if (status === 'all') {
                    order.style.display = 'block';
                } else {
                    order.style.display = order.dataset.status === status ? 'block' : 'none';
                }
            });
        }

        // --- Review popup for delivered orders ---
        (function(){
            // Only run when user is logged in and there are delivered orders
            try {
                const ROOT = '<?php echo rtrim(ROOT_URL, "/"); ?>';
                const deliveredOrders = Array.from(document.querySelectorAll('.order-item-shopee')).filter(o => o.dataset.status === 'delivered');
                if (!deliveredOrders.length) return;

                // For each delivered order, check if any product is not yet commented by user
                const formatCurrency = (v) => {
                    try { return new Intl.NumberFormat('vi-VN').format(Number(v)) + ' ₫'; } catch(e) { return v; }
                };

                const checkOrder = async (orderEl) => {
                    const orderId = orderEl.dataset.orderId || null;
                    if (!orderId) return null;

                    const items = Array.from(orderEl.querySelectorAll('.order-product'));
                    const products = items.map(it => {
                        const pid = it.dataset.productId || null;
                        return { element: it, productId: pid };
                    }).filter(p => p.productId);

                    const unreviewed = [];
                    for (const p of products) {
                        try {
                            const res = await fetch(ROOT + '/product/ajaxHasComment/' + encodeURIComponent(p.productId));
                            const json = await res.json();
                            if (json.ok && !json.hasComment) {
                                unreviewed.push(p);
                            }
                        } catch (e) { /* ignore network errors */ }
                    }
                    return { orderId, unreviewed };
                };

                // Only show 1 popup at a time; choose the first order with unreviewed products
                (async function(){
                    for (const ord of deliveredOrders) {
                        const info = await checkOrder(ord);
                        if (info && info.unreviewed && info.unreviewed.length) {
                            showReviewModal(info.orderId, info.unreviewed);
                            // show a toast notification
                            showToast('Đơn hàng đã được giao — bạn có thể đánh giá sản phẩm đã mua');
                            break;
                        }
                    }
                })();

                function showToast(text) {
                    const t = document.createElement('div');
                    t.style.position = 'fixed'; t.style.right = '20px'; t.style.bottom = '20px'; t.style.background = 'rgba(0,0,0,0.8)';
                    t.style.color = '#fff'; t.style.padding = '12px 16px'; t.style.borderRadius = '8px'; t.style.zIndex = 99999;
                    t.textContent = text;
                    document.body.appendChild(t);
                    setTimeout(()=>{ t.style.transition = 'opacity 0.3s'; t.style.opacity = '0'; setTimeout(()=>t.remove(),300); }, 5000);
                }

                function escapeHtml(unsafe) {
                    return unsafe
                        .replace(/&/g, "&amp;")
                        .replace(/</g, "&lt;")
                        .replace(/>/g, "&gt;")
                        .replace(/\"/g, "&quot;")
                        .replace(/'/g, "&#039;");
                }

                function showReviewModal(orderId, products) {
                    // Build modal
                    const modal = document.createElement('div');
                    modal.style.position = 'fixed'; modal.style.left = '0'; modal.style.top = '0'; modal.style.width = '100%'; modal.style.height = '100%';
                    modal.style.background = 'rgba(0,0,0,0.6)'; modal.style.display = 'flex'; modal.style.alignItems = 'center'; modal.style.justifyContent = 'center'; modal.style.zIndex = 99998;

                    const box = document.createElement('div');
                    box.style.width = '760px'; box.style.maxWidth = '95%'; box.style.background = '#fff'; box.style.borderRadius = '8px'; box.style.padding = '18px'; box.style.maxHeight = '85%'; box.style.overflow = 'auto';

                    const title = document.createElement('h3'); title.textContent = 'Cảm ơn bạn đã mua hàng — Đánh giá sản phẩm';
                    box.appendChild(title);
                    const desc = document.createElement('p'); desc.textContent = 'Hãy để lại đánh giá cho các sản phẩm bạn đã mua (bắt buộc đăng nhập).'; box.appendChild(desc);

                    // Show order meta (date)
                    const orderDate = document.querySelector('.order-item-shopee[data-order-id="' + orderId + '"]')?.dataset?.orderDate || '';
                    if (orderDate) {
                        const meta = document.createElement('div'); meta.style.marginBottom = '10px'; meta.style.color = 'var(--text-light)'; meta.textContent = 'Ngày đặt: ' + orderDate; box.appendChild(meta);
                    }

                    // Create form list: one textarea per product, display details (name, qty, unit, total)
                    products.forEach((p, idx) => {
                        const el = p.element;
                        const pname = el.dataset.productName || ('Sản phẩm ' + (idx+1));
                        const qty = el.dataset.qty || '0';
                        const unit = el.dataset.unitPrice || '0';
                        const total = el.dataset.total || (Number(qty) * Number(unit));

                        const wrap = document.createElement('div'); wrap.style.marginBottom = '12px'; wrap.style.borderBottom = '1px solid #eee'; wrap.style.paddingBottom = '10px';

                        const nameEl = document.createElement('div'); nameEl.style.fontWeight = '700'; nameEl.style.marginBottom = '6px'; nameEl.textContent = pname; wrap.appendChild(nameEl);

                        const info = document.createElement('div'); info.style.fontSize = '14px'; info.style.color = 'var(--text-light)'; info.style.marginBottom = '8px';
                        info.innerHTML = '<strong>Số lượng:</strong> ' + qty + ' &nbsp; • &nbsp; <strong>Đơn giá:</strong> ' + formatCurrency(unit) + ' &nbsp; • &nbsp; <strong>Thành tiền:</strong> ' + formatCurrency(total);
                        wrap.appendChild(info);

                        const ta = document.createElement('textarea'); ta.rows = 4; ta.style.width = '100%'; ta.name = 'content_' + p.productId; ta.placeholder = 'Viết đánh giá...';
                        wrap.appendChild(ta);

                        const btn = document.createElement('button'); btn.type = 'button'; btn.textContent = 'Gửi đánh giá cho sản phẩm này';
                        btn.style.marginTop = '6px'; btn.className = 'btn btn-primary';
                        btn.addEventListener('click', async function(){
                            const content = ta.value.trim();
                            if (!content) { alert('Vui lòng nhập nội dung đánh giá'); return; }
                            try {
                                const res = await fetch(ROOT + '/product/postComment/' + encodeURIComponent(p.productId), {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                                    body: 'content=' + encodeURIComponent(content)
                                });
                                const json = await res.json();
                                if (json.ok) {
                                    showToast('Đã gửi đánh giá');
                                    // If product detail is present on this page and reloadComments helper exists, refresh comments in-place
                                    if (typeof window.reloadComments === 'function') {
                                        try {
                                            await window.reloadComments();
                                        } catch (e) { /* ignore */ }
                                        // close modal overlay
                                        try {
                                            let ancestorModal = btn.closest('div');
                                            while (ancestorModal && !ancestorModal.style?.position?.includes('fixed')) {
                                                ancestorModal = ancestorModal.parentElement;
                                            }
                                            if (ancestorModal) ancestorModal.remove();
                                        } catch (e) { /* ignore */ }
                                        return;
                                    }

                                    // Fallback: append comment locally then redirect to product detail
                                    const c = json.comment;
                                    const commentItem = document.createElement('div'); commentItem.className = 'comment-item';
                                    commentItem.innerHTML = '<div class="comment-header"><strong>' + (c.user_name || 'Bạn') + '</strong> <span class="comment-date">' + (c.Create_at || '') + '</span></div><div class="comment-content">' + (c.Content ? escapeHtml(c.Content) : '') + '</div>';
                                    const target = document.querySelector('.comments-list[data-product-id="' + p.productId + '"]') || document.querySelector('.comments-list');
                                    if (target) target.insertBefore(commentItem, target.firstChild);

                                    try {
                                        let ancestorModal = btn.closest('div');
                                        while (ancestorModal && !ancestorModal.style?.position?.includes('fixed')) {
                                            ancestorModal = ancestorModal.parentElement;
                                        }
                                        if (ancestorModal) ancestorModal.remove();
                                    } catch (e) { /* ignore */ }
                                    setTimeout(function(){ window.location.href = ROOT + '/product/detail/' + encodeURIComponent(p.productId); }, 300);
                                } else {
                                    alert('Không thể gửi đánh giá');
                                }
                            } catch (e) {
                                console.error(e); alert('Lỗi gửi đánh giá');
                            }
                        });
                        wrap.appendChild(btn);

                        box.appendChild(wrap);
                    });

                    const close = document.createElement('button'); close.type = 'button'; close.textContent = 'Đóng'; close.className = 'btn btn-secondary';
                    close.style.marginTop = '8px'; close.addEventListener('click', function(){ modal.remove(); });
                    box.appendChild(close);

                    modal.appendChild(box);
                    document.body.appendChild(modal);
                }
            } catch (e) { console.error(e); }
        })();
    </script>
</body>
</html>
