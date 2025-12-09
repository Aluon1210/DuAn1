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
                            <label for="filter-sort">Sắp xếp theo ngày</label>
                            <select id="filter-sort" name="sort">
                                <option value="desc" <?php echo ($data['filter']['sort'] === 'desc') ? 'selected' : ''; ?>>Z-A (Mới nhất)</option>
                                <option value="asc" <?php echo ($data['filter']['sort'] === 'asc') ? 'selected' : ''; ?>>A-Z (Cũ nhất)</option>
                            </select>
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
                                <th width="12%">Mã Đơn Hàng</th>
                                <th width="12%">Người nhận</th>
                                <th width="10%">SDT</th>
                                <th width="10%">Tổng tiền</th>
                                <th width="8%">Số lượng</th>
                                <th width="13%">Trạng thái</th>
                                <th width="10%">Ngày đặt hàng</th>
                                <th width="12%">Ghi chú</th>
                                <th width="13%">Địa chỉ giao hàng</th>
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
                                    $customerName = $order['user_name'] ?? 'Không xác định';
                                    $customerPhone = $order['user_phone'] ?? 'N/A';
                                    $address = $order['Adress'] ?? 'Không có địa chỉ';
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($order['Order_Id']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($customerName); ?></td>
                                    <td><?php echo htmlspecialchars($customerPhone); ?></td>
                                    <td><strong><?php echo number_format((float)($order['total'] ?? 0), 0, ',', '.'); ?> ₫</strong></td>
                                    <td><?php echo (int)($order['items_count'] ?? 0); ?></td>
                                    <td>
                                        <?php
                                        // Chỉ cho phép chọn trạng thái kế tiếp, hủy khi chờ xác nhận, trả hàng khi đã hoàn thành
                                        $nextStatus = [
                                            'pending' => 'confirmed',
                                            'confirmed' => 'shipping',
                                            'shipping' => 'delivered',
                                        ];
                                        $canCancel = ($status === 'pending');
                                        $canReturn = ($status === 'delivered');
                                        $isFinal = ($status === 'delivered' || $status === 'cancelled');
                                        ?>
                                        <select class="status-select" data-order-id="<?php echo htmlspecialchars($order['Order_Id']); ?>" data-current-status="<?php echo htmlspecialchars($status); ?>" <?php echo $isFinal ? 'disabled' : ''; ?>>
                                            <option value="<?php echo $status; ?>" selected><?php echo $statusLabel; ?></option>
                                            <?php if (!$isFinal && isset($nextStatus[$status])): ?>
                                                <option value="<?php echo $nextStatus[$status]; ?>"><?php echo $statusMap[$nextStatus[$status]][0]; ?></option>
                                            <?php endif; ?>
                                            <?php if ($canCancel): ?>
                                                <option value="cancelled">Đã hủy</option>
                                            <?php endif; ?>
                                            <?php if ($canReturn): ?>
                                                <option value="return">Trả hàng</option>
                                            <?php endif; ?>
                                        </select>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($order['Order_date'])); ?></td>
                                    <td><small><?php echo htmlspecialchars($order['Note'] ?? 'Không có ghi chú'); ?></small></td>
                                    <td><small><?php echo htmlspecialchars($address); ?></small></td>
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
                <div class="pagination" id="orders-pagination">
                    <?php
                        $page = (int)$data['pagination']['page'];
                        $totalPages = (int)$data['pagination']['totalPages'];
                        $status = $data['filter']['status'] ?? '';
                        $q = $data['filter']['q'] ?? '';
                        $perPage = $data['pagination']['perPage'] ?? '';
                        $sort = $data['filter']['sort'] ?? 'desc';

                        // Helper to build url preserving filters
                        $buildUrl = function($p) use ($status, $q, $perPage, $sort) {
                            $params = ['page' => $p];
                            if ($status !== '') $params['status'] = $status;
                            if ($q !== '') $params['q'] = $q;
                            if ($perPage !== '') $params['perPage'] = $perPage;
                            if ($sort !== 'desc') $params['sort'] = $sort;
                            return ROOT_URL . 'admin/orders?' . http_build_query($params);
                        };

                        // First and Previous
                        if ($page > 1) {
                            echo '<a href="' . htmlspecialchars($buildUrl(1)) . '">« Đầu</a>';
                            echo '<a href="' . htmlspecialchars($buildUrl($page - 1)) . '">&lsaquo; Trước</a>';
                        }

                        // Determine window of pages to show (centered on current)
                        $window = 5; // show up to 5 page numbers
                        $half = (int)floor($window / 2);
                        $start = max(1, $page - $half);
                        $end = min($totalPages, $start + $window - 1);
                        if ($end - $start + 1 < $window) {
                            $start = max(1, $end - $window + 1);
                        }

                        if ($start > 1) {
                            echo '<a href="' . htmlspecialchars($buildUrl(1)) . '">1</a>';
                            if ($start > 2) echo '<span>...</span>';
                        }

                        for ($i = $start; $i <= $end; $i++) {
                            if ($i == $page) {
                                echo '<span class="current">' . $i . '</span>';
                            } else {
                                echo '<a href="' . htmlspecialchars($buildUrl($i)) . '">' . $i . '</a>';
                            }
                        }

                        if ($end < $totalPages) {
                            if ($end < $totalPages - 1) echo '<span>...</span>';
                            echo '<a href="' . htmlspecialchars($buildUrl($totalPages)) . '">' . $totalPages . '</a>';
                        }

                        // Next and Last
                        if ($page < $totalPages) {
                            echo '<a href="' . htmlspecialchars($buildUrl($page + 1)) . '">Tiếp &rsaquo;</a>';
                            echo '<a href="' . htmlspecialchars($buildUrl($totalPages)) . '">Cuối »</a>';
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

        // Thêm event listener để mở modal khi nhấn vào hàng
        document.querySelectorAll('tbody tr').forEach(row => {
            row.style.cursor = 'pointer';
            row.addEventListener('click', function(e) {
                // Không mở modal nếu nhấn vào status select
                if (e.target.classList.contains('status-select')) return;
                
                const orderId = this.querySelector('td:first-child strong').textContent;
                openOrderDetailModal(orderId);
            });
        });

        function openOrderDetailModal(orderId) {
            const url = '<?php echo ROOT_URL; ?>admin/orders/detail/' + encodeURIComponent(orderId);
            console.log('Fetching from:', url);
            
            fetch(url)
                .then(res => {
                    console.log('Response status:', res.status);
                    if (!res.ok) {
                        throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                    }
                    return res.text(); // Get text first to inspect
                })
                .then(text => {
                    console.log('Response text:', text);
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            displayOrderDetailModal(data);
                        } else {
                            alert('Lỗi: ' + (data.error || 'Không tìm thấy đơn hàng'));
                        }
                    } catch(e) {
                        console.error('JSON parse error:', e);
                        alert('Lỗi: Dữ liệu không hợp lệ');
                    }
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    alert('Lỗi: ' + err.message);
                });
        }

        function displayOrderDetailModal(data) {
            try {
                const order = data.order || {};
                const details = data.details || [];
                const total = data.total || 0;

                let productsHtml = '';
                details.forEach(item => {
                    const qty = parseInt(item.quantity) || 0;
                    const price = parseFloat(item.Price) || 0;
                    const subtotal = qty * price;
                    const colorStr = item.color_name ? `<span style="display:inline-block;width:14px;height:14px;border-radius:50%;background-color:${item.color_hex||'#ccc'};border:1px solid #ddd;margin-right:6px;vertical-align:middle;"></span>${item.color_name}` : '';
                    const sizeStr = item.size_name ? `Size: ${item.size_name}` : '';
                    const variantInfo = [colorStr, sizeStr].filter(s => s).join(' | ');
                    
                    productsHtml += `
                        <tr>
                            <td style="padding:10px 6px;border-bottom:1px solid #f0f0f0;width:40%;word-break:break-word;font-size:12px;">${item.product_name || ''}</td>
                            <td style="padding:10px 6px;text-align:center;border-bottom:1px solid #f0f0f0;width:15%;white-space:nowrap;font-size:12px;">${qty}</td>
                            <td style="padding:10px 6px;text-align:right;border-bottom:1px solid #f0f0f0;width:22.5%;white-space:nowrap;font-size:12px;">${number_format(price)} ₫</td>
                            <td style="padding:10px 6px;text-align:right;border-bottom:1px solid #f0f0f0;width:22.5%;white-space:nowrap;color:#d4af37;font-weight:700;font-size:12px;">${number_format(subtotal)} ₫</td>
                        </tr>
                        ${variantInfo ? `<tr><td colspan="4" style="padding:6px 6px;font-size:11px;color:#999;border-bottom:1px solid #f0f0f0;"><em>${variantInfo}</em></td></tr>` : ''}
                    `;
                });

                const statusMap = {
                    'pending': 'Chờ xác nhận',
                    'confirmed': 'Chờ giao hàng',
                    'shipping': 'Vận chuyển',
                    'delivered': 'Hoàn thành',
                    'cancelled': 'Đã hủy',
                    'return': 'Trả hàng'
                };

                const modalHtml = `
                    <div class="__ei_backdrop" style="position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.7);display:flex;align-items:center;justify-content:center;z-index:9999;padding:20px;">
                        <div class="__ei_modal" style="background:white;border-radius:12px;width:100%;max-width:850px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,0.4);">
                            
                            <!-- HEADER GOLD -->
                            <div style="background:linear-gradient(135deg, #d4af37 0%, #c9a327 100%);color:#1a1a1a;padding:20px 24px;border-radius:12px 12px 0 0;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:10;">
                                <h2 style="margin:0;font-size:22px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">HÓA ĐƠN GIAO DỊCH #${order.Order_Id || 'N/A'}</h2>
                                <button class="__ei_close" style="background:none;border:none;font-size:28px;cursor:pointer;color:#1a1a1a;padding:0;width:32px;height:32px;display:flex;align-items:center;justify-content:center;">×</button>
                            </div>

                            <div style="padding:24px;">
                                
                                <!-- INFO GRID -->
                                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;background:#f9f9f9;padding:16px;border-radius:8px;">
                                    <div>
                                        <p style="margin:0 0 6px;font-size:11px;color:#999;text-transform:uppercase;font-weight:700;letter-spacing:0.5px;">Ngày đặt hàng</p>
                                        <p style="margin:0;font-size:15px;font-weight:700;color:#222;">${new Date(order.Order_date).toLocaleDateString('vi-VN')}</p>
                                    </div>
                                    <div>
                                        <p style="margin:0 0 6px;font-size:11px;color:#999;text-transform:uppercase;font-weight:700;letter-spacing:0.5px;">Trạng thái</p>
                                        <p style="margin:0;font-size:15px;font-weight:700;color:#d4af37;">${statusMap[order.TrangThai] || order.TrangThai || 'N/A'}</p>
                                    </div>
                                    <div>
                                        <p style="margin:0 0 6px;font-size:11px;color:#999;text-transform:uppercase;font-weight:700;letter-spacing:0.5px;">Khách hàng</p>
                                        <p style="margin:0;font-size:15px;font-weight:700;color:#222;">${order.user_name || 'N/A'}</p>
                                    </div>
                                    <div>
                                        <p style="margin:0 0 6px;font-size:11px;color:#999;text-transform:uppercase;font-weight:700;letter-spacing:0.5px;">Điện thoại</p>
                                        <p style="margin:0;font-size:15px;font-weight:700;color:#222;">${order.user_phone || 'N/A'}</p>
                                    </div>
                                </div>

                                <!-- ADDRESS & NOTE -->
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
                                    <div>
                                        <p style="margin:0 0 8px;font-size:11px;color:#999;text-transform:uppercase;font-weight:700;letter-spacing:0.5px;">Địa chỉ nhận hàng</p>
                                        <p style="margin:0;font-size:14px;background:#fff;border:2px solid #d4af37;padding:12px;border-radius:6px;line-height:1.6;color:#333;">${order.Adress || 'Không có'}</p>
                                    </div>
                                    <div>
                                        <p style="margin:0 0 8px;font-size:11px;color:#999;text-transform:uppercase;font-weight:700;letter-spacing:0.5px;">Ghi chú</p>
                                        <p style="margin:0;font-size:14px;background:#fff;border:2px solid #d4af37;padding:12px;border-radius:6px;line-height:1.6;color:${order.Note ? '#333' : '#999'};">${order.Note || '(Không có ghi chú)'}</p>
                                    </div>
                                </div>

                                <!-- PRODUCTS TABLE -->
                                <p style="margin:0 0 12px;font-size:13px;font-weight:700;text-transform:uppercase;color:#d4af37;letter-spacing:0.5px;">CHI TIẾT SẢN PHẨM</p>
                                <div style="margin-bottom:24px;width:100%;overflow:hidden;">
                                    <table style="width:100%;border-collapse:collapse;font-size:12px;table-layout:fixed;">
                                        <thead>
                                            <tr style="background:#000;color:#d4af37;text-transform:uppercase;font-weight:700;">
                                                <th style="padding:12px 6px;text-align:left;width:40%;font-size:12px;">Sản phẩm</th>
                                                <th style="padding:12px 6px;text-align:center;width:15%;font-size:12px;">SL</th>
                                                <th style="padding:12px 6px;text-align:right;width:22.5%;white-space:nowrap;font-size:12px;">Đơn giá</th>
                                                <th style="padding:12px 6px;text-align:right;width:22.5%;white-space:nowrap;font-size:12px;">Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>${productsHtml}</tbody>
                                    </table>
                                </div>

                                <!-- TOTAL -->
                                <div style="border-top:2px solid #d4af37;border-bottom:2px solid #d4af37;padding:20px;text-align:right;margin-bottom:20px;">
                                    <p style="margin:0 0 8px;font-size:13px;color:#666;">Tổng giá trị đơn hàng:</p>
                                    <p style="margin:0;font-size:28px;font-weight:700;color:#d4af37;font-family:'Playfair Display',serif;">${number_format(total)} ₫</p>
                                </div>

                                <!-- THANK YOU -->
                                <div style="text-align:center;padding:16px 0;border-top:1px dashed #d4af37;">
                                    <p style="margin:0;font-size:13px;color:#666;line-height:1.6;">Cảm ơn quý khách đã tin tưởng và mua sắm tại <strong>*LUXURY FASHION*</strong></p>
                                    <p style="margin:6px 0 0 0;font-size:12px;color:#999;">Mọi thắc mắc vui lòng liên hệ Bộ phận Chăm sóc Khách hàng</p>
                                </div>

                            </div>
                        </div>
                    </div>
                `;

                // Thêm modal vào body
                const modal = document.createElement('div');
                modal.innerHTML = modalHtml;
                document.body.appendChild(modal);

                // Close modal when clicking on backdrop
                const backdrop = modal.querySelector('.__ei_backdrop');
                const closeBtn = modal.querySelector('.__ei_close');

                function removeModal() {
                    if (modal && modal.parentNode) modal.parentNode.removeChild(modal);
                    document.removeEventListener('keydown', onKeyDown);
                }

                backdrop.addEventListener('click', function(e){
                    if (e.target === backdrop) removeModal();
                });

                closeBtn.addEventListener('click', removeModal);

                // Close on Escape
                function onKeyDown(e){ if (e.key === 'Escape') removeModal(); }
                document.addEventListener('keydown', onKeyDown);
            } catch(err) {
                console.error('Error displaying modal:', err);
                alert('Lỗi: Không thể hiển thị chi tiết đơn hàng');
            }
        }

        function number_format(n) {
            return new Intl.NumberFormat('vi-VN').format(n);
        }
    </script>
</body>

</html>
