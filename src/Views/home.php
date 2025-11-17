<?php
// File: src/Views/home.php
?>
<div style="background-color: white; padding: 40px; border-radius: 8px; text-align: center;">
    <h1 style="color: #2c3e50; margin-bottom: 20px;">Chào mừng đến với Shop Thời Trang</h1>
    <p style="font-size: 18px; color: #7f8c8d; margin-bottom: 30px;">
        Khám phá bộ sưu tập thời trang đa dạng với giá cả hợp lý
    </p>
    
    <div style="display: flex; gap: 20px; justify-content: center; margin-top: 40px;">
        <a href="<?php echo ROOT_URL; ?>product" class="btn btn-primary" style="padding: 15px 30px; font-size: 16px;">
            🛍️ Xem Sản Phẩm
        </a>
        <a href="<?php echo ROOT_URL; ?>home/about" class="btn" style="padding: 15px 30px; font-size: 16px; background-color: #95a5a6; color: white;">
            ℹ️ Về Chúng Tôi
        </a>
    </div>
</div>

