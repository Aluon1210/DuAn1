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

    body { background: var(--bg); color: var(--text); }

    .admin-content { max-width: 1400px; width: 100%; margin: 32px auto; padding: 0 20px; }

    .page-title { font-size: 24px; font-weight: 800; margin: 0 0 18px; }

    .card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        margin-bottom: 18px;
    }

    .table-container { border-radius: 12px; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 800px; }
    thead { background: #f4f4f4; border-bottom: 2px solid #ddd; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
    tbody tr:hover { background: #fafafa; }

    .panel-grid { display: grid; grid-template-columns: repeat(2, minmax(360px, 1fr)); gap: 18px; align-items: start; }
    @media (max-width: 900px) { .panel-grid { grid-template-columns: 1fr; } }

    .panel-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
    .panel-title { font-weight: 700; }
    .controls { display:flex; gap:10px; align-items:center; }
    .controls label { font-size: 13px; color: var(--muted); }
    .controls input[type="date"], .controls select { padding: 6px 8px; border: 1px solid #ddd; border-radius: 8px; font-size: 13px; }
    .controls button { padding: 6px 12px; border-radius: 8px; border: 1px solid #ddd; background: #f5f5f5; cursor: pointer; }
    .controls button:hover { background: #ececec; }
    .helper-text { font-size: 12px; color: var(--muted); margin-bottom: 8px; }
</style>

<body>
    <div class="admin-container">
        <?php include __DIR__ . '/aside.php'; ?>

        <main class="admin-content">
            <h2 class="page-title">Thống kê tổng quan</h2>
            <div class="helper-text">Dữ liệu thống kê dựa trên đơn hàng Hoàn thành</div>

            

            <section class="card">
                <h3>Doanh thu theo tuần & tháng - <?php echo $data['year']; ?></h3>

                <div class="panel-grid">
                    <!-- Revenue by custom date range panel -->
                    <div class="card">
                        <div class="panel-header">
                            <div class="panel-title">Doanh thu theo khoảng ngày</div>
                            <div class="controls">
                                <label>Từ ngày</label>
                                <input type="date" id="range-start" value="<?php echo htmlspecialchars($data['rangeStart'] ?? ''); ?>" />
                                <label>Đến ngày</label>
                                <input type="date" id="range-end" value="<?php echo htmlspecialchars($data['rangeEnd'] ?? ''); ?>" />
                                <button type="button" id="apply-range">Xem</button>
                            </div>
                        </div>
                        <?php if (!empty($data['rangeStart']) && !empty($data['rangeEnd'])): ?>
                            <div class="helper-text">Khoảng: <?php echo htmlspecialchars($data['rangeStart']); ?> → <?php echo htmlspecialchars($data['rangeEnd']); ?></div>
                        <?php endif; ?>
                        <canvas id="chart-weekly" width="600" height="260"></canvas>
                    </div>

                    <!-- Monthly revenue panel -->
                    <div class="card">
                        <div class="panel-header">
                            <div class="panel-title">Doanh thu theo tháng</div>
                            <div class="controls">
                                <label>Tháng</label>
                                <select class="select-month" data-target="monthly">
                                    <option value="">-- Chọn tháng --</option>
                                    <?php foreach (($data['monthOptions'] ?? []) as $m): ?>
                                        <option value="<?php echo htmlspecialchars($m); ?>" <?php echo (!empty($data['selectedMonth']) && $data['selectedMonth'] === $m) ? 'selected' : ''; ?>><?php echo htmlspecialchars($m); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <canvas id="chart-monthly" width="600" height="260"></canvas>
                    </div>

                    <!-- Top products panel -->
                    <div class="card">
                        <div class="panel-header">
                            <div class="panel-title">Top 5 sản phẩm bán chạy</div>
                        </div>
                        <canvas id="chart-top-products" width="600" height="240"></canvas>
                    </div>

                    <!-- Top customers panel -->
                    <div class="card">
                        <div class="panel-header">
                            <div class="panel-title">Top 5 khách hàng theo doanh số</div>
                        </div>
                        <canvas id="chart-top-customers" width="600" height="240"></canvas>
                    </div>
                </div>
                <?php if (!empty($data['topWorst'])): ?>
                    <div class="card">
                        <h3>Top 5 sản phẩm bán ế (ít bán nhất)</h3>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Sản phẩm</th>
                                        <th>Số lượng bán</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=1; foreach ($data['topWorst'] as $w): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo htmlspecialchars($w['name'] ?? $w['id']); ?></td>
                                        <td><?php echo (int)($w['total_sold'] ?? 0); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

            <?php if (!empty($data['topCustomers'])): ?>
            <section class="card">
                <h3>Top 5 khách hàng theo doanh số (<?php echo $data['year']; ?>)</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Khách hàng</th>
                                <th>Email</th>
                                <th>Đơn hàng</th>
                                <th>Tổng doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($data['topCustomers'] as $c): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($c['name'] ?? $c['id']); ?></td>
                                    <td><?php echo htmlspecialchars($c['email'] ?? ''); ?></td>
                                    <td><?php echo (int)($c['orders_count'] ?? 0); ?></td>
                                    <td><?php echo number_format($c['total_revenue'] ?? 0, 0, ',', '.'); ?> ₫</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            <?php endif; ?>

            <?php if (!empty($data['topProducts'])): ?>
            <section class="card">
                <h3>Top 5 sản phẩm bán chạy (<?php echo $data['year']; ?>)</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sản phẩm</th>
                                <th>Số lượng bán</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($data['topProducts'] as $p): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($p['name'] ?? $p['id']); ?></td>
                                    <td><?php echo (int)($p['total_sold'] ?? 0); ?></td>
                                    <td><?php echo number_format($p['total_revenue'] ?? 0, 0, ',', '.'); ?> ₫</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Prepare datasets from PHP
// weekly: if a per-day week was selected, use per-day values (7 days). Otherwise show aggregated weekly points
const weeklyPerDay = <?php echo json_encode(array_values($data['weeklyPerDay'] ?? [])); ?>;
const weeklyPerDayLabels = <?php echo json_encode(array_keys($data['weeklyPerDay'] ?? [])); ?>;

const weeklyAggregatedLabels = <?php echo json_encode(array_keys($data['revenueByWeek'] ?? [])); ?>;
const weeklyAggregatedData = <?php echo json_encode(array_values($data['revenueByWeek'] ?? [])); ?>;

// monthly: per-day for selected month (30 days) or aggregated by month
const monthlyPerDay = <?php echo json_encode(array_values($data['monthlyPerDay'] ?? [])); ?>;
const monthlyPerDayLabels = <?php echo json_encode(array_keys($data['monthlyPerDay'] ?? [])); ?>;

const monthlyAggregatedLabels = <?php echo json_encode(array_map(function($m){return 'Tháng ' . $m;}, array_keys($data['revenueByMonth'] ?? []))); ?>;
const monthlyAggregatedData = <?php echo json_encode(array_values($data['revenueByMonth'] ?? [])); ?>;

const topProductLabels = <?php echo json_encode(array_map(function($p){return $p['name'] ?? $p['id'];}, $data['topProductsPeriod'] ?? $data['topProducts'] ?? [])); ?>;
const topProductData = <?php echo json_encode(array_map(function($p){return (int)($p['total_sold'] ?? 0);}, $data['topProductsPeriod'] ?? $data['topProducts'] ?? [])); ?>;

const topCustomerLabels = <?php echo json_encode(array_map(function($c){return $c['name'] ?? $c['id'];}, $data['topCustomersPeriod'] ?? $data['topCustomers'] ?? [])); ?>;
const topCustomerData = <?php echo json_encode(array_map(function($c){return (float)($c['total_revenue'] ?? 0);}, $data['topCustomersPeriod'] ?? $data['topCustomers'] ?? [])); ?>;

function makeLineChart(ctx, labels, data, labelText){
    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: labelText,
                data: data,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
}

function makeBarChart(ctx, labels, data, labelText){
    return new Chart(ctx, {
        type: 'bar',
        data: { labels: labels, datasets: [{ label: labelText, data: data, backgroundColor: 'rgba(54, 162, 235, 0.7)'}] },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
}

    // weekly: prefer per-day if selected; otherwise aggregated series
    const ctxWeek = document.getElementById('chart-weekly');
    if (ctxWeek) {
        if (weeklyPerDay && weeklyPerDay.length > 0) {
            // show dates as labels
            const labels = weeklyPerDayLabels.map(l => l.replace(/^(\d{4})-(\d{2})-(\d{2})$/, '$3/$2'));
            makeLineChart(ctxWeek, labels, weeklyPerDay, 'Doanh thu (VNĐ)');
        } else {
            makeLineChart(ctxWeek, weeklyAggregatedLabels, weeklyAggregatedData, 'Doanh thu (VNĐ)');
        }
    }

    // monthly: per day (30 days) if selected or aggregated by month
    const ctxMonth = document.getElementById('chart-monthly');
    if (ctxMonth) {
        if (monthlyPerDay && monthlyPerDay.length > 0) {
            const labels = monthlyPerDayLabels.map(l => l.replace(/^(\d{4})-(\d{2})-(\d{2})$/, '$3/$2'));
            makeLineChart(ctxMonth, labels, monthlyPerDay, 'Doanh thu (VNĐ)');
        } else {
            makeLineChart(ctxMonth, monthlyAggregatedLabels, monthlyAggregatedData, 'Doanh thu (VNĐ)');
        }
    }

const rangeStartInput = document.getElementById('range-start');
const rangeEndInput = document.getElementById('range-end');
const applyBtn = document.getElementById('apply-range');
if (applyBtn) {
    applyBtn.addEventListener('click', function(){
        const s = rangeStartInput && rangeStartInput.value ? rangeStartInput.value : '';
        const e = rangeEndInput && rangeEndInput.value ? rangeEndInput.value : '';
        if (!s || !e) { alert('Vui lòng chọn đủ ngày bắt đầu và kết thúc'); return; }
        if (s > e) { alert('Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc'); return; }
        window.location.href = window.location.pathname + '?start=' + encodeURIComponent(s) + '&end=' + encodeURIComponent(e);
    });
}

// top products
const ctxTopProd = document.getElementById('chart-top-products');
if(ctxTopProd){ makeBarChart(ctxTopProd, topProductLabels, topProductData, 'Số lượng bán'); }

// top customers
const ctxTopCust = document.getElementById('chart-top-customers');
if(ctxTopCust){ makeBarChart(ctxTopCust, topCustomerLabels, topCustomerData, 'Doanh thu (VNĐ)'); }

// selector behaviour: attach to all per-panel selects
document.querySelectorAll('.select-week').forEach(function(el){
    el.addEventListener('change', function(e){
        const v = e.target.value;
        const params = new URLSearchParams(window.location.search);
        if (v) { params.set('week', v); } else { params.delete('week'); }
        const qs = params.toString();
        window.location.href = window.location.pathname + (qs ? '?' + qs : '');
    });
});
document.querySelectorAll('.select-month').forEach(function(el){
    el.addEventListener('change', function(e){
        const v = e.target.value;
        const params = new URLSearchParams(window.location.search);
        if (v) { params.set('month', v); } else { params.delete('month'); }
        const qs = params.toString();
        window.location.href = window.location.pathname + (qs ? '?' + qs : '');
    });
});
</script>
