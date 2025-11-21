
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý sản phẩm</title>
  <!-- Link đúng tới file CSS tone đen xám vàng -->
  <link rel="stylesheet" href="/DuAn1/asset/css/admin.css">
</head>
<body>
  <!-- Header -->
  <header class="admin-header">
    <div class="header-content">
      <h1>Luxury Admin Panel</h1>
      <div class="admin-actions">
        <a href="/DuAn1/" class="btn-back">Trang chủ</a>
        <a href="<?php echo ROOT_URL; ?>logout" class="btn-logout">Đăng xuất</a>
      </div>
    </div>
  </header>

  <!-- Layout: Sidebar + Content -->
  <div class="admin-container">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
      <nav class="admin-menu">
        <ul>
          <li><a href="<?php echo ROOT_URL; ?>admin" class="menu-item">Tổng quan</a></li>
          <li><a href="<?php echo ROOT_URL; ?>admin/user.php" class="menu-item">Người dùng</a></li>
          <li><a href="<?php echo ROOT_URL; ?>admin/producs" class="menu-item active">Sản phẩm</a></li>
          <li><a href="<?php echo ROOT_URL; ?>admin/order.php" class="menu-item">Đơn hàng</a></li>
          <li><a href="<?php echo ROOT_URL; ?>admin/category.php" class="menu-item">Danh mục</a></li>
          <li><a href="<?php echo ROOT_URL; ?>admin/comment.php" class="menu-item">Bình luận</a></li>
          <li><a href="#" class="menu-item">Thống kê</a></li>
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
            <div class="stat-number"><?php echo isset($totalProducts) ? (int)$totalProducts : 0; ?></div>
          </div>
        </div>

        <!-- Form thêm / sửa sản phẩm -->
        <form action="<?php echo ROOT_URL; ?>admin/saveProduct" method="POST" enctype="multipart/form-data">
          <?php if (isset($editing) && $editing && !empty($product)): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
          <?php endif; ?>

          <div class="form-row">
            <div class="form-group">
              <label>Tên sản phẩm</label>
              <input type="text" name="name" required value="<?php echo isset($product['name']) ? htmlspecialchars($product['name']) : ''; ?>">
            </div>
            <div class="form-group">
              <label>Giá</label>
              <input type="number" name="price" required min="0" value="<?php echo isset($product['price']) ? (int)$product['price'] : 0; ?>">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Số lượng kho</label>
              <input type="number" name="quantity" min="0" value="<?php echo isset($product['quantity']) ? (int)$product['quantity'] : 0; ?>">
            </div>
            <div class="form-group">
              <label>Danh mục</label>
              <select name="category_id" required>
                <option value="">-- Chọn danh mục --</option>
                <?php if (!empty($categories)): ?>
                  <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category['id']); ?>"
                      <?php echo (isset($product['category_id']) && $product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Hãng (Branch)</label>
              <select name="branch_id" required>
                <option value="">-- Chọn hãng --</option>
                <?php if (!empty($branches)): ?>
                  <?php foreach ($branches as $branch): ?>
                    <option value="<?php echo htmlspecialchars($branch['id']); ?>"
                      <?php echo (isset($product['branch_id']) && $product['branch_id'] == $branch['id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($branch['name']); ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label>Mô tả sản phẩm</label>
            <textarea name="description" rows="4"><?php echo isset($product['description']) ? htmlspecialchars($product['description']) : ''; ?></textarea>
          </div>

          <div class="form-group">
            <label>Ảnh sản phẩm (tên file hoặc upload)</label>
            <input type="file" name="image">
            <?php if (!empty($product['image'])): ?>
              <div style="margin-top:10px;">
                <img src="<?php echo ROOT_URL; ?>public/images/<?php echo htmlspecialchars($product['image']); ?>" alt="" style="height:80px;border-radius:4px;">
              </div>
            <?php endif; ?>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-success"><?php echo (isset($editing) && $editing) ? 'Cập nhật' : 'Thêm mới'; ?></button>
            <button type="reset" class="btn btn-cancel">Hủy</button>
          </div>
        </form>

        <!-- Danh sách sản phẩm -->
        <?php if (!empty($products)): ?>
          <h3 style="margin-top:40px;margin-bottom:15px;">Danh sách sản phẩm</h3>
          <table>
            <thead>
              <tr>
                <th>Mã</th>
                <th>Ảnh</th>
                <th>Tên</th>
                <th>Danh mục</th>
                <th>Hãng</th>
                <th>Giá</th>
                <th>Kho</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $p): ?>
                <tr>
                  <td><?php echo htmlspecialchars($p['id']); ?></td>
                  <td>
                    <?php if (!empty($p['image'])): ?>
                      <img src="<?php echo ROOT_URL; ?>public/images/<?php echo htmlspecialchars($p['image']); ?>" alt="" style="height:50px;border-radius:4px;">
                    <?php endif; ?>
                  </td>
                  <td><?php echo htmlspecialchars($p['name']); ?></td>
                  <td><?php echo !empty($p['category_name']) ? htmlspecialchars($p['category_name']) : ''; ?></td>
                  <td><?php echo !empty($p['branch_name']) ? htmlspecialchars($p['branch_name']) : ''; ?></td>
                  <td><?php echo number_format($p['price'], 0, ',', '.'); ?> ₫</td>
                  <td><?php echo (int)$p['quantity']; ?></td>
                  <td>
                    <a class="btn btn-warning btn-small" href="<?php echo ROOT_URL; ?>admin/editProduct/<?php echo $p['id']; ?>">Sửa</a>
                    <a class="btn btn-danger btn-small" href="<?php echo ROOT_URL; ?>admin/deleteProduct/<?php echo $p['id']; ?>" onclick="return confirm('Xóa sản phẩm này?');">Xóa</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>Chưa có sản phẩm nào.</p>
        <?php endif; ?>

      </section>
    </main>
  </div>
</body>
</html>
