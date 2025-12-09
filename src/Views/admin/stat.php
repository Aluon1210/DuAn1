<?php
// stat.php
// Giả sử đã có kết nối DB và lấy dữ liệu thống kê từ DB
// Ví dụ:
// $totalUsers = pdo_query_value("SELECT COUNT(*) FROM users");
// $totalProducts = pdo_query_value("SELECT COUNT(*) FROM products");
// $totalOrders = pdo_query_value("SELECT COUNT(*) FROM orders");
// $totalComments = pdo_query_value("SELECT COUNT(*) FROM comments");

// Demo số liệu
$totalUsers = 128;
$totalProducts = 342;
$totalOrders = 215;
$totalComments = 87;
?>

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
  --primary: #007bff;
}

body {
  margin: 0;
  font-family: 'Inter', sans-serif;
  background: var(--bg);
  color: var(--text);
}

.admin-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.admin-content {
  max-width: 1500px;
  width: 100%;
  margin: 32px auto;
  padding: 0 20px;
}

h2 {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 30px;
}

/* Dashboard stats */
.dashboard-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

.stat-card {
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 20px 25px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  text-align: center;
}

.stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.stat-card h3 {
  font-size: 18px;
  font-weight: 600;
  color: var(--muted);
  margin-bottom: 10px;
}

.stat-number {
  font-size: 32px;
  font-weight: 700;
  color: var(--gold);
}

/* Responsive */
@media (max-width: 768px) {
  .admin-content {
    padding: 0 12px;
  }

  .stat-card {
    padding: 16px;
  }

  .stat-number {
    font-size: 28px;
  }
}

</style>
<body>


<div class="admin-container">
  <!-- SIDEBAR -->
  <?php include __DIR__ . '/aside.php'; ?>
    <main class="admin-content">
        <h2>Thống kê hệ thống</h2>

        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Người dùng</h3>
                <div class="stat-number"><?php echo $totalUsers; ?></div>
            </div>
            <div class="stat-card">
                <h3>Sản phẩm</h3>
                <div class="stat-number"><?php echo $totalProducts; ?></div>
            </div>
            <div class="stat-card">
                <h3>Đơn hàng</h3>
                <div class="stat-number"><?php echo $totalOrders; ?></div>
            </div>
            <div class="stat-card">
                <h3>Bình luận</h3>
                <div class="stat-number"><?php echo $totalComments; ?></div>
            </div>
        </div>
    </main>
</div>

</body>
</html>
