<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý đơn hàng</title>
  <link rel="stylesheet" href="/DuAn1/asset/css/admin.css">
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
    <aside class="admin-sidebar">
      <nav class="admin-menu">
        <ul>
          <li><a href="dashboard.php" class="menu-item">Tổng quan</a></li>
          <li><a href="user.php" class="menu-item">Người dùng</a></li>
          <li><a href="product.php" class="menu-item">Sản phẩm</a></li>
          <li><a href="order.php" class="menu-item active">Đơn hàng</a></li>
          <li><a href="category.php" class="menu-item">Danh mục</a></li>
          <li><a href="comment.php" class="menu-item">Bình luận</a></li>
          <li><a href="stat.php" class="menu-item">Thống kê</a></li>
        </ul>
      </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="admin-content">
      <section class="admin-section active">

        <h2>Quản lý đơn hàng</h2>

        <!-- THỐNG KÊ -->
        <div class="stat-card" style="text-align:left; padding:20px 25px; margin-bottom:25px;">
          <h3 style="margin:0; font-size:18px; color:#ffd700;">Tổng số đơn hàng</h3>
          <div class="stat-number" style="font-size:30px; margin-top:10px;">215</div>
        </div>

        <!-- BẢNG ĐƠN HÀNG -->
        <table class="order-table">
  <thead>
    <tr>
      <th>Mã đơn</th>
      <th>Khách hàng</th>
      <th>Ngày đặt</th>
      <th>Trạng thái</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>#DH001</td>
      <td>Nguyễn Văn A</td>
      <td>18/11/2025</td>
      <td><span class="order-status completed">Hoàn tất</span></td>
      <td class="order-actions">
        <a href="/admin/order/view/DH001" class="btn-view">Xem</a>
        <a href="/admin/order/delete/DH001" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa đơn này?')">Xóa</a>
      </td>
    </tr>
    <tr>
      <td>#DH002</td>
      <td>Trần Thị B</td>
      <td>17/11/2025</td>
      <td><span class="order-status processing">Đang xử lý</span></td>
      <td class="order-actions">
        <a href="/admin/order/view/DH002" class="btn-view">Xem</a>
        <a href="/admin/order/delete/DH002" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa đơn này?')">Xóa</a>
      </td>
    </tr>
  </tbody>
</table>


      </section>
    </main>

  </div>

</body>
</html>
