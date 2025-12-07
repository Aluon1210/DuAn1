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
        --primary: #007bff;
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

    .card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 28px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 32px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 18px;
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
    .form-group select,
    .form-group textarea {
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.2s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: var(--gold);
        outline: none;
    }

    .form-actions {
        margin-top: 20px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
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
    }

    .btn-success { background: var(--success); color: #fff; }
    .btn-success:hover { background: #218838; }
    .btn-cancel { background: var(--danger); color: #fff; }
    .btn-cancel:hover { background: #c82333; }
    .btn-warning { background: #ffc107; color: #111; }
    .btn-warning:hover { background: #e0a800; }

    .stats {
        display: flex;
        gap: 18px;
        flex-wrap: wrap;
    }

    .stat-item {
        flex: 1;
        min-width: 220px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .stat-item h4 {
        margin: 0;
        font-size: 14px;
        text-transform: uppercase;
        color: var(--muted);
        letter-spacing: 0.5px;
    }

    .stat-item p {
        margin: 8px 0 0;
        font-size: 28px;
        font-weight: 700;
        color: var(--black);
    }

    .table-container {
        background: #fff;
        border-radius: 12px;
        overflow-x: auto;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1100px;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    thead {
        background-color: #f4f4f4;
        border-bottom: 2px solid #ddd;
    }

    tbody tr:hover {
        background-color: #fafafa;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        border: 1px solid var(--border);
        background: #f9f9f9;
    }

    .empty-message {
        padding: 24px;
        text-align: center;
        color: var(--muted);
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

            <section class="stats">
                <div class="stat-item">
                    <h4>Biến thể</h4>
                    <p><?php echo (int)($data['totalVariants'] ?? 0); ?></p>
                </div>
                <div class="stat-item">
                    <h4>Sản phẩm</h4>
                    <p><?php echo isset($data['products']) ? count($data['products']) : 0; ?></p>
                </div>
                <div class="stat-item">
                    <h4>Màu sắc</h4>
                    <p><?php echo isset($data['colors']) ? count($data['colors']) : 0; ?></p>
                </div>
                <div class="stat-item">
                    <h4>Kích cỡ</h4>
                    <p><?php echo isset($data['sizes']) ? count($data['sizes']) : 0; ?></p>
                </div>
            </section>

            <div class="card">
                <h2><?php echo !empty($data['editing']) ? 'Cập nhật biến thể' : 'Thêm biến thể mới'; ?></h2>
                <form method="POST" action="<?php echo ROOT_URL; ?>admin/saveProductVariant">
                    <?php if (!empty($data['editing']) && isset($data['variant']['id'])): ?>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['variant']['id']); ?>">
                    <?php endif; ?>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="variant-product">Sản phẩm *</label>
                            <select id="variant-product" name="product_id" required>
                                <option value="">-- Chọn sản phẩm --</option>
                                <?php if (!empty($data['products'])): ?>
                                    <?php foreach ($data['products'] as $product): ?>
                                            <option value="<?php echo htmlspecialchars($product['id']); ?>"
                                                data-price="<?php echo (int)($product['price'] ?? 0); ?>"
                                                <?php echo (!empty($data['variant']['product_id']) && $data['variant']['product_id'] === $product['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($product['name']); ?>
                                            </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="variant-name">Tên biến thể</label>
                            <input id="variant-name" type="text" name="variant_name" placeholder="Ví dụ: Đen / Size 40"
                                   value="<?php echo htmlspecialchars($data['editing'] && isset($data['variant']) ? $data['variant']['variant_name'] : ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="variant-color">Màu sắc</label>
                            <select id="variant-color" name="color_id">
                                <option value="">-- Tất cả màu --</option>
                                <?php if (!empty($data['colors'])): ?>
                                    <?php foreach ($data['colors'] as $color): ?>
                                        <option value="<?php echo htmlspecialchars($color['id']); ?>"
                                            <?php echo (!empty($data['variant']['color_id']) && $data['variant']['color_id'] === $color['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($color['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="variant-size">Kích cỡ</label>
                            <select id="variant-size" name="size_id">
                                <option value="">-- Tất cả size --</option>
                                <?php if (!empty($data['sizes'])): ?>
                                    <?php foreach ($data['sizes'] as $size): ?>
                                        <option value="<?php echo htmlspecialchars($size['id']); ?>"
                                            <?php echo (!empty($data['variant']['size_id']) && $data['variant']['size_id'] === $size['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($size['value']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="variant-sku">SKU</label>
                            <input id="variant-sku" type="text" name="sku" placeholder="SKU riêng cho biến thể"
                                   value="<?php echo htmlspecialchars($data['editing'] && isset($data['variant']) ? $data['variant']['sku'] : ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="variant-price">Giá (VNĐ)</label>
                            <input id="variant-price" type="number" name="price" min="0" step="1000"
                                   value="<?php echo htmlspecialchars($data['editing'] && isset($data['variant']) ? $data['variant']['price'] : 0); ?>">
                        </div>

                        <div class="form-group">
                            <label for="variant-stock">Tồn kho</label>
                            <input id="variant-stock" type="number" name="stock" min="0"
                                   value="<?php echo htmlspecialchars($data['editing'] && isset($data['variant']) ? $data['variant']['stock'] : 0); ?>">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">
                            <?php echo !empty($data['editing']) ? 'Cập nhật biến thể' : 'Thêm biến thể'; ?>
                        </button>
                        <a href="<?php echo ROOT_URL; ?>admin/productVariants" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>

            <section>
                <div style="margin-bottom: 16px; display:flex; justify-content: space-between; align-items:center;">
                    <h2>Danh sách biến thể</h2>
                    <span style="color: var(--muted); font-size:14px;">
                        Nên duy trì SKU & thuộc tính rõ ràng để đồng bộ kho hàng.
                    </span>
                </div>

                <div class="table-container">
                    <?php if (!empty($data['variants'])): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th width="15%">Mã</th>
                                    <th width="20%">Sản phẩm</th>
                                    <th width="15%">Thuộc tính</th>
                                    <th width="10%">Màu</th>
                                    <th width="10%">Size</th>
                                    <th width="10%">Giá</th>
                                    <th width="10%">Tồn kho</th>
                                    <th width="10%">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['variants'] as $variant): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($variant['id']); ?></td>
                                        <td>
                                            <div style="font-weight:600;"><?php echo htmlspecialchars($variant['product_name'] ?? $variant['product_id']); ?></div>
                                            <div style="font-size:13px;color:var(--muted);">SKU: <?php echo htmlspecialchars($variant['sku'] ?? 'N/A'); ?></div>
                                        </td>
                                        <td><?php echo htmlspecialchars($variant['variant_name'] ?? ''); ?></td>
                                        <td>
                                            <?php if (!empty($variant['color_name'])): ?>
                                                <span class="badge">
                                                    <?php echo htmlspecialchars($variant['color_name']); ?>
                                                </span>
                                            <?php else: ?>
                                                —
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo !empty($variant['size_value']) ? htmlspecialchars($variant['size_value']) : '—'; ?>
                                        </td>
                                        <td><?php echo number_format($variant['price'] ?? 0, 0, ',', '.'); ?> ₫</td>
                                        <td><?php echo (int)($variant['stock'] ?? 0); ?></td>
                                        <td>
                                            <a class="btn btn-warning" style="padding:6px 12px;"
                                               href="<?php echo ROOT_URL; ?>admin/editProductVariant/<?php echo urlencode($variant['id']); ?>">Sửa</a>
                                            <a class="btn btn-cancel" style="padding:6px 12px;"
                                               href="<?php echo ROOT_URL; ?>admin/deleteProductVariant/<?php echo urlencode($variant['id']); ?>"
                                               onclick="return confirm('Xóa biến thể này?');">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-message">
                            Chưa có biến thể nào. Hãy bắt đầu tạo biến thể theo màu sắc và kích cỡ.
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</body>

</html>

<script>
document.addEventListener('DOMContentLoaded', function(){
    var prodSelect = document.getElementById('variant-product');
    var priceInput = document.getElementById('variant-price');
    var isEditing = <?php echo !empty($data['editing']) ? 'true' : 'false'; ?>;

    function applyPriceFromSelected(){
        if(!prodSelect) return;
        var opt = prodSelect.selectedOptions && prodSelect.selectedOptions[0];
        if(!opt) return;
        var p = opt.getAttribute('data-price');
        var priceVal = p ? parseInt(p, 10) : 0;
        var current = priceInput ? parseInt(priceInput.value || '0', 10) : 0;
        // if not editing or current price is zero, set the input
        if(priceInput && (!isEditing || current === 0)){
            priceInput.value = priceVal;
        }
    }

    if(prodSelect){
        prodSelect.addEventListener('change', applyPriceFromSelected);
        // initial apply
        applyPriceFromSelected();
    }
});
</script>

