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
    <?php include __DIR__ . '/aside.php'; ?>

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
