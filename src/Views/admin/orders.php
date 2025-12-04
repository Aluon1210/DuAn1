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

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .modal-content h3 {
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 18px;
    }

    .modal-body {
        margin-bottom: 20px;
    }

    .modal-footer {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .modal-footer .btn {
        margin: 0;
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
                                <option value="confirmed" <?php echo ($data['filter']['status'] === 'confirmed') ? 'selected' : ''; ?>>Đã xác nhận</option>
                                <option value="shipping" <?php echo ($data['filter']['status'] === 'shipping') ? 'selected' : ''; ?>>Đang giao</option>
                                <option value="delivered" <?php echo ($data['filter']['status'] === 'delivered') ? 'selected' : ''; ?>>Đã giao</option>
                                <option value="cancelled" <?php echo ($data['filter']['status'] === 'cancelled') ? 'selected' : ''; ?>>Đã hủy</option>
                                <option value="return" <?php echo ($data['filter']['status'] === 'return') ? 'selected' : ''; ?>>Hoàn trả</option>
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
                                <th width="18%">Khách hàng</th>
                                <th width="12%">Số lượng</th>
                                <th width="12%">Tổng tiền</th>
                                <th width="15%">Trạng thái</th>
                                <th width="13%">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['orders'] as $order): ?>
                                <?php
                                    $statusMap = [
                                        'pending' => ['Chờ xác nhận', 'status-pending'],
                                        'confirmed' => ['Đã xác nhận', 'status-confirmed'],
                                        'shipping' => ['Đang giao', 'status-shipping'],
                                        'delivered' => ['Đã giao', 'status-delivered'],
                                        'cancelled' => ['Đã hủy', 'status-cancelled'],
                                        'return' => ['Hoàn trả', 'status-return']
                                    ];
                                    $status = $order['TrangThai'] ?? 'pending';
                                    $statusLabel = $statusMap[$status][0] ?? $status;
                                    $statusClass = $statusMap[$status][1] ?? 'status-pending';
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($order['Order_Id']); ?></strong></td>
                                    <td><?php echo date('d/m/Y', strtotime($order['Order_date'])); ?></td>
                                    <td>
                                        <div><?php echo htmlspecialchars($order['user_name'] ?? 'N/A'); ?></div>
                                        <small style="color: var(--muted);"><?php echo htmlspecialchars($order['user_email'] ?? ''); ?></small>
                                    </td>
                                    <td><?php echo (int)($order['items_count'] ?? 0); ?> sản phẩm</td>
                                    <td><strong><?php echo number_format((float)($order['total'] ?? 0), 0, ',', '.'); ?>₫</strong></td>
                                    <td>
                                        <span class="status-badge <?php echo $statusClass; ?>" data-status="<?php echo htmlspecialchars($status); ?>" data-order-id="<?php echo htmlspecialchars($order['Order_Id']); ?>">
                                            <?php echo $statusLabel; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-info btn-small btn-update-status" data-order-id="<?php echo htmlspecialchars($order['Order_Id']); ?>">
                                                Cập nhật
                                            </button>
                                            <button class="btn btn-danger btn-small btn-delete" data-order-id="<?php echo htmlspecialchars($order['Order_Id']); ?>">
                                                Xóa
                                            </button>
                                        </div>
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

    <!-- Status Update Modal -->
    <div class="modal" id="statusModal">
        <div class="modal-content">
            <h3>Cập nhật trạng thái đơn hàng</h3>
            <div class="modal-body">
                <div class="form-group">
                    <label for="modalStatus">Trạng thái mới</label>
                    <select id="modalStatus">
                        <option value="pending">Chờ xác nhận</option>
                        <option value="confirmed">Đã xác nhận</option>
                        <option value="shipping">Đang giao</option>
                        <option value="delivered">Đã giao</option>
                        <option value="cancelled">Đã hủy</option>
                        <option value="return">Hoàn trả</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnConfirmStatus">Cập nhật</button>
                <button type="button" class="btn btn-danger" id="btnCancelStatus">Hủy</button>
            </div>
        </div>
    </div>

    <script>
        let currentOrderId = null;

        // Open status modal
        document.querySelectorAll('.btn-update-status').forEach(btn => {
            btn.addEventListener('click', function() {
                currentOrderId = this.dataset.orderId;
                const statusBadge = document.querySelector(`[data-order-id="${currentOrderId}"]`);
                if (statusBadge) {
                    document.getElementById('modalStatus').value = statusBadge.dataset.status;
                }
                document.getElementById('statusModal').classList.add('active');
            });
        });

        // Close modal
        document.getElementById('btnCancelStatus').addEventListener('click', function() {
            document.getElementById('statusModal').classList.remove('active');
        });

        // Confirm status update via AJAX
        document.getElementById('btnConfirmStatus').addEventListener('click', function() {
            const newStatus = document.getElementById('modalStatus').value;
            if (!currentOrderId || !newStatus) return;

            const formData = new FormData();
            formData.append('order_id', currentOrderId);
            formData.append('status', newStatus);

            fetch('<?php echo ROOT_URL; ?>admin/orders/updateStatus', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const statusBadge = document.querySelector(`[data-order-id="${currentOrderId}"]`);
                    const statusMap = {
                        'pending': ['Chờ xác nhận', 'status-pending'],
                        'confirmed': ['Đã xác nhận', 'status-confirmed'],
                        'shipping': ['Đang giao', 'status-shipping'],
                        'delivered': ['Đã giao', 'status-delivered'],
                        'cancelled': ['Đã hủy', 'status-cancelled'],
                        'return': ['Hoàn trả', 'status-return']
                    };
                    const [label, cls] = statusMap[newStatus] || ['N/A', 'status-pending'];
                    statusBadge.textContent = label;
                    statusBadge.className = 'status-badge ' + cls;
                    statusBadge.dataset.status = newStatus;
                    alert('Cập nhật trạng thái thành công!');
                } else {
                    alert('Cập nhật thất bại');
                }
                document.getElementById('statusModal').classList.remove('active');
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi khi cập nhật trạng thái');
            });
        });

        // Delete order
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!confirm('Bạn có chắc muốn xóa đơn hàng này?')) return;

                const orderId = this.dataset.orderId;
                const formData = new FormData();
                formData.append('order_id', orderId);

                fetch('<?php echo ROOT_URL; ?>admin/orders/delete', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json ? res.json() : res)
                .then(() => {
                    alert('Xóa đơn hàng thành công');
                    location.reload();
                })
                .catch(err => {
                    console.error(err);
                    alert('Lỗi khi xóa');
                });
            });
        });
    </script>
</body>

</html>