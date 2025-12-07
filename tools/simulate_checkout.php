<?php
/**
 * Simulate checkout: insert an order and order_detail, update variant stock.
 * Usage (PowerShell):
 * php tools\simulate_checkout.php --product=P002 --qty=1 --user=kh+0000000002
 * Adjust DB credentials below if needed.
 */

$opts = getopt('', ['product::', 'qty::', 'user::']);
$productId = $opts['product'] ?? 'P002';
$qty = (int)($opts['qty'] ?? 1);
$userId = $opts['user'] ?? 'kh+0000000002';

$host = '127.0.0.1';
$dbname = 'duan1';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    echo "DB connect failed: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

try {
    // Find a variant with stock > 0 for product
    $stmt = $pdo->prepare('SELECT * FROM product_variants WHERE Product_Id = :pid AND Quantity_In_Stock > 0 LIMIT 1');
    $stmt->execute([':pid' => $productId]);
    $variant = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$variant) {
        echo "No available variant with stock > 0 for product $productId" . PHP_EOL;
        exit(1);
    }
    $variantId = $variant['Variant_Id'];
    $price = $variant['Price'] ?: 0;
    $stock = (int)$variant['Quantity_In_Stock'];
    if ($qty > $stock) {
        echo "Requested qty ($qty) greater than available stock ($stock)." . PHP_EOL;
        exit(1);
    }

    $pdo->beginTransaction();

    // Simple Order_Id generator
    $orderId = 'OR' . substr(time() . rand(100,999), 0, 13);
    $orderDate = date('Y-m-d');
    $sql = "INSERT INTO orders (Order_Id, Order_date, Adress, Note, TrangThai, _UserName_Id) VALUES (:id, :date, :addr, :note, :status, :user)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $orderId,
        ':date' => $orderDate,
        ':addr' => 'Simulated address',
        ':note' => 'Simulated order',
        ':status' => 'pending',
        ':user' => $userId
    ]);

    $sql = "INSERT INTO order_detail (Order_Id, Variant_Id, quantity, Price) VALUES (:order_id, :variant_id, :qty, :price)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':order_id' => $orderId,
        ':variant_id' => $variantId,
        ':qty' => $qty,
        ':price' => $price
    ]);

    // update variant stock
    $newStock = $stock - $qty;
    $stmt = $pdo->prepare('UPDATE product_variants SET Quantity_In_Stock = :stock WHERE Variant_Id = :vid');
    $stmt->execute([':stock' => $newStock, ':vid' => $variantId]);

    // update product total quantity (optional)
    $stmt = $pdo->prepare('UPDATE products SET Quantity = (SELECT SUM(Quantity_In_Stock) FROM product_variants WHERE Product_Id = products.Product_Id) WHERE Product_Id = :pid');
    $stmt->execute([':pid' => $productId]);

    $pdo->commit();

    echo "Simulated order created: $orderId\n";
    echo "Variant $variantId stock updated to $newStock\n";
    exit(0);
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
