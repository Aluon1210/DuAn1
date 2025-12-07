<?php
$host = '127.0.0.1';
$dbname = 'duan1';
$user = 'root';
$pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    echo "DB connect failed: " . $e->getMessage() . PHP_EOL; exit(1);
}

// show last 20 orders
$stmt = $pdo->prepare('SELECT Order_Id, Order_date, TrangThai, _UserName_Id FROM orders ORDER BY Order_date DESC, Order_Id DESC LIMIT 20');
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$rows) {
    echo "No orders found\n";
    exit(0);
}

echo "Recent orders (last " . count($rows) . "):\n";
foreach ($rows as $r) {
    echo sprintf("%s | %s | user=%s | status=%s\n", $r['Order_Id'], $r['Order_date'], $r['_UserName_Id'], $r['TrangThai']);
}

// count total orders
$stmt = $pdo->query('SELECT COUNT(*) as c FROM orders');
$c = $stmt->fetch(PDO::FETCH_ASSOC);
echo "\nTotal orders: " . ($c['c'] ?? 0) . "\n";
?>