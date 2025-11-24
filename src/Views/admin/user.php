<?php
// Giả sử số lượng người dùng lấy từ DB
$totalUsers = 0;



// Biến thông báo
// $message = "";

// Xử lý form khi submit
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $username   = $_POST['username'] ?? '';
//     $email      = $_POST['email'] ?? '';
//     $fullName   = $_POST['full_name'] ?? '';
//     $phone      = $_POST['phone'] ?? '';
//     $address    = $_POST['address'] ?? '';
//     $role       = $_POST['role'] ?? 'user';
//     $password   = $_POST['password'] ?? '';

    // TODO: Lưu dữ liệu vào DB (ví dụ gọi Model User::create([...]))
    // Ở đây mình chỉ demo thông báo
//     $message = "Người dùng '$username' đã được thêm thành công!";
// }
?>
<!DOCTYPE html>
<html lang="vi">
<?php include __DIR__ . '/head.php'; ?>
<style>
  :root {
  --black: #0f0f10;
  --white: #ffffff;
  --text: #222;
  --muted: #666;
  --gold: #d4af37;
  --border: #e8e8e8;
  --bg: #f9f9f9;
  --success: #28a745;
  --danger: #dc3545;
}

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;
  background: var(--bg);
  color: var(--text);
  line-height: 1.5;
}

/* Container chính */
.admin-container {
  display: flex;
  min-height: 100vh;
  flex-direction: column;
}

/* Nội dung chính */
.admin-content {
  flex: 1;
  max-width: 1200px;
  margin: 32px auto;
  padding: 0 20px;
}

/* Section */
.admin-section h2 {
  font-size: 22px;
  font-weight: 700;
  margin-bottom: 20px;
  color: var(--black);
}

/* Thẻ thống kê */
.stat-card {
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.stat-card h3 {
  font-size: 18px;
  font-weight: 600;
  color: var(--gold);
}

.stat-number {
  font-size: 30px;
  font-weight: 700;
  color: var(--black);
}

/* Form */
.admin-form {
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 25px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.form-row {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
}

.form-group {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.form-group label {
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 6px;
  color: var(--muted);
}

.form-group input,
.form-group select {
  padding: 10px 12px;
  border: 1px solid var(--border);
  border-radius: 6px;
  font-size: 14px;
  transition: border-color 0.2s ease;
}

.form-group input:focus,
.form-group select:focus {
  border-color: var(--gold);
  outline: none;
}

/* Nút hành động */
.form-actions {
  margin-top: 20px;
  display: flex;
  gap: 12px;
}

.btn {
  padding: 10px 18px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  border: none;
  transition: all 0.2s ease;
}

.btn-success {
  background: var(--success);
  color: #fff;
}

.btn-success:hover {
  background: #218838;
}

.btn-cancel {
  background: var(--danger);
  color: #fff;
}

.btn-cancel:hover {
  background: #c82333;
}

/* Responsive */
@media (max-width: 768px) {
  .form-row {
    flex-direction: column;
  }
}

</style>
<body>
<div class="admin-container">

  <!-- SIDEBAR -->

  <?php include __DIR__ . '/aside.php'; ?>

  <!-- MAIN CONTENT -->
  <main class="admin-content">
    <section class="admin-section active">
      <h2>Quản lý người dùng</h2>

      <!-- BOX THỐNG KÊ -->
      <div class="stat-card" style="text-align:left; padding:20px 25px; margin-bottom:25px;">
        <h3 style="margin:0; font-size:18px; color:#ffd700;">Tổng số người dùng</h3>
        <div class="stat-number" style="font-size:30px; margin-top:10px;">
          <?= $totalUsers ?>
        </div>
      </div>

      <!-- THÔNG BÁO -->
      <?php if (!empty($message)): ?>
        <div style="padding:10px; background:#d4edda; color:#155724; border:1px solid #c3e6cb; border-radius:6px; margin-bottom:20px;">
          <?= htmlspecialchars($message) ?>
        </div>
      <?php endif; ?>

      <!-- FORM NGƯỜI DÙNG -->
      <form class="admin-form" method="POST">
        <div class="form-row">
          <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Họ tên</label>
            <input type="text" name="full_name" required>
          </div>
          <div class="form-group">
            <label>Số điện thoại</label>
            <input type="text" name="phone">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Địa chỉ</label>
            <input type="text" name="address">
          </div>
          <div class="form-group">
            <label>Vai trò</label>
            <select name="role">
              <option value="user">Người dùng</option>
              <option value="admin">Quản trị viên</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label>Mật khẩu</label>
          <input type="password" name="password">
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-success">Lưu</button>
          <button type="reset" class="btn btn-cancel">Hủy</button>
        </div>
      </form>
    </section>

    
  </main>
</div>
</body>
</html>
