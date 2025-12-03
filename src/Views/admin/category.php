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
        /* rộng hơn mặc định */
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
        min-width: 1000px;
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
        min-width: 180px;
        /* đảm bảo cột hành động rộng hơn */
    }

    .btn-small {
        padding: 8px 16px;
        /* tăng kích thước nút */
        margin: 4px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        min-width: 80px;
        /* nút đều nhau */
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

            <!-- FORM THÊM / SỬA DANH MỤC -->
            <div class="form-section">
                <h2><?php echo $data['editing'] ? 'Sửa danh mục' : 'Thêm danh mục mới'; ?></h2>

                <form method="POST" action="/DuAn1/admin/saveCategory">
                    <?php if ($data['editing'] && isset($data['category'])): ?>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['category']['id']); ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="name">Tên danh mục *</label>
                        <input type="text" id="name" name="name"
                            value="<?php echo htmlspecialchars($data['editing'] && isset($data['category']) ? $data['category']['name'] : ''); ?>"
                            required placeholder="Nhập tên danh mục">
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea id="description" name="description"
                            placeholder="Nhập mô tả danh mục"><?php echo htmlspecialchars($data['editing'] && isset($data['category']) ? $data['category']['description'] : ''); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">
                            <?php echo $data['editing'] ? 'Cập nhật' : 'Lưu'; ?>
                        </button>
                        <a href="/DuAn1/admin/categories" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>

            <!-- DANH SÁCH DANH MỤC -->
            <section>
                <div class="content-header">
                    <h2>Danh sách danh mục</h2>
                    <a href="/DuAn1/admin/categories" class="btn-add">+ Thêm danh mục</a>
                </div>

                <!-- THỐNG KÊ -->
                <div class="stats-box">
                    <p><strong>Tổng danh mục:</strong> <?php echo $data['totalCategories']; ?></p>
                </div>

                <!-- BẢNG DANH SÁCH -->
                <div class="table-container">
                    <?php if ($data['totalCategories'] > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th width="10%">ID</th>
                                    <th width="35%">Tên</th>
                                    <th width="45%">Mô tả</th>
                                    <th width="10%">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['categories'] as $category): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($category['id']); ?></td>
                                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($category['description'] ?? '', 0, 60)) . (strlen($category['description'] ?? '') > 60 ? '...' : ''); ?>
                                        </td>
                                        <td>
                                            <a href="/DuAn1/admin/editCategory/<?php echo urlencode($category['id']); ?>"
                                                class="btn-small btn-edit">Sửa</a>
                                            <a href="/DuAn1/admin/deleteCategory/<?php echo urlencode($category['id']); ?>"
                                                class="btn-small btn-delete"
                                                onclick="return confirm('Bạn chắc chắn muốn xóa danh mục này?')">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-message">
                            Chưa có danh mục nào. <a href="/DuAn1/admin/categories">Thêm danh mục mới</a>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

        </main>

    </div>

</body>

</html>