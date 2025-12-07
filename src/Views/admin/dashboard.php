<!DOCTYPE html>
<html lang="vi">

<?php include __DIR__ . '/head.php'; ?>

<body>
    <div class="admin-container">
        <?php include __DIR__ . '/aside.php'; ?>

        <main class="admin-content">
            <h2>Thống kê tổng quan</h2>

            <?php if (!empty($data['topCustomers'])): ?>
            <section class="card" style="margin-bottom:18px;">
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
            <section class="card" style="margin-bottom:18px;">
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

            <section class="card">
                <h3>Doanh thu theo tuần & tháng - <?php echo $data['year']; ?></h3>

                <style>
                    .dashboard-panels { display: grid; grid-template-columns: repeat(2, minmax(360px, 1fr)); gap: 18px; align-items: start; }
                    @media (max-width: 900px) { .dashboard-panels { grid-template-columns: 1fr; } }
                </style>

                <div class="dashboard-panels">
                    <!-- Weekly revenue panel -->
                    <div class="card" style="padding:12px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                            <div style="font-weight:700;">Doanh thu theo tuần</div>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <label style="font-size:13px;">Tuần</label>
                                <select class="select-week" data-target="weekly" style="padding:6px;border-radius:6px;">
                                    <option value="">-- Chọn tuần --</option>
                                    <?php foreach (($data['weekOptions'] ?? []) as $w): ?>
                                        <option value="<?php echo htmlspecialchars($w); ?>" <?php echo (!empty($data['selectedWeek']) && $data['selectedWeek'] === $w) ? 'selected' : ''; ?>><?php echo htmlspecialchars($w); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <canvas id="chart-weekly" width="600" height="260"></canvas>
                    </div>

                    <!-- Monthly revenue panel -->
                    <div class="card" style="padding:12px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                            <div style="font-weight:700;">Doanh thu theo tháng</div>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <label style="font-size:13px;">Tháng</label>
                                <select class="select-month" data-target="monthly" style="padding:6px;border-radius:6px;">
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
                    <div class="card" style="padding:12px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                            <div style="font-weight:700;">Top 5 sản phẩm bán chạy</div>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <label style="font-size:13px;">Tuần/Tháng</label>
                                <select class="select-week" data-target="top-products" style="padding:6px;border-radius:6px;">
                                    <option value="">-- Chọn tuần --</option>
                                    <?php foreach (($data['weekOptions'] ?? []) as $w): ?>
                                        <option value="<?php echo htmlspecialchars($w); ?>"><?php echo htmlspecialchars($w); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select class="select-month" data-target="top-products" style="padding:6px;border-radius:6px;">
                                    <option value="">-- Chọn tháng --</option>
                                    <?php foreach (($data['monthOptions'] ?? []) as $m): ?>
                                        <option value="<?php echo htmlspecialchars($m); ?>"><?php echo htmlspecialchars($m); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <canvas id="chart-top-products" width="600" height="240"></canvas>
                    </div>

                    <!-- Top customers panel -->
                    <div class="card" style="padding:12px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                            <div style="font-weight:700;">Top 5 khách hàng theo doanh số</div>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <label style="font-size:13px;">Tuần/Tháng</label>
                                <select class="select-week" data-target="top-customers" style="padding:6px;border-radius:6px;">
                                    <option value="">-- Chọn tuần --</option>
                                    <?php foreach (($data['weekOptions'] ?? []) as $w): ?>
                                        <option value="<?php echo htmlspecialchars($w); ?>"><?php echo htmlspecialchars($w); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select class="select-month" data-target="top-customers" style="padding:6px;border-radius:6px;">
                                    <option value="">-- Chọn tháng --</option>
                                    <?php foreach (($data['monthOptions'] ?? []) as $m): ?>
                                        <option value="<?php echo htmlspecialchars($m); ?>"><?php echo htmlspecialchars($m); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <canvas id="chart-top-customers" width="600" height="240"></canvas>
                    </div>
                </div>

                <?php if (!empty($data['topWorst'])): ?>
                    <div style="margin-top:18px;">
                        <h4>Top 5 sản phẩm bán ế (ít bán nhất)</h4>
                        <div class="table-container" style="margin-top:8px;">
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
        if (v) window.location.href = window.location.pathname + '?week=' + encodeURIComponent(v);
        else window.location.href = window.location.pathname;
    });
});
document.querySelectorAll('.select-month').forEach(function(el){
    el.addEventListener('change', function(e){
        const v = e.target.value;
        if (v) window.location.href = window.location.pathname + '?month=' + encodeURIComponent(v);
        else window.location.href = window.location.pathname;
    });
});
</script>
