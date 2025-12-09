<?php
// File: src/Views/includes/footer.php
// Footer chung cho tất cả các trang
?>
    </div>

    <!-- Footer Navigation -->
    <footer style="background: linear-gradient(135deg, var(--primary-black) 0%, #2c2c2c 100%); color: white; margin-top: 80px; border-top: 3px solid var(--primary-gold);">
        <div style="max-width: 1400px; margin: 0 auto; padding: 60px 40px 40px; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px;">
            <!-- About Section -->
            <div>
                <h3 style="font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 600; margin-bottom: 20px; color: var(--primary-gold); letter-spacing: 1px;">
                    Luxury Fashion
                </h3>
                <p style="color: rgba(255,255,255,0.7); line-height: 1.8; margin-bottom: 20px; font-size: 14px;">
                    Khám phá bộ sưu tập thời trang cao cấp với thiết kế tinh tế và chất liệu sang trọng. Chúng tôi cam kết mang đến những sản phẩm chất lượng nhất.
                </p>
                <div class="social-links" style="display: flex; gap: 15px; margin-top: 25px;">
                    <a href="#" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(212,175,55,0.1); border: 2px solid var(--primary-gold); display: flex; align-items: center; justify-content: center; color: var(--primary-gold); text-decoration: none; transition: var(--transition-smooth); font-size: 18px;" title="Facebook">
                        <span>📘</span>
                    </a>
                    <a href="#" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(212,175,55,0.1); border: 2px solid var(--primary-gold); display: flex; align-items: center; justify-content: center; color: var(--primary-gold); text-decoration: none; transition: var(--transition-smooth); font-size: 18px;" title="Instagram">
                        <span>📷</span>
                    </a>
                    <a href="#" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(212,175,55,0.1); border: 2px solid var(--primary-gold); display: flex; align-items: center; justify-content: center; color: var(--primary-gold); text-decoration: none; transition: var(--transition-smooth); font-size: 18px;" title="Twitter">
                        <span>🐦</span>
                    </a>
                    <a href="#" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(212,175,55,0.1); border: 2px solid var(--primary-gold); display: flex; align-items: center; justify-content: center; color: var(--primary-gold); text-decoration: none; transition: var(--transition-smooth); font-size: 18px;" title="YouTube">
                        <span>📺</span>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 style="font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 600; margin-bottom: 20px; color: var(--primary-gold); letter-spacing: 1px;">
                    Menu Nhanh
                </h4>
                <nav style="display: flex; flex-direction: column; gap: 12px;">
                    <a href="<?php echo ROOT_URL; ?>" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 14px; transition: var(--transition-smooth); padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 8px;">
                        <span>🏠</span>
                        <span>Trang chủ</span>
                    </a>
                    <a href="<?php echo ROOT_URL; ?>product" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 14px; transition: var(--transition-smooth); padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 8px;">
                        <span>🛍️</span>
                        <span>Sản phẩm</span>
                    </a>
                    <a href="<?php echo ROOT_URL; ?>product/category/1" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 14px; transition: var(--transition-smooth); padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 8px;">
                        <span>👔</span>
                        <span>Áo thun</span>
                    </a>
                    <a href="<?php echo ROOT_URL; ?>product/category/2" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 14px; transition: var(--transition-smooth); padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 8px;">
                        <span>👕</span>
                        <span>Áo sơ mi</span>
                    </a>
                    <a href="<?php echo ROOT_URL; ?>product/category/3" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 14px; transition: var(--transition-smooth); padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 8px;">
                        <span>👖</span>
                        <span>Quần</span>
                    </a>
                    <a href="<?php echo ROOT_URL; ?>home/about" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 14px; transition: var(--transition-smooth); padding: 8px 0; display: flex; align-items: center; gap: 8px;">
                        <span>ℹ️</span>
                        <span>Giới thiệu</span>
                    </a>
                </nav>
            </div>

            <!-- Customer Service -->
            <div>
                <h4 style="font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 600; margin-bottom: 20px; color: var(--primary-gold); letter-spacing: 1px;">
                    Hỗ Trợ Khách Hàng
                </h4>
                <nav style="display: flex; flex-direction: column; gap: 12px;">
                    <a href="#" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 14px; transition: var(--transition-smooth); padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 8px;">
                        <span>📞</span>
                        <span>Liên hệ</span>
                    </a>
                    <a href="#" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 14px; transition: var(--transition-smooth); padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 8px;">
                        <span>❓</span>
                        <span>FAQ</span>
                    </a>
                    <a href="#" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 14px; transition: var(--transition-smooth); padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 8px;">
                        <span>🚚</span>
                        <span>Vận chuyển</span>
                    </a>
                    <a href="#" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 14px; transition: var(--transition-smooth); padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 8px;">
                        <span>↩️</span>
                        <span>Đổi trả</span>
                    </a>
                    <a href="#" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 14px; transition: var(--transition-smooth); padding: 8px 0; display: flex; align-items: center; gap: 8px;">
                        <span>🔒</span>
                        <span>Chính sách bảo mật</span>
                    </a>
                </nav>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 style="font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 600; margin-bottom: 20px; color: var(--primary-gold); letter-spacing: 1px;">
                    Liên Hệ
                </h4>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <div style="display: flex; align-items: start; gap: 12px;">
                        <span style="font-size: 20px; color: var(--primary-gold);">📍</span>
                        <div>
                            <div style="color: rgba(255,255,255,0.9); font-weight: 500; margin-bottom: 4px; font-size: 14px;">Địa chỉ</div>
                            <div style="color: rgba(255,255,255,0.7); font-size: 13px; line-height: 1.6;">123 Đường ABC<br>Quận XYZ, TP.HCM</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: start; gap: 12px;">
                        <span style="font-size: 20px; color: var(--primary-gold);">📧</span>
                        <div>
                            <div style="color: rgba(255,255,255,0.9); font-weight: 500; margin-bottom: 4px; font-size: 14px;">Email</div>
                            <a href="mailto:contact@luxuryfashion.com" style="color: rgba(255,255,255,0.7); font-size: 13px; text-decoration: none; transition: var(--transition-smooth);">
                                contact@luxuryfashion.com
                            </a>
                        </div>
                    </div>
                    <div style="display: flex; align-items: start; gap: 12px;">
                        <span style="font-size: 20px; color: var(--primary-gold);">📱</span>
                        <div>
                            <div style="color: rgba(255,255,255,0.9); font-weight: 500; margin-bottom: 4px; font-size: 14px;">Hotline</div>
                            <a href="tel:+84123456789" style="color: rgba(255,255,255,0.7); font-size: 13px; text-decoration: none; transition: var(--transition-smooth);">
                                +84 123 456 789
                            </a>
                        </div>
                    </div>
                    <div style="display: flex; align-items: start; gap: 12px;">
                        <span style="font-size: 20px; color: var(--primary-gold);">🕒</span>
                        <div>
                            <div style="color: rgba(255,255,255,0.9); font-weight: 500; margin-bottom: 4px; font-size: 14px;">Giờ làm việc</div>
                            <div style="color: rgba(255,255,255,0.7); font-size: 13px; line-height: 1.6;">
                                Thứ 2 - Thứ 6: 9:00 - 18:00<br>
                                Thứ 7 - CN: 10:00 - 16:00
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom" style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 40px; padding: 30px 40px; background: rgba(0,0,0,0.2);">
            <div style="max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                <div style="color: rgba(255,255,255,0.6); font-size: 14px;">
                    © <?php echo date('Y'); ?> Luxury Fashion Store. Tất cả quyền được bảo lưu.
                </div>
                <div style="display: flex; gap: 30px; flex-wrap: wrap;">
                    <a href="#" style="color: rgba(255,255,255,0.6); text-decoration: none; font-size: 13px; transition: var(--transition-smooth);">Điều khoản sử dụng</a>
                    <a href="#" style="color: rgba(255,255,255,0.6); text-decoration: none; font-size: 13px; transition: var(--transition-smooth);">Chính sách bảo mật</a>
                    <a href="#" style="color: rgba(255,255,255,0.6); text-decoration: none; font-size: 13px; transition: var(--transition-smooth);">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <style>
        footer a:hover {
            color: var(--primary-gold) !important;
            transform: translateX(5px);
        }

        footer .social-links a:hover {
            background: var(--primary-gold) !important;
            color: var(--primary-black) !important;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
        }

        @media (max-width: 768px) {
            footer > div:first-child {
                grid-template-columns: 1fr;
                gap: 30px;
                padding: 40px 20px 30px;
            }

            footer .footer-bottom {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }

            footer .footer-bottom > div:last-child {
                justify-content: center;
            }
        }
    </style>
</body>
</html>

