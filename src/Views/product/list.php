<?php
// File: src/Views/product/list.php
?>
<div style="display: grid; grid-template-columns: 250px 1fr; gap: 20px;">
    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Danh Mục</h3>
        <div class="category-list">
            <a href="<?php echo ROOT_URL; ?>product">Tất cả sản phẩm</a>
            <?php foreach ($categories as $category): ?>
                <a href="<?php echo ROOT_URL; ?>product/category/<?php echo $category['id']; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Products -->
    <div>
        <h2 style="color: #2c3e50; margin-bottom: 20px;">Sản Phẩm</h2>
        <?php if (!empty($products)): ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?php echo ROOT_URL; ?>public/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <?php else: ?>
                                <div style="font-size: 50px;">👕</div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                            <div class="product-price"><?php echo number_format($product['price'], 0, ',', '.'); ?> ₫</div>
                            <div class="product-description"><?php echo substr(htmlspecialchars($product['description']), 0, 50); ?>...</div>
                            <div style="margin-bottom: 10px; font-size: 12px; color: #7f8c8d;">
                                Kho: <strong><?php echo $product['quantity']; ?></strong>
                            </div>
                            <a href="<?php echo ROOT_URL; ?>product/detail/<?php echo $product['id']; ?>" class="btn btn-primary" style="width: 100%; margin-bottom: 5px;">Xem chi tiết</a>
                            <form method="POST" action="<?php echo ROOT_URL; ?>cart/add/<?php echo $product['id']; ?>" style="display: flex; gap: 5px;">
                                <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['quantity']; ?>" style="flex: 1; padding: 8px; border: 1px solid #bdc3c7; border-radius: 4px;">
                                <button type="submit" class="btn btn-success" style="flex: 1;">Thêm giỏ</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="text-align: center; color: #7f8c8d; padding: 40px;">Không có sản phẩm nào</p>
        <?php endif; ?>
    </div>
</div>

