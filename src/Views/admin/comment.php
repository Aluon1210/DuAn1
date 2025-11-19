<?php
// comment.php
// Giả sử có kết nối DB trước đó
// include 'pdo.php'; hoặc file kết nối DB
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý bình luận</title>
    <link rel="stylesheet" href="/DUAN1/asset/css/admin.css">
</head>
<body>

    <!-- HEADER -->
    <header class="admin-header">
        <div class="header-content">
            <h1>Luxury Admin Panel</h1>
            <div class="admin-actions">
                <a href="/DuAn1/" class="btn-back">Trang chủ</a>
                <a href="/DuAn1/auth/logout.php" class="btn-logout">Đăng xuất</a>
            </div>
        </div>
    </header>

    <!-- CONTAINER -->
    <div class="admin-container">

        <!-- SIDEBAR -->
        <aside class="admin-sidebar">
            <nav class="admin-menu">
                <ul>
                    <li><a href="dashboard.php" class="menu-item">Tổng quan</a></li>
                    <li><a href="user.php" class="menu-item">Người dùng</a></li>
                    <li><a href="product.php" class="menu-item">Sản phẩm</a></li>
                    <li><a href="order.php" class="menu-item">Đơn hàng</a></li>
                    <li><a href="category.php" class="menu-item">Danh mục</a></li>
                    <li><a href="comment.php" class="menu-item active">Bình luận</a></li>
                    <li><a href="stat.php" class="menu-item">Thống kê</a></li>
                </ul>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="admin-content">
            <section class="admin-section active">
                <h2>Quản lý bình luận</h2>

                <!-- Bảng bình luận -->
                <table class="comment-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người dùng</th>
                            <th>Sản phẩm</th>
                            <th>Nội dung</th>
                            <th>Ngày</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Ví dụ: lấy dữ liệu bình luận từ DB
                        // $comments = pdo_query("SELECT * FROM comments ORDER BY created_at DESC");
                        // foreach($comments as $c):
                        ?>
                        <tr>
                            <td>1</td>
                            <td>Nguyen Van A</td>
                            <td>Áo thun trắng</td>
                            <td>Bình luận rất hay!</td>
                            <td>2025-11-19</td>
                            <td><span class="status-badge approved">Đã duyệt</span></td>
                            <td class="action-buttons">
                                <a href="#" class="btn-edit">Sửa</a>
                                <a href="#" class="btn-delete">Xóa</a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Tran Thi B</td>
                            <td>Quần jeans</td>
                            <td>Chất lượng tốt</td>
                            <td>2025-11-18</td>
                            <td><span class="status-badge pending">Chờ duyệt</span></td>
                            <td class="action-buttons">
                                <a href="#" class="btn-edit">Sửa</a>
                                <a href="#" class="btn-delete">Xóa</a>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Le Van C</td>
                            <td>Áo sơ mi xanh</td>
                            <td>Không vừa size</td>
                            <td>2025-11-17</td>
                            <td><span class="status-badge rejected">Từ chối</span></td>
                            <td class="action-buttons">
                                <a href="#" class="btn-edit">Sửa</a>
                                <a href="#" class="btn-delete">Xóa</a>
                            </td>
                        </tr>
                        <?php
                        // endforeach;
                        ?>
                    </tbody>
                </table>

            </section>
        </main>

    </div>

</body>
</html>
