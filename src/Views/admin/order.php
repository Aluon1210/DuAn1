<?php
// Dữ liệu mẫu đơn hàng (sau này thay bằng DB)
$orders = [
  ['id' => 'DH001', 'name' => 'Nguyễn Văn A', 'date' => '18/11/2025', 'status' => 'Hoàn tất'],
  ['id' => 'DH002', 'name' => 'Trần Thị B', 'date' => '17/11/2025', 'status' => 'Đang xử lý'],
];
?>
<!DOCTYPE html>
<html lang="vi">
<?php include __DIR__ . '/head.php'; ?>
<style>
  :root {
    --black: #0f0f10;
    --white: #fff;
    --text: #222;
    --gold: #d4af37;
    --border: #e8e8e8;
    --bg: #f6f6f7;
    --success: #28a745;
    --danger: #dc3545;
    --processing: #ffc107;
  }

  body {
    margin: 0;
    font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;
    background: var(--bg);
    color: var(--text);
    line-height: 1.5;
  }

  /* Header ĐEN */
  .header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100px;
    background: #0f0f10;
    color: var(--gold);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 60px;
    z-index: 1000;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.45);
  }

  .header .brand {
    font-size: 30px;
    font-weight: 700;
  }

  /* Sidebar TRẮNG như yêu cầu */
  aside.sidebar {
    position: fixed;
    top: 100px;
    left: 0;
    width: 260px;
    height: calc(100vh - 100px);
    background: #fff;
    border-right: 3px solid var(--gold);
    padding: 20px;
    overflow-y: auto;
    box-shadow: 4px 0 15px rgba(0,0,0,0.08);
    z-index: 999;
  }

  .menu-item {
    display: block;
    padding: 12px 16px;
    margin-bottom: 10px;
    background: #ededed;
    color: #000;
    border-radius: 10px;
    font-weight: 500;
    transition: 0.22s;
  }

  .menu-item:hover {
    background: #dcdcdc;
    transform: translateX(4px);
  }

  .menu-item.active {
    background: var(--gold);
    color: #111;
    font-weight: 600;
  }

  /* CONTENT */
  main.container {
    margin-top: 120px;
    margin-left: 300px;
    width: calc(100% - 300px);
    padding: 20px;
    box-sizing: border-box;
  }

  h2 {
    margin-bottom: 18px;
  }

  .stat-card {
    background: #fff;
    padding: 16px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.07);
  }

  .stat-number {
    font-size: 30px;
    font-weight: 700;
    margin-top: 5px;
  }

  /* Table */
  table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.06);
  }

  table th, table td {
    padding: 12px 14px;
    border-bottom: 1px solid var(--border);
  }

  table th {
    background: #0f0f10;
    color: #fff;
    font-weight: 600;
  }

  table tr:hover td {
    background: #f1f1f1;
  }

  /* Status */
  .order-status {
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #fff;
  }

  .completed { background: var(--success); }
  .processing { background: var(--processing); color: #111; }

  /* Action buttons */
  .order-actions a {
    margin-right: 8px;
    font-size: 14px;
    padding: 6px 10px;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    transition: .2s;
  }

  .order-actions a:first-child {
    background: var(--gold);
    color: #111;
  }

  .btn-delete {
    background: var(--danger);
    color: #fff;
  }

  .order-actions a:hover {
    opacity: .85;
  }

</style>

<body>
  <div class="admin-container">
    <?php include __DIR__ . '/aside.php'; ?>

    <main class="container">
      <h2>Quản lý đơn hàng</h2>

      <div class="stat-card">
        <h3>Tổng số đơn hàng</h3>
        <div class="stat-number"><?= count($orders) ?></div>
      </div>

      <table>
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
          <?php foreach ($orders as $order): ?>
            <tr>
              <td>#<?= htmlspecialchars($order['id']) ?></td>
              <td><?= htmlspecialchars($order['name']) ?></td>
              <td><?= htmlspecialchars($order['date']) ?></td>
              <td>
                <span class="order-status <?= $order['status'] === 'Hoàn tất' ? 'completed' : 'processing' ?>">
                  <?= htmlspecialchars($order['status']) ?>
                </span>
              </td>
              <td class="order-actions">
                <a href="/admin/order/view/<?= $order['id'] ?>">Xem</a>
                <a href="/admin/order/delete/<?= $order['id'] ?>" class="btn-delete"
                  onclick="return confirm('Xóa đơn hàng?')">Xóa</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </main>
  </div>
</body>
</html>
