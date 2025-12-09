<?php
// Simple demo to fetch and display JSON data from the provided GAS URL
// Usage: open http://localhost/DuAn1/tools/gas_demo.php in your browser

$gasUrl = "https://script.googleusercontent.com/macros/echo?user_content_key=AehSKLg3u5UbB6PNOWTQKbTUFJqPh2VipnK61rKdtQkTO5DS6X3GtiCRetLtc01RFPR4CiahvR76Ffe2G5L4gqG7bJDPKTUEi1ob1vqL7QLWOpIuxxF16fe4BtNXje_XSBtg0f7YmyLnC2PhQS_lvIt_Ke0tJsE6k5vuEQ8sy5dVLD-RXqsbBvDWvQyHBy8nqlsA_UYGyH91t75-JRVRnDRq4BXMyDBJ3y-jJCqRECZFfrX6DNC_LQgXwAIBqva07gPbJPuRgHBCaYIYJAtYj6Ywsl5-yy60Pw&lib=MUoExfrMQjyQ36Yszw56Uf0FNOc6D1c8x";

function http_get($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_USERAGENT => 'DuAn1-GAS-Demo/1.0',
        CURLOPT_HTTPHEADER => [
            'Accept: application/json, text/plain, */*'
        ]
    ]);
    $resp = curl_exec($ch);
    $info = curl_getinfo($ch);
    $err = curl_error($ch);
    curl_close($ch);
    return ['body' => $resp, 'info' => $info, 'error' => $err];
}

$result = http_get($gasUrl);
$body = $result['body'];
$info = $result['info'];
$error = $result['error'];

// Try decode JSON
$decoded = null;
if ($body !== false) {
    $decoded = json_decode($body, true);
}

?><!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>GAS Demo - Fetch Data</title>
<style>
  body{font-family:Arial,Helvetica,sans-serif; background:#f6f7fb; color:#222; padding:18px}
  .box{background:#fff;border-radius:8px;padding:18px;box-shadow:0 6px 18px rgba(0,0,0,0.08)}
  table{border-collapse:collapse;width:100%}
  th,td{padding:8px 12px;border:1px solid #eee;text-align:left}
  th{background:#fafafa}
  pre{background:#111;color:#bada55;padding:12px;border-radius:6px;overflow:auto}
  .meta{font-size:13px;color:#666;margin-bottom:12px}
</style>
</head>
<body>
  <div class="box">
    <h2>GAS Demo - Fetch Data</h2>
    <p class="meta">URL: <a href="<?php echo htmlspecialchars($gasUrl); ?>" target="_blank"><?php echo htmlspecialchars($gasUrl); ?></a></p>
    <h3>HTTP Info</h3>
    <table>
      <tr><th>HTTP Code</th><td><?php echo htmlspecialchars($info['http_code'] ?? ''); ?></td></tr>
      <tr><th>Content Type</th><td><?php echo htmlspecialchars($info['content_type'] ?? ''); ?></td></tr>
      <tr><th>Total Time</th><td><?php echo htmlspecialchars(isset($info['total_time']) ? $info['total_time'] . 's' : ''); ?></td></tr>
      <tr><th>cURL Error</th><td><?php echo htmlspecialchars($error); ?></td></tr>
    </table>

    <?php if (is_array($decoded) && (isset($decoded['data']) || isset($decoded[0]))): ?>
      <h3>Decoded JSON</h3>
      <?php
        // If structure contains 'data' which is an array, use it
        $rows = [];
        if (isset($decoded['data']) && is_array($decoded['data'])) {
          $rows = $decoded['data'];
        } elseif (is_array($decoded)) {
          $rows = $decoded;
        }
      ?>
      <table>
        <thead>
          <tr>
            <?php
              // Collect all keys
              $keys = [];
              foreach ($rows as $r) {
                if (is_array($r)) {
                  foreach (array_keys($r) as $k) $keys[$k] = true;
                }
              }
              $keys = array_keys($keys);
              foreach ($keys as $k):
            ?>
            <th><?php echo htmlspecialchars($k); ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <?php foreach ($keys as $k): ?>
                <td><?php echo htmlspecialchars(is_scalar($r[$k] ?? '') ? $r[$k] : json_encode($r[$k])); ?></td>
              <?php endforeach; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <h3>Raw Response</h3>
      <pre><?php echo htmlspecialchars($body); ?></pre>
    <?php endif; ?>

  </div>
</body>
</html>