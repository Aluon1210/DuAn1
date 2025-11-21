<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý danh mục</title>
    <link rel="stylesheet" href="/DuAn1/asset/css/admin.css">
</head>

<body>


    <header class="admin-header">
        <div class="header-content">
            <h1>Luxury Admin Panel</h1>
            <div class="admin-actions">
                <a href="/DuAn1/" class="btn-back">Trang chủ</a>
                <a href="/DuAn1/auth/logout.php" class="btn-logout">Đăng xuất</a>
            </div>
        </div>
    </header>

    <div class="admin-container">

        <!-- SIDEBAR -->
        <aside class="admin-sidebar">
            <nav class="admin-menu">
                <ul>
                    <li><a href="dashboard.php" class="menu-item">Tổng quan</a></li>
                    <li><a href="user.php" class="menu-item">Người dùng</a></li>
                    <li><a href="product.php" class="menu-item">Sản phẩm</a></li>
                    <li><a href="order.php" class="menu-item">Đơn hàng</a></li>
                    <li><a href="category.php" class="menu-item active">Danh mục</a></li>
                    <li><a href="comment.php" class="menu-item">Bình luận</a></li>
                    <li><a href="stat.php" class="menu-item">Thống kê</a></li>
                </ul>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="admin-content">
            <section class="admin-section active">

                <h2>Quản lý danh mục</h2>

                <!-- BOX THỐNG KÊ -->
                <div class="stat-card" style="text-align:left; padding:20px 25px; margin-bottom:25px;">
                    <h3 style="margin:0; font-size:18px; color:#ffd700;">Tổng danh mục</h3>
                    <div class="stat-number" style="font-size:30px; margin-top:10px;">12</div>
                </div>

                <!-- FORM THÊM DANH MỤC -->
                <form method="POST" action="/admin/category/save">

                    <div class="form-group">
                        <label>Tên danh mục</label>
                        <input type="text" name="name" required>
                    </div>

                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="description" rows="3"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">Lưu</button>
                        <button type="reset" class="btn btn-cancel">Hủy</button>
                    </div>

                </form>

            </section>
        </main>

    </div>

</body>

</html>