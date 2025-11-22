<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($data['title'] ?? 'Quản lý danh mục'); ?></title>
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
            background: yellow;
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
            background: black;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
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
        }
        
        table tbody tr:hover {
            background-color: #f5f5f5;
        }
        
        .btn-small {
            padding: 6px 12px;
            margin-right: 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
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
            background: #f4e4bc;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            max-width: 600px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
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
        <aside class="admin-sidebar">
            <nav class="admin-menu">
                <ul>
                    <li><a href="/DuAn1/admin/dashboard" class="menu-item">Tổng quan</a></li>
                    <li><a href="/DuAn1/admin/users" class="menu-item">Người dùng</a></li>
                    <li><a href="/DuAn1/admin/products" class="menu-item">Sản phẩm</a></li>
                    <li><a href="/DuAn1/admin/orders" class="menu-item">Đơn hàng</a></li>
                    <li><a href="/DuAn1/admin/categories" class="menu-item active">Danh mục</a></li>
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

            <!-- FORM THÊM / SỬA DANH MỤC -->
            <div class="form-section">
                <h2><?php echo $data['editing'] ? 'Sửa danh mục' : 'Thêm danh mục mới'; ?></h2>
                
                <form method="POST" action="/DuAn1/admin/saveCategory">
                    <?php if ($data['editing'] && isset($data['category'])): ?>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['category']['id']); ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="name">Tên danh mục *</label>
                        <input 
                            type="text" 
                            id="name"
                            name="name" 
                            value="<?php echo htmlspecialchars($data['editing'] && isset($data['category']) ? $data['category']['name'] : ''); ?>"
                            required
                            placeholder="Nhập tên danh mục"
                        >
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea 
                            id="description"
                            name="description" 
                            placeholder="Nhập mô tả danh mục"
                        ><?php echo htmlspecialchars($data['editing'] && isset($data['category']) ? $data['category']['description'] : ''); ?></textarea>
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
                                        <td><?php echo htmlspecialchars(substr($category['description'] ?? '', 0, 60)) . (strlen($category['description'] ?? '') > 60 ? '...' : ''); ?></td>
                                        <td>
                                            <a href="/DuAn1/admin/editCategory/<?php echo urlencode($category['id']); ?>" class="btn-small btn-edit">Sửa</a>
                                            <a href="/DuAn1/admin/deleteCategory/<?php echo urlencode($category['id']); ?>" class="btn-small btn-delete" onclick="return confirm('Bạn chắc chắn muốn xóa danh mục này?')">Xóa</a>
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