<?php require_once ROOT_PATH . '/src/Views/includes/header.php'; ?>

<style>
    .product-quantity-input {
        flex: 1;
        padding: 12px 16px;
        border: 2px solid var(--border-light);
        border-radius: 30px;
        font-size: 14px;
        font-weight: 500;
        text-align: center;
        transition: var(--transition-smooth);
        background: white;
        color: var(--text-dark);
    }

    .product-quantity-input:focus {
        outline: none;
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-soft);
    }

    .empty-state-icon {
        font-size: 80px;
        margin-bottom: 24px;
        opacity: 0.5;
    }

    .empty-state-text {
        font-size: 18px;
        color: var(--text-light);
        font-weight: 500;
    }

    .category-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 3px solid var(--primary-gold-light);
    }

    .sidebar {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: var(--shadow-soft);
        height: fit-content;
    }

    .sidebar h3 {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--primary-black);
        padding-bottom: 15px;
        border-bottom: 2px solid var(--primary-gold-light);
    }

    .category-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .category-list a {
        padding: 14px 18px;
        border-radius: 8px;
        text-decoration: none;
        color: var(--text-dark);
        font-size: 15px;
        font-weight: 500;
        transition: var(--transition-smooth);
        border: 2px solid transparent;
        background: var(--accent-gray);
    }

    .category-list a:hover,
    .category-list a.active {
        background: linear-gradient(135deg, var(--primary-gold-light) 0%, #f4e4bc 100%);
        border-color: var(--primary-gold);
        color: var(--primary-black);
        transform: translateX(5px);
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        transition: var(--transition-smooth);
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
    }

    .product-image {
        width: 100%;
        height: 280px;
        overflow: hidden;
        background: linear-gradient(135deg, var(--accent-gray) 0%, #f0f0f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition-smooth);
    }

    .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    .product-info {
        padding: 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .product-name {
        font-family: 'Playfair Display', serif;
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 12px;
        color: var(--primary-black);
        line-height: 1.3;
    }

    .product-price {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        font-weight: 700;
        color: var(--primary-gold);
        margin-bottom: 12px;
    }

    .product-price::after {
        content: ' ₫';
        font-size: 18px;
    }

    .product-description {
        font-size: 14px;
        color: var(--text-light);
        line-height: 1.6;
        margin-bottom: 12px;
        flex: 1;
    }

    .product-stock {
        font-size: 13px;
        color: var(--text-light);
        margin-bottom: 16px;
    }

    .product-stock strong {
        color: var(--primary-black);
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: 1fr;
        }

        div[style*="grid-template-columns: 280px 1fr"] {
            grid-template-columns: 1fr !important;
        }

        .sidebar {
            position: static !important;
            margin-bottom: 30px;
        }
    }
</style>

<div style="display: grid; grid-template-columns: 280px 1fr; gap: 40px;">
    <!-- Sidebar -->
    <div class="sidebar" style="position: sticky; top: 100px; height: fit-content;">
        <h3>Danh Mục</h3>
        <div class="category-list">
            <a href="<?php echo ROOT_URL; ?>product">Tất cả sản phẩm</a>
            <?php foreach ($categories as $category): ?>
                <a href="<?php echo ROOT_URL; ?>product/category/<?php echo $category['id']; ?>" 
                   class="<?php echo (isset($name) && $name['id'] == $category['id']) ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Products -->
    <div>
        <a href="<?php echo ROOT_URL; ?>product" class="btn btn-primary" style="margin-bottom: 30px; display: inline-flex; align-items: center; gap: 8px;">
            <span>←</span> <span>Quay lại</span>
        </a>
        
        <div class="category-header">
            <h2 style="font-family: 'Playfair Display', serif; font-size: 36px; font-weight: 600; margin-bottom: 8px; letter-spacing: 1px;">
                <?php echo isset($name) ? htmlspecialchars($name['name']) : 'Danh mục'; ?>
            </h2>
            <p style="color: var(--text-light); font-size: 16px;">
                <?php echo !empty($products) ? count($products) . ' sản phẩm' : 'Không có sản phẩm'; ?>
            </p>
        </div>

        <?php if (!empty($products)): ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?php echo ROOT_URL; ?>public/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <?php else: ?>
                                <div style="font-size: 80px; opacity: 0.3;">✨</div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                            <div class="product-price"><?php echo number_format($product['price'], 0, ',', '.'); ?></div>
                            <div class="product-description"><?php echo substr(htmlspecialchars($product['description']), 0, 60); ?>...</div>
                            <div class="product-stock">
                                Kho: <strong><?php echo $product['quantity']; ?></strong> sản phẩm
                            </div>
                            <a href="<?php echo ROOT_URL; ?>product/detail/<?php echo $product['id']; ?>" class="btn btn-primary" style="width: 100%; margin-bottom: 12px;">
                                Xem Chi Tiết
                            </a>
                            <form method="POST" action="<?php echo ROOT_URL; ?>cart/add/<?php echo $product['id']; ?>" style="display: flex; gap: 8px;">
                                <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['quantity']; ?>" class="product-quantity-input">
                                <button type="submit" class="btn btn-success" style="flex: 1;">
                                    🛒 Thêm
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">👗</div>
                <div class="empty-state-text">Không có sản phẩm nào trong danh mục này</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once ROOT_PATH . '/src/Views/includes/footer.php'; ?>

