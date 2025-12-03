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
<<<<<<< HEAD

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($data['title'] ?? 'Quản lý người dùng'); ?></title>
    <link rel="stylesheet" href="/DuAn1/asset/css/admin.css">
    <style>
        .admin-content {
            padding: 20px;
        }
        
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .btn-add {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-add:hover {
            background-color: #218838;
        }
        
        .stats-box {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .stats-box p {
            margin: 0;
            font-size: 16px;
            color: #333;
        }
        
        .table-container {
            background: white;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table thead {
            background-color: #f9f9f9;
            border-bottom: 2px solid #ddd;
        }
        
        table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            color: #333;
        }
        
        table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        
        table tbody tr:hover {
            background-color: #f5f5f5;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .badge-admin {
            background-color: #dc3545;
            color: white;
        }
        
        .badge-user {
            background-color: #007bff;
            color: white;
        }
        
        .btn-small {
            padding: 6px 12px;
            margin-right: 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            font-size: 13px;
            display: inline-block;
            white-space: nowrap;
        }
        
        .btn-edit {
            background-color: #007bff;
            color: white;
        }
        
        .btn-edit:hover {
            background-color: #0056b3;
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background-color: #c82333;
        }
        
        .form-section {
            background: white;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            max-width: 800px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #218838;
        }
        
        .btn-cancel {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-cancel:hover {
            background-color: #5a6268;
        }
        
        .alert {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .empty-message {
            padding: 30px;
            text-align: center;
            color: #666;
            font-size: 16px;
        }
    </style>
</head>
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


    <header class="admin-header">
        <div class="header-content">
            <h1>Luxury Admin Panel</h1>
            <div class="admin-actions">
                <a href="/DuAn1/" class="btn-back">Trang chủ</a>
                <a href="/DuAn1/logout" class="btn-logout">Đăng xuất</a>
            </div>
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
    </header>


    <div class="admin-container">

        <!-- SIDEBAR -->
        <aside class="admin-sidebar">
            <nav class="admin-menu">
                <ul>
                    <li><a href="/DuAn1/admin/dashboard" class="menu-item">Tổng quan</a></li>
                    <li><a href="/DuAn1/admin/users" class="menu-item active">Người dùng</a></li>
                    <li><a href="/DuAn1/admin/products" class="menu-item">Sản phẩm</a></li>
                    <li><a href="/DuAn1/admin/orders" class="menu-item">Đơn hàng</a></li>
                    <li><a href="/DuAn1/admin/categories" class="menu-item">Danh mục</a></li>
                    <li><a href="/DuAn1/admin/branch" class="menu-item">Hãng</a></li>
                    <li><a href="/DuAn1/admin/comments" class="menu-item">Bình luận</a></li>
                    <li><a href="/DuAn1/admin/stats" class="menu-item">Thống kê</a></li>
                </ul>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="admin-content">
            
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
                            <input 
                                type="email" 
                                id="email"
                                name="email" 
                                value="<?php echo htmlspecialchars($data['editing'] && isset($data['user']) ? $data['user']['email'] : ''); ?>"
                                required
                                placeholder="user@example.com"
                            >
                        </div>

                        <div class="form-group">
                            <label for="name">Họ tên *</label>
                            <input 
                                type="text" 
                                id="name"
                                name="name" 
                                value="<?php echo htmlspecialchars($data['editing'] && isset($data['user']) ? $data['user']['name'] : ''); ?>"
                                required
                                placeholder="Nhập họ tên"
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input 
                                type="text" 
                                id="phone"
                                name="phone" 
                                value="<?php echo htmlspecialchars($data['editing'] && isset($data['user']) ? $data['user']['phone'] : ''); ?>"
                                placeholder="Nhập số điện thoại"
                            >
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
                        <input 
                            type="text" 
                            id="address"
                            name="address" 
                            value="<?php echo htmlspecialchars($data['editing'] && isset($data['user']) ? $data['user']['address'] : ''); ?>"
                            placeholder="Nhập địa chỉ"
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">
                            Mật khẩu <?php echo $data['editing'] ? '(để trống nếu không thay đổi)' : '*'; ?>
                        </label>
                        <input 
                            type="password" 
                            id="password"
                            name="password" 
                            placeholder="<?php echo $data['editing'] ? 'Để trống nếu không thay đổi' : 'Nhập mật khẩu'; ?>"
                            <?php echo !$data['editing'] ? 'required' : ''; ?>
                        >
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
                    <p><strong>Tổng người dùng:</strong> <?php echo $data['totalUsers']; ?></p>
                </div>

                <!-- BẢNG DANH SÁCH -->
                <div class="table-container">
                    <?php if ($data['totalUsers'] > 0): ?>
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
                                        <td><code><?php echo htmlspecialchars($user['id']); ?></code></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['phone'] ?? '-'); ?></td>
                                        <td>
                                            <span class="badge <?php echo $user['role'] === 'admin' ? 'badge-admin' : 'badge-user'; ?>">
                                                <?php echo $user['role'] === 'admin' ? 'Admin' : 'User'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="/DuAn1/admin/users?edit=<?php echo urlencode($user['id']); ?>" class="btn-small btn-edit">Sửa</a>
                                            <a href="/DuAn1/admin/deleteUser/<?php echo urlencode($user['id']); ?>" class="btn-small btn-delete" onclick="return confirm('Bạn chắc chắn muốn xóa người dùng này?')">Xóa</a>
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
=</body>
--
</html>
