<?php
$stats = [
  ['label' => 'Doanh thu hôm nay', 'value' => '12,500,000₫'],
  ['label' => 'Đơn hàng mới', 'value' => '18'],
  ['label' => 'Người dùng mới', 'value' => '5'],
  ['label' => 'Sản phẩm tồn kho', 'value' => '1,024'],
];
?>
<!DOCTYPE html>
<html lang="vi">

<?php include __DIR__ . '/head.php'; ?>

<style>
.admin-container {
  display: flex;
  min-height: 100vh;
}

/* SIDEBAR */
.sidebar {
  width: 250px;
  background: #111;
  padding: 20px;
  color: #fff;
  flex-shrink: 0;
}

/* MAIN CONTENT */
.admin-content {
  padding: 30px;
  background: #f4f4f4;
  overflow-y: auto;
  position: relative; /* để margin-left có tác dụng */
  margin-left: calc(250px + 50px); /* sidebar 250px + khoảng cách 50px */
  width: calc(100% - 250px - 50px); /* tổng width trừ sidebar + khoảng cách */
  box-sizing: border-box;
}

.stats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}

.card {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.section-title {
  margin-bottom: 20px;
}

/* CARD – bo góc + đổ bóng + hiệu ứng hover */
.card {
  background: #fff;
  padding: 20px;
  border-radius: 16px; /* bo góc mềm */
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  border: 1px solid rgba(255, 215, 0, 0.15); /* viền vàng nhẹ luxury */
}

.card:hover {
  transform: translateY(-5px); /* nhấc lên nhẹ */
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  border-color: rgba(255, 215, 0, 0.4); /* vàng đậm khi hover */
}

/* Tiêu đề section */
.section-title {
  margin-bottom: 20px;
  font-size: 22px;
  font-weight: 600;
  color: #333;
}

/* Số hiển thị lớn */
.number {
  margin-top: 10px;
  font-size: 26px;
  font-weight: bold;
  color: #000;
}

/* Hiệu ứng fade-in khi load */
.card {
  opacity: 0;
  animation: fadeInUp 0.4s ease forwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Grid – khoảng cách đẹp hơn */
.stats {
  gap: 24px;
}

</style>

<body>

  <!-- Bọc toàn bộ giao diện -->
  <div class="admin-container">

    <!-- SIDEBAR -->
    <?php include __DIR__ . '/aside.php'; ?>

    <!-- PHẦN NỘI DUNG -->
    <main class="admin-content">
      <section>
        <h2 class="section-title">Tổng quan hệ thống</h2>

        <div class="stats">
          <?php foreach ($stats as $s): ?>
            <div class="card">
              <h3><?= htmlspecialchars($s['label']) ?></h3>
              <div class="number"><?= htmlspecialchars($s['value']) ?></div>
            </div>
          <?php endforeach; ?>
        </div>

      </section>
    </main>

  </div>

</body>
</html>
