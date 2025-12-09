<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thống kê hệ thống</title>
  <link rel="stylesheet" href="/DuAn1/asset/css/admin.css">
</head>
<body>
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
    <aside class="admin-sidebar">
      <nav class="admin-menu">
        <ul>
          <li><a href="dashboard.php" class="menu-item">Tổng quan</a></li>
          <li><a href="user.php" class="menu-item">Người dùng</a></li>
          <li><a href="product.php" class="menu-item">Sản phẩm</a></li>
          <li><a href="order.php" class="menu-item">Đơn hàng</a></li>
          <li><a href="category.php" class="menu-item">Danh mục</a></li>
          <li><a href="comment.php" class="menu-item">Bình luận</a></li>
          <li><a href="stat.php" class="menu-item active">Thống kê</a></li>
        </ul>
      </nav>
    </aside>

    <main class="admin-content">
      <section class="admin-section active">
        <h2>Thống kê hệ thống</h2>

        <div class="dashboard-stats">
          <div class="stat-card">
            <h3>Người dùng</h3>
            <div class="stat-number">128</div>
          </div>
          <div class="stat-card">
            <h3>Sản phẩm</h3>
            <div class="stat-number">342</div>
          </div>
          <div class="stat-card">
            <h3>Đơn hàng</h3>
            <div class="stat-number">215</div>
          </div>
          <div class="stat-card">
            <h3>Bình luận</h3>
            <div class="stat-number">87</div>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
