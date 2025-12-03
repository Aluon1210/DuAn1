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
    max-width: 1400px;
    width: 100%;
    margin: 32px auto;
    padding: 0 20px;
  }

  h2 {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 20px;
  }

  /* Alert messages */
  .alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
    font-size: 15px;
  }

  .alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
  }

  .alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
  }

  /* Form section */
  .form-section {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    margin-bottom: 40px;
  }

  .form-group {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
  }

  .form-group label {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 6px;
    color: var(--muted);
  }

  .form-group input {
    padding: 10px 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s ease;
  }

  .form-group input:focus {
    border-color: var(--gold);
    outline: none;
  }

  .form-actions {
    display: flex;
    gap: 12px;
    margin-top: 10px;
  }

  .btn {
    padding: 10px 18px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    text-decoration: none;
    display: inline-block;
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

  /* Content header */
  .content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }

  .btn-add {
    background: var(--primary);
    color: #fff;
    padding: 8px 14px;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
  }

  .btn-add:hover {
    background: #0056b3;
  }

  /* Stats box */
  .stats-box {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 15px 20px;
    margin-bottom: 20px;
    font-size: 16px;
    color: #333;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
  }

  /* Table */
  .table-container {
    background: #fff;
    border-radius: 12px;
    overflow-x: auto;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    width: 100%;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
  }

  table thead {
    background-color: #f4f4f4;
    border-bottom: 2px solid #ddd;
  }

  table th {
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: #555;
  }

  table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    font-size: 15px;
  }

  table tbody tr:hover {
    background-color: #fafafa;
  }

  /* Action buttons */
  table td:last-child {
    text-align: center;
    min-width: 200px;
  }

  .btn-small {
    padding: 8px 16px;
    margin: 4px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    min-width: 90px;
    display: inline-block;
    text-align: center;
  }

  .btn-edit {
    background-color: var(--primary);
    color: white;
  }

  .btn-edit:hover {
    background-color: #0056b3;
  }

  .btn-delete {
    background-color: var(--danger);
    color: white;
  }

  .btn-delete:hover {
    background-color: #c82333;
  }

  /* Empty message */
  .empty-message {
    padding: 20px;
    text-align: center;
    font-size: 15px;
    color: var(--muted);
  }

  .empty-message a {
    color: var(--primary);
    text-decoration: underline;
  }
</style>

<body>
  <div class="admin-container">

    <!-- SIDEBAR -->
    <?php include __DIR__ . '/aside.php'; ?>
    <!-- MAIN CONTENT -->
    <main class="admin-content">

      <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
          <?php echo htmlspecialchars($_SESSION['message']);
          unset($_SESSION['message']); ?>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
          <?php echo htmlspecialchars($_SESSION['error']);
          unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <!-- FORM THÊM / SỬA HÃng -->
      <div class="form-section">
        <h2><?php echo $data['editing'] ? 'Sửa hãng' : 'Thêm hãng mới'; ?></h2>

        <form method="POST" action="/DuAn1/admin/saveBranch">
          <?php if ($data['editing'] && isset($data['branch'])): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['branch']['id']); ?>">
          <?php endif; ?>

          <div class="form-group">
            <label for="name">Tên hãng *</label>
            <input type="text" id="name" name="name"
              value="<?php echo htmlspecialchars($data['editing'] && isset($data['branch']) ? $data['branch']['name'] : ''); ?>"
              required placeholder="Nhập tên hãng">
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-success">
              <?php echo $data['editing'] ? 'Cập nhật' : 'Lưu'; ?>
            </button>
            <a href="/DuAn1/admin/branch" class="btn btn-cancel">Hủy</a>
          </div>
        </form>
      </div>

      <!-- DANH SÁCH HÃng -->
      <section>
        <div class="content-header">
          <h2>Danh sách hãng</h2>
          <a href="/DuAn1/admin/branch" class="btn-add">+ Thêm hãng</a>
        </div>

        <!-- THỐNG KÊ -->
        <div class="stats-box">
          <p><strong>Tổng hãng:</strong> <?php echo $data['totalBranches']; ?></p>
        </div>

        <!-- BẢNG DANH SÁCH -->
        <div class="table-container">
          <?php if ($data['totalBranches'] > 0): ?>
            <table>
              <thead>
                <tr>
                  <th width="20%">ID</th>
                  <th width="60%">Tên</th>
                  <th width="20%">Hành động</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($data['branches'] as $branch): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($branch['id']); ?></td>
                    <td><?php echo htmlspecialchars($branch['name']); ?></td>
                    <td>
                      <a href="/DuAn1/admin/editBranch/<?php echo urlencode($branch['id']); ?>"
                        class="btn-small btn-edit">Sửa</a>
                      <a href="/DuAn1/admin/deleteBranch/<?php echo urlencode($branch['id']); ?>"
                        class="btn-small btn-delete" onclick="return confirm('Bạn chắc chắn muốn xóa hãng này?')">Xóa</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <div class="empty-message">
              Chưa có hãng nào. <a href="/DuAn1/admin/branch">Thêm hãng mới</a>
            </div>
          <?php endif; ?>
        </div>
      </section>

    </main>

  </div>

</body>

</html>