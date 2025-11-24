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
    --bg: #f9f9f9;
    --success: #28a745;
    --danger: #dc3545;
    --processing: #ffc107;
  }

  /* Base */
  body {
    margin: 0;
    font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;
    background: var(--bg);
    color: var(--text);
    line-height: 1.5;
  }

  /* Header */
  .header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100px;
    background: #1B2127;
    color: #FFD700;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 80px;
    border-bottom-left-radius: 12px;
    border-bottom-right-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
    z-index: 1000;
  }

  .header .brand {
    font-size: 32px;
    font-weight: 700;
  }

  /* Sidebar */
  aside.sidebar {
    position: fixed;
    top: 100px;
    /* ngay dưới header */
    left: 0;
    width: 260px;
    height: calc(100vh - 100px);
    background: #fff;
    border-right: 3px solid #CD853F;
    padding: 20px;
    overflow-y: auto;
    box-shadow: 4px 0 15px rgba(0, 0, 0, 0.08);
    z-index: 999;
  }

  .menu-item {
    display: block;
    padding: 12px 16px;
    margin-bottom: 10px;
    background: #e8e8e8;
    color: #000;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.2s;
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

  /* Main content */
  main.container {
    margin-top: 100px;
    /* bằng header height */
    margin-left: calc(260px + 30px);
    /* sidebar + khoảng cách */
    width: calc(100% - 260px - 30px);
    padding: 20px;
    box-sizing: border-box;
  }

  /* Table */
  table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
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

  /* Trạng thái */
  .order-status {
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #fff;
    display: inline-block;
  }

  .order-status.completed {
    background: var(--success);
  }

  .order-status.processing {
    background: var(--processing);
    color: #111;
  }

  /* Hành động */
  .order-actions a {
    margin-right: 8px;
    font-size: 14px;
    text-decoration: none;
    padding: 6px 10px;
    border-radius: 4px;
    font-weight: 600;
    transition: all 0.2s;
  }

  .order-actions a:first-child {
    background: var(--gold);
    color: #111;
  }

  .order-actions .btn-delete {
    background: var(--danger);
    color: #fff;
  }

  .order-actions a:hover {
    opacity: 0.85;
  }

  /* Responsive */
  @media (max-width: 768px) {
    aside.sidebar {
      position: relative;
      width: 100%;
      height: auto;
      top: 0;
    }

    main.container {
      margin-left: 0;
      margin-top: calc(100px + 20px);
      width: 100%;
      padding: 15px;
    }

    table th,
    table td {
      font-size: 13px;
      padding: 10px;
    }

    .order-actions a {
      font-size: 12px;
      padding: 5px 8px;
    }

    table {
      display: block;
      max-height: 400px;
      overflow-y: auto;
    }
  }
</style>

</style>

<body>
  <!-- SIDEBAR -->
  <div class="admin-container">
    <?php include __DIR__ . '/aside.php'; ?>
    <!-- Nội dung -->
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
                  onclick="return confirm('Bạn có chắc muốn xóa đơn này?')">Xóa</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </main>
  </div>
</body>

</html>