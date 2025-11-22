<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý người dùng</title>
  <link rel="stylesheet" href="/DUAN1/asset/css/admin.css">
</head>

<body>
  <!-- HEADER -->
  <header class="admin-header">
    <div class="header-content">
      <h1>Luxury Admin Panel</h1>
      <div class="admin-actions">
        <a href="/DuAn1/" class="btn-back">Trang chủ</a>
        <a href="/DuAn1/auth/logout.php" class="btn-logout">Đăng xuất</a>
      </div>
    </div>
  </header>

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
          <div class="stat-number" style="font-size:30px; margin-top:10px;">128</div>
        </div>

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
