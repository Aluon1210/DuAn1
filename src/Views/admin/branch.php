<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($data['title'] ?? 'Quản lý hãng'); ?></title>
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
            background: white;
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
        
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
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
        <?php include __DIR__ .'/aside.php'; ?>

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

            <!-- FORM THÊM / SỬA HÃng -->
            <div class="form-section">
                <h2><?php echo $data['editing'] ? 'Sửa hãng' : 'Thêm hãng mới'; ?></h2>
                
                <form method="POST" action="/DuAn1/admin/saveBranch">
                    <?php if ($data['editing'] && isset($data['branch'])): ?>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['branch']['id']); ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="name">Tên hãng *</label>
                        <input 
                            type="text" 
                            id="name"
                            name="name" 
                            value="<?php echo htmlspecialchars($data['editing'] && isset($data['branch']) ? $data['branch']['name'] : ''); ?>"
                            required
                            placeholder="Nhập tên hãng"
                        >
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
                                            <a href="/DuAn1/admin/editBranch/<?php echo urlencode($branch['id']); ?>" class="btn-small btn-edit">Sửa</a>
                                            <a href="/DuAn1/admin/deleteBranch/<?php echo urlencode($branch['id']); ?>" class="btn-small btn-delete" onclick="return confirm('Bạn chắc chắn muốn xóa hãng này?')">Xóa</a>
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
