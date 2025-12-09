<?php
// File: src/Controllers/PaymentController.php
namespace Controllers;

use Core\Controller;
use Core\PaymentHelper;

class PaymentController extends Controller
{
    /**
     * Get QR Code URL for order
     * URL: /payment/get-qr-code (GET)
     * Parameters: amount (optional), description (optional)
     */
    public function getQRCode()
    {
        // Kiểm tra QR có được bật không
        if (!PaymentHelper::isQREnabled()) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'QR Code payment is not enabled'
            ]);
            return;
        }

        // Lấy parameters từ query string
        $amount = isset($_GET['amount']) ? (int)$_GET['amount'] : null;
        $description = isset($_GET['description']) ? trim($_GET['description']) : null;

        // Nếu không có amount, lấy từ session (giỏ hàng)
        if (empty($amount) && isset($_SESSION['cart_total'])) {
            $amount = $_SESSION['cart_total'];
        }

        // Generate description nếu không có
        if (empty($description)) {
            $description = 'Thanh toán đơn hàng';
            if (isset($_SESSION['user'])) {
                $user = $_SESSION['user'];
                $description = 'Thanh toan don hang - ' . (isset($user['name']) ? $user['name'] : $user['username']);
            }
        }

        // Generate QR URL
        $qrUrl = PaymentHelper::generateQRUrl($amount, $description);

        if (empty($qrUrl)) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to generate QR code'
            ]);
            return;
        }

        // Return success response
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'qr_url' => $qrUrl,
            'bank_id' => PaymentHelper::getQRConfig()['bank_id'],
            'account_no' => PaymentHelper::getQRConfig()['account_no'],
            'account_name' => PaymentHelper::getQRConfig()['account_name'],
            'amount' => $amount
        ]);
    }

    /**
     * Get payment configuration (for admin)
     * URL: /payment/config (GET)
     */
    public function config()
    {
        // Kiểm tra quyền admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'config' => PaymentHelper::getQRConfig(),
            'banks' => PaymentHelper::getAllBankCodes()
        ]);
    }

    /**
     * Update payment configuration (for admin)
     * URL: /payment/update-config (POST)
     * POST Parameters: bank_id, account_no, account_name, template
     */
    public function updateConfig()
    {
        // Kiểm tra request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        // Kiểm tra quyền admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        // Lấy dữ liệu từ POST
        $bankId = isset($_POST['bank_id']) ? trim($_POST['bank_id']) : null;
        $accountNo = isset($_POST['account_no']) ? trim($_POST['account_no']) : null;
        $accountName = isset($_POST['account_name']) ? trim($_POST['account_name']) : null;
        $template = isset($_POST['template']) ? trim($_POST['template']) : 'print';

        // Validate
        if (empty($bankId) || empty($accountNo) || empty($accountName)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Missing required fields: bank_id, account_no, account_name'
            ]);
            return;
        }

        // Update config
        if (PaymentHelper::updateQRConfig($bankId, $accountNo, $accountName, $template)) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Configuration updated successfully',
                'config' => PaymentHelper::getQRConfig()
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update configuration'
            ]);
        }
    }

    /**
     * Record payment to Google Apps Script
     * URL: /payment/record-payment (POST)
     * POST Parameters: order_id, amount, description, account_name, user_email, user_phone
     */
    public function recordPayment()
    {
        // Kiểm tra request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        // Lấy dữ liệu từ POST/JSON
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';

        if (strpos($contentType, 'application/json') !== false) {
            $jsonData = json_decode(file_get_contents('php://input'), true);
            $data = $jsonData ?? [];
        } else {
            $data = $_POST ?? [];
        }

        // Prepare payment data
        $paymentData = [
            'order_id' => isset($data['order_id']) ? trim($data['order_id']) : '',
            'amount' => isset($data['amount']) ? (int)$data['amount'] : 0,
            'description' => isset($data['description']) ? trim($data['description']) : 'Thanh toan don hang',
            'account_name' => isset($data['account_name']) ? trim($data['account_name']) : PaymentHelper::getQRConfig()['account_name'],
            'user_email' => isset($data['user_email']) ? trim($data['user_email']) : ($_SESSION['user']['email'] ?? ''),
            'user_phone' => isset($data['user_phone']) ? trim($data['user_phone']) : ($_SESSION['user']['phone'] ?? ''),
        ];

        // Validate
        if (empty($paymentData['order_id']) || empty($paymentData['amount'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Missing required fields: order_id, amount'
            ]);
            return;
        }

        // Call Google Apps Script
        $result = PaymentHelper::callGoogleAppsScript($paymentData);

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Update Google Apps Script API URL (for admin)
     * URL: /payment/update-gas-api (POST)
     * POST Parameters: api_url
     */
    public function updateGasApi()
    {
        // Check request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        // Check admin permission
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        // Get API URL from POST
        $apiUrl = isset($_POST['api_url']) ? trim($_POST['api_url']) : null;

        // Validate
        if (empty($apiUrl)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'API URL is required'
            ]);
            return;
        }

        // Validate URL format
        if (!filter_var($apiUrl, FILTER_VALIDATE_URL)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid API URL format'
            ]);
            return;
        }

        // Update config
        if (PaymentHelper::updateGoogleAppsScriptUrl($apiUrl)) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'API URL updated successfully',
                'api_url' => PaymentHelper::getGoogleAppsScriptUrl()
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update API URL'
            ]);
        }
    }

    /**
     * ============================================================
     * CHECK PAYMENT - Kiểm tra thanh toán
     * ============================================================
     * URL: /payment/check-payment (POST)
     * POST Parameters (JSON):
     *   - order_id: Mã đơn hàng
     *   - amount: Số tiền
     *   - description: Nội dung chuyển khoản
     *   - account_no: Số tài khoản
     *   - bank_id: Mã ngân hàng
     * 
     * Gọi API của Google Apps Script để kiểm tra thanh toán
     */
    public function checkPayment()
    {
        // Kiểm tra request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        // Kiểm tra user đã login
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        // Lấy dữ liệu từ JSON request
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
        if (strpos($contentType, 'application/json') !== false) {
            $jsonData = json_decode(file_get_contents('php://input'), true);
            $data = $jsonData ?? [];
        } else {
            $data = $_POST ?? [];
        }

        // Validate dữ liệu
        $orderId = isset($data['order_id']) ? trim($data['order_id']) : '';
        $amount = isset($data['amount']) ? (int)$data['amount'] : 0;
        $description = isset($data['description']) ? trim($data['description']) : '';
        $accountNo = isset($data['account_no']) ? trim($data['account_no']) : '';
        $bankId = isset($data['bank_id']) ? trim($data['bank_id']) : '';

        if (empty($orderId) || empty($amount) || empty($description)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Missing required fields: order_id, amount, description'
            ]);
            return;
        }

        // ===== GỌI API CỦA BẠN (GOOGLE APPS SCRIPT) =====
        $result = $this->callPaymentCheckAPI($orderId, $amount, $description, $accountNo, $bankId);

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Gọi API của Google Apps Script để kiểm tra thanh toán
     * 
     * @param string $orderId - Mã đơn hàng
     * @param int $amount - Số tiền
     * @param string $description - Nội dung chuyển khoản
     * @param string $accountNo - Số tài khoản
     * @param string $bankId - Mã ngân hàng
     * @return array Kết quả từ API
     */
    private function callPaymentCheckAPI($orderId, $amount, $description, $accountNo, $bankId)
    {
        // Lấy API URL từ config
        $gasUrl = PaymentHelper::getGoogleAppsScriptUrl();
        
        if (empty($gasUrl)) {
            return [
                'success' => false,
                'message' => 'Payment API không được cấu hình'
            ];
        }

        // Chuẩn bị dữ liệu gửi đến API
        $payload = [
            'action' => 'checkPayment',
            'data' => [
                'order_id' => $orderId,
                'amount' => $amount,
                'description' => $description,
                'account_no' => $accountNo,
                'bank_id' => $bankId,
                'timestamp' => date('Y-m-d H:i:s'),
                'user_id' => $_SESSION['user']['id'] ?? $_SESSION['user']['username'] ?? ''
            ]
        ];

        try {
            // Gọi API bằng cURL
            $ch = curl_init($gasUrl);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query($payload),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/x-www-form-urlencoded',
                ]
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            // Ghi log request + response để debug
            try {
                $logDir = ROOT_PATH . '/storage';
                if (!is_dir($logDir)) {
                    @mkdir($logDir, 0755, true);
                }
                $logFile = $logDir . '/payment_check.log';
                $logEntry = "---\n";
                $logEntry .= "TIMESTAMP: " . date('Y-m-d H:i:s') . "\n";
                $logEntry .= "REQUEST_URL: " . $gasUrl . "\n";
                $logEntry .= "REQUEST_PAYLOAD: " . print_r($payload, true) . "\n";
                $logEntry .= "HTTP_CODE: " . $httpCode . "\n";
                $logEntry .= "CURL_ERROR: " . $error . "\n";
                $logEntry .= "RESPONSE: " . ($response === false ? 'false' : $response) . "\n";
                $logEntry .= "---\n\n";
                @file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
            } catch (\Exception $e) {
                // Ignore logging errors
            }

            // Xử lý lỗi cURL
            if ($error) {
                return [
                    'success' => false,
                    'message' => 'Lỗi kết nối API: ' . $error
                ];
            }

            // Parse response
            if (!empty($response)) {
                $result = json_decode($response, true);
                // Nếu API trả về JSON hợp lệ
                if (is_array($result)) {
                    return $result;
                }

                // Nếu API chỉ trả về text
                return [
                    'success' => ($httpCode === 200),
                    'message' => $response,
                    'http_code' => $httpCode
                ];
            }

            return [
                'success' => ($httpCode === 200),
                'message' => 'API returned empty response',
                'http_code' => $httpCode
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }
}
