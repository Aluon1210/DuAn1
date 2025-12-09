<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Tài khoản bị chặn</title>
  <link rel="stylesheet" href="/DuAn1/asset/css/style.css">
  <style>
    body { font-family: Arial, sans-serif; background:#fafafa; color:#222; }
    .forbidden-box { max-width:720px; margin:80px auto; background:white; padding:32px; border-radius:12px; box-shadow:0 8px 32px rgba(0,0,0,0.06); }
    .forbidden-box h1 { font-size:28px; margin-bottom:12px; color:#b00020; }
    .forbidden-box p { font-size:16px; margin-bottom:18px; }
    .forbidden-box a { display:inline-block; padding:10px 18px; background:#007bff; color:white; border-radius:8px; text-decoration:none; }
  </style>
</head>
<body>
  <div class="forbidden-box">
    <h1>Tài khoản bị chặn</h1>
    <p><?php echo htmlspecialchars($data['message'] ?? 'Tài khoản của bạn đã bị chặn.'); ?></p>
    <p>Nếu bạn cho rằng đây là nhầm lẫn, vui lòng liên hệ quản trị để được hỗ trợ.</p>
    <a href="<?php echo ROOT_URL; ?>">Quay về trang chủ</a>
  </div>
</body>
</html>