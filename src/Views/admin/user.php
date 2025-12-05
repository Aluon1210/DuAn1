<?php
// Giả sử số lượng người dùng lấy từ DB
$totalUsers = $data['totalUsers'] ?? 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($data['title'] ?? 'Quản lý người dùng'); ?></title>
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

/* ===== BODY CHUNG ===== */
body {
  font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;
  background: var(--bg);
  color: var(--text);
  margin: 0;
  line-height: 1.5;
}

/* ===== HEADER ===== */
.admin-header {
  background: var(--black);
  color: #fff;
  padding: 18px 30px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-content h1 {
  font-size: 22px;
  font-weight: 700;
  margin: 0;
}

.admin-actions a {
  padding: 8px 14px;
  margin-left: 10px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 600;
  font-size: 14px;
  transition: 0.2s;
}

.btn-back {
  background: var(--warning);
  color: #111;
}
.btn-back:hover {
  background: #e0a800;
}

.btn-logout {
  background: var(--danger);
  color: #fff;
}
.btn-logout:hover {
  background: #bd2130;
}

/* ===== CONTAINER CHUNG ===== */
.admin-container {
  display: flex;
  min-height: calc(100vh - 80px);
}

/* ===== MAIN CONTENT ===== */
.admin-content {
  flex: 1;
  max-width: 1200px;
  margin: 32px auto;
  padding: 0 20px;
}

/* ===== STAT CARD (Tổng người dùng) ===== */
.stat-card {
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 20px 25px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.stat-card h3 {
  font-size: 18px;
  color: var(--gold);
  margin: 0;
}

.stat-number {
  font-size: 32px;
  font-weight: 700;
  margin-top: 10px;
  color: var(--black);
}

/* ===== ALERTS ===== */
.alert {
  padding: 12px 16px;
  border-radius: 6px;
  margin-bottom: 16px;
  font-size: 14px;
}

.alert-success {
  background: #d4edda;
  border: 1px solid #c3e6cb;
  color: #155724;
}

.alert-error {
  background: #f8d7da;
  border: 1px solid #f5c6cb;
  color: #721c24;
}

/* ===== FORM SECTION ===== */
.form-section {
  background: #fff;
  padding: 25px;
  border: 1px solid var(--border);
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  margin-bottom: 30px;
}

.form-section h2 {
  margin-bottom: 20px;
  font-size: 22px;
  font-weight: 700;
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
  color: var(--muted);
  margin-bottom: 6px;
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
  text-decoration: none;
  transition: 0.2s;
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

/* ===== TABLE ===== */
.table-container table {
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
  padding: 14px 12px;
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

/* Badge cho role */
.badge {
  padding: 5px 10px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 600;
  color: #fff;
}

.badge-admin {
  background: var(--black);
}

.badge-user {
  background: var(--gold);
  color: #111;
}

/* Nút danh sách */
.btn-small {
  padding: 6px 10px;
  font-size: 13px;
  border-radius: 4px;
}

.btn-edit {
  background: var(--warning);
  color: #111;
}
.btn-edit:hover {
  background: #e0a800;
}

.btn-delete {
  background: var(--danger);
  color: #fff;
}
.btn-delete:hover {
  background: #bd2130;
}

/* Responsive */
@media (max-width: 768px) {
  .form-row {
    flex-direction: column;
  }
}
 /* ================== FIX & BỔ SUNG CÁC CLASS CÒN THIẾU ================== */

/* Header danh sách người dùng */
.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

/* Nút thêm người dùng */
.btn-add {
    background: var(--success);
    color: #fff;
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 14px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.2s;
}
.btn-add:hover {
    background: #218838;
}

/* Ô thống kê nhỏ */
.stats-box {
    background: #fff;
    border: 1px solid var(--border);
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 16px;
    font-size: 14px;
}

/* Hiển thị khi không có dữ liệu */
.empty-message {
    padding: 18px;
    background: #fff9c4;
    border: 1px solid #ffe082;
    border-radius: 8px;
    color: #6d5f00;
    margin-top: 10px;
}

/* Giảm việc bảng bị tràn ở màn hình nhỏ */
.table-container {
    overflow-x: auto;
}

    </style>
</head>
<body>

<header class="admin-header">
    <div class="header-content">
        <h1>Luxury Admin Panel</h1>
        <div class="admin-actions">
            <a href="/DuAn1/" class="btn-back">Trang chủ</a>
            <a href="/DuAn1/logout" class="btn-logout">Đăng xuất</a>
        </div>
    </div>
</header>

<div class="admin-container">

    <!-- SIDEBAR -->
    <?php include __DIR__ . '/aside.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="admin-content">

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

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- FORM THÊM / SỬA NGƯỜI DÙNG -->
        <div class="form-section">
            <h2><?php echo $data['editing'] ? 'Sửa người dùng' : 'Thêm người dùng mới'; ?></h2>

            <form method="POST" action="/DuAn1/admin/saveUser">
                <?php if ($data['editing'] && isset($data['user'])): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['user']['id']); ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo htmlspecialchars($data['editing'] && isset($data['user']) ? $data['user']['email'] : ''); ?>"
                               required placeholder="user@example.com">
                    </div>

                    <div class="form-group">
                        <label for="name">Họ tên *</label>
                        <input type="text" id="name" name="name"
                               value="<?php echo htmlspecialchars($data['editing'] && isset($data['user']) ? $data['user']['name'] : ''); ?>"
                               required placeholder="Nhập họ tên">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="text" id="phone" name="phone"
                               value="<?php echo htmlspecialchars($data['editing'] && isset($data['user']) ? $data['user']['phone'] : ''); ?>"
                               placeholder="Nhập số điện thoại">
                    </div>

                    <div class="form-group">
                        <label for="role">Vai trò</label>
                        <select id="role" name="role">
                            <option value="user" <?php echo ($data['editing'] && isset($data['user']) && $data['user']['role'] === 'user') ? 'selected' : ''; ?>>Người dùng</option>
                            <option value="admin" <?php echo ($data['editing'] && isset($data['user']) && $data['user']['role'] === 'admin') ? 'selected' : ''; ?>>Quản trị viên</option>
                            <option value="forbident" <?php echo ($data['editing'] && isset($data['user']) && $data['user']['role'] === 'forbident') ? 'selected' : ''; ?>>Bị chặn</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Địa chỉ</label>
                    <input type="text" id="address" name="address"
                           value="<?php echo htmlspecialchars($data['editing'] && isset($data['user']) ? $data['user']['address'] : ''); ?>"
                           placeholder="Nhập địa chỉ">
                </div>

                <div class="form-group">
                    <label for="password">
                        Mật khẩu <?php echo $data['editing'] ? '(để trống nếu không thay đổi)' : '*'; ?>
                    </label>
                    <input type="password" id="password" name="password"
                           placeholder="<?php echo $data['editing'] ? 'Để trống nếu không thay đổi' : 'Nhập mật khẩu'; ?>"
                           <?php echo !$data['editing'] ? 'required' : ''; ?>>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success">
                        <?php echo $data['editing'] ? 'Cập nhật' : 'Lưu'; ?>
                    </button>
                    <a href="/DuAn1/admin/users" class="btn btn-cancel">Hủy</a>
                </div>
            </form>
        </div>

        <!-- DANH SÁCH NGƯỜI DÙNG -->
        <section>
            <div class="content-header">
                <h2>Danh sách người dùng</h2>
                <a href="/DuAn1/admin/users" class="btn-add">+ Thêm người dùng</a>
            </div>

            <!-- THỐNG KÊ -->
            <div class="stats-box">
                <p><strong>Tổng người dùng:</strong> <?= $totalUsers ?></p>
            </div>

            <!-- BẢNG DANH SÁCH -->
            <div class="table-container">
                <?php if (!empty($data['users'])): ?>
                    <table>
                        <thead>
                            <tr>
                                <th width="12%">Username</th>
                                <th width="18%">Email</th>
                                <th width="15%">Họ tên</th>
                                <th width="13%">Số điện thoại</th>
                                <th width="10%">Vai trò</th>
                                <th width="22%">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['users'] as $user): ?>
                                <tr>
                                    <td><code><?= htmlspecialchars($user['id']); ?></code></td>
                                    <td><?= htmlspecialchars($user['email']); ?></td>
                                    <td><?= htmlspecialchars($user['name']); ?></td>
                                    <td><?= htmlspecialchars($user['phone'] ?? '-'); ?></td>
                                    <td>
                                        <span class="badge <?= $user['role'] === 'admin' ? 'badge-admin' : 'badge-user'; ?>">
                                            <?= $user['role'] === 'admin' ? 'Admin' : 'User'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/DuAn1/admin/users?edit=<?= urlencode($user['id']); ?>" class="btn-small btn-edit">Sửa</a>
                                        <a href="/DuAn1/admin/deleteUser/<?= urlencode($user['id']); ?>" class="btn-small btn-delete" onclick="return confirm('Bạn chắc chắn muốn xóa người dùng này?')">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-message">
                        Chưa có người dùng nào. <a href="/DuAn1/admin/users">Thêm người dùng mới</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

    </main>
</div>

</body>
</html>
