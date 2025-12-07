<?php require_once ROOT_PATH . '/src/Views/includes/header.php'; ?>

<style>
    .admin-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .detail-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        font-weight: 700;
        color: var(--primary-black);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }

    .detail-section {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: var(--shadow-soft);
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--primary-black);
        padding-bottom: 12px;
        border-bottom: 2px solid var(--border-light);
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-light);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: var(--text-dark);
        min-width: 150px;
    }

    .detail-value {
        color: var(--text-dark);
        text-align: right;
        flex: 1;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-confirmed {
        background: #cce5ff;
        color: #004085;
    }

    .status-shipping {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-delivered {
        background: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .status-return {
        background: #f5c6cb;
        color: #721c24;
    }

    .order-items {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: var(--shadow-soft);
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
    }

    .items-table thead {
        background: linear-gradient(135deg, var(--primary-black) 0%, #2c2c2c 100%);
        color: white;
    }

    .items-table thead th {
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
    }

    .items-table tbody tr {
        border-bottom: 1px solid var(--border-light);
    }

    .items-table tbody td {
        padding: 16px;
        vertical-align: middle;
    }

    .item-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        background: var(--accent-gray);
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-name {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .item-variant {
        font-size: 12px;
        color: var(--text-light);
    }

    .item-price {
        font-weight: 600;
        color: var(--primary-gold);
    }

    .item-qty {
        text-align: center;
        font-weight: 600;
    }

    .item-subtotal {
        font-weight: 700;
        color: var(--primary-gold);
        text-align: right;
    }

    .totals-section {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid var(--border-light);
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 14px;
    }

    .total-row.grand {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--border-light);
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-gold);
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    .btn-action {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
    }

    .btn-primary {
        background: var(--primary-gold);
        color: var(--primary-black);
    }

    .btn-primary:hover {
        background: #b8941f;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: var(--accent-gray);
        color: var(--text-dark);
        border: 1px solid var(--border-light);
    }

    .btn-secondary:hover {
        background: var(--border-light);
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-row {
            flex-direction: column;
        }

        .detail-value {
            text-align: left;
            margin-top: 4px;
        }
    }
</style>

<div class="admin-container">
    <div class="detail-header">
        <h1>📋 Chi Tiết Đơn Hàng</h1>
        <a href="<?php echo ROOT_URL; ?>admin/orders" class="btn-action btn-secondary">← Quay lại</a>
    </div>

    <div class="detail-grid">
        <!-- Order Info -->
        <div class="detail-section">
            <div class="section-title">Thông Tin Đơn Hàng</div>
            
            <div class="detail-row">
                <span class="detail-label">Mã Đơn:</span>
                <span class="detail-value"><strong><?php echo htmlspecialchars($data['order']['Order_Id']); ?></strong></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Ngày Đặt:</span>
                <span class="detail-value"><?php echo htmlspecialchars($data['order']['Order_date']); ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Trạng Thái:</span>
                <span class="detail-value">
                    <?php 
                        $status = $data['order']['TrangThai'] ?? 'pending';
                        $statusClass = 'status-' . $status;
                        $statusText = [
                            'pending' => 'Chờ xác nhận',
                            'confirmed' => 'Chờ giao hàng',
                            'shipping' => 'Đang giao',
                            'delivered' => 'Đã giao',
                            'cancelled' => 'Đã hủy',
                            'return' => 'Trả hàng/Hoàn tiền'
                        ][$status] ?? 'Chưa rõ';
                    ?>
                    <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Ghi Chú:</span>
                <span class="detail-value"><?php echo htmlspecialchars($data['order']['Note'] ?? 'Không có'); ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Địa Chỉ:</span>
                <span class="detail-value"><?php echo htmlspecialchars($data['order']['Adress'] ?? 'N/A'); ?></span>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="detail-section">
            <div class="section-title">Thông Tin Khách Hàng</div>
            
            <div class="detail-row">
                <span class="detail-label">Tên:</span>
                <span class="detail-value"><?php echo htmlspecialchars($data['order']['user_name'] ?? 'N/A'); ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value"><?php echo htmlspecialchars($data['order']['user_email'] ?? 'N/A'); ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">ID Khách:</span>
                <span class="detail-value"><?php echo htmlspecialchars($data['order']['_UserName_Id']); ?></span>
            </div>

            <div class="action-buttons">
                <form method="POST" action="<?php echo ROOT_URL; ?>admin/orders/updateStatus" style="width: 100%;">
                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($data['order']['Order_Id']); ?>">
                    <div style="display: flex; gap: 12px;">
                        <select name="status" required style="flex: 1; padding: 10px; border: 1px solid var(--border-light); border-radius: 6px; font-size: 13px;">
                            <option value="">Cập nhật trạng thái...</option>
                            <option value="pending">Chờ xác nhận</option>
                            <option value="confirmed">Chờ giao hàng</option>
                            <option value="shipping">Đang giao</option>
                            <option value="delivered">Đã giao</option>
                            <option value="cancelled">Đã hủy</option>
                            <option value="return">Trả hàng/Hoàn tiền</option>
                        </select>
                        <button type="submit" class="btn-action btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="order-items">
        <div class="section-title">Chi Tiết Sản Phẩm</div>
        
        <?php if (!empty($data['items'])): ?>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Hình Ảnh</th>
                        <th>Sản Phẩm</th>
                        <th>Giá</th>
                        <th>Số Lượng</th>
                        <th>Thành Tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['items'] as $item): 
                        $img = $item['product_image'] ?? null;
                        $imgUrl = $img ? ROOT_URL . 'public/images/' . htmlspecialchars($img) : ROOT_URL . 'public/images/placeholder.jpg';
                        $productName = $item['product_name'] ?? 'Sản phẩm';
                        $price = (float)($item['Price'] ?? 0);
                        $qty = (int)($item['quantity'] ?? 0);
                        $subtotal = $price * $qty;
                        
                        $variantLabel = '';
                        if (!empty($item['color_name'])) {
                            $variantLabel .= 'Màu: ' . htmlspecialchars($item['color_name']);
                        }
                        if (!empty($item['size_name'])) {
                            $variantLabel .= ($variantLabel ? ' • ' : '') . 'Size: ' . htmlspecialchars($item['size_name']);
                        }
                    ?>
                        <tr>
                            <td>
                                <div class="item-image">
                                    <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo htmlspecialchars($productName); ?>">
                                </div>
                            </td>
                            <td>
                                <div class="item-name"><?php echo htmlspecialchars($productName); ?></div>
                                <?php if ($variantLabel): ?>
                                    <div class="item-variant"><?php echo $variantLabel; ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="item-price">₫<?php echo number_format($price, 0, ',', '.'); ?></td>
                            <td class="item-qty"><?php echo $qty; ?></td>
                            <td class="item-subtotal">₫<?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="totals-section">
                <div class="total-row">
                    <span>Tổng cộng:</span>
                    <span class="item-price">₫<?php echo number_format($data['total'], 0, ',', '.'); ?></span>
                </div>
            </div>
        <?php else: ?>
            <p style="text-align: center; color: var(--text-light); padding: 40px;">Không có sản phẩm trong đơn hàng này.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once ROOT_PATH . '/src/Views/includes/footer.php'; ?>
