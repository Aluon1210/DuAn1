<?php
// File: src/Views/about.php
?>
<style>
    .about-hero {
        background: linear-gradient(135deg, var(--primary-black) 0%, #2c2c2c 100%);
        color: white;
        padding: 80px 60px;
        border-radius: 16px;
        text-align: center;
        margin-bottom: 60px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-hover);
    }

    .about-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(212,175,55,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .about-hero-content {
        position: relative;
        z-index: 1;
    }

    .about-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 56px;
        font-weight: 700;
        margin-bottom: 24px;
        letter-spacing: 3px;
        text-shadow: 0 4px 20px rgba(0,0,0,0.3);
        color: white;
    }

    .about-hero-divider {
        width: 120px;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-gold) 0%, #b8941f 100%);
        margin: 0 auto 30px;
        border-radius: 2px;
    }

    .about-hero p {
        font-size: 20px;
        color: rgba(255,255,255,0.9);
        line-height: 1.8;
        max-width: 700px;
        margin: 0 auto;
    }

    .about-section {
        background: white;
        padding: 50px 60px;
        border-radius: 16px;
        margin-bottom: 40px;
        box-shadow: var(--shadow-soft);
    }

    .about-section h2 {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 600;
        margin-bottom: 24px;
        color: var(--primary-black);
        padding-bottom: 20px;
        border-bottom: 3px solid var(--primary-gold-light);
    }

    .about-section p {
        font-size: 16px;
        line-height: 1.8;
        color: var(--text-dark);
        margin-bottom: 20px;
    }

    .about-section ul {
        margin-left: 30px;
        margin-bottom: 30px;
    }

    .about-section li {
        font-size: 16px;
        line-height: 1.8;
        color: var(--text-dark);
        margin-bottom: 12px;
        padding-left: 10px;
    }

    .about-section li::marker {
        color: var(--primary-gold);
        font-size: 20px;
    }

    .about-features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }

    .about-feature {
        padding: 30px;
        background: linear-gradient(135deg, var(--accent-gray) 0%, #f0f0f0 100%);
        border-radius: 12px;
        border-top: 4px solid var(--primary-gold);
        transition: var(--transition-smooth);
    }

    .about-feature:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-soft);
    }

    .about-feature-icon {
        font-size: 48px;
        margin-bottom: 20px;
    }

    .about-feature h3 {
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        margin-bottom: 12px;
        color: var(--primary-black);
    }

    .about-feature p {
        color: var(--text-light);
        line-height: 1.6;
        margin-bottom: 0;
    }
</style>

<div class="about-hero">
    <div class="about-hero-content">
        <h1>Về Luxury Fashion Store</h1>
        <div class="about-hero-divider"></div>
        <p>
            Chúng tôi tự hào là thương hiệu thời trang cao cấp, mang đến những sản phẩm chất lượng với thiết kế tinh tế và phong cách sang trọng.
        </p>
    </div>
</div>

<div class="about-section">
    <h2>Câu Chuyện Của Chúng Tôi</h2>
    <p>
        Luxury Fashion Store được thành lập với sứ mệnh mang đến những trải nghiệm mua sắm thời trang cao cấp nhất. 
        Chúng tôi tin rằng thời trang không chỉ là quần áo, mà còn là cách thể hiện cá tính và phong cách sống của bạn.
    </p>
    <p>
        Với sự kết hợp giữa truyền thống và hiện đại, chúng tôi luôn tìm kiếm những xu hướng mới nhất trong ngành thời trang, 
        đồng thời duy trì tiêu chuẩn chất lượng cao nhất cho mọi sản phẩm.
    </p>
</div>

<div class="about-section">
    <h2>Sứ Mệnh Của Chúng Tôi</h2>
    <p>
        Mang đến cho mọi người cơ hội sở hữu những bộ trang phục đẹp, chất lượng cao và phù hợp với phong cách cá nhân của họ.
    </p>
    <p>
        Chúng tôi cam kết:
    </p>
    <ul>
        <li>Chất lượng sản phẩm tuyệt đối</li>
        <li>Dịch vụ khách hàng chuyên nghiệp</li>
        <li>Giá cả hợp lý và minh bạch</li>
        <li>Giao hàng nhanh chóng và an toàn</li>
        <li>Bảo hành và đổi trả linh hoạt</li>
    </ul>
</div>

<div class="about-section">
    <h2>Bộ Sưu Tập Của Chúng Tôi</h2>
    <p>
        Chúng tôi cung cấp đa dạng các loại sản phẩm thời trang cao cấp:
    </p>
    <div class="about-features">
        <div class="about-feature">
            <div class="about-feature-icon">👔</div>
            <h3>Áo Sơ Mi & Áo Thun</h3>
            <p>Thiết kế hiện đại với chất liệu cao cấp, thoải mái và sang trọng</p>
        </div>
        
        <div class="about-feature">
            <div class="about-feature-icon">👖</div>
            <h3>Quần Các Loại</h3>
            <p>Form dáng chuẩn, chất liệu bền đẹp, phù hợp mọi dịp</p>
        </div>
        
        <div class="about-feature">
            <div class="about-feature-icon">👗</div>
            <h3>Váy & Đầm</h3>
            <p>Thiết kế tinh tế, phong cách thanh lịch, quyến rũ</p>
        </div>
        
        <div class="about-feature">
            <div class="about-feature-icon">👠</div>
            <h3>Giày Dép</h3>
            <p>Thoải mái, bền đẹp, phù hợp mọi hoàn cảnh</p>
        </div>
        
        <div class="about-feature">
            <div class="about-feature-icon">💎</div>
            <h3>Phụ Kiện</h3>
            <p>Hoàn thiện phong cách của bạn với các phụ kiện cao cấp</p>
        </div>
        
        <div class="about-feature">
            <div class="about-feature-icon">🎁</div>
            <h3>Bộ Sưu Tập Độc Quyền</h3>
            <p>Những sản phẩm độc quyền không thể tìm thấy ở đâu khác</p>
        </div>
    </div>
</div>

<div class="about-section">
    <h2>Cam Kết Của Chúng Tôi</h2>
    <p>
        Tại Luxury Fashion Store, chúng tôi không chỉ bán quần áo - chúng tôi bán sự tự tin, phong cách và trải nghiệm. 
        Mỗi sản phẩm đều được chọn lọc kỹ lưỡng để đảm bảo chất lượng và phù hợp với tiêu chuẩn cao nhất.
    </p>
    <p style="margin-top: 30px; padding: 24px; background: linear-gradient(135deg, var(--primary-gold-light) 0%, #f4e4bc 100%); border-radius: 12px; border-left: 4px solid var(--primary-gold); font-style: italic;">
        "Chất lượng không phải là hành động, đó là thói quen của chúng tôi."
    </p>
</div>

<div style="margin-top: 40px; text-align: center;">
    <a href="<?php echo ROOT_URL; ?>product" class="btn btn-success" style="padding: 18px 50px; font-size: 18px; text-transform: uppercase; letter-spacing: 1.5px;">
        🛍️ Khám Phá Bộ Sưu Tập
    </a>
</div>
