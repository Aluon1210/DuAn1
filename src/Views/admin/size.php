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
        max-width: 1200px;
        width: 100%;
        margin: 32px auto;
        padding: 0 20px;
    }

    h2 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 20px;
    }

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

    .form-section {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 28px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
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

    .form-group input,
    .form-group textarea {
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.2s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: var(--gold);
        outline: none;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
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

    .table-container {
        background: #fff;
        border-radius: 12px;
        overflow-x: auto;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    thead {
        background-color: #f4f4f4;
        border-bottom: 2px solid #ddd;
    }

    tbody tr:hover {
        background-color: #fafafa;
    }

    .empty-message {
        padding: 20px;
        text-align: center;
        color: var(--muted);
    }
</style>

<body>
    <div class="admin-container">
        <?php include __DIR__ . '/aside.php'; ?>

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

            <div class="form-section">
                <h2><?php echo !empty($data['editing']) ? 'Sửa kích cỡ' : 'Thêm kích cỡ mới'; ?></h2>
                <form method="POST" action="<?php echo ROOT_URL; ?>admin/saveSize">
                    <?php if (!empty($data['editing']) && isset($data['size']['id'])): ?>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['size']['id']); ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="size-value">Giá trị size *</label>
                        <input id="size-value" type="text" name="value" required placeholder="Ví dụ: XS, 38, EU 40"
                               value="<?php echo htmlspecialchars($data['editing'] && isset($data['size']) ? $data['size']['value'] : ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="size-description">Mô tả</label>
                        <textarea id="size-description" name="description" rows="3"
                                  placeholder="Miêu tả chi tiết kích cỡ"><?php echo htmlspecialchars($data['editing'] && isset($data['size']) ? $data['size']['description'] : ''); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">
                            <?php echo !empty($data['editing']) ? 'Cập nhật' : 'Lưu kích cỡ'; ?>
                        </button>
                        <a href="<?php echo ROOT_URL; ?>admin/sizes" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>

            <section>
                <div style="margin-bottom: 20px; display:flex; justify-content: space-between; align-items:center;">
                    <h2>Danh sách kích cỡ</h2>
                    <div><strong>Tổng:</strong> <?php echo (int)($data['totalSizes'] ?? 0); ?></div>
                </div>

                <div class="table-container">
                    <?php if (!empty($data['sizes'])): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th width="15%">Mã</th>
                                    <th width="20%">Giá trị</th>
                                    <th width="55%">Mô tả</th>
                                    <th width="10%">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['sizes'] as $size): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($size['id']); ?></td>
                                        <td><?php echo htmlspecialchars($size['value']); ?></td>
                                        <td><?php echo htmlspecialchars($size['description'] ?? ''); ?></td>
                                        <td>
                                            <a class="btn btn-success" style="padding:6px 12px;"
                                               href="<?php echo ROOT_URL; ?>admin/editSize/<?php echo urlencode($size['id']); ?>">Sửa</a>
                                            <a class="btn btn-cancel" style="padding:6px 12px;"
                                               href="<?php echo ROOT_URL; ?>admin/deleteSize/<?php echo urlencode($size['id']); ?>"
                                               onclick="return confirm('Bạn có chắc muốn xóa kích cỡ này?');">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-message">
                            Chưa có kích cỡ nào. Hãy thêm kích cỡ để hoàn thiện bảng thông số.
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</body>

</html>

