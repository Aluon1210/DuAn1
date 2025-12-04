<?php require_once ROOT_PATH . '/src/Views/includes/header.php'; ?>

<style>
    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
    }

    .admin-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 700;
        color: var(--primary-black);
    }

    .status-tabs {
        display: flex;
        gap: 0;
        margin-bottom: 30px;
        border-bottom: 2px solid var(--border-light);
        overflow-x: auto;
        flex-wrap: wrap;
    }

    .status-tab {
        padding: 15px 20px;
        border: none;
        background: none;
        color: var(--text-dark);
        font-weight: 600;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .status-tab:hover {
        color: var(--primary-gold);
    }

    .status-tab.active {
        color: var(--primary-gold);
        border-bottom-color: var(--primary-gold);
    }

    .orders-table-container {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-soft);
        overflow: hidden;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table thead {
        background: linear-gradient(135deg, var(--primary-black) 0%, #2c2c2c 100%);
        color: white;
    }

    .orders-table thead th {
        padding: 16px 20px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .orders-table tbody tr {
        border-bottom: 1px solid var(--border-light);
        transition: background 0.3s;
    }

    .orders-table tbody tr:hover {
        background: var(--accent-gray);
    }

    .orders-table tbody td {
        padding: 16px 20px;
        font-size: 14px;
    }

    .order-id {
        font-weight: 600;
        color: var(--primary-black);
    }

    .order-customer {
        display: flex;
        flex-direction: column;
    }

    .customer-name {
        font-weight: 600;
        color: var(--text-dark);
    }

    .customer-email {
        font-size: 12px;
        color: var(--text-light);
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        white-space: nowrap;
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

    .order-total {
        font-weight: 700;
        color: var(--primary-gold);
        font-size: 15px;
    }

    .order-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .order-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .order-btn-view {
        background: var(--primary-gold);
        color: var(--primary-black);
    }

    .order-btn-view:hover {
        background: #b8941f;
        transform: translateY(-2px);
    }

    .order-btn-status {
        background: var(--accent-gray);
        color: var(--text-dark);
        border: 1px solid var(--border-light);
    }

    .order-btn-status:hover {
        background: var(--border-light);
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        padding: 30px;
        max-width: 500px;
        width: 90%;
        box-shadow: var(--shadow-hover);
    }

    .modal-header {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--primary-black);
    }

    .modal-body {
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--text-dark);
    }

    .form-group select {
        width: 100%;
        padding: 12px 14px;
        border: 2px solid var(--border-light);
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: border 0.3s;
    }

    .form-group select:focus {
        outline: none;
        border-color: var(--primary-gold);
    }

    .modal-footer {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .btn-modal {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-confirm {
        background: var(--primary-gold);
        color: var(--primary-black);
    }

    .btn-confirm:hover {
        background: #b8941f;
    }

    .btn-cancel {
        background: var(--accent-gray);
        color: var(--text-dark);
        border: 1px solid var(--border-light);
    }

    .btn-cancel:hover {
        background: var(--border-light);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-soft);
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    .empty-state-title {
        font-size: 20px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 10px;
    }

    .empty-state-text {
        color: var(--text-light);
    }

    .alert {
        padding: 16px 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        border-left: 4px solid;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left-color: #28a745;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border-left-color: #dc3545;
    }
</style>

<div class="admin-container">
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="admin-header">
        <h1>📦 Quản Lý Đơn Hàng</h1>
        <a href="<?php echo ROOT_URL; ?>admin" class="order-btn order-btn-view">← Quay lại Admin</a>
    </div>

    <!-- Status Filter Tabs -->
    <div class="status-tabs">
        <button class="status-tab active" onclick="filterByStatus('all')">Tất cả</button>
        <button class="status-tab" onclick="filterByStatus('pending')">Chờ xác nhận</button>
        <button class="status-tab" onclick="filterByStatus('confirmed')">Chờ giao hàng</button>
        <button class="status-tab" onclick="filterByStatus('shipping')">Đang giao</button>
        <button class="status-tab" onclick="filterByStatus('delivered')">Đã giao</button>
        <button class="status-tab" onclick="filterByStatus('cancelled')">Đã hủy</button>
        <button class="status-tab" onclick="filterByStatus('return')">Trả hàng/Hoàn tiền</button>
    </div>

    <?php if (!empty($data['orders'])): ?>
        <div class="orders-table-container">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Khách Hàng</th>
                        <th>Ngày Đặt</th>
                        <th>Trạng Thái</th>
                        <th>Số Lượng</th>
                        <th>Tổng Tiền</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    <?php foreach ($data['orders'] as $order): 
                        $status = $order['TrangThai'] ?? 'pending';
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
                        <tr class="order-row" data-status="<?php echo $status; ?>">
                            <td class="order-id"><?php echo htmlspecialchars($order['Order_Id']); ?></td>
                            <td>
                                <div class="order-customer">
                                    <span class="customer-name"><?php echo htmlspecialchars($order['user_name'] ?? 'N/A'); ?></span>
                                    <span class="customer-email"><?php echo htmlspecialchars($order['user_email'] ?? ''); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($order['Order_date'] ?? ''); ?></td>
                            <td>
                                <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                            </td>
                            <td><?php echo $order['items_count'] ?? 0; ?> sản phẩm</td>
                            <td class="order-total">₫<?php echo number_format($order['total'] ?? 0, 0, ',', '.'); ?></td>
                            <td>
                                <div class="order-actions">
                                    <a href="<?php echo ROOT_URL; ?>admin/orders/detail/<?php echo urlencode($order['Order_Id']); ?>" class="order-btn order-btn-view">Chi tiết</a>
                                    <button type="button" class="order-btn order-btn-status" onclick="openStatusModal('<?php echo htmlspecialchars($order['Order_Id']); ?>', '<?php echo $status; ?>')">Trạng thái</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">📦</div>
            <div class="empty-state-title">Không có đơn hàng nào</div>
            <div class="empty-state-text">Hiện tại chưa có đơn hàng trong hệ thống.</div>
        </div>
    <?php endif; ?>
</div>

<!-- Status Modal -->
<div id="statusModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">Cập nhật trạng thái</div>
        <form method="POST" action="<?php echo ROOT_URL; ?>admin/orders/updateStatus">
            <div class="modal-body">
                <input type="hidden" name="order_id" id="orderIdInput" value="">
                
                <div class="form-group">
                    <label for="statusSelect">Trạng thái mới:</label>
                    <select name="status" id="statusSelect" required>
                        <option value="">-- Chọn trạng thái --</option>
                        <option value="pending">Chờ xác nhận</option>
                        <option value="confirmed">Chờ giao hàng</option>
                        <option value="shipping">Đang giao</option>
                        <option value="delivered">Đã giao</option>
                        <option value="cancelled">Đã hủy</option>
                        <option value="return">Trả hàng/Hoàn tiền</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal btn-cancel" onclick="closeStatusModal()">Hủy</button>
                <button type="submit" class="btn-modal btn-confirm">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<?php require_once ROOT_PATH . '/src/Views/includes/footer.php'; ?>

<script>
function openStatusModal(orderId, currentStatus) {
    document.getElementById('orderIdInput').value = orderId;
    document.getElementById('statusSelect').value = currentStatus;
    document.getElementById('statusModal').classList.add('active');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.remove('active');
}

function filterByStatus(status) {
    // Update active tab
    document.querySelectorAll('.status-tab').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');

    // Filter rows
    const rows = document.querySelectorAll('.order-row');
    rows.forEach(row => {
        if (status === 'all') {
            row.style.display = '';
        } else {
            row.style.display = row.dataset.status === status ? '' : 'none';
        }
    });
}

// Close modal when clicking outside
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStatusModal();
    }
});
</script>
