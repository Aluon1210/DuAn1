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

    .comment-form {
        background: linear-gradient(135deg, var(--accent-gray) 0%, #f5f5f5 100%);
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 40px;
        border-left: 4px solid var(--primary-gold);
    }

    .comment-form textarea {
        width: 100%;
        min-height: 120px;
        padding: 16px;
        border: 2px solid var(--border-light);
        border-radius: 8px;
        font-family: Arial, sans-serif;
        font-size: 14px;
        resize: vertical;
        box-sizing: border-box;
        margin-bottom: 16px;
    }

    .comment-form textarea:focus {
        outline: none;
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    .comment-form button {
        padding: 12px 32px;
        background: var(--primary-gold);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition-smooth);
        font-size: 14px;
    }

    .comment-form button:hover {
        background: #d4a844;
        transform: translateY(-2px);
    }

    .login-prompt {
        background: linear-gradient(135deg, #e7f3ff 0%, #f0f8ff 100%);
        padding: 24px;
        border-radius: 12px;
        text-align: center;
        border-left: 4px solid #007bff;
        margin-bottom: 40px;
    }

    .login-prompt p {
        color: #004085;
        font-size: 16px;
        margin-bottom: 12px;
    }

    .login-prompt a {
        display: inline-block;
        padding: 10px 24px;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: var(--transition-smooth);
    }

    .login-prompt a:hover {
        background: #0056b3;
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

    .alert {
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 500;
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
</style>

<?php if ($product): ?>
    <div class="product-detail-container">
        <a href="<?php echo ROOT_URL; ?>product" class="btn btn-primary"
            style="margin-bottom: 30px; display: inline-flex; align-items: center; gap: 8px;">
            <span>←</span> <span>Quay lại danh sách</span>
        </a>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; margin-top: 30px;">
            <!-- Hình ảnh -->
            <div class="product-detail-image">
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

                <div class="product-detail-price"><?php echo number_format($product['price'], 0, ',', '.'); ?></div>

                <div class="product-info-box">
                    <p>
                        <strong>Danh mục:</strong>
                        <?php
                        $cat = array_filter($categories, function ($c) use ($product) {
                            return $c['id'] == $product['category_id'];
                        });
                        if (!empty($cat)) {
                            echo htmlspecialchars(array_values($cat)[0]['name']);
                        }
                        ?>
                    </p>
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
    </div>

    <!-- COMMENTS SECTION -->
    <div class="product-detail-container" style="margin-top: 40px;">
        <div class="comments-section">
            <h3>📝 Bình luận sản phẩm</h3>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['message']);
                    unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- FORM COMMENT (chỉ hiển thị nếu đã đăng nhập và đã nhận hàng) -->
            <?php if (isset($_SESSION['user'])): ?>
                <?php if (isset($canComment) && $canComment): ?>
                    <div class="comment-form">
                        <h4 style="margin-top: 0; margin-bottom: 16px; color: var(--primary-black);">
                            Chia sẻ ý kiến của bạn
                        </h4>
                        <form method="POST"
                            action="<?php echo ROOT_URL; ?>product/postComment/<?php echo htmlspecialchars($product['id']); ?>">
                            <textarea name="content" placeholder="Nhập bình luận của bạn..." required></textarea>
                            <button type="submit">Đăng bình luận</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="login-prompt"
                        style="background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border-left: 4px solid #ffc107;">
                        <p style="color: #856404;">📦 Bạn chỉ có thể bình luận sau khi đã nhận hàng sản phẩm này</p>
                        <p style="color: #856404; font-size: 14px; margin-top: 8px;">Vui lòng đợi đơn hàng của bạn được giao thành
                            công.</p>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="login-prompt">
                    <p>👤 Vui lòng đăng nhập để bình luận sản phẩm này</p>
                    <a href="<?php echo ROOT_URL; ?>login">Đăng nhập ngay</a>
                </div>
            <?php endif; ?>

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
<?php endif; ?>

<script>
    // Helper to reload comments section via AJAX using the new CommentController
    (function(){
        const productId = '<?php echo htmlspecialchars($product['id'] ?? $product['Product_Id'] ?? ''); ?>';
        if (!productId) return;
        const ROOT = '<?php echo rtrim(ROOT_URL, "/"); ?>';

        window.reloadComments = async function(){
            try {
                const res = await fetch(ROOT + '/comment/ajaxList/' + encodeURIComponent(productId));
                const json = await res.json();
                if (json.ok && typeof json.html !== 'undefined') {
                    const container = document.querySelector('.comments-list[data-product-id="' + productId + '"]') || document.querySelector('.comments-list');
                    if (container) {
                        container.innerHTML = json.html;
                    }
                }
            } catch (e) { console.error('Failed to reload comments', e); }
        };

        // Optionally expose: reload on page load (comment out if not desired)
        // reloadComments();
    })();
</script>