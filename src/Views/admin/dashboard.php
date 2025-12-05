<!DOCTYPE html>
<html lang="vi">

<style>
  /* ===================== BASE STYLES ===================== */
  html,
  body {
    margin: 0;
    padding: 0;
    font-family: 'Inter', Helvetica, Arial, sans-serif;
    background: #f8f9fa;
    color: #333;
    line-height: 1.6;
  }

  h1,
  h2,
  h3,
  h4,
  h5,
  h6 {
    font-family: 'Playfair Display', serif;
    color: #3b3024;
  }

  a {
    text-decoration: none;
    color: inherit;
  }

  /* ===================== HEADER ===================== */
  .header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100px;
    /* chiều cao header */
    background: #1B2127;
    color: #FFD700;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 80px;
    /* khoảng cách lề */
    border-bottom-left-radius: 12px;
    border-bottom-right-radius: 12px;
    /* box-shadow: 0 8px 25px rgba(0,0,0,0.5); */
    z-index: 1000;
  }

  .header .brand {
    display: flex;
    align-items: center;
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
  }

  .header .brand i {
    margin-right: 12px;
    font-size: 34px;
  }

  .header nav {
    display: flex;
    align-items: center;
    gap: 25px;
    padding-right: 200px;
    /* cách mép phải 50px */
  }

  .header nav a {
    color: #fff;
    font-weight: 500;
    padding: 12px 20px;
    border-radius: 10px;
    border: 1px solid rgba(255, 215, 0, 0.4);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.25);
    transition: all 0.2s ease;
  }

  .header nav a:hover {
    background: rgba(255, 215, 0, 0.1);
    transform: translateY(-2px);
  }

  /* ===================== SIDEBAR ===================== */
  aside.sidebar {
    position: fixed;
    top: 100px;
    /* bằng height header */
    left: 0;
    width: 260px;
    height: calc(100vh - 100px);
    /* phần còn lại */
    background: #fff;
    border-right: 3px solid #CD853F;
    padding: 20px;
    overflow-y: auto;
    box-shadow: 4px 0 15px rgba(0, 0, 0, 0.08);
    z-index: 999;
  }

  aside.sidebar::-webkit-scrollbar {
    width: 6px;
  }

  aside.sidebar::-webkit-scrollbar-track {
    background: #fff;
    border-radius: 10px;
  }

  aside.sidebar::-webkit-scrollbar-thumb {
    background: rgba(205, 133, 63, 0.4);
    border-radius: 10px;
  }

  .menu-item {
    display: block;
    padding: 14px 16px;
    margin-bottom: 10px;
    background: #e8e8e8;
    color: #000;
    font-weight: 500;
    border-radius: 12px;
    transition: all 0.2s ease;
  }

  .menu-item:hover {
    background: #dcdcdc;
    transform: translateX(6px) scale(1.02);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
  }

  .menu-item.active {
    background: #fffacd;
    color: #3b3024;
    font-weight: 600;
    border: 2px solid #cd853f;
    box-shadow: 0 4px 12px rgba(205, 133, 63, 0.35);
  }

  /* ===================== MAIN CONTENT ===================== */
  main.admin-content {
    margin-top: 100px;
    /* bằng height header */
    margin-left: calc(260px + 50px);
    /* sidebar + khoảng cách 50px */
    width: calc(100% - 260px - 50px);
    padding: 30px;
    box-sizing: border-box;
  }

  /* ===================== RESPONSIVE ===================== */
  @media (max-width: 768px) {
    .header {
      flex-direction: column;
      height: auto;
      padding: 15px 30px;
    }

    aside.sidebar {
      position: relative;
      top: 0;
      width: 100%;
      height: auto;
    }

    main.admin-content {
      margin-left: 0;
      margin-top: calc(100px + 20px);
      /* header cao hơn khi responsive */
      width: 100%;
      padding: 20px;
    }
  }

  /* Căn chỉnh body */
  /* ===================== BODY ===================== */
  body {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding-top: 100px;
    /* tránh header */
    transition: background 0.5s ease;
  }

  /* ===================== MAIN CONTENT ===================== */
  main.admin-content {
    margin-top: 100px;
    margin-left: calc(260px + 50px);
    width: calc(100% - 260px - 50px);
    padding: 30px;
    box-sizing: border-box;
    animation: fadeIn 0.8s ease-in-out;
  }

  /* Hiệu ứng xuất hiện */
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(15px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* ===================== CARD ===================== */
  .card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: translateY(-6px) scale(1.01);
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.18);
  }

  /* ===================== TABLE ===================== */
  .table-container table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
  }

  .table-container th,
  .table-container td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }

  .table-container th {
    background: #1B2127;
    color: #FFD700;
    font-weight: 600;
  }

  .table-container tr:hover {
    background: rgba(255, 215, 0, 0.08);
    transition: background 0.3s ease;
  }

  /* ===================== BUTTON ===================== */
  button {
    background: #FFD700;
    border: none;
    padding: 8px 14px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  button:hover {
    background: #e6c200;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  }

  /* ===================== Ô CHỌN TUẦN & THÁNG ===================== */
  #select-week,
  #select-month {
    padding: 10px 14px;
    border-radius: 10px;
    border: 1px solid #cd853f;
    background: #fff;
    color: #1B2127;
    font-weight: 500;
    min-width: 160px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
  }

  #select-week:hover,
  #select-month:hover {
    border-color: #b87333;
    box-shadow: 0 4px 12px rgba(205, 133, 63, 0.18);
  }

  #select-week:focus,
  #select-month:focus {
    outline: none;
    border-color: #FFD700;
    box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.3);
    transform: translateY(-1px);
  }

  /* ===================== LABEL ===================== */
  label {
    font-weight: 600;
    font-size: 15px;
    color: #3b3024;
    margin-right: 6px;
  }

  /* ===================== NÚT BỎ CHỌN ===================== */
  #btn-clear-period {
    background: linear-gradient(to right, #FFD700, #FFA500);
    color: #1B2127;
    font-weight: 600;
    padding: 10px 18px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
  }

  #btn-clear-period:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    opacity: 0.95;
  }
</style>

<body>
  <div class="admin-container">

    <header class="header">
      <div class="brand">
        <i class="fas fa-gem"></i> Luxury Admin
      </div>
      <nav>
        <a href="#">Trang chủ</a>
        <a href="#">Đăng xuất</a>
      </nav>
    </header>

    <!--  icon Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <aside class="sidebar">
      <a href="/DuAn1/admin/dashboard" class="menu-item active">
        Tổng quan </a>
      <a href="/DuAn1/admin/users" class="menu-item ">
        Người dùng </a>
      <a href="/DuAn1/admin/products" class="menu-item ">
        Sản phẩm </a>
      <a href="/DuAn1/admin/productVariants" class="menu-item ">
        Biến thể </a>
      <a href="/DuAn1/admin/colors" class="menu-item ">
        Màu sắc </a>
      <a href="/DuAn1/admin/sizes" class="menu-item ">
        Kích cỡ </a>
      <a href="/DuAn1/admin/orders" class="menu-item ">
        Đơn hàng </a>
      <a href="/DuAn1/admin/categories" class="menu-item ">
        Danh mục </a>
      <a href="/DuAn1/admin/branch" class="menu-item ">
        Hãng </a>
      <a href="/DuAn1/admin/comments" class="menu-item ">
        Bình luận </a>
      <a href="/DuAn1/admin/stats" class="menu-item ">
        Thống kê </a>
    </aside>
    <main class="admin-content">
      <h2>Thống kê tổng quan</h2>

      <section class="card">
        <div style="max-width:1100px; margin:0 auto;">

          <h3>Doanh thu theo tuần & tháng - 2025</h3>

          <div style="display:flex; gap:30px; width:100%; flex-wrap:nowrap;">

            <!-- ==== CỘT TUẦN ==== -->
            <div style="flex:1 1 50%; min-width:420px;">

              <!-- Chọn tuần -->
              <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                <label style="font-weight:600;">Chọn tuần:</label>
                <select id="select-week" style="padding:6px;border-radius:6px;">
                  <option value="">-- Chọn tuần --</option>
                  <option value="2025-W38">2025-W38</option>
                  <option value="2025-W39">2025-W39</option>
                  <option value="2025-W40">2025-W40</option>
                  <option value="2025-W41">2025-W41</option>
                  <option value="2025-W42">2025-W42</option>
                  <option value="2025-W43">2025-W43</option>
                  <option value="2025-W44">2025-W44</option>
                  <option value="2025-W45">2025-W45</option>
                  <option value="2025-W46">2025-W46</option>
                  <option value="2025-W47">2025-W47</option>
                  <option value="2025-W48">2025-W48</option>
                  <option value="2025-W49">2025-W49</option>
                </select>
              </div>

              <!-- Tiêu đề + biểu đồ -->
              <div style="margin-bottom:12px;font-weight:700;">Doanh thu theo tuần (mấy tuần gần nhất)</div>
              <canvas id="chart-weekly" width="600" height="260"></canvas>
            </div>


            <!-- ==== CỘT THÁNG ==== -->
            <div style="flex:1 1 50%; min-width:420px;">

              <!-- Chọn tháng -->
              <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                <label style="font-weight:600;">Chọn tháng:</label>
                <select id="select-month" style="padding:6px;border-radius:6px;">
                  <option value="">-- Chọn tháng --</option>
                  <option value="2025-12">2025-12</option>
                  <option value="2025-11">2025-11</option>
                  <option value="2025-10">2025-10</option>
                  <option value="2025-09">2025-09</option>
                  <option value="2025-08">2025-08</option>
                  <option value="2025-07">2025-07</option>
                  <option value="2025-06">2025-06</option>
                  <option value="2025-05">2025-05</option>
                  <option value="2025-04">2025-04</option>
                  <option value="2025-03">2025-03</option>
                  <option value="2025-02">2025-02</option>
                  <option value="2025-01">2025-01</option>
                </select>

                <button id="btn-clear-period" class="btn btn-small">Bỏ chọn</button>
              </div>

              <!-- Tiêu đề + biểu đồ -->
              <div style="margin-bottom:12px;font-weight:700;">Doanh thu theo tháng</div>
              <canvas id="chart-monthly" width="600" height="260"></canvas>
            </div>

          </div>

        </div>



        <div style="margin-top:18px; display:flex; gap:18px; flex-wrap:wrap;">
          <div style="flex:1; min-width:360px;">
            <h4>Top 5 sản phẩm bán chạy</h4>
            <canvas id="chart-top-products" width="600" height="240"></canvas>
          </div>

          <div style="flex:1; min-width:360px;">
            <h4>Top 5 khách hàng theo doanh số</h4>
            <canvas id="chart-top-customers" width="600" height="240"></canvas>
          </div>
        </div>

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
                <tr>
                  <td>1</td>
                  <td>Đồng hồ Speedmaster Moonwatch</td>
                  <td>0</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Đồng hồ Tank Must</td>
                  <td>0</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Giày Loafer Horsebit</td>
                  <td>0</td>
                </tr>
                <tr>
                  <td>4</td>
                  <td>Túi Speedy Monogram</td>
                  <td>0</td>
                </tr>
                <tr>
                  <td>5</td>
                  <td>Đầm Dạ Hội Ren Trắng</td>
                  <td>0</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Prepare datasets from PHP
  // weekly: if a per-day week was selected, use per-day values (7 days). Otherwise show aggregated weekly points
  const weeklyPerDay = [];
  const weeklyPerDayLabels = [];

  const weeklyAggregatedLabels = ["2025-W38", "2025-W39", "2025-W40", "2025-W41", "2025-W42", "2025-W43", "2025-W44", "2025-W45", "2025-W46", "2025-W47", "2025-W48", "2025-W49"];
  const weeklyAggregatedData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

  // monthly: per-day for selected month (30 days) or aggregated by month
  const monthlyPerDay = [];
  const monthlyPerDayLabels = [];

  const monthlyAggregatedLabels = ["Th\u00e1ng 1", "Th\u00e1ng 2", "Th\u00e1ng 3", "Th\u00e1ng 4", "Th\u00e1ng 5", "Th\u00e1ng 6", "Th\u00e1ng 7", "Th\u00e1ng 8", "Th\u00e1ng 9", "Th\u00e1ng 10", "Th\u00e1ng 11", "Th\u00e1ng 12"];
  const monthlyAggregatedData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

  const topProductLabels = [];
  const topProductData = [];

  const topCustomerLabels = [];
  const topCustomerData = [];

  function makeLineChart(ctx, labels, data, labelText) {
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

  function makeBarChart(ctx, labels, data, labelText) {
    return new Chart(ctx, {
      type: 'bar',
      data: { labels: labels, datasets: [{ label: labelText, data: data, backgroundColor: 'rgba(54, 162, 235, 0.7)' }] },
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
  if (ctxTopProd) { makeBarChart(ctxTopProd, topProductLabels, topProductData, 'Số lượng bán'); }

  // top customers
  const ctxTopCust = document.getElementById('chart-top-customers');
  if (ctxTopCust) { makeBarChart(ctxTopCust, topCustomerLabels, topCustomerData, 'Doanh thu (VNĐ)'); }

  // selector behaviour
  document.getElementById('select-week')?.addEventListener('change', function (e) {
    const v = e.target.value;
    if (v) location.href = new URL(window.location.href).origin + window.location.pathname + '?week=' + encodeURIComponent(v);
    else location.href = window.location.pathname;
  });
  document.getElementById('select-month')?.addEventListener('change', function (e) {
    const v = e.target.value;
    if (v) location.href = new URL(window.location.href).origin + window.location.pathname + '?month=' + encodeURIComponent(v);
    else location.href = window.location.pathname;
  });
  document.getElementById('btn-clear-period')?.addEventListener('click', function (e) {
    location.href = window.location.pathname;
  });
</script>

<script>
// Tạo gradient màu
function createGradient(ctx, color) {
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, color + "CC");
    gradient.addColorStop(1, color + "00");
    return gradient;
}

// BIỂU ĐỒ DOANH THU TUẦN
const ctxWeek = document.getElementById("chart-weekly").getContext("2d");
const gradientWeek = createGradient(ctxWeek, "#4e73df");

new Chart(ctxWeek, {
    type: "line",
    data: {
        labels: ["W38","W39","W40","W41","W42","W43"],
        datasets: [{
            label: "Doanh thu tuần",
            data: [12, 19, 15, 22, 30, 25],
            fill: true,
            backgroundColor: gradientWeek,
            borderColor: "#4e73df",
            tension: 0.35, // đường cong mượt
            borderWidth: 3,
            pointRadius: 4,
            pointHoverRadius: 8, // hiệu ứng phóng lớn khi hover
            pointBackgroundColor: "#fff",
            pointBorderColor: "#4e73df",
            shadowOffsetX: 0,
            shadowOffsetY: 4,
        }]
    },
    options: {
        responsive: true,
        animation: {
            duration: 1200,
            easing: "easeOutQuart", // hiệu ứng mượt khi load
        },
        plugins: {
            legend: { display: false }
        }
    }
});


// BIỂU ĐỒ DOANH THU THÁNG
const ctxMonth = document.getElementById("chart-monthly").getContext("2d");
const gradientMonth = createGradient(ctxMonth, "#1cc88a");

new Chart(ctxMonth, {
    type: "bar",
    data: {
        labels: ["01","02","03","04","05","06"],
        datasets: [{
            label: "Doanh thu tháng",
            data: [140, 160, 120, 190, 210, 250],
            backgroundColor: gradientMonth,
            borderColor: "#1cc88a",
            borderWidth: 2,
            hoverBorderWidth: 4, // hiệu ứng đậm khi hover
            borderRadius: 10 // bo góc cột
        }]
    },
    options: {
        responsive: true,
        animation: {
            duration: 1000,
            easing: "easeOutBounce" // hiệu ứng "bật bật" khi hiện cột
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>
