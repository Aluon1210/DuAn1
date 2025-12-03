<?php
$uri = $_SERVER['REQUEST_URI']; // Lấy đường dẫn hiện tại
$menuItems = [
    '/DuAn1/admin/dashboard' => 'Tổng quan',
    '/DuAn1/admin/users' => 'Người dùng',
    '/DuAn1/admin/products' => 'Sản phẩm',
    '/DuAn1/admin/productVariants' => 'Biến thể',
    '/DuAn1/admin/colors' => 'Màu sắc',
    '/DuAn1/admin/sizes' => 'Kích cỡ',
    '/DuAn1/admin/orders' => 'Đơn hàng',
    '/DuAn1/admin/categories' => 'Danh mục',
    '/DuAn1/admin/branch' => 'Hãng',
    '/DuAn1/admin/comments' => 'Bình luận',
    '/DuAn1/admin/stats' => 'Thống kê',
];
?>

<header class="header">
    <div class="brand">
        <i class="fas fa-gem"></i> Luxury Admin
    </div>
    <nav>
        <a href="#">Trang chủ</a>
        <a href="#">Đăng xuất</a>
    </nav>
</header>

<!--  icon Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


<aside class="sidebar">
    <?php foreach ($menuItems as $link => $label): ?>
        <a href="<?= $link ?>" class="menu-item <?= ($uri === $link) ? 'active' : '' ?>">
            <?= $label ?>
        </a>
    <?php endforeach; ?>
</aside>