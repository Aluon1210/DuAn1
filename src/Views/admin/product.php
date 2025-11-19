<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý sản phẩm</title>
  <!-- Link đúng tới file CSS tone đen xám vàng -->
  <link rel="stylesheet" href="/DUAN1/asset/css/admin.css">
</head>
<body>
  <!-- Header -->
  <header class="admin-header">
    <div class="header-content">
      <h1>Luxury Admin Panel</h1>
      <div class="admin-actions">
        <a href="/DuAn1/" class="btn-back">Trang chủ</a>
        <a href="/DuAn1/auth/logout.php" class="btn-logout">Đăng xuất</a>
      </div>
    </div>
  </header>

  <!-- Layout: Sidebar + Content -->
  <div class="admin-container">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
      <nav class="admin-menu">
        <ul>
          <li><a href="dashboard.php" class="menu-item">Tổng quan</a></li>
          <li><a href="user.php" class="menu-item">Người dùng</a></li>
          <li><a href="product.php" class="menu-item active">Sản phẩm</a></li>
          <li><a href="order.php" class="menu-item">Đơn hàng</a></li>
          <li><a href="category.php" class="menu-item">Danh mục</a></li>
          <li><a href="comment.php" class="menu-item">Bình luận</a></li>
          <li><a href="stat.php" class="menu-item">Thống kê</a></li>
        </ul>
      </nav>
    </aside>

    <!-- Content -->
    <main class="admin-content">
      <section class="admin-section active">
        <h2>Quản lý sản phẩm</h2>

        <div class="dashboard-stats">
          <div class="stat-card">
            <h3>Tổng số sản phẩm</h3>
            <div class="stat-number">342</div>
          </div>
        </div>

        <form action="#" method="POST" enctype="multipart/form-data">
          <div class="form-row">
            <div class="form-group">
              <label>Tên sản phẩm</label>
              <input type="text" name="product_name" required>
            </div>
            <div class="form-group">
              <label>Giá</label>
              <input type="number" name="price" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Số lượng kho</label>
              <input type="number" name="stock">
            </div>
            <div class="form-group">
              <label>Danh mục</label>
              <select name="category">
                <option value="">-- Chọn danh mục --</option>
                <option value="ao">Áo</option>
                <option value="quan">Quần</option>
                <option value="phukien">Phụ kiện</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label>Mô tả sản phẩm</label>
            <textarea name="description" rows="4"></textarea>
          </div>

          <div class="form-group">
            <label>Ảnh sản phẩm</label>
            <input type="file" name="image">
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
