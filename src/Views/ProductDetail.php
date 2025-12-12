<?php require_once ROOT_PATH . '/src/Views/includes/header.php'; ?>

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

    /* Variant Selection Styles */
    .variant-selector {
        margin: 30px 0;
        padding: 24px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        border: 2px solid var(--border-light);
    }

    .variant-selector h4 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 16px;
        color: var(--primary-black);
    }

    .color-options {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .color-option {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.3s ease;
        position: relative;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .color-option:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .color-option.selected {
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 2px white, 0 0 0 4px var(--primary-gold);
        transform: scale(1.15);
    }

    .color-option.disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .color-option.disabled:hover {
        transform: none;
    }

    .color-label {
        position: absolute;
        bottom: -25px;
        left: 50%;
        transform: translateX(-50%);
        white-space: nowrap;
        font-size: 12px;
        color: var(--text-dark);
        font-weight: 500;
    }

    .size-options {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .size-option {
        padding: 12px 24px;
        border: 2px solid var(--border-light);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        background: white;
        min-width: 70px;
        text-align: center;
    }

    .size-option:hover {
        border-color: var(--primary-gold);
        background: var(--primary-gold-light);
    }

    .size-option.selected {
        border-color: var(--primary-gold);
        background: var(--primary-gold);
        color: white;
    }

    .size-option.disabled {
        opacity: 0.3;
        cursor: not-allowed;
        background: #f5f5f5;
    }

    .size-option.disabled:hover {
        border-color: var(--border-light);
        background: #f5f5f5;
    }

    .variant-info {
        margin-top: 20px;
        padding: 16px;
        background: linear-gradient(135deg, #e8f5e9 0%, #f1f8f4 100%);
        border-radius: 8px;
        border-left: 4px solid #4caf50;
    }

    .variant-info p {
        margin: 8px 0;
        font-size: 15px;
        color: var(--text-dark);
    }

    .variant-info strong {
        color: var(--primary-black);
    }

    .alert {
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 500;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .alert-info {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Comments Section */
    .comments-section {
        margin-top: 60px;
        padding-top: 40px;
        border-top: 2px solid var(--border-light);
    }

    .comments-section h3 {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        margin-bottom: 30px;
        color: var(--primary-black);
        padding-bottom: 15px;
        border-bottom: 2px solid var(--primary-gold-light);
    }

    .comments-list {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .comment-item {
        padding: 24px;
        background: white;
        border: 2px solid var(--border-light);
        border-radius: 12px;
        transition: var(--transition-smooth);
    }

    .comment-item:hover {
        border-color: var(--primary-gold);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.1);
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .comment-author {
        font-weight: 600;
        color: var(--primary-black);
        font-size: 15px;
    }

    .comment-date {
        font-size: 13px;
        color: var(--text-light);
    }

    .comment-content {
        color: var(--text-dark);
        line-height: 1.6;
        font-size: 14px;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .empty-comments {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-light);
    }

    .empty-comments p {
        font-size: 16px;
    }

    @media (max-width: 768px) {
        .product-detail-container {
            padding: 20px;
        }

        .product-detail-info {
            padding-left: 0;
            margin-top: 30px;
        }

        .product-detail-container>div {
            grid-template-columns: 1fr !important;
            gap: 30px;
        }

        .product-detail-image {
            height: 400px;
        }
    }
</style>

<script>
    // Chuyển dữ liệu PHP sang JavaScript
    const variantsData = <?php echo json_encode($variants ?? []); ?>;
    const productData = <?php echo json_encode($product ?? []); ?>;

    let selectedColor = null;
    let selectedSize = null;
    let currentVariant = null;

    // Hàm tìm variant theo color và size
    function findVariant(colorId, sizeId) {
        return variantsData.find(v => {
            const matchColor = colorId ? v.color_id == colorId : !v.color_id;
            const matchSize = sizeId ? v.size_id == sizeId : !v.size_id;
            return matchColor && matchSize;
        });
    }

    // Hàm cập nhật UI khi chọn variant
    function updateVariantUI() {
        const variant = findVariant(selectedColor, selectedSize);
        currentVariant = variant;

        if (variant) {
            // Cập nhật giá
            const priceElement = document.getElementById('product-price');
            if (priceElement && variant.price) {
                priceElement.textContent = new Intl.NumberFormat('vi-VN').format(variant.price);
            }

            // Cập nhật thông tin variant
            const variantInfo = document.getElementById('variant-info');
            if (variantInfo) {
                const stock = variant.stock || 0;
                variantInfo.innerHTML = `
                <p><strong>Tồn kho:</strong> <span style="color: ${stock > 0 ? '#27ae60' : '#e74c3c'}; font-weight: 600;">${stock} sản phẩm</span></p>
            `;
                variantInfo.style.display = 'block';
            }

            // Cập nhật input số lượng
            const quantityInput = document.getElementById('quantity');
            if (quantityInput) {
                quantityInput.max = variant.stock;
                quantityInput.value = Math.min(1, variant.stock);
            }

            // Hiển thị số lượng còn lại
            const remainingStockEl = document.getElementById('remaining-stock');
            if (remainingStockEl) {
                const stock = variant.stock || 0;
                remainingStockEl.textContent = `(Còn lại: ${stock})`;
                remainingStockEl.style.display = 'inline';
                remainingStockEl.style.color = stock > 0 ? '#27ae60' : '#e74c3c';
            }

            // Cập nhật variant_id hidden input
            const variantIdInput = document.getElementById('variant_id');
            if (variantIdInput) {
                variantIdInput.value = variant.id;
            }

            // Hiển thị/ẩn nút thêm giỏ hàng
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            const outOfStockMsg = document.getElementById('out-of-stock-msg');

            if (variant.stock > 0) {
                if (addToCartBtn) addToCartBtn.style.display = 'block';
                if (outOfStockMsg) outOfStockMsg.style.display = 'none';
            } else {
                if (addToCartBtn) addToCartBtn.style.display = 'none';
                if (outOfStockMsg) outOfStockMsg.style.display = 'block';
            }
        }

        const alertEl = document.getElementById('variant-alert');
        if (alertEl) {
            if (selectedColor || selectedSize) {
                alertEl.style.display = 'none';
            } else {
                alertEl.style.display = 'block';
            }
        }

        // Cập nhật trạng thái available của các options
        updateAvailableOptions();
    }

    // Hàm cập nhật các options có sẵn
    function updateAvailableOptions() {
        // Cập nhật sizes available dựa trên color đã chọn
        const sizeOptions = document.querySelectorAll('.size-option');
        sizeOptions.forEach(option => {
            const sizeId = option.dataset.sizeId;
            const variant = findVariant(selectedColor, sizeId);

            if (variant && variant.stock > 0) {
                option.classList.remove('disabled');
            } else {
                option.classList.add('disabled');
            }
        });

        // Cập nhật colors available dựa trên size đã chọn
        const colorOptions = document.querySelectorAll('.color-option');
        colorOptions.forEach(option => {
            const colorId = option.dataset.colorId;
            const variant = findVariant(colorId, selectedSize);

            if (variant && variant.stock > 0) {
                option.classList.remove('disabled');
            } else {
                option.classList.add('disabled');
            }
        });
    }

    // Hàm chọn màu
    function selectColor(colorId) {
        selectedColor = colorId;

        // Cập nhật UI
        document.querySelectorAll('.color-option').forEach(option => {
            option.classList.remove('selected');
        });

        const selectedOption = document.querySelector(`.color-option[data-color-id="${colorId}"]`);
        if (selectedOption) {
            selectedOption.classList.add('selected');
        }

        updateVariantUI();
    }

    // Hàm chọn size
    function selectSize(sizeId) {
        selectedSize = sizeId;

        // Cập nhật UI
        document.querySelectorAll('.size-option').forEach(option => {
            option.classList.remove('selected');
        });

        const selectedOption = document.querySelector(`.size-option[data-size-id="${sizeId}"]`);
        if (selectedOption) {
            selectedOption.classList.add('selected');
        }

        updateVariantUI();
    }

    // Khởi tạo khi trang load
    document.addEventListener('DOMContentLoaded', function () {
        // Nếu chỉ có 1 variant, tự động chọn
        if (variantsData.length === 1) {
            const variant = variantsData[0];
            if (variant.color_id) selectColor(variant.color_id);
            if (variant.size_id) selectSize(variant.size_id);
        }

        // Cập nhật trạng thái ban đầu
        updateAvailableOptions();
    });
</script>

<?php if (isset($product) && $product): ?>
    <div class="product-detail-container" style="position: relative;">
        <div style="margin-bottom: 30px; display: flex; align-items: center; justify-content: flex-end; gap: 16px;">
            <?php if (!empty($variants) && (!empty($availableColors) || !empty($availableSizes))): ?>
                <div id="variant-alert" class="alert alert-warning" style="margin: 0;">
                    ℹ️ Vui lòng chọn
                    <?php echo !empty($availableColors) ? 'màu sắc' : ''; ?><?php echo !empty($availableColors) && !empty($availableSizes) ? ' và ' : ''; ?><?php echo !empty($availableSizes) ? 'kích thước' : ''; ?>
                    để xem thông tin chi tiết và giá.
                </div>
            <?php endif; ?>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; margin-top: 30px;">
            <!-- Hình ảnh -->
            <div class="product-detail-image">
                <a href="<?php echo ROOT_URL; ?>product" class="btn btn-primary"
                    style="position: absolute; top: 16px; left: 16px; z-index: 3; display: inline-flex; align-items: center; gap: 8px;">
                    <span>←</span>
                    <span>Tiếp tục mua sắm</span>
                </a>
                <?php if (!empty($product['image'])): ?>
                    <img src="<?php echo ROOT_URL; ?>public/images/<?php echo htmlspecialchars($product['image']); ?>"
                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                <?php else: ?>
                    <div
                        style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 150px; opacity: 0.2; background: linear-gradient(135deg, var(--accent-gray) 0%, #e8e8e8 100%);">
                        ✨
                    </div>
                <?php endif; ?>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="product-detail-info">
                <h1 class="product-detail-title"><?php echo htmlspecialchars($product['name']); ?></h1>

                <div class="product-detail-price" id="product-price">
                    <?php echo number_format($product['price'], 0, ',', '.'); ?></div>

                <p style="margin: 16px 0;">
                    <strong>Danh mục:</strong>
                    <?php
                    $categoryId = $product['category_id'] ?? null;
                    if (!empty($categories) && $categoryId !== null) {
                        $cat = array_filter($categories, function ($c) use ($categoryId) {
                            return is_array($c) && isset($c['id']) && $c['id'] == $categoryId;
                        });
                        if (!empty($cat)) {
                            $firstCat = array_values($cat)[0];
                            if (is_array($firstCat) && isset($firstCat['name'])) {
                                echo htmlspecialchars($firstCat['name']);
                            }
                        }
                    } elseif (!empty($product['category_name'])) {
                        echo htmlspecialchars($product['category_name']);
                    }
                    ?>
                </p>

                <?php if (!empty($variants)): ?>
                    <!-- Chọn màu sắc và kích thước -->
                    <div class="variant-selector">
                        <?php if (!empty($availableColors)): ?>
                            <h4>Chọn màu sắc:</h4>
                            <div class="color-options">
                                <?php foreach ($availableColors as $color): ?>
                                    <div class="color-option" data-color-id="<?php echo $color['id']; ?>"
                                        style="background-color: <?php echo htmlspecialchars($color['hex_code']); ?>;"
                                        onclick="selectColor(<?php echo $color['id']; ?>)"
                                        title="<?php echo htmlspecialchars($color['name']); ?>">
                                        <span class="color-label"><?php echo htmlspecialchars($color['name']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($availableSizes)): ?>
                            <h4>Chọn kích thước:</h4>
                            <div class="size-options">
                                <?php foreach ($availableSizes as $size): ?>
                                    <div class="size-option" data-size-id="<?php echo $size['id']; ?>"
                                        onclick="selectSize(<?php echo $size['id']; ?>)">
                                        <?php echo htmlspecialchars($size['name'] ?? ''); ?>
                                        <?php if (!empty($size['type'])): ?>
                                            <div style="font-size: 11px; color: #888; margin-top: 2px;">
                                                <?php echo htmlspecialchars($size['type']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div id="variant-info" class="variant-info" style="display: none;">
                        </div>
                    </div>

                    <?php if (empty($availableColors) && empty($availableSizes)): ?>
                        <div class="alert alert-info">Sản phẩm này có sẵn với cấu hình tiêu chuẩn.</div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="product-info-box">
                        <p>
                            <strong>Kho hàng:</strong>
                            <span
                                style="color: <?php echo $product['quantity'] > 0 ? '#27ae60' : '#e74c3c'; ?>; font-weight: 600;">
                                <?php echo $product['quantity']; ?> sản phẩm
                            </span>
                            <?php if ($product['quantity'] > 0): ?>
                                <span class="stock-badge stock-available">Còn hàng</span>
                            <?php else: ?>
                                <span class="stock-badge stock-unavailable">Hết hàng</span>
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>

                <!-- Form thêm vào giỏ hàng -->
                <?php if (!empty($variants)): ?>
                    <form method="POST" action="<?php echo ROOT_URL; ?>cart/add/<?php echo $product['id']; ?>"
                        id="add-to-cart-form">
                        <input type="hidden" name="variant_id" id="variant_id" value="">

                        <div class="quantity-selector">
                            <label for="quantity">Số lượng:</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="1" required>
                            <span id="remaining-stock" style="font-size: 14px; margin-left: 8px; display: none;"></span>
                        </div>

                        <button type="submit" id="add-to-cart-btn" class="btn btn-success"
                            style="padding: 18px 50px; font-size: 18px; width: 100%; text-transform: uppercase; letter-spacing: 1.5px; display: none;">
                            🛒 Thêm vào giỏ hàng
                        </button>
                    </form>

                    <div id="out-of-stock-msg"
                        style="padding: 24px; background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); border-radius: 12px; text-align: center; margin-top: 30px; display: none;">
                        <div style="font-size: 48px; margin-bottom: 16px;">⏳</div>
                        <div style="color: #721c24; font-weight: 600; font-size: 18px;">Biến thể này hiện đang hết hàng</div>
                        <p style="color: #721c24; margin-top: 8px; font-size: 14px;">Vui lòng chọn màu sắc hoặc kích thước khác
                        </p>
                    </div>
                <?php elseif ($product['quantity'] > 0): ?>
                    <form method="POST" action="<?php echo ROOT_URL; ?>cart/add/<?php echo $product['id']; ?>">
                        <div class="quantity-selector">
                            <label for="quantity">Số lượng:</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1"
                                max="<?php echo $product['quantity']; ?>" required>
                            <span style="color: var(--text-light); font-size: 14px;">(Tối đa:
                                <?php echo $product['quantity']; ?>)</span>
                        </div>
                        <button type="submit" class="btn btn-success"
                            style="padding: 18px 50px; font-size: 18px; width: 100%; text-transform: uppercase; letter-spacing: 1.5px;">
                            🛒 Thêm vào giỏ hàng
                        </button>
                    </form>
                <?php else: ?>
                    <div
                        style="padding: 24px; background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); border-radius: 12px; text-align: center; margin-top: 30px;">
                        <div style="font-size: 48px; margin-bottom: 16px;">⏳</div>
                        <div style="color: #721c24; font-weight: 600; font-size: 18px;">Sản phẩm hiện đang hết hàng</div>
                        <p style="color: #721c24; margin-top: 8px; font-size: 14px;">Vui lòng quay lại sau hoặc liên hệ với
                            chúng tôi</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="product-description-box">
            <h3>Mô tả sản phẩm</h3>
            <p>
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </p>
        </div>
    </div>

    <!-- COMMENTS SECTION -->
    <div class="product-detail-container" style="margin-top: 40px;">
        <div class="comments-section">
            <h3>📝 Bình luận sản phẩm</h3>

            <!-- COMMENTING DISABLED: show read-only notice only -->
            <div style="margin-bottom: 20px;">
                <div class="alert" style="background: linear-gradient(135deg, #eef6ff 0%, #f7fbff 100%); border-left: 4px solid #007bff;">
                    <strong>Chú ý:</strong> Chức năng đăng bình luận tạm thời bị vô hiệu hóa. Trang chỉ hiển thị các bình luận hiện có.
                </div>
            </div>

            <!-- DANH SÁCH BÌNH LUẬN -->
            <div style="margin-top: 30px;">
                <?php if (!empty($comments)): ?>
                    <div class="comments-list" data-product-id="<?php echo htmlspecialchars($product['id'] ?? $product['Product_Id'] ?? ''); ?>">
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment-item">
                                <div class="comment-header">
                                    <span class="comment-author">
                                        👤 <?php echo htmlspecialchars($comment['user_name'] ?? 'Ẩn danh'); ?>
                                    </span>
                                    <span class="comment-date">
                                        <?php echo htmlspecialchars($comment['Create_at'] ?? date('d/m/Y')); ?>
                                    </span>
                                </div>
                                <div class="comment-content">
                                    <?php echo htmlspecialchars($comment['Content']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-comments">
                        <div style="font-size: 48px; margin-bottom: 12px;">💭</div>
                        <p>Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <div
        style="background: white; padding: 60px; border-radius: 16px; text-align: center; box-shadow: var(--shadow-soft); margin-top: 20px;">
        <div style="font-size: 80px; margin-bottom: 20px; opacity: 0.3;">❌</div>
        <h2 style="font-family: 'Playfair Display', serif; font-size: 32px; margin-bottom: 12px; color: var(--text-dark);">
            Sản phẩm không tồn tại</h2>
        <p style="color: var(--text-light); margin-bottom: 30px;">Không tìm thấy sản phẩm bạn đang tìm kiếm.</p>
        <a href="<?php echo ROOT_URL; ?>product" class="btn btn-primary" style="padding: 14px 30px;">
            ← Quay lại danh sách sản phẩm
        </a>
    </div>
<?php endif; ?>

<?php require_once ROOT_PATH . '/src/Views/includes/footer.php'; ?>
