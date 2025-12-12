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

                        <div class="profile-grid">
                            <div class="form-group">
                                <label>Số dư tài khoản</label>
                                <input type="text" value="<?php echo number_format((float)($data['user']['price'] ?? 0), 2, ',', '.'); ?>" disabled style="background: var(--accent-gray); cursor: not-allowed;">
                            </div>
                            <div class="form-group">
                                <label for="bank_account_number">Số tài khoản ngân hàng</label>
                                <input type="text" id="bank_account_number" name="bank_account_number" value="<?php echo htmlspecialchars($data['user']['bank_account_number'] ?? ''); ?>">
                            </div>
                        </div>

                        <?php 
                            $bankCodes = \Core\PaymentHelper::getAllBankCodes();
                            $currentBankName = $data['user']['bank_name'] ?? '';
                        ?>
                        <div class="form-group">
                            <label for="bank_name">Tên ngân hàng</label>
                            <select id="bank_name" name="bank_name">
                                <option value="">-- Chọn ngân hàng --</option>
                                <?php foreach ($bankCodes as $code => $name): ?>
                                    <option value="<?php echo htmlspecialchars($name); ?>" <?php echo ($currentBankName === $name) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($name) . ' (' . htmlspecialchars($code) . ')'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
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
                    
                    <div style="margin-bottom:20px;">
                        <form method="GET" action="<?php echo ROOT_URL; ?>account/orders" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                            <input 
                                type="text" 
                                name="order_id" 
                                value="<?php echo isset($_GET['order_id']) ? htmlspecialchars($_GET['order_id']) : ''; ?>" 
                                placeholder="Nhập mã đơn hàng (ví dụ: Ord123456)" 
                                style="flex:1;min-width:220px;padding:12px;border:1px solid var(--border-light);border-radius:8px;"
                                required
                            >
                            <button type="submit" class="btn btn-primary" style="padding:12px 18px;">Tìm kiếm</button>
                            <a href="<?php echo ROOT_URL; ?>account/orders" class="btn btn-secondary" style="padding:12px 18px;">Xóa tìm kiếm</a>
                        </form>
                    </div>

                    <?php if (isset($_SESSION['refund_qr'])): ?>
                        <?php $rq = $_SESSION['refund_qr']; ?>
                        <div style="background:#fff;border:1px solid var(--border-light);border-radius:12px;padding:16px;margin-bottom:20px;box-shadow:var(--shadow-soft)">
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
                                <div>
                                    <div style="font-weight:700;color:#333">QR Hoàn tiền cho đơn <?php echo htmlspecialchars($rq['order_id']); ?></div>
                                    <div style="font-size:12px;color:#666">Số tiền: ₫<?php echo number_format((float)($rq['amount'] ?? 0),0,',','.'); ?> · <?php echo htmlspecialchars($rq['account_name']); ?> · <?php echo htmlspecialchars($rq['account_no']); ?> (<?php echo htmlspecialchars($rq['bank_code']); ?>)</div>
                                </div>
                                <img src="<?php echo htmlspecialchars($rq['qr_url']); ?>" alt="Refund QR" style="max-height:140px;border-radius:8px;border:1px solid #eee">
                            </div>
                            <form method="POST" action="<?php echo ROOT_URL; ?>account/confirmRefund" style="margin-top:12px;display:flex;gap:10px;align-items:center">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($rq['order_id']); ?>">
                                <input type="hidden" name="amount" value="<?php echo (int)($rq['amount'] ?? 0); ?>">
                                <button type="submit" class="order-btn order-btn-primary" onclick="return confirm('Xác nhận đã chuyển lại tiền online?');">Xác nhận hoàn tiền</button>
                                <button type="button" class="order-btn order-btn-secondary" onclick="(function(){try{delete window.sessionRefundQr;}catch(e){};})();">Ẩn thông báo</button>
                            </form>
                        </div>
                        <?php unset($_SESSION['refund_qr']); ?>
                    <?php endif; ?>
                    
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
                                                <div><?php $d=$order['Order_date']??null; $s=$d?date('H:i:s d/m/Y',strtotime($d)):'N/A'; echo htmlspecialchars($s); ?></div>
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
                                            <button type="button" class="order-btn order-btn-secondary" onclick="openInvoiceModal('<?php echo htmlspecialchars($order['Order_Id']); ?>')">🧾 Hóa đơn (Pop-up)</button>
                                            <?php if ($status === 'pending'): ?>
                                                <form method="POST" action="<?php echo ROOT_URL; ?>account/cancelOrder" style="display:inline;">
                                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['Order_Id']); ?>">
                                                    <button type="submit" class="order-btn order-btn-secondary" onclick="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?');">Hủy đơn</button>
                                                </form>
                                            <?php endif; ?>
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

        // Mở hóa đơn khi nhấn vào toàn bộ hàng đơn (trừ khu vực nút/hyperlink)
        (function(){
            try {
                const container = document.getElementById('ordersContainer');
                if (!container) return;
                container.addEventListener('click', function(e){
                    const actionArea = e.target.closest('.order-actions');
                    const isButtonOrLink = e.target.closest('button, a, input, select, textarea');
                    if (actionArea || isButtonOrLink) { return; }
                    const item = e.target.closest('.order-item-shopee');
                    if (item) {
                        const orderId = item.getAttribute('data-order-id') || '';
                        if (orderId) { openInvoiceModal(orderId); }
                    }
                });
            } catch (err) {}
        })();

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

                function showToast(text, type = 'info') {
                    const colors = {
                        'success': { bg: '#4caf50', icon: '✅' },
                        'error': { bg: '#f44336', icon: '❌' },
                        'warning': { bg: '#ff9800', icon: '⚠️' },
                        'info': { bg: '#2196f3', icon: 'ℹ️' }
                    };
                    const style = colors[type] || colors['info'];
                    
                    const t = document.createElement('div');
                    t.style.cssText = `position: fixed; right: 20px; bottom: 20px; background: ${style.bg}; color: white; padding: 16px 20px; border-radius: 12px; z-index: 99999; font-weight: 600; font-size: 14px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); animation: slideInRight 0.3s ease-out;`;
                    t.textContent = text;
                    document.body.appendChild(t);
                    
                    // Add animation
                    const style2 = document.createElement('style');
                    style2.textContent = '@keyframes slideInRight { from { transform: translateX(400px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }';
                    if (!document.querySelector('style[data-toast-anim]')) {
                        style2.setAttribute('data-toast-anim', '1');
                        document.head.appendChild(style2);
                    }
                    
                    setTimeout(()=>{ 
                        t.style.transition = 'opacity 0.3s ease-out'; 
                        t.style.opacity = '0'; 
                        setTimeout(()=>t.remove(), 300); 
                    }, 4000);
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
                    // Build modern modal overlay
                    const modal = document.createElement('div');
                    modal.style.cssText = 'position: fixed; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 99998; animation: fadeIn 0.3s ease-out;';

                    const box = document.createElement('div');
                    box.style.cssText = 'width: 700px; max-width: 95%; background: white; border-radius: 16px; padding: 0; max-height: 85vh; overflow: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: slideUp 0.3s ease-out;';

                    // Modal header with close button
                    const header = document.createElement('div');
                    header.style.cssText = 'background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); padding: 30px; border-radius: 16px 16px 0 0; position: relative; display: flex; justify-content: space-between; align-items: flex-start;';

                    const titleDiv = document.createElement('div');
                    const title = document.createElement('h2');
                    title.textContent = '⭐ Đánh giá sản phẩm';
                    title.style.cssText = 'color: white; font-family: "Playfair Display", serif; font-size: 28px; margin: 0 0 8px 0; font-weight: 700;';
                    titleDiv.appendChild(title);

                    const subtitle = document.createElement('p');
                    subtitle.textContent = 'Chia sẻ trải nghiệm của bạn với sản phẩm đã mua';
                    subtitle.style.cssText = 'color: rgba(255,255,255,0.8); font-size: 14px; margin: 0; font-weight: 300;';
                    titleDiv.appendChild(subtitle);
                    header.appendChild(titleDiv);

                    const closeBtn = document.createElement('button');
                    closeBtn.innerHTML = '✕';
                    closeBtn.style.cssText = 'background: rgba(255,255,255,0.2); border: none; color: white; font-size: 24px; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s;';
                    closeBtn.addEventListener('mouseenter', ()=>{ closeBtn.style.background = 'rgba(255,255,255,0.3)'; });
                    closeBtn.addEventListener('mouseleave', ()=>{ closeBtn.style.background = 'rgba(255,255,255,0.2)'; });
                    closeBtn.addEventListener('click', ()=>{ modal.remove(); });
                    header.appendChild(closeBtn);
                    box.appendChild(header);

                    // Order info
                    const orderDate = document.querySelector('.order-item-shopee[data-order-id="' + orderId + '"]')?.dataset?.orderDate || '';
                    if (orderDate) {
                        const meta = document.createElement('div');
                        meta.style.cssText = 'padding: 15px 30px; background: #f8f9fa; border-bottom: 1px solid #eee; font-size: 14px; color: #666;';
                        meta.innerHTML = '<strong>📅 Ngày đặt hàng:</strong> ' + escapeHtml(orderDate);
                        box.appendChild(meta);
                    }

                    // Content container with padding
                    const content = document.createElement('div');
                    content.style.cssText = 'padding: 30px;';

                    // Create form list: one textarea per product
                    products.forEach((p, idx) => {
                        const el = p.element;
                        const pname = el.dataset.productName || ('Sản phẩm ' + (idx+1));
                        const qty = el.dataset.qty || '0';
                        const unit = el.dataset.unitPrice || '0';
                        const total = el.dataset.total || (Number(qty) * Number(unit));

                        const wrap = document.createElement('div');
                        wrap.style.cssText = 'margin-bottom: 28px; padding: 20px; background: #f8f9fa; border-radius: 12px; border-left: 4px solid #d4af37;';

                        // Product name
                        const nameEl = document.createElement('div');
                        nameEl.style.cssText = 'font-weight: 700; font-size: 16px; color: #1a1a1a; margin-bottom: 12px;';
                        nameEl.textContent = '🛍️ ' + pname;
                        wrap.appendChild(nameEl);

                        // Product details in a nice format
                        const info = document.createElement('div');
                        info.style.cssText = 'font-size: 13px; color: #666; margin-bottom: 15px; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; line-height: 1.6;';
                        info.innerHTML = '<div><strong>Số lượng:</strong> ' + escapeHtml(qty) + '</div>' +
                                         '<div><strong>Đơn giá:</strong> ' + formatCurrency(unit) + '</div>' +
                                         '<div colspan="2"><strong>Thành tiền:</strong> ' + formatCurrency(total) + '</div>';
                        wrap.appendChild(info);

                        // Textarea with better styling
                        const ta = document.createElement('textarea');
                        ta.rows = 5;
                        ta.name = 'content_' + p.productId;
                        ta.placeholder = 'Chia sẻ trải nghiệm của bạn... (ví dụ: chất lượng, kích thước, màu sắc, tính năng)';
                        ta.style.cssText = 'width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-family: "Poppins", sans-serif; font-size: 14px; resize: vertical; transition: border-color 0.3s; outline: none;';
                        ta.addEventListener('focus', ()=>{ ta.style.borderColor = '#d4af37'; ta.style.boxShadow = '0 0 0 3px rgba(212,175,55,0.1)'; });
                        ta.addEventListener('blur', ()=>{ ta.style.borderColor = '#ddd'; ta.style.boxShadow = 'none'; });
                        wrap.appendChild(ta);

                        // Submit button
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.textContent = '📤 Gửi đánh giá';
                        btn.style.cssText = 'margin-top: 12px; padding: 12px 20px; background: linear-gradient(135deg, #d4af37 0%, #e8c855 100%); border: none; color: white; font-weight: 600; border-radius: 8px; cursor: pointer; transition: all 0.3s; font-size: 14px; width: 100%;';
                        btn.addEventListener('mouseenter', ()=>{ btn.style.transform = 'translateY(-2px)'; btn.style.boxShadow = '0 6px 20px rgba(212,175,55,0.3)'; });
                        btn.addEventListener('mouseleave', ()=>{ btn.style.transform = 'none'; btn.style.boxShadow = 'none'; });
                        btn.addEventListener('click', async function(){
                            const comment = ta.value.trim();
                            if (!comment) { 
                                ta.style.borderColor = '#f8d7da';
                                setTimeout(()=>{ ta.style.borderColor = '#ddd'; }, 1000);
                                showToast('⚠️ Vui lòng nhập nội dung đánh giá', 'warning');
                                return; 
                            }
                            btn.disabled = true;
                            btn.textContent = '⏳ Đang gửi...';
                            try {
                                const res = await fetch(ROOT + '/product/postComment/' + encodeURIComponent(p.productId), {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                                    body: 'content=' + encodeURIComponent(comment)
                                });
                                const json = await res.json();
                                if (json.ok) {
                                    showToast('✅ Đã gửi đánh giá thành công', 'success');
                                    ta.value = '';
                                    ta.style.background = '#e8f5e9';
                                    btn.textContent = '✓ Đã gửi';
                                    btn.style.background = '#4caf50';
                                    btn.disabled = true;
                                    
                                    // Try to reload comments if on product detail
                                    if (typeof window.reloadComments === 'function') {
                                        try {
                                            await window.reloadComments();
                                        } catch (e) { /* ignore */ }
                                    }
                                } else {
                                    showToast('❌ Không thể gửi đánh giá', 'error');
                                    btn.disabled = false;
                                    btn.textContent = '📤 Gửi đánh giá';
                                }
                            } catch (e) {
                                console.error(e);
                                showToast('❌ Lỗi gửi đánh giá', 'error');
                                btn.disabled = false;
                                btn.textContent = '📤 Gửi đánh giá';
                            }
                        });
                        wrap.appendChild(btn);

                        content.appendChild(wrap);
                    });

                    box.appendChild(content);

                    // Modal footer with close button
                    const footer = document.createElement('div');
                    footer.style.cssText = 'padding: 20px 30px; background: #f8f9fa; border-top: 1px solid #eee; border-radius: 0 0 16px 16px; text-align: right;';

                    const closeFooterBtn = document.createElement('button');
                    closeFooterBtn.type = 'button';
                    closeFooterBtn.textContent = '✕ Đóng';
                    closeFooterBtn.style.cssText = 'padding: 10px 20px; background: #e5e5e5; border: none; color: #333; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;';
                    closeFooterBtn.addEventListener('mouseenter', ()=>{ closeFooterBtn.style.background = '#d5d5d5'; });
                    closeFooterBtn.addEventListener('mouseleave', ()=>{ closeFooterBtn.style.background = '#e5e5e5'; });
                    closeFooterBtn.addEventListener('click', ()=>{ modal.remove(); });
                    footer.appendChild(closeFooterBtn);
                    box.appendChild(footer);

                    modal.appendChild(box);
                    document.body.appendChild(modal);

                    // Add animations
                    const style = document.createElement('style');
                    style.textContent = '@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } } @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }';
                    document.head.appendChild(style);
                }
            } catch (e) { console.error(e); }
        })();

        function openInvoiceModal(orderId){
            var url = '<?php echo ROOT_URL; ?>cart/invoice/' + encodeURIComponent(orderId);
            var m = document.getElementById('invoiceModal');
            var f = document.getElementById('invoiceIframe');
            var btn = document.getElementById('invoicePrintBtn');
            f.src = url; m.classList.add('active');
            if(btn){ btn.onclick = function(){ try{ f.contentWindow.focus(); f.contentWindow.print(); }catch(e){} }; }
        }
        function closeInvoiceModal(){
            var m = document.getElementById('invoiceModal');
            var f = document.getElementById('invoiceIframe');
            f.src = 'about:blank'; m.classList.remove('active');
        }
    </script>
</body>
<style>
    .invoice-modal-overlay{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:9999}
    .invoice-modal-overlay.active{display:flex;align-items:center;justify-content:center}
    .invoice-modal{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,0.3);width:90%;max-width:960px;max-height:85vh;display:flex;flex-direction:column}
    .invoice-modal-header{display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-bottom:1px solid #eee}
    .invoice-modal-title{font-weight:700}
    .invoice-modal-close{border:none;background:#e74c3c;color:#fff;padding:8px 12px;border-radius:8px;cursor:pointer}
    .invoice-modal-body{flex:1}
    .invoice-iframe{width:100%;height:70vh;border:0}
    .invoice-modal-footer{padding:10px 16px;border-top:1px solid #eee;text-align:right}
</style>
<div id="invoiceModal" class="invoice-modal-overlay" aria-hidden="true">
  <div class="invoice-modal" role="dialog" aria-modal="true">
    <div class="invoice-modal-header">
      <div class="invoice-modal-title">Hóa đơn A4</div>
      <button class="invoice-modal-close" type="button" onclick="closeInvoiceModal()">Đóng</button>
    </div>
    <div class="invoice-modal-body">
      <iframe id="invoiceIframe" class="invoice-iframe"></iframe>
    </div>
    <div class="invoice-modal-footer">
      <button id="invoicePrintBtn" class="order-btn order-btn-primary" type="button">In hóa đơn</button>
    </div>
  </div>
</div>
</html>
</body>
</html>
