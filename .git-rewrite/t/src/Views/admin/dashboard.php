<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
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
    
    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
      <nav class="admin-menu">
        <ul>
          <li><a href="dashboard.php" class="menu-item active">Tổng quan</a></li>
          <li><a href="user.php" class="menu-item">Người dùng</a></li>
          <li><a href="product.php" class="menu-item">Sản phẩm</a></li>
          <li><a href="order.php" class="menu-item">Đơn hàng</a></li>
          <li><a href="category.php" class="menu-item">Danh mục</a></li>
          <li><a href="comment.php" class="menu-item">Bình luận</a></li>
          <li><a href="stat.php" class="menu-item">Thống kê</a></li>
        </ul>
      </nav>
    </aside>

    <!-- CONTENT -->
    <main class="admin-content">
      <section class="admin-section active">
        
        <h2>Tổng quan hệ thống</h2>

        <div class="dashboard-stats">
          <div class="stat-card">
            <h3>Doanh thu hôm nay</h3>
            <div class="stat-number">12,500,000đ</div>
          </div>

          <div class="stat-card">
            <h3>Đơn hàng mới</h3>
            <div class="stat-number">18</div>
          </div>

          <div class="stat-card">
            <h3>Người dùng mới</h3>
            <div class="stat-number">5</div>
          </div>

          <div class="stat-card">
            <h3>Sản phẩm tồn kho</h3>
            <div class="stat-number">1,024</div>
          </div>
        </div>

      </section>
    </main>
  </div>

</body>
</html>
