<?php
// File: tools/test_comments.php
// Manual test script to check comments in database

require_once __DIR__ . '/../app.php';

// Get product ID from query string
$productId = $_GET['product_id'] ?? 'P002';

echo "<h2>Testing Comments for Product: " . htmlspecialchars($productId) . "</h2>";

try {
    // Test the CommentController directly via AJAX
    echo "<h3>1. Testing AJAX Endpoint:</h3>";
    $url = "http://localhost/DuAn1/comment/ajaxList/" . urlencode($productId);
    echo "<p>URL: <strong>" . htmlspecialchars($url) . "</strong></p>";
    
    $json = file_get_contents($url);
    echo "<p>Response:</p>";
    echo "<pre>";
    echo htmlspecialchars($json);
    echo "</pre>";
    
    $decoded = json_decode($json, true);
    if ($decoded) {
        echo "<h4>Decoded JSON:</h4>";
        echo "<p>OK: " . ($decoded['ok'] ? 'true' : 'false') . "</p>";
        echo "<p>Count: " . ($decoded['count'] ?? 'N/A') . "</p>";
        if (isset($decoded['html'])) {
            echo "<h4>HTML Output:</h4>";
            echo "<pre>";
            echo htmlspecialchars($decoded['html']);
            echo "</pre>";
            echo "<h4>HTML Rendered:</h4>";
            echo $decoded['html'];
        }
    }
    
} catch (\Exception $e) {
    echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>";
    print_r($e->getTrace());
    echo "</pre>";
}
?>
