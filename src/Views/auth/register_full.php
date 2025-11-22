<?php require_once ROOT_PATH . '/src/Views/includes/header.php'; ?>

<style>
    .auth-container {
        max-width: 500px;
        margin: 60px auto;
        background: white;
        padding: 50px 40px;
        border-radius: 16px;
        box-shadow: var(--shadow-hover);
    }

    .auth-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .auth-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 12px;
        color: var(--primary-black);
        letter-spacing: 1px;
    }

    .auth-header p {
        color: var(--text-light);
        font-size: 15px;
    }

    .auth-form {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-group input {
        padding: 16px 20px;
        border: 2px solid var(--border-light);
        border-radius: 12px;
        font-size: 15px;
        font-family: 'Poppins', sans-serif;
        transition: var(--transition-smooth);
        background: white;
        color: var(--text-dark);
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    .form-group input::placeholder {
        color: var(--text-light);
    }

    .form-hint {
        font-size: 13px;
        color: var(--text-light);
        margin-top: -4px;
    }

    .auth-submit {
        margin-top: 10px;
        padding: 18px;
        font-size: 16px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .auth-divider {
        display: flex;
        align-items: center;
        gap: 16px;
        margin: 30px 0;
        color: var(--text-light);
        font-size: 14px;
    }

    .auth-divider::before,
    .auth-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border-light);
    }

    .auth-footer {
        text-align: center;
        margin-top: 30px;
        padding-top: 30px;
        border-top: 1px solid var(--border-light);
        color: var(--text-light);
        font-size: 14px;
    }

    .auth-footer a {
        color: var(--primary-gold);
        text-decoration: none;
        font-weight: 600;
        margin-left: 4px;
        transition: var(--transition-smooth);
    }

    .auth-footer a:hover {
        color: var(--primary-black);
        text-decoration: underline;
    }
</style>

<div class="auth-container">
    <div class="auth-header">
        <h1>✨ Đăng Ký</h1>
        <p>Tạo tài khoản mới để trải nghiệm dịch vụ tốt nhất</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div style="background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); color: #721c24; padding: 16px 24px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #dc3545; box-shadow: var(--shadow-soft);">
            <?php echo htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['message'])): ?>
        <div style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); color: #155724; padding: 16px 24px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #28a745; box-shadow: var(--shadow-soft);">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <form method="POST" action="<?php echo ROOT_URL; ?>register/registerProcess" class="auth-form">
        <div class="form-group">
            <label for="name">Họ và tên</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                placeholder="Nhập họ và tên của bạn"
                value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                required
                autofocus
            >
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="Nhập email của bạn"
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)"
                required
                minlength="6"
            >
            <div class="form-hint">Mật khẩu phải có ít nhất 6 ký tự</div>
        </div>

        <div class="form-group">
            <label for="confirm_password">Xác nhận mật khẩu</label>
            <input 
                type="password" 
                id="confirm_password" 
                name="confirm_password" 
                placeholder="Nhập lại mật khẩu"
                required
                minlength="6"
            >
        </div>

        <div class="form-group">
            <label for="phone">Số điện thoại <span style="color: var(--text-light); font-weight: normal;">(Tùy chọn)</span></label>
            <input 
                type="tel" 
                id="phone" 
                name="phone" 
                placeholder="Nhập số điện thoại"
                value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
            >
        </div>

        <div class="form-group">
            <label for="address">Địa chỉ <span style="color: var(--text-light); font-weight: normal;">(Tùy chọn)</span></label>
            <input 
                type="text" 
                id="address" 
                name="address" 
                placeholder="Nhập địa chỉ"
                value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>"
            >
        </div>

        <button type="submit" class="btn btn-success auth-submit">
            Đăng Ký
        </button>
    </form>

    <div class="auth-divider">
        <span>HOẶC</span>
    </div>

    <div class="auth-footer">
        Đã có tài khoản? 
        <a href="<?php echo ROOT_URL; ?>login">Đăng nhập ngay</a>
    </div>
</div>

<?php require_once ROOT_PATH . '/src/Views/includes/footer.php'; ?>

