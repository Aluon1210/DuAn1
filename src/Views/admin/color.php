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

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
    }

    .form-group {
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
        margin-top: 20px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
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

    .btn-add {
        background: var(--primary);
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
    }

    .stats-box {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 18px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .color-preview {
        width: 100%;
        height: 42px;
        border: 1px solid var(--border);
        border-radius: 6px;
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
        min-width: 900px;
    }

    table thead {
        background-color: #f4f4f4;
        border-bottom: 2px solid #ddd;
    }

    table th,
    table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    table tbody tr:hover {
        background-color: #fafafa;
    }

    .tag {
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid var(--border);
    }

    .tag-swatch {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .empty-message {
        padding: 20px;
        text-align: center;
        font-size: 15px;
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
                <h2><?php echo !empty($data['editing']) ? 'Sửa màu sắc' : 'Thêm màu sắc mới'; ?></h2>
                <form method="POST" action="<?php echo ROOT_URL; ?>admin/saveColor">
                    <?php if (!empty($data['editing']) && isset($data['color']['id'])): ?>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['color']['id']); ?>">
                    <?php endif; ?>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="color-name">Tên màu *</label>
                            <input id="color-name" type="text" name="name" required
                                   value="<?php echo htmlspecialchars($data['editing'] && isset($data['color']) ? $data['color']['name'] : ''); ?>"
                                   placeholder="Ví dụ: Midnight Black">
                        </div>

                        <div class="form-group">
                            <label for="color-code">Mã màu (HEX)</label>
                            <input id="color-code" type="text" name="hex_code"
                                   value="<?php echo htmlspecialchars($data['editing'] && isset($data['color']['hex_code']) ? $data['color']['hex_code'] : '#000000'); ?>"
                                   placeholder="#000000">
                        </div>

                        <div class="form-group">
                            <label>Xem trước</label>
                            <div class="color-preview"
                                 style="background: <?php echo htmlspecialchars($data['editing'] && isset($data['color']['hex_code']) ? $data['color']['hex_code'] : '#000000'); ?>"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="color-description">Mô tả</label>
                        <textarea id="color-description" name="description" rows="3"
                                  placeholder="Mô tả cảm hứng hoặc chất liệu của màu sắc"><?php echo htmlspecialchars($data['editing'] && isset($data['color']) ? $data['color']['description'] : ''); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">
                            <?php echo !empty($data['editing']) ? 'Cập nhật' : 'Lưu màu mới'; ?>
                        </button>
                        <a href="<?php echo ROOT_URL; ?>admin/colors" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>

            <section>
                <div class="stats-box">
                    <h2>Danh sách màu sắc</h2>
                    <div><strong>Tổng số màu:</strong> <?php echo (int)($data['totalColors'] ?? 0); ?></div>
                </div>

                <div class="table-container">
                    <?php if (!empty($data['colors'])): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th width="15%">Mã màu</th>
                                    <th width="20%">Tên</th>
                                    <th width="20%">HEX</th>
                                    <th width="35%">Mô tả</th>
                                    <th width="10%">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['colors'] as $color): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($color['id']); ?></td>
                                        <td><?php echo htmlspecialchars($color['name']); ?></td>
                                        <td>
                                            <span class="tag">
                                                <span class="tag-swatch" style="background: <?php echo htmlspecialchars($color['hex_code']); ?>"></span>
                                                <?php echo htmlspecialchars($color['hex_code']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($color['description'] ?? ''); ?></td>
                                        <td>
                                            <a class="btn btn-success" style="padding:6px 12px;"
                                               href="<?php echo ROOT_URL; ?>admin/editColor/<?php echo urlencode($color['id']); ?>">Sửa</a>
                                            <a class="btn btn-cancel" style="padding:6px 12px;"
                                               href="<?php echo ROOT_URL; ?>admin/deleteColor/<?php echo urlencode($color['id']); ?>"
                                               onclick="return confirm('Xóa màu này?');">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-message">
                            Chưa có màu nào. Hãy thêm màu mới để làm phong phú bộ sưu tập.
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>

    <script>
        (function () {
            const codeInput = document.getElementById('color-code');
            const preview = document.querySelector('.color-preview');

            if (!codeInput || !preview) return;

            const updatePreview = () => {
                const value = codeInput.value.trim();
                if (/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/.test(value)) {
                    preview.style.background = value;
                }
            };

            codeInput.addEventListener('input', updatePreview);
            updatePreview();
        })();
    </script>
</body>

</html>

