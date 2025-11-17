<?php require_once 'views/header.php'; ?>

<div class="container">
    <a href="?url=product" class="btn btn-primary">← Quay lại</a>
    
    <h2 style="margin-top: 20px;">Kết quả tìm kiếm: "<?php echo htmlspecialchars($keyword); ?>"</h2>

    <?php if (!empty($products)): ?>
        <p style="color: #7f8c8d; margin-bottom: 20px;">Tìm thấy <?php echo count($products); ?> sản phẩm</p>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php if (!empty($product['image'])): ?>
                            <img src="public/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php else: ?>
                            <div style="font-size: 50px;">👕</div>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                        <div class="product-price"><?php echo number_format($product['price'], 0, ',', '.'); ?> ₫</div>
                        <div class="product-description"><?php echo substr(htmlspecialchars($product['description']), 0, 50); ?>...</div>
                        <a href="?url=product/detail/<?php echo $product['id']; ?>" class="btn btn-primary" style="width: 100%; margin-bottom: 5px;">Xem chi tiết</a>
                        <form method="POST" action="?url=cart/add/<?php echo $product['id']; ?>" style="display: flex; gap: 5px;">
                            <input type="number" name="quantity" value="1" min="1" style="flex: 1; padding: 8px; border: 1px solid #bdc3c7; border-radius: 4px;">
                            <button type="submit" class="btn btn-success" style="flex: 1;">Thêm giỏ</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: #7f8c8d; padding: 40px;">
            <?php echo !empty($keyword) ? 'Không tìm thấy sản phẩm nào' : 'Hãy nhập từ khóa để tìm kiếm'; ?>
        </p>
    <?php endif; ?>
</div>
