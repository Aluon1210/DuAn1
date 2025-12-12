<?php require_once ROOT_PATH . '/src/Views/includes/header.php'; ?>
<style>
  .auth-container { max-width: 520px; margin: 40px auto; background: #fff; border-radius: 16px; box-shadow: var(--shadow-hover); padding: 24px; }
  .auth-header { text-align: center; margin-bottom: 16px; }
  .auth-header h1 { font-size: 28px; margin: 0; color: var(--primary-black); }
  .auth-form { display: flex; flex-direction: column; gap: 16px; }
  .form-group { display: flex; flex-direction: column; gap: 8px; }
  .form-group label { font-weight: 600; }
  .auth-submit { width: 100%; padding: 12px 18px; border-radius: 10px; background: var(--primary-gold); color: #fff; border: none; cursor: pointer; }
  .auth-submit:hover { filter: brightness(0.95); }
  .alert-success { background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 16px; border-left: 4px solid #28a745; }
  .alert-danger { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 16px; border-left: 4px solid #dc3545; }
  .pwd-input { width: 100%; height: 42px; border: 1px solid var(--border-light); border-radius: 10px; padding: 8px 12px; outline: none; }
</style>
<div class="auth-container">
  <div class="auth-header">
    <h1>Đặt lại mật khẩu</h1>
  </div>
  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert-success"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert-danger"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>
  <form method="POST" action="<?php echo ROOT_URL; ?>auth/reset-password/<?php echo htmlspecialchars($token); ?>" class="auth-form">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    <div class="form-group">
      <label for="password">Mật khẩu mới</label>
      <input type="password" id="password" name="password" class="pwd-input" minlength="6" required autocomplete="new-password">
    </div>
    <div class="form-group">
      <label for="confirm_password">Xác nhận mật khẩu</label>
      <input type="password" id="confirm_password" name="confirm_password" class="pwd-input" minlength="6" required autocomplete="new-password">
    </div>
    <button type="submit" class="btn btn-success auth-submit">Đặt lại mật khẩu</button>
  </form>
</div>
<?php require_once ROOT_PATH . '/src/Views/includes/footer.php'; ?>
