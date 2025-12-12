
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
  --warning: #ffc107;
}

body {
  font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;
  background: var(--bg);
  color: var(--text);
  margin: 0;
  line-height: 1.5;
}

.admin-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.admin-content {
  max-width: 1200px;
  margin: 32px auto;
  padding: 0 20px;
}

.admin-section h2 {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 24px;
}

/* Thống kê */
.dashboard-stats {
  display: flex;
  gap: 20px;
  margin-bottom: 30px;
}

.stat-card {
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 20px 25px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  flex: 1;
}

.stat-card h3 {
  font-size: 18px;
  color: var(--gold);
  margin-bottom: 10px;
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
.form-group select,
.form-group textarea {
  padding: 10px 12px;
  border: 1px solid var(--border);
  border-radius: 6px;
  font-size: 14px;
  transition: border-color 0.2s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: var(--gold);
  outline: none;
}

/* Nút */
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
  text-decoration: none;
  display: inline-block;
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

.btn-warning {
  background: var(--warning);
  color: #111;
}
.btn-warning:hover {
  background: #e0a800;
}

.btn-danger {
  background: var(--danger);
  color: #fff;
}
.btn-danger:hover {
  background: #bd2130;
}

.btn-small {
  padding: 6px 10px;
  font-size: 13px;
  border-radius: 4px;
}

/* Bảng sản phẩm */
table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

table th,
table td {
  padding: 12px 14px;
  text-align: left;
  border-bottom: 1px solid var(--border);
}

table th {
  background: var(--black);
  color: #fff;
  font-weight: 600;
}

table tr:hover td {
  background: #f1f1f1;
}

.col-action {
  width: 140px;
  text-align: center;
}
.col-action .action-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 6px;
}
.col-action .action-grid .btn {
  width: 100%;
  padding: 6px 0;
  display: inline-block;
}

/* Responsive */
@media (max-width: 768px) {
  .form-row {
    flex-direction: column;
  }
  .dashboard-stats {
    flex-direction: column;
  }
}

</style>
<body>

  <!-- Layout: Sidebar + Content -->
  <div class="admin-container">
    <!-- Sidebar -->
    <?php include __DIR__ . '/aside.php'; ?>

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
                <th class="col-action">Thao tác</th>
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
                  <td class="col-action">
                    <div class="action-grid">
                      <a class="btn btn-warning btn-small" href="<?php echo ROOT_URL; ?>admin/editProduct/<?php echo $p['id']; ?>">Sửa</a>
                      <a class="btn btn-danger btn-small" href="<?php echo ROOT_URL; ?>admin/deleteProduct/<?php echo $p['id']; ?>" onclick="return confirm('Ẩn sản phẩm này?');">Ẩn</a>
                    </div>
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
