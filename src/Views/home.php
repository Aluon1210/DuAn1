<?php
// File: src/Views/home.php
?>
<div style="background: linear-gradient(135deg, #1a1a1a 0%, #2c2c2c 100%); color: white; padding: 80px 40px; border-radius: 16px; text-align: center; margin-bottom: 40px; position: relative; overflow: hidden; box-shadow: var(--shadow-hover);">
    <div style="position: absolute; top: -50%; right: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(212,175,55,0.1) 0%, transparent 70%); border-radius: 50%;"></div>
    <div style="position: relative; z-index: 1;">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 56px; font-weight: 700; margin-bottom: 24px; letter-spacing: 3px; text-shadow: 0 4px 20px rgba(0,0,0,0.3);">
            Luxury Fashion Store
        </h1>
        <div style="width: 100px; height: 4px; background: linear-gradient(90deg, var(--primary-gold) 0%, #b8941f 100%); margin: 0 auto 30px; border-radius: 2px;"></div>
        <p style="font-size: 20px; color: rgba(255,255,255,0.9); margin-bottom: 40px; line-height: 1.8; max-width: 600px; margin-left: auto; margin-right: auto;">
            Khám phá bộ sưu tập thời trang cao cấp với thiết kế tinh tế và chất liệu sang trọng
        </p>
        
        <div style="display: flex; gap: 20px; justify-content: center; margin-top: 50px; flex-wrap: wrap;">
            <a href="<?php echo ROOT_URL; ?>product" class="btn btn-success" style="padding: 18px 40px; font-size: 16px; text-transform: uppercase; letter-spacing: 1.5px;">
                🛍️ Khám Phá Bộ Sưu Tập
            </a>
            <a href="<?php echo ROOT_URL; ?>home/about" class="btn btn-primary" style="padding: 18px 40px; font-size: 16px; text-transform: uppercase; letter-spacing: 1.5px;">
                ℹ️ Về Chúng Tôi
            </a>
        </div>
    </div>
</div>

<!-- Features Section -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-bottom: 60px;">
    <div style="background: white; padding: 40px 30px; border-radius: 12px; text-align: center; box-shadow: var(--shadow-soft); transition: var(--transition-smooth); border-top: 4px solid var(--primary-gold);">
        <div style="font-size: 48px; margin-bottom: 20px;">✨</div>
        <h3 style="font-family: 'Playfair Display', serif; font-size: 22px; margin-bottom: 12px; color: var(--primary-black);">Chất Lượng Cao Cấp</h3>
        <p style="color: var(--text-light); line-height: 1.6;">Sản phẩm được chọn lọc kỹ lưỡng với chất liệu cao cấp nhất</p>
    </div>
    
    <div style="background: white; padding: 40px 30px; border-radius: 12px; text-align: center; box-shadow: var(--shadow-soft); transition: var(--transition-smooth); border-top: 4px solid var(--primary-gold);">
        <div style="font-size: 48px; margin-bottom: 20px;">🎨</div>
        <h3 style="font-family: 'Playfair Display', serif; font-size: 22px; margin-bottom: 12px; color: var(--primary-black);">Thiết Kế Tinh Tế</h3>
        <p style="color: var(--text-light); line-height: 1.6;">Phong cách hiện đại, sang trọng phù hợp mọi dịp</p>
    </div>
    
    <div style="background: white; padding: 40px 30px; border-radius: 12px; text-align: center; box-shadow: var(--shadow-soft); transition: var(--transition-smooth); border-top: 4px solid var(--primary-gold);">
        <div style="font-size: 48px; margin-bottom: 20px;">🚚</div>
        <h3 style="font-family: 'Playfair Display', serif; font-size: 22px; margin-bottom: 12px; color: var(--primary-black);">Giao Hàng Nhanh</h3>
        <p style="color: var(--text-light); line-height: 1.6;">Dịch vụ giao hàng tận nơi nhanh chóng và an toàn</p>
    </div>
    
    <div style="background: white; padding: 40px 30px; border-radius: 12px; text-align: center; box-shadow: var(--shadow-soft); transition: var(--transition-smooth); border-top: 4px solid var(--primary-gold);">
        <div style="font-size: 48px; margin-bottom: 20px;">💎</div>
        <h3 style="font-family: 'Playfair Display', serif; font-size: 22px; margin-bottom: 12px; color: var(--primary-black);">Độc Quyền</h3>
        <p style="color: var(--text-light); line-height: 1.6;">Bộ sưu tập độc quyền không thể tìm thấy ở đâu khác</p>
    </div>
</div>
