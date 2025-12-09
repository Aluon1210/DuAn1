<?php
/**
 * Test if GAS URL is accessible
 */

$gasUrl = 'https://script.googleusercontent.com/macros/echo?user_content_key=AehSKLjCw-aLPcbzgnefn16ff68Qq-AkaRnHrzXG-BGgrMiRUKJZ5otpOYXxhMucytRsbNFRfAEXL_sZRdvCQa8CozwtXxyfr84O-dFNgLShHhhnLljTH74X39RIlNbvdv6SvCJW7BG7iynACgUQq4DNU48ynWm_RDA60ER_3ivkMD95XNK_1tDAE-FVKCUiPbH2cKkjZnfZvpNaJMnK_t2UoNNoYv_bGebIVBU6z2qYWVRCy05pF_j5wKaBOeLE-7V1a3fr74jhE2Rn9IumQuFjdP3-lKXf8A&lib=MUoExfrMQjyQ36Yszw56Uf0FNOc6D1c8x';

echo "=== GAS URL ACCESSIBILITY TEST ===\n\n";
echo "Testing URL: $gasUrl\n\n";

$ch = curl_init($gasUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => ['Accept: application/json'],
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_VERBOSE => false
]);

echo "Sending test request...\n";
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
$time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
curl_close($ch);

echo "Response Time: {$time}s\n";
echo "HTTP Code: $httpCode\n";
if ($error) {
    echo "cURL Error: $error\n";
} else {
    echo "✓ Connected successfully\n";
}

echo "\nResponse (first 500 chars):\n";
echo str_repeat("-", 60) . "\n";
echo substr($response, 0, 500) . "\n";
echo str_repeat("-", 60) . "\n";

// Try to parse as JSON
$parsed = json_decode($response, true);
if ($parsed) {
    echo "\n✓ Response is valid JSON\n";
    echo "Structure:\n";
    echo json_encode($parsed, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
} else {
    echo "\n✗ Response is not JSON\n";
}
?>
