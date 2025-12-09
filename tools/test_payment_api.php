<?php
/**
 * Test Payment Check API Endpoint
 * Kiểm tra endpoint /payment/check-payment
 */

$testData = [
    'order_id' => 'ORD_TEST_' . time(),
    'amount' => 8000,
    'description' => 'Thanh toan - Test User',
    'account_no' => '0833268346',
    'bank_id' => 'MB'
];

echo "=== PAYMENT API TEST ===\n\n";

echo "Sending request to: http://localhost/DuAn1/payment/check-payment\n";
echo "Payload:\n";
echo json_encode($testData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

// Need a valid session for the API
session_start();
$_SESSION['user'] = ['id' => 'test', 'username' => 'test'];
$sessionId = session_id();

$ch = curl_init('http://localhost/DuAn1/payment/check-payment');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($testData),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json'
    ],
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_COOKIE => 'PHPSESSID=' . $sessionId
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$error = curl_error($ch);
curl_close($ch);

echo "=== RESPONSE ===\n";
echo "HTTP Status: $httpCode\n";
echo "Content-Type: $contentType\n";
if ($error) echo "cURL Error: $error\n";
echo "\n";

echo "Response Body:\n";
echo str_repeat("-", 60) . "\n";
echo $response . "\n";
echo str_repeat("-", 60) . "\n\n";

// Try to parse as JSON
$parsed = json_decode($response, true);
if ($parsed) {
    echo "✓ Valid JSON received\n\n";
    echo "Parsed data:\n";
    echo json_encode($parsed, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
} else {
    echo "✗ Response is not valid JSON\n";
    echo "Error: " . json_last_error_msg() . "\n";
}
?>
