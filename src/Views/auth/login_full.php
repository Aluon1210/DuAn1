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

    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        margin-top: -8px;
    }

    .form-options label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-weight: 400;
        text-transform: none;
        letter-spacing: 0;
        color: var(--text-dark);
    }

    .form-options input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .form-options a {
        color: var(--primary-gold);
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition-smooth);
    }

    .form-options a:hover {
        color: var(--primary-black);
        text-decoration: underline;
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
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .modal-overlay.active { display: flex; }
    .modal-card {
        background: #fff;
        border-radius: 16px;
        width: 100%;
        max-width: 480px;
        padding: 24px;
        box-shadow: var(--shadow-hover);
    }
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .modal-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary-black);
    }
    .modal-close {
        background: transparent;
        border: none;
        font-size: 20px;
        cursor: pointer;
        line-height: 1;
    }
</style>

<div class="auth-container">
    <div class="auth-header">
        <h1>🔐 Đăng Nhập</h1>
        <p>Chào mừng bạn quay trở lại Luxury Fashion Store</p>
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

    <form method="POST" action="<?php echo ROOT_URL; ?>login/process" class="auth-form">
        <input type="hidden" name="redirect" value="<?php echo isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : ''; ?>">
        <div class="form-group">
            <label for="email">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="Nhập email của bạn"
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                required
                autofocus
            >
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                placeholder="Nhập mật khẩu"
                required
            >
        </div>

        <div class="form-options">
            <label>
                <input type="checkbox" name="remember">
                <span>Ghi nhớ đăng nhập</span>
            </label>
            <a href="#" id="forgotPasswordLink">Quên mật khẩu?</a>
        </div>

        <button type="submit" class="btn btn-success auth-submit">
            Đăng Nhập
        </button>
    </form>

    <div class="modal-overlay" id="forgotPasswordModal">
      <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="forgotTitle">
        <div class="modal-header">
          <div class="modal-title" id="forgotTitle">🔐 Lấy lại mật khẩu</div>
          <button class="modal-close" type="button" id="forgotCloseBtn" aria-label="Đóng">×</button>
        </div>
        <form method="POST" action="<?php echo ROOT_URL; ?>auth/send-reset-link" class="auth-form" id="forgotPasswordForm">
          <div class="form-group">
            <label for="forgot_email">Email đã đăng ký</label>
            <input 
              type="email" 
              id="forgot_email" 
              name="email" 
              placeholder="Nhập email đã đăng ký"
              required
            >
          </div>
          <button type="submit" class="btn btn-primary auth-submit">
            Gửi liên kết đặt lại mật khẩu
          </button>
        </form>
      </div>
    </div>

    <div class="auth-divider">
        <span>HOẶC</span>
    </div>

    <div style="text-align:center;">
        <a href="<?php echo ROOT_URL; ?>auth/google" class="btn" style="display:inline-flex;align-items:center;gap:8px;padding:12px 18px;border-radius:10px;border:1px solid var(--border-light);background:white;color:var(--text-dark);text-decoration:none;box-shadow:var(--shadow-soft);">
            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google" style="width:20px;height:20px;"> Đăng nhập với Google
        </a>
    </div>

    <div class="auth-footer">
        Chưa có tài khoản? 
        <a href="<?php echo ROOT_URL; ?>register">Đăng ký ngay</a>
    </div>
</div>

<script>
  (function(){
    var link = document.getElementById('forgotPasswordLink');
    var modal = document.getElementById('forgotPasswordModal');
    var closeBtn = document.getElementById('forgotCloseBtn');
    var emailInput = document.getElementById('email');
    var forgotEmail = document.getElementById('forgot_email');
    function openModal(){
      if (!modal) return;
      modal.classList.add('active');
      if (emailInput && forgotEmail && !forgotEmail.value) {
        forgotEmail.value = emailInput.value || '';
      }
      try { forgotEmail.focus(); } catch(_){}
    }
    function closeModal(){
      if (!modal) return;
      modal.classList.remove('active');
    }
    if (link) {
      link.addEventListener('click', function(e){ e.preventDefault(); openModal(); });
    }
    if (closeBtn) {
      closeBtn.addEventListener('click', function(){ closeModal(); });
    }
    if (modal) {
      modal.addEventListener('click', function(e){ if (e.target === modal) { closeModal(); } });
      document.addEventListener('keydown', function(e){ if (e.key === 'Escape') { closeModal(); } });
    }
  })();
</script>

<?php require_once ROOT_PATH . '/src/Views/includes/footer.php'; ?>

