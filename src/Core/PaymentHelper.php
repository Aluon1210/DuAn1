<?php
/**
 * Payment Helper
 * Trợ giúp generate QR Code URL từ VietQR
 */

namespace Core;

class PaymentHelper
{
    private static $config = null;

    /**
     * Load cấu hình thanh toán
     */
    public static function loadConfig()
    {
        if (self::$config === null) {
            self::$config = require ROOT_PATH . '/src/Config/payment.php';
        }
        return self::$config;
    }

    /**
     * ============================================================
     * FUNCTION CHÍNH: buildQRUrl()
     * ============================================================
     * Tạo QR Code URL từ VietQR
     * 
     * Định dạng: 
     * https://img.vietqr.io/image/{BANK_ID}-{ACCOUNT_NO}-{TEMPLATE}.png?amount={AMOUNT}&addInfo={DESCRIPTION}&accountName={ACCOUNT_NAME}
     * 
     * @param string $bankId          - Mã ngân hàng (VD: ACB, VIETCOMBANK, BIDV)
     * @param string $accountNo       - Số tài khoản
     * @param string $accountName     - Tên chủ tài khoản
     * @param int|float $amount       - Số tiền (VND) - tùy chọn
     * @param string $description     - Nội dung chuyển khoản - tùy chọn
     * @param string $template        - Template QR (print, compact) - mặc định 'print'
     * @return string QR Code URL
     * 
     * CÁC VÍ DỤ SỬ DỤNG:
     * 
     * // Ví dụ 1: Đầy đủ thông tin
     * $qr = PaymentHelper::buildQRUrl(
     *     'ACB',              // Mã ngân hàng
     *     '123456789',        // Số tài khoản
     *     'NGUYEN VAN A',     // Tên chủ tài khoản
     *     500000,             // Số tiền
     *     'Thanh toan don hang' // Nội dung
     * );
     * // Kết quả: https://img.vietqr.io/image/ACB-123456789-print.png?amount=500000&addInfo=Thanh+toan+don+hang&accountName=NGUYEN+VAN+A
     * 
     * // Ví dụ 2: Chỉ tài khoản (không có tiền, nội dung)
     * $qr = PaymentHelper::buildQRUrl('BIDV', '0987654321', 'LE THI B');
     * // Kết quả: https://img.vietqr.io/image/BIDV-0987654321-print.png?accountName=LE+THI+B
     * 
     * // Ví dụ 3: Có tiền nhưng không nội dung
     * $qr = PaymentHelper::buildQRUrl('VIETCOMBANK', '1122334455', 'TRAN VAN C', 1000000);
     * // Kết quả: https://img.vietqr.io/image/VIETCOMBANK-1122334455-print.png?amount=1000000&accountName=TRAN+VAN+C
     * 
     * // Ví dụ 4: Tùy chỉnh template
     * $qr = PaymentHelper::buildQRUrl('MB', '5566778899', 'HOANG VAN D', 250000, 'Test', 'compact');
     * // Kết quả: https://img.vietqr.io/image/MB-5566778899-compact.png?amount=250000&addInfo=Test&accountName=HOANG+VAN+D
     */
    public static function buildQRUrl($bankId, $accountNo, $accountName, $amount = null, $description = null, $template = 'print')
    {
        // Validate required parameters
        if (empty($bankId) || empty($accountNo) || empty($accountName)) {
            return '';
        }

        // Build base QR URL
        $qrCode = "{$bankId}-{$accountNo}-{$template}.png";
        $baseUrl = 'https://img.vietqr.io/image/';
        $url = $baseUrl . $qrCode;

        // Build query parameters
        $params = [];

        // Add amount if provided
        if (!empty($amount) && $amount > 0) {
            $params['amount'] = (int)$amount;
        }

        // Add description if provided
        if (!empty($description)) {
            $params['addInfo'] = trim($description);
        }

        // Add account name
        $params['accountName'] = trim($accountName);

        // Build final URL with query string
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }

    /**
     * Generate QR Code URL từ VietQR (sử dụng cấu hình mặc định)
     * 
     * @param int|float $amount - Số tiền (tùy chọn)
     * @param string $description - Nội dung chuyển khoản (tùy chọn)
     * @param string $bankId - Mã ngân hàng (tùy chọn, dùng từ config)
     * @param string $accountNo - Số tài khoản (tùy chọn, dùng từ config)
     * @return string QR Code URL
     */
    public static function generateQRUrl($amount = null, $description = null, $bankId = null, $accountNo = null)
    {
        $config = self::loadConfig();
        
        // Sử dụng giá trị từ config nếu không cung cấp
        $bankId = $bankId ?? $config['qr']['bank_id'];
        $accountNo = $accountNo ?? $config['qr']['account_no'];
        $accountName = $config['qr']['account_name'];
        $template = $config['qr']['template'];
        
        return self::buildQRUrl($bankId, $accountNo, $accountName, $amount, $description, $template);
    }

    /**
     * Get QR Config
     */
    public static function getQRConfig()
    {
        $config = self::loadConfig();
        return $config['qr'];
    }

    /**
     * Check if QR is enabled
     */
    public static function isQREnabled()
    {
        $config = self::loadConfig();
        return $config['qr']['enabled'] ?? true;
    }

    /**
     * Get Bank Name by ID
     */
    public static function getBankName($bankId)
    {
        $config = self::loadConfig();
        return $config['bank_codes'][$bankId] ?? $bankId;
    }

    /**
     * Get all available bank codes
     */
    public static function getAllBankCodes()
    {
        $config = self::loadConfig();
        return $config['bank_codes'];
    }

    /**
     * Update QR Config (for admin panel)
     */
    public static function updateQRConfig($bankId, $accountNo, $accountName, $template = 'print')
    {
        // Validate bank_id format
        if (empty($bankId) || empty($accountNo) || empty($accountName)) {
            return false;
        }
        
        $configPath = ROOT_PATH . '/src/Config/payment.php';
        
        // Read current config
        $config = require $configPath;
        
        // Update values
        $config['qr']['bank_id'] = $bankId;
        $config['qr']['account_no'] = $accountNo;
        $config['qr']['account_name'] = $accountName;
        $config['qr']['template'] = $template;
        
        // Write back to file
        $content = "<?php\n/**\n * Payment Configuration\n * Auto-generated configuration\n */\n\nreturn " . var_export($config, true) . ";";
        
        return file_put_contents($configPath, $content) !== false;
    }

    /**
     * ============================================================
     * GOOGLE APPS SCRIPT API INTEGRATION
     * ============================================================
     */

    /**
     * Gọi Google Apps Script API để xử lý thanh toán
     * 
     * @param array $data - Dữ liệu thanh toán:
     *        - order_id: Mã đơn hàng (string)
     *        - amount: Số tiền (int)
     *        - description: Nội dung chuyển khoản (string)
     *        - account_name: Tên tài khoản (string)
     *        - user_email: Email người thanh toán (string)
     *        - user_phone: Số điện thoại (string)
     * @return array|false Kết quả từ API hoặc false nếu lỗi
     * 
     * Ví dụ:
     * $result = PaymentHelper::callGoogleAppsScript([
     *     'order_id' => 'ORD123456',
     *     'amount' => 500000,
     *     'description' => 'Thanh toan don hang',
     *     'account_name' => 'NGUYEN VAN A',
     *     'user_email' => 'user@example.com',
     *     'user_phone' => '0912345678'
     * ]);
     */
    public static function callGoogleAppsScript($data = [])
    {
        $config = self::loadConfig();
        $gasConfig = $config['google_apps_script'];

        // Check if API is enabled
        if (empty($gasConfig['enabled'])) {
            return [
                'success' => false,
                'message' => 'Google Apps Script API is not enabled'
            ];
        }

        // Get API URL
        $apiUrl = $gasConfig['payment_api_url'];
        if (empty($apiUrl)) {
            return [
                'success' => false,
                'message' => 'API URL not configured'
            ];
        }

        // Prepare data for API
        $payload = [
            'action' => 'recordPayment',
            'data' => $data
        ];

        try {
            // Call API using cURL (send JSON, follow redirects)
            $ch = curl_init($apiUrl);
            $jsonPayload = json_encode($payload);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $jsonPayload,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_USERAGENT => 'DuAn1-PaymentHelper/1.0',
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json'
                ]
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            // Logging request/response for debugging
            try {
                $logDir = ROOT_PATH . '/storage';
                if (!is_dir($logDir)) {
                    @mkdir($logDir, 0755, true);
                }
                $logFile = $logDir . '/payment_record.log';
                $logEntry = "---\n";
                $logEntry .= "TIME: " . date('Y-m-d H:i:s') . "\n";
                $logEntry .= "API_URL: " . $apiUrl . "\n";
                $logEntry .= "PAYLOAD: " . $jsonPayload . "\n";
                $logEntry .= "HTTP_CODE: " . $httpCode . "\n";
                $logEntry .= "CURL_ERROR: " . $error . "\n";
                $logEntry .= "RESPONSE: " . ($response === false ? 'false' : $response) . "\n";
                $logEntry .= "---\n\n";
                @file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
            } catch (\Exception $e) {
                // ignore logging errors
            }

            // Check for errors
            if ($error) {
                return [
                    'success' => false,
                    'message' => 'API request failed: ' . $error
                ];
            }

            // Decode response JSON if possible
            if (!empty($response)) {
                $result = json_decode($response, true);
                return is_array($result) ? $result : [
                    'success' => true,
                    'raw_response' => $response
                ];
            }

            return [
                'success' => ($httpCode === 200),
                'http_code' => $httpCode
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get Google Apps Script API URL
     */
    public static function getGoogleAppsScriptUrl()
    {
        $config = self::loadConfig();
        return $config['google_apps_script']['payment_api_url'] ?? '';
    }

    /**
     * Update Google Apps Script API URL (for admin)
     */
    public static function updateGoogleAppsScriptUrl($apiUrl)
    {
        if (empty($apiUrl)) {
            return false;
        }

        $configPath = ROOT_PATH . '/src/Config/payment.php';
        $config = require $configPath;

        $config['google_apps_script']['payment_api_url'] = trim($apiUrl);

        $content = "<?php\n/**\n * Payment Configuration\n * Auto-generated configuration\n */\n\nreturn " . var_export($config, true) . ";";

        return file_put_contents($configPath, $content) !== false;
    }
}
