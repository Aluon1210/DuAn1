<?php require_once 'views/header.php'; ?>

<div class="container">
    <?php if ($product): ?>
        <div style="background-color: white; padding: 20px; border-radius: 8px;">
            <a href="?url=product" class="btn btn-primary">← Quay lại</a>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 20px;">
                <!-- Hình ảnh -->
                <div class="product-image" style="height: 400px; border-radius: 8px; overflow: hidden;">
                    <?php if (!empty($product['image'])): ?>
                        <img src="public/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php else: ?>
                        <div style="width: 100%; display: flex; align-items: center; justify-content: center; font-size: 100px;">👕</div>
                    <?php endif; ?>
                </div>

                <!-- Thông tin sản phẩm -->
                <div>
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    
                    <div class="product-price" style="font-size: 32px; margin: 20px 0;">
                        <?php echo number_format($product['price'], 0, ',', '.'); ?> ₫
                    </div>

                    <div style="margin: 20px 0; padding: 15px; background-color: #ecf0f1; border-radius: 4px;">
                        <p><strong>Danh mục:</strong> 
                            <?php 
                            $cat = array_filter($categories, function($c) use ($product) {
                                return $c['id'] == $product['category_id'];
                            });
                            if (!empty($cat)) {
                                echo htmlspecialchars(array_values($cat)[0]['name']);
                            }
                            ?>
                        </p>
                        <p><strong>Kho hàng:</strong> <span style="color: <?php echo $product['quantity'] > 0 ? '#27ae60' : '#e74c3c'; ?>;"><?php echo $product['quantity']; ?></span></p>
                    </div>

                    <h3>Mô tả sản phẩm</h3>
                    <p style="line-height: 1.6; color: #555;">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </p>

                    <?php if ($product['quantity'] > 0): ?>
                        <form method="POST" action="?url=cart/add/<?php echo $product['id']; ?>" style="margin-top: 20px;">
                            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                                <div>
                                    <label for="quantity">Số lượng:</label>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['quantity']; ?>" style="padding: 10px; border: 1px solid #bdc3c7; border-radius: 4px; width: 80px;">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success" style="padding: 12px 30px; font-size: 16px;">
                                🛒 Thêm vào giỏ hàng
                            </button>
                        </form>
                    <?php else: ?>
                        <div style="color: #e74c3c; font-weight: bold; margin-top: 20px;">Sản phẩm hết hàng</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
