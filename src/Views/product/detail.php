<?php
// File: src/Views/product/detail.php
?>
<style>
    .product-detail-container {
        background: white;
        border-radius: 16px;
        padding: 40px;
        box-shadow: var(--shadow-soft);
        margin-top: 20px;
    }

    .product-detail-image {
        height: 600px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-hover);
        position: relative;
    }

    .product-detail-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-detail-info {
        padding-left: 40px;
    }

    .product-detail-title {
        font-family: 'Playfair Display', serif;
        font-size: 42px;
        font-weight: 700;
        margin-bottom: 24px;
        color: var(--primary-black);
        letter-spacing: 1px;
        line-height: 1.2;
    }

    .product-detail-price {
        font-family: 'Playfair Display', serif;
        font-size: 48px;
        font-weight: 700;
        margin: 30px 0;
        color: var(--primary-black);
        letter-spacing: 2px;
    }

    .product-detail-price::after {
        content: ' ₫';
        font-size: 32px;
        color: var(--primary-gold);
    }

    .product-info-box {
        margin: 30px 0;
        padding: 24px;
        background: linear-gradient(135deg, var(--accent-gray) 0%, #f0f0f0 100%);
        border-radius: 12px;
        border-left: 4px solid var(--primary-gold);
    }

    .product-info-box p {
        margin-bottom: 12px;
        font-size: 16px;
        color: var(--text-dark);
    }

    .product-info-box p:last-child {
        margin-bottom: 0;
    }

    .product-info-box strong {
        color: var(--primary-black);
        font-weight: 600;
        min-width: 120px;
        display: inline-block;
    }

    .product-description-box {
        margin: 40px 0;
        padding: 30px;
        background: white;
        border: 2px solid var(--border-light);
        border-radius: 12px;
    }

    .product-description-box h3 {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        margin-bottom: 20px;
        color: var(--primary-black);
        padding-bottom: 15px;
        border-bottom: 2px solid var(--primary-gold-light);
    }

    .product-description-box p {
        font-size: 16px;
        line-height: 1.8;
        color: var(--text-dark);
    }

    .quantity-selector {
        display: flex;
        gap: 16px;
        align-items: center;
        margin: 30px 0;
    }

    .quantity-selector label {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 16px;
    }

    .quantity-selector input {
        padding: 14px 20px;
        border: 2px solid var(--border-light);
        border-radius: 30px;
        width: 100px;
        text-align: center;
        font-size: 16px;
        font-weight: 600;
        transition: var(--transition-smooth);
    }

    .quantity-selector input:focus {
        outline: none;
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    .stock-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-left: 12px;
    }

    .stock-available {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
    }

    .stock-unavailable {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
    }
</style>

<?php if ($product): ?>
    <div class="product-detail-container">
        <a href="<?php echo ROOT_URL; ?>product" class="btn btn-primary" style="margin-bottom: 30px; display: inline-flex; align-items: center; gap: 8px;">
            <span>←</span> <span>Quay lại danh sách</span>
        </a>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; margin-top: 30px;">
            <!-- Hình ảnh -->
            <div class="product-detail-image">
                <?php if (!empty($product['image'])): ?>
                    <img src="<?php echo ROOT_URL; ?>public/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <?php else: ?>
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 150px; opacity: 0.2; background: linear-gradient(135deg, var(--accent-gray) 0%, #e8e8e8 100%);">
                        ✨
                    </div>
                <?php endif; ?>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="product-detail-info">
                <h1 class="product-detail-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <div class="product-detail-price"><?php echo number_format($product['price'], 0, ',', '.'); ?></div>

                <div class="product-info-box">
                    <p>
                        <strong>Danh mục:</strong> 
                        <?php 
                        $cat = array_filter($categories, function($c) use ($product) {
                            return $c['id'] == $product['category_id'];
                        });
                        if (!empty($cat)) {
                            echo htmlspecialchars(array_values($cat)[0]['name']);
                        }
                        ?>
                    </p>
                    <p>
                        <strong>Kho hàng:</strong> 
                        <span style="color: <?php echo $product['quantity'] > 0 ? '#27ae60' : '#e74c3c'; ?>; font-weight: 600;">
                            <?php echo $product['quantity']; ?> sản phẩm
                        </span>
                        <?php if ($product['quantity'] > 0): ?>
                            <span class="stock-badge stock-available">Còn hàng</span>
                        <?php else: ?>
                            <span class="stock-badge stock-unavailable">Hết hàng</span>
                        <?php endif; ?>
                    </p>
                </div>

                <div class="product-description-box">
                    <h3>Mô tả sản phẩm</h3>
                    <p>
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </p>
                </div>

                <?php if ($product['quantity'] > 0): ?>
                    <form method="POST" action="<?php echo ROOT_URL; ?>cart/add/<?php echo $product['id']; ?>">
                        <div class="quantity-selector">
                            <label for="quantity">Số lượng:</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['quantity']; ?>" required>
                            <span style="color: var(--text-light); font-size: 14px;">(Tối đa: <?php echo $product['quantity']; ?>)</span>
                        </div>
                        <button type="submit" class="btn btn-success" style="padding: 18px 50px; font-size: 18px; width: 100%; text-transform: uppercase; letter-spacing: 1.5px;">
                            🛒 Thêm vào giỏ hàng
                        </button>
                    </form>
                <?php else: ?>
                    <div style="padding: 24px; background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); border-radius: 12px; text-align: center; margin-top: 30px;">
                        <div style="font-size: 48px; margin-bottom: 16px;">⏳</div>
                        <div style="color: #721c24; font-weight: 600; font-size: 18px;">Sản phẩm hiện đang hết hàng</div>
                        <p style="color: #721c24; margin-top: 8px; font-size: 14px;">Vui lòng quay lại sau hoặc liên hệ với chúng tôi</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
