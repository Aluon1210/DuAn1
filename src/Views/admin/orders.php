<!DOCTYPE html>
<html lang="vi">

<?php include __DIR__ . '/head.php'; ?>
<style>
    :root {
        --black: #0f0f10;
        --white: #ffffff;
        --text: #222;
        --muted: #666;
        --gold: #d4af37;
        --border: #e8e8e8;
        --bg: #f9f9f9;
        --success: #28a745;
        --danger: #dc3545;
        --warning: #ffc107;
        --primary: #007bff;
        --info: #17a2b8;
    }

    body {
        margin: 0;
        font-family: 'Inter', sans-serif;
        background: var(--bg);
        color: var(--text);
    }

    .admin-container {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .admin-content {
        max-width: 1400px;
        width: 100%;
        margin: 32px auto;
        padding: 0 20px;
    }

    h2 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 15px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .filter-section {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        align-items: flex-end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 6px;
        color: var(--muted);
    }

    .form-group input,
    .form-group select {
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.2s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: var(--gold);
        outline: none;
    }

    .btn {
        padding: 10px 18px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-block;
    }

    .btn-primary {
        background: var(--primary);
        color: #fff;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    .btn-success {
        background: var(--success);
        color: #fff;
    }

    .btn-success:hover {
        background: #218838;
    }

    .btn-danger {
        background: var(--danger);
        color: #fff;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .btn-info {
        background: var(--info);
        color: #fff;
        padding: 6px 12px;
        font-size: 13px;
    }

    .btn-info:hover {
        background: #138496;
    }

    .btn-small {
        padding: 6px 12px;
        font-size: 13px;
    }

    .stats-box {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 18px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .table-container {
        background: #fff;
        border-radius: 12px;
        overflow-x: auto;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }

    table thead {
        background-color: #f4f4f4;
        border-bottom: 2px solid #ddd;
    }

    table th,
    table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    table tbody tr:hover {
        background-color: #fafafa;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .status-confirmed {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .status-shipping {
        background: #cfe2ff;
        color: #084298;
        border: 1px solid #b6d4fe;
    }

    .status-delivered {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .status-return {
        background: #f3b8d9;
        color: #78284c;
        border: 1px solid #e9c3dd;
    }

    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .status-select {
        padding: 6px 10px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        background: #fff;
        cursor: pointer;
        transition: border-color 0.2s ease;
    }

    .status-select:hover,
    .status-select:focus {
        border-color: var(--gold);
        outline: none;
    }

    .empty-message {
        padding: 40px;
        text-align: center;
        color: var(--muted);
        font-size: 16px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 6px;
        margin-top: 25px;
        flex-wrap: wrap;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        text-decoration: none;
        color: var(--text);
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .pagination a:hover {
        background: var(--gold);
        color: #fff;
    }

    .pagination span.current {
        background: var(--gold);
        color: #fff;
        border-color: var(--gold);
    }
</style>

<body>
    <div class="admin-container">
        <?php include __DIR__ . '/aside.php'; ?>

        <main class="admin-content">
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

            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" action="<?php echo ROOT_URL; ?>admin/orders">
                    <div class="filter-row">
                        <div class="form-group">
                            <label for="filter-status">Trạng thái</label>
                            <select id="filter-status" name="status">
                                <option value="">-- Tất cả --</option>
                                <option value="pending" <?php echo ($data['filter']['status'] === 'pending') ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                <option value="confirmed" <?php echo ($data['filter']['status'] === 'confirmed') ? 'selected' : ''; ?>>Chờ giao hàng</option>
                                <option value="shipping" <?php echo ($data['filter']['status'] === 'shipping') ? 'selected' : ''; ?>>Vận chuyển</option>
                                <option value="delivered" <?php echo ($data['filter']['status'] === 'delivered') ? 'selected' : ''; ?>>Hoàn thành</option>
                                <option value="cancelled" <?php echo ($data['filter']['status'] === 'cancelled') ? 'selected' : ''; ?>>Đã hủy</option>
                                <option value="return" <?php echo ($data['filter']['status'] === 'return') ? 'selected' : ''; ?>>Trả hàng</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="filter-search">Tìm kiếm</label>
                            <input type="text" id="filter-search" name="q" placeholder="Mã đơn hàng, tên khách..."
                                   value="<?php echo htmlspecialchars($data['filter']['q'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Lọc</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Stats -->
            <div class="stats-box">
                <div>
                    <strong>Tổng đơn hàng:</strong> <?php echo (int)($data['pagination']['total'] ?? 0); ?>
                </div>
                <div>
                    <strong>Trang:</strong> <?php echo $data['pagination']['page']; ?> / <?php echo $data['pagination']['totalPages']; ?>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="table-container">
                <?php if (!empty($data['orders'])): ?>
                    <table>
                        <thead>
                            <tr>
                                <th width="15%">Mã đơn hàng</th>
                                <th width="15%">Ngày</th>
                                <th width="25%">Sản phẩm</th>
                                <th width="12%">Số lượng</th>
                                <th width="12%">Tổng tiền</th>
                                <th width="21%">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['orders'] as $order): ?>
                                <?php
                                    $statusMap = [
                                        'pending' => ['Chờ xác nhận', 'status-pending'],
                                        'confirmed' => ['Chờ giao hàng', 'status-confirmed'],
                                        'shipping' => ['Vận chuyển', 'status-shipping'],
                                        'delivered' => ['Hoàn thành', 'status-delivered'],
                                        'cancelled' => ['Đã hủy', 'status-cancelled'],
                                        'return' => ['Trả hàng', 'status-return']
                                    ];
                                    $status = $order['TrangThai'] ?? 'pending';
                                    $statusLabel = $statusMap[$status][0] ?? $status;
                                    $statusClass = $statusMap[$status][1] ?? 'status-pending';
                                    $productVariants = $order['product_variants'] ?? 'N/A';
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($order['Order_Id']); ?></strong></td>
                                    <td><?php echo date('d/m/Y', strtotime($order['Order_date'])); ?></td>
                                    <td>
                                        <small><?php echo htmlspecialchars($productVariants); ?></small>
                                    </td>
                                    <td><?php echo (int)($order['items_count'] ?? 0); ?> sản phẩm</td>
                                    <td><strong><?php echo number_format((float)($order['total'] ?? 0), 0, ',', '.'); ?>₫</strong></td>
                                    <td>
                                        <select class="status-select" data-order-id="<?php echo htmlspecialchars($order['Order_Id']); ?>" data-current-status="<?php echo htmlspecialchars($status); ?>">
                                            <option value="pending" <?php echo ($status === 'pending') ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                            <option value="confirmed" <?php echo ($status === 'confirmed') ? 'selected' : ''; ?>>Chờ giao hàng</option>
                                            <option value="shipping" <?php echo ($status === 'shipping') ? 'selected' : ''; ?>>Vận chuyển</option>
                                            <option value="delivered" <?php echo ($status === 'delivered') ? 'selected' : ''; ?>>Hoàn thành</option>
                                            <option value="cancelled" <?php echo ($status === 'cancelled') ? 'selected' : ''; ?>>Đã hủy</option>
                                            <option value="return" <?php echo ($status === 'return') ? 'selected' : ''; ?>>Trả hàng</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-message">
                        Không có đơn hàng nào.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($data['pagination']['totalPages'] > 1): ?>
                <div class="pagination">
                    <?php
                        $page = $data['pagination']['page'];
                        $totalPages = $data['pagination']['totalPages'];
                        $status = $data['filter']['status'] ?? '';
                        $q = $data['filter']['q'] ?? '';

                        // Previous page
                        if ($page > 1) {
                            $prevUrl = ROOT_URL . 'admin/orders?' . http_build_query(['page' => $page - 1, 'status' => $status, 'q' => $q]);
                            echo '<a href="' . htmlspecialchars($prevUrl) . '">&laquo; Trước</a>';
                        }

                        // Page numbers
                        for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++) {
                            if ($i == $page) {
                                echo '<span class="current">' . $i . '</span>';
                            } else {
                                $pageUrl = ROOT_URL . 'admin/orders?' . http_build_query(['page' => $i, 'status' => $status, 'q' => $q]);
                                echo '<a href="' . htmlspecialchars($pageUrl) . '">' . $i . '</a>';
                            }
                        }

                        // Next page
                        if ($page < $totalPages) {
                            $nextUrl = ROOT_URL . 'admin/orders?' . http_build_query(['page' => $page + 1, 'status' => $status, 'q' => $q]);
                            echo '<a href="' . htmlspecialchars($nextUrl) . '">Tiếp &raquo;</a>';
                        }
                    ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        // Handle status dropdown change with auto-save
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                const orderId = this.dataset.orderId;
                const newStatus = this.value;
                const previousStatus = this.dataset.currentStatus;

                if (newStatus === previousStatus) return;

                const formData = new FormData();
                formData.append('order_id', orderId);
                formData.append('status', newStatus);

                // Disable select while saving
                this.disabled = true;
                this.parentElement.style.opacity = '0.6';

                fetch('<?php echo ROOT_URL; ?>admin/updateOrderStatus', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('HTTP ' + res.status + ' ' + res.statusText);
                    }
                    return res.json();
                })
                .then(data => {
                    if (data && data.success) {
                        // Update the data attribute and re-enable
                        this.dataset.currentStatus = newStatus;
                        this.disabled = false;
                        this.parentElement.style.opacity = '1';
                        console.log('✅ Cập nhật trạng thái ' + orderId + ' thành ' + newStatus + ' thành công');
                    } else {
                        // Revert the change
                        this.value = previousStatus;
                        this.disabled = false;
                        this.parentElement.style.opacity = '1';
                        alert('❌ Cập nhật trạng thái thất bại: ' + (data.error || 'Lỗi không xác định'));
                    }
                })
                .catch(err => {
                    console.error('❌ Lỗi khi cập nhật:', err);
                    // Revert the change
                    this.value = previousStatus;
                    this.disabled = false;
                    this.parentElement.style.opacity = '1';
                    alert('❌ Lỗi khi cập nhật trạng thái:\n' + err.message);
                });
            });
        });
    </script>
</body>

</html>