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
        // Set Content-Type header ở đầu
        header('Content-Type: application/json; charset=utf-8');
        
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
        $rawBody = file_get_contents('php://input');
        
        if (strpos($contentType, 'application/json') !== false) {
            $jsonData = json_decode($rawBody, true);
            $data = $jsonData ?? [];
        } else {
            $data = $_POST ?? [];
        }

        // Ghi log request đầu vào để debug (lưu request body và headers)
        try {
            $logDir = ROOT_PATH . '/storage';
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0755, true);
            }
            $reqLog = $logDir . '/check_payment_requests.log';
            $headers = '';
            if (function_exists('getallheaders')) {
                foreach (getallheaders() as $k => $v) {
                    $headers .= "$k: $v\n";
                }
            }
            $entry = "---\n";
            $entry .= "TIME: " . date('Y-m-d H:i:s') . "\n";
            $entry .= "REMOTE_ADDR: " . ($_SERVER['REMOTE_ADDR'] ?? '') . "\n";
            $entry .= "METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? '') . "\n";
            $entry .= "HEADERS:\n" . $headers;
            $entry .= "RAW_BODY:\n" . $rawBody . "\n";
            $entry .= "PARSED_DATA:\n" . print_r($data, true) . "\n";
            $entry .= "---\n\n";
            @file_put_contents($reqLog, $entry, FILE_APPEND | LOCK_EX);
        } catch (\Exception $e) {
            // ignore
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
                'message' => 'Payment API chưa được cấu hình'
            ];
        }

        try {
            $queryParams = http_build_query([
                'action' => 'checkPayment',
                'order_id' => $orderId,
                'amount' => (int)$amount,
                'description' => $description,
                'account_no' => $accountNo,
                'bank_id' => $bankId,
                'timestamp' => date('Y-m-d H:i:s'),
                'user_id' => isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : ($_SESSION['user']['username'] ?? '')
            ]);

            $requestUrl = $gasUrl . (strpos($gasUrl, '?') === false ? '?' : '&') . $queryParams;

            $maxRetries = 5;
            $retryDelayMs = 500;
            $response = '';
            $httpCode = 0;
            $error = '';
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                $ch = curl_init($requestUrl);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 12,
                    CURLOPT_CONNECTTIMEOUT => 8,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_USERAGENT => 'DuAn1-PaymentChecker/1.0',
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_HTTPHEADER => [
                        'Accept: application/json',
                        'Accept-Encoding: gzip, deflate'
                    ]
                ]);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $error = curl_error($ch);
                curl_close($ch);
                if (!$error && !empty($response)) { break; }
                usleep($retryDelayMs * 1000);
            }
            
            // Ghi log request + response để debug
            try {
                $logDir = ROOT_PATH . '/storage';
                if (!is_dir($logDir)) {
                    @mkdir($logDir, 0755, true);
                }
                $logFile = $logDir . '/payment_check.log';
                $logEntry = "---\n";
                $logEntry .= "TIMESTAMP: " . date('Y-m-d H:i:s') . "\n";
                $logEntry .= "REQUEST_URL: " . $requestUrl . "\n";
                $logEntry .= "REQUEST_METHOD: GET\n";
            $logEntry .= "REQUEST_QUERY: " . $queryParams . "\n";
            $logEntry .= "HTTP_CODE: " . $httpCode . "\n";
            $logEntry .= "CURL_ERROR: " . $error . "\n";
                $logEntry .= "RESPONSE_LENGTH: " . strlen($response) . "\n";
                $logEntry .= "RESPONSE: " . (strlen($response) > 500 ? substr($response, 0, 500) . '...' : $response) . "\n";
                $logEntry .= "---\n\n";
                @file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
            } catch (\Exception $e) {
                // Ignore logging errors
            }

            // Xử lý lỗi cURL với fallback
            if ($error) {
                // Fallback: thử lấy thanh toán mới nhất rồi so khớp
                $fallback = $this->getLatestPaymentFromAPI();
                if ($fallback['success']) {
                    $match = $this->matchPaymentTransaction([$fallback['data']], $amount, $description, $accountNo);
                    if ($match['success']) { return $match; }
                }
                return [
                    'success' => false,
                    'message' => 'Lỗi kết nối API: ' . $error
                ];
            }

            // Parse response - nếu không có response hoặc không phải JSON, trả về lỗi
            if (empty($response)) {
                // Fallback: thử lấy thanh toán mới nhất rồi so khớp
                $fallback = $this->getLatestPaymentFromAPI();
                if ($fallback['success']) {
                    $match = $this->matchPaymentTransaction([$fallback['data']], $amount, $description, $accountNo);
                    if ($match['success']) { return $match; }
                }
                return [
                    'success' => false,
                    'message' => 'API không trả về dữ liệu'
                ];
            }

            // Một số GAS trả về JSON kèm BOM hoặc khoảng trắng
            $clean = preg_replace('/^\xEF\xBB\xBF/', '', $response);
            $result = json_decode(trim($clean), true);
            
            // Nếu API trả về JSON hợp lệ
            if (is_array($result)) {
                // Kiểm tra nếu response chứa một data array (danh sách transactions)
                if (isset($result['data']) && is_array($result['data'])) {
                    // Tìm transaction phù hợp với amount, description, account_no
                    return $this->matchPaymentTransaction($result['data'], $amount, $description, $accountNo);
                }
                // Nếu API trả về success flag trực tiếp
                if (isset($result['success'])) {
                    return $result;
                }
                // Mặc định nếu nhận được response JSON
                return [
                    'success' => true,
                    'message' => 'Kiểm tra thanh toán thành công',
                    'data' => $result
                ];
            }

            // Nếu API chỉ trả về text (không phải JSON)
            return [
                'success' => false,
                'message' => 'API trả về dữ liệu không hợp lệ (không phải JSON)',
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
     * Tìm và so khớp transaction từ danh sách data của GAS
     * 
     * @param array $transactions - Danh sách transactions từ GAS
     * @param int $amount - Số tiền cần tìm
     * @param string $description - Nội dung cần tìm
     * @param string $accountNo - Số tài khoản nhận
     * @return array Kết quả so khớp
     */
    private function matchPaymentTransaction($transactions, $amount, $description, $accountNo)
    {
        if (!is_array($transactions) || empty($transactions)) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy giao dịch nào'
            ];
        }

        // Chuẩn hóa description để so khớp (loại bỏ dấu cách thừa, chuyển về chữ hoa, loại dấu)
        $descriptionNormalized = strtoupper(trim(preg_replace('/\s+/', ' ', $description)));
        // Loại bỏ tất cả ký tự đặc biệt để so khớp
        $descriptionSimple = preg_replace('/[^A-Z0-9]/i', '', $descriptionNormalized);

        // Tìm giao dịch phù hợp
        $matchedTransaction = null;
        $bestMatch = null;
        
        foreach ($transactions as $trans) {
            // Các trường có thể có trong transaction
            $transAmount = isset($trans['Giá trị']) ? (int)$trans['Giá trị'] : 
                          (isset($trans['amount']) ? (int)$trans['amount'] : 0);
            $transDescription = isset($trans['Mô tả']) ? $trans['Mô tả'] : 
                               (isset($trans['description']) ? $trans['description'] : '');
            $transAccountNo = isset($trans['Số tài khoản']) ? trim($trans['Số tài khoản']) : 
                             (isset($trans['account_no']) ? trim($trans['account_no']) : '');
            
            // Chuẩn hóa description từ transaction
            $transDescriptionNormalized = strtoupper(trim(preg_replace('/\s+/', ' ', $transDescription)));
            $transDescriptionSimple = preg_replace('/[^A-Z0-9]/i', '', $transDescriptionNormalized);
            
            // Tiêu chí so khớp:
            // 1. Tiền phải chính xác
            $amountMatch = ($transAmount === (int)$amount);
            
            // 2. Nội dung: kiểm tra xem có chứa các từ khóa hay không
            $descMatch = false;
            if (!empty($descriptionSimple) && !empty($transDescriptionSimple)) {
                // Nếu description request là substring của transaction, hoặc ngược lại
                $descMatch = (strpos($transDescriptionSimple, $descriptionSimple) !== false ||
                             strpos($descriptionSimple, $transDescriptionSimple) !== false);
            }
            
            // 3. Số tài khoản
            $accountMatch = (empty($accountNo) || $transAccountNo === trim($accountNo));
            
            // Nếu tất cả điều kiện match
            if ($amountMatch && $descMatch && $accountMatch) {
                $matchedTransaction = $trans;
                break; // Lấy giao dịch đầu tiên khớp
            }
        }

        if ($matchedTransaction !== null) {
            return [
                'success' => true,
                'message' => 'Thanh toán thành công - Giao dịch đã được xác nhận',
                'transaction' => $matchedTransaction
            ];
        }

        return [
            'success' => false,
            'message' => 'Giao dịch chưa được phát hiện. Vui lòng kiểm tra lại thông tin chuyển khoản.'
        ];
    }

    /**
     * ============================================================
     * CREATE ORDER ON PAYMENT SUCCESS
     * ============================================================
     * URL: /payment/create-order-on-payment (POST)
     * POST Parameters (JSON):
     *   - amount: Số tiền thanh toán
     *   - description: Nội dung chuyển khoản
     *   - address: Địa chỉ giao hàng
     *   - note: Ghi chú
     * 
     * Tự động tạo đơn hàng từ giỏ hàng khi thanh toán QR thành công
     * 
     * Response:
     * {
     *     "success": true,
     *     "message": "Đơn hàng đã được tạo thành công",
     *     "order_id": "Ord0000000001",
     *     "order_data": {...}
     * }
     */
    public function createOrderOnPayment()
    {
        // Set Content-Type header
        header('Content-Type: application/json; charset=utf-8');
        
        // Kiểm tra request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
            return;
        }

        // Kiểm tra user đã login
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized - Vui lòng đăng nhập'
            ]);
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

        // Lấy thông tin từ request
        $amount = isset($data['amount']) ? (int)$data['amount'] : 0;
        $description = isset($data['description']) ? trim($data['description']) : '';
        $address = isset($data['address']) ? trim($data['address']) : '';
        $note = isset($data['note']) ? trim($data['note']) : '';
        $selected = isset($data['selected']) && is_array($data['selected']) ? array_values($data['selected']) : [];
        $quantitiesOverride = isset($data['quantities']) && is_array($data['quantities']) ? $data['quantities'] : [];
        $voucherCode = isset($data['voucher_code']) ? strtoupper(trim($data['voucher_code'])) : '';
        $voucherDiscount = isset($data['voucher_discount']) ? (int)$data['voucher_discount'] : 0;

        // Validate
        if (empty($amount)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'amount là bắt buộc'
            ]);
            return;
        }

        if (empty($address)) {
            // Fallback: dùng địa chỉ từ session user nếu không gửi
            $address = isset($_SESSION['user']['address']) ? trim($_SESSION['user']['address']) : '';
            if (empty($address)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'address là bắt buộc'
                ]);
                return;
            }
        }

        // Lấy giỏ hàng từ database
        $userId = $_SESSION['user']['id'] ?? $_SESSION['user']['username'] ?? '';
        if (empty($userId)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Không tìm thấy thông tin user'
            ]);
            return;
        }

        try {
            $cartModel = new \Models\Cart();
            $orderModel = new \Models\Order();
            $orderDetailModel = new \Models\OrderDetail();
            $variantModel = new \Models\Product_Varirant();
            $productModel = new \Models\Product();

            // Lấy các item trong giỏ hàng
            $cartItems = $cartModel->getCartByUserId($userId);
            if (empty($cartItems)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Giỏ hàng trống - không thể tạo đơn hàng'
                ]);
                return;
            }

            // Chuẩn bị dữ liệu để tạo đơn hàng
            $u = $_SESSION['user'] ?? [];
            $recipientName = isset($data['recipient_name']) ? trim($data['recipient_name']) : ($u['name'] ?? $u['username'] ?? '');
            $recipientPhone = isset($data['recipient_phone']) ? trim($data['recipient_phone']) : ($u['phone'] ?? '');
            if (!empty($recipientPhone)) {
                $um = new \Models\User();
                $bp = $um->getByPhone($recipientPhone);
                if ($bp && ($bp['id'] ?? '') !== $userId) {
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Số điện thoại người nhận đã được sử dụng bởi tài khoản khác'
                    ]);
                    return;
                }
            }
            $noteFull = '[Receiver: ' . $recipientName . ' | Phone: ' . $recipientPhone . '] ' . ($note ?: 'Thanh toán Online') . ($voucherCode !== '' ? (' | Voucher: ' . $voucherCode . ' - ' . $voucherDiscount) : '');

            $orderData = [
                'user_id' => $userId,
                'address' => $address,
                'note' => $noteFull,
                'status' => 'pending',
                'payment_method' => 'online',
                'voucher_code' => $voucherCode,
                'voucher_discount' => $voucherDiscount,
                'order_date' => date('Y-m-d')
            ];

            // Chuẩn bị chi tiết đơn hàng
            $orderDetails = [];
            $totalCartAmount = 0;

            // Nếu có danh sách selected, chỉ xử lý các item đó
            $cartItemsToProcess = [];
            if (!empty($selected)) {
                $selectedMap = array_flip($selected);
                foreach ($cartItems as $ci) {
                    if (isset($selectedMap[$ci['_Cart_Id']])) {
                        $cartItemsToProcess[] = $ci;
                    }
                }
            } else {
                $cartItemsToProcess = $cartItems;
            }

            foreach ($cartItemsToProcess as $cartItem) {
                $variantId = (int)$cartItem['Variant_Id'];
                $cartId = $cartItem['_Cart_Id'] ?? null;
                $quantity = isset($cartId, $quantitiesOverride[$cartId]) ? (int)$quantitiesOverride[$cartId] : (int)$cartItem['Quantity'];

                // Lấy thông tin variant
                $variant = $variantModel->getById($variantId);
                if (!$variant) {
                    continue;
                }

                $productId = $variant['product_id'] ?? $variant['Product_Id'] ?? null;
                if (!$productId) {
                    continue;
                }

                // Lấy thông tin sản phẩm
                $product = $productModel->getById($productId);
                if (!$product) {
                    continue;
                }

                $price = (int)($variant['price'] ?? $product['price'] ?? 0);
                $stock = (int)($variant['stock'] ?? $variant['Quantity_In_Stock'] ?? 0);

                // Kiểm tra tồn kho
                if ($stock <= 0) {
                    // Bỏ qua sản phẩm hết hàng
                    continue;
                }

                if ($quantity > $stock) {
                    $quantity = $stock;
                }

                $subtotal = $price * $quantity;
                $totalCartAmount += $subtotal;

                // Thêm vào chi tiết đơn hàng
                $orderDetails[] = [
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal
                ];
            }

            // Tính tổng thanh toán chuẩn theo front-end: VAT 5% + ship 50k - voucher
            $vatAmount = (int)round($totalCartAmount * 0.05);
            $shippingFee = 50000;
            $expectedAmount = $totalCartAmount + $vatAmount + $shippingFee - max(0, $voucherDiscount);

            // Kiểm tra tổng tiền có khớp không
            if ($expectedAmount !== $amount) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Tổng tiền không khớp. Hệ thống: ' . $expectedAmount . ' VND, thanh toán: ' . $amount . ' VND',
                    'cart_subtotal' => $totalCartAmount,
                    'vat' => $vatAmount,
                    'shipping' => $shippingFee,
                    'voucher_discount' => max(0, $voucherDiscount),
                    'payment_amount' => $amount
                ]);
                return;
            }

            // Tạo đơn hàng với chi tiết
            $orderId = $orderModel->createWithDetails($orderData, $orderDetails);

            if (!$orderId) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Không thể tạo đơn hàng - vui lòng thử lại'
                ]);
                return;
            }

            // Xóa giỏ hàng sau khi tạo đơn hàng thành công (chỉ xóa các item đã xử lý)
            foreach ($cartItemsToProcess as $cartItem) {
                if (!empty($cartItem['_Cart_Id'])) {
                    $cartModel->deleteCart($cartItem['_Cart_Id']);
                }
            }

            // Lấy thông tin đơn hàng vừa tạo
            $order = $orderModel->getByIdWithUser($orderId);

            // Return success response
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Đơn hàng đã được tạo thành công',
                'order_id' => $orderId,
                'order_data' => $order,
                'items_count' => count($orderDetails),
                'total_amount' => $totalCartAmount
            ]);

        } catch (\Exception $e) {
            error_log("Payment createOrderOnPayment Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi server - không thể tạo đơn hàng',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * ============================================================
     * POLL LATEST PAYMENT - Kiểm tra thanh toán mới liên tục
     * ============================================================
     * URL: /payment/poll-latest-payment (POST)
     * POST Parameters (JSON):
     *   - order_id: Mã đơn hàng để theo dõi (tùy chọn)
     *   - user_id: User ID để kiểm tra thanh toán của user
     * 
     * Lấy thanh toán mới nhất từ API, so sánh với hệ thống
     * Nếu khớp: tự động tạo đơn hàng
     * Nếu không khớp: thông báo lỗi
     * 
     * Response:
     * {
     *     "success": true/false,
     *     "message": "...",
     *     "payment": {...},
     *     "order_id": "Ord0000000001" (nếu tạo thành công)
     * }
     */
    public function pollLatestPayment()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
        if (strpos($contentType, 'application/json') !== false) {
            $jsonData = json_decode(file_get_contents('php://input'), true);
            $data = $jsonData ?? [];
        } else {
            $data = $_POST ?? [];
        }

        $userId = $_SESSION['user']['id'] ?? $_SESSION['user']['username'] ?? '';
        $orderId = isset($data['order_id']) ? trim($data['order_id']) : '';
        
        // Gọi API để lấy thanh toán mới nhất
        $latestPayment = $this->getLatestPaymentFromAPI();

        if (!$latestPayment['success']) {
            http_response_code(400);
            echo json_encode($latestPayment);
            return;
        }

        $payment = $latestPayment['data'];
        
        // Kiểm tra xem thanh toán này đã được xử lý chưa
        if ($this->isPaymentProcessed($payment)) {
            echo json_encode([
                'success' => false,
                'message' => 'Thanh toán này đã được xử lý trước đó',
                'payment' => $payment
            ]);
            return;
        }

        // Ghi lại thanh toán vào file để theo dõi
        $this->recordPaymentForTracking($payment);

        // Nếu có order_id, thực hiện so sánh và tạo đơn hàng
        if (!empty($orderId)) {
            // Lấy thông tin đơn hàng để so sánh
            $systemOrderInfo = $this->getSystemOrderInfo($orderId);
            
            if (!$systemOrderInfo['found']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin đơn hàng trong hệ thống',
                    'order_id' => $orderId
                ]);
                return;
            }

            // So sánh thông tin thanh toán
            $comparison = $this->comparePaymentInfo($payment, $systemOrderInfo['data']);
            
            if (!$comparison['match']) {
                // Thanh toán không khớp
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Thanh toán không khớp với thông tin hệ thống: ' . $comparison['reason'],
                    'payment' => $payment,
                    'system_info' => $systemOrderInfo['data'],
                    'comparison' => $comparison
                ]);
                return;
            }

            // Thanh toán khớp - tạo đơn hàng
            $createResult = $this->autoCreateOrderFromPayment($payment, $userId);
            
            if ($createResult['success']) {
                // Đánh dấu thanh toán đã được xử lý
                $this->markPaymentAsProcessed($payment);
                
                http_response_code(201);
                echo json_encode([
                    'success' => true,
                    'message' => 'Thanh toán khớp - Đơn hàng đã được tạo thành công',
                    'payment' => $payment,
                    'order_id' => $createResult['order_id'],
                    'order_data' => $createResult['order_data']
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Thanh toán khớp nhưng không thể tạo đơn hàng: ' . $createResult['message'],
                    'payment' => $payment
                ]);
            }
        } else {
            // Chỉ trả về thông tin thanh toán mới nhất
            echo json_encode([
                'success' => true,
                'message' => 'Thanh toán mới nhất',
                'payment' => $payment
            ]);
        }
    }

    /**
     * Lấy thanh toán mới nhất từ Google Apps Script API
     * Với retry logic và fallback data
     * @return array
     */
    private function getLatestPaymentFromAPI()
    {
        $gasUrl = PaymentHelper::getGoogleAppsScriptUrl();
        
        if (empty($gasUrl)) {
            return [
                'success' => false,
                'message' => 'Payment API chưa được cấu hình',
                'code' => 'NO_API_CONFIG'
            ];
        }

        // Retry logic - thử 3 lần
        $maxRetries = 3;
        $retryDelay = 500; // milliseconds
        $lastError = null;
        
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => $gasUrl . (strpos($gasUrl, '?') === false ? '?' : '&') . 'action=getLatestPayment&t=' . time(),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 15, // Tăng timeout lên 15 giây
                    CURLOPT_CONNECTTIMEOUT => 10, // Connection timeout 10 giây
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_MAXREDIRS => 5,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_USERAGENT => 'DuAn1-PaymentPoller/1.0',
                    CURLOPT_HTTPHEADER => [
                        'Accept: application/json',
                        'Accept-Encoding: gzip, deflate'
                    ]
                ]);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $error = curl_error($ch);
                $curlErrno = curl_errno($ch);
                curl_close($ch);

                // Ghi log chi tiết
                $this->logPaymentPolling($gasUrl, $response, $httpCode, $error, $attempt, $maxRetries);

                // Nếu có lỗi CURL
                if ($error) {
                    $lastError = $error;
                    
                    // Nếu là timeout hoặc connection error, retry
                    if ($curlErrno === CURLE_OPERATION_TIMEDOUT || 
                        $curlErrno === CURLE_COULDNT_CONNECT ||
                        $curlErrno === CURLE_PARTIAL_FILE) {
                        
                        // Delay trước khi retry
                        if ($attempt < $maxRetries) {
                            usleep($retryDelay * 1000);
                        }
                        continue;
                    }
                    
                    // Lỗi khác thì return ngay
                    return [
                        'success' => false,
                        'message' => 'Lỗi kết nối API: ' . $error,
                        'code' => 'CURL_ERROR',
                        'errno' => $curlErrno,
                        'attempt' => $attempt
                    ];
                }

                // Nếu không có response
                if (empty($response)) {
                    $lastError = 'API không trả về dữ liệu';
                    
                    if ($attempt < $maxRetries) {
                        usleep($retryDelay * 1000);
                    }
                    continue;
                }

                // Parse response
                $result = json_decode($response, true);
                
                if (!is_array($result)) {
                    $lastError = 'API trả về dữ liệu không hợp lệ (không phải JSON)';
                    
                    if ($attempt < $maxRetries) {
                        usleep($retryDelay * 1000);
                    }
                    continue;
                }

                // Nếu API trả về data array (danh sách transactions)
                if (isset($result['data']) && is_array($result['data']) && !empty($result['data'])) {
                    // Lấy transaction cuối cùng (mới nhất)
                    $latestPayment = end($result['data']);
                    return [
                        'success' => true,
                        'data' => $latestPayment,
                        'attempt' => $attempt,
                        'http_code' => $httpCode
                    ];
                }

                // Nếu API trả về single transaction
                if (isset($result['Mã GD']) || isset($result['Giá trị']) || 
                    isset($result['payment_id']) || isset($result['amount'])) {
                    return [
                        'success' => true,
                        'data' => $result,
                        'attempt' => $attempt,
                        'http_code' => $httpCode
                    ];
                }

                // Nếu response trống
                $lastError = 'Không tìm thấy dữ liệu thanh toán từ API';
                if ($attempt < $maxRetries) {
                    usleep($retryDelay * 1000);
                }
                continue;

            } catch (\Exception $e) {
                $lastError = $e->getMessage();
                
                if ($attempt < $maxRetries) {
                    usleep($retryDelay * 1000);
                }
                continue;
            }
        }

        // Nếu tất cả retry đều thất bại
        return [
            'success' => false,
            'message' => 'API timeout hoặc lỗi sau ' . $maxRetries . ' lần thử: ' . $lastError,
            'code' => 'API_TIMEOUT',
            'lastError' => $lastError,
            'attempts' => $maxRetries
        ];
    }

    /**
     * Kiểm tra xem thanh toán đã được xử lý chưa
     * @param array $payment
     * @return bool
     */
    private function isPaymentProcessed($payment)
    {
        $stateFile = ROOT_PATH . '/storage/payment_polling_state.json';
        
        if (!file_exists($stateFile)) {
            return false;
        }

        $state = json_decode(file_get_contents($stateFile), true) ?? [];
        $lastCheckedId = $state['last_checked_payment_id'] ?? null;
        $lastTimestamp = $state['last_payment_timestamp'] ?? null;

        $paymentId = $payment['Mã GD'] ?? $payment['payment_id'] ?? null;
        $paymentTime = $payment['Ngày diễn ra'] ?? $payment['timestamp'] ?? null;

        // Nếu cùng ID hoặc cùng timestamp, thì đã được xử lý
        return ($paymentId && $paymentId === $lastCheckedId) || 
               ($paymentTime && $paymentTime === $lastTimestamp);
    }

    /**
     * Ghi lại thanh toán vào file theo dõi
     * @param array $payment
     */
    private function recordPaymentForTracking($payment)
    {
        $stateFile = ROOT_PATH . '/storage/payment_polling_state.json';
        
        $state = file_exists($stateFile) ? json_decode(file_get_contents($stateFile), true) : [];
        
        $state['last_checked_payment_id'] = $payment['Mã GD'] ?? $payment['payment_id'] ?? null;
        $state['last_payment_timestamp'] = $payment['Ngày diễn ra'] ?? $payment['timestamp'] ?? date('Y-m-d H:i:s');
        $state['last_polling_timestamp'] = date('Y-m-d H:i:s');

        @file_put_contents($stateFile, json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Đánh dấu thanh toán đã được xử lý
     * @param array $payment
     */
    private function markPaymentAsProcessed($payment)
    {
        $stateFile = ROOT_PATH . '/storage/payment_polling_state.json';
        
        $state = file_exists($stateFile) ? json_decode(file_get_contents($stateFile), true) : [];
        
        if (!isset($state['created_orders'])) {
            $state['created_orders'] = [];
        }

        $state['created_orders'][] = [
            'payment_id' => $payment['Mã GD'] ?? $payment['payment_id'] ?? null,
            'payment_amount' => $payment['Giá trị'] ?? $payment['amount'] ?? 0,
            'processed_at' => date('Y-m-d H:i:s')
        ];

        @file_put_contents($stateFile, json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Lấy thông tin đơn hàng từ hệ thống để so sánh
     * @param string $orderId
     * @return array
     */
    private function getSystemOrderInfo($orderId)
    {
        try {
            $orderModel = new \Models\Order();
            $order = $orderModel->getById($orderId);

            if (!$order) {
                return ['found' => false];
            }

            // Lấy chi tiết đơn hàng và tính tổng thanh toán cuối cùng
            $orderDetailModel = new \Models\OrderDetail();
            $orderDetails = $orderDetailModel->getByOrderId($orderId);

            $subtotal = 0;
            foreach ($orderDetails as $detail) {
                $qty = (int)($detail['quantity'] ?? $detail['Quantity'] ?? 0);
                $price = (float)($detail['Price'] ?? $detail['price'] ?? 0);
                $subtotal += $qty * $price;
            }
            $vat = (int)round($subtotal * 0.05);
            $ship = 50000;
            $voucherDiscount = 0;
            $noteStr = (string)($order['Note'] ?? '');
            if ($noteStr !== '' && preg_match('/Voucher:\s*([A-Z0-9_-]+)\s*-\s*(\d+)/i', $noteStr, $m)) {
                $voucherDiscount = (int)($m[2] ?? 0);
            }
            $totalAmount = max(0, $subtotal + $vat + $ship - max(0, $voucherDiscount));

            return [
                'found' => true,
                'data' => [
                    'order_id' => $orderId,
                    'amount' => $totalAmount,
                    'description' => 'Thanh toan - ' . (isset($order['user_id']) ? $order['user_id'] : $order['User_Id'] ?? ''),
                    'account_no' => PaymentHelper::getQRConfig()['account_no'],
                    'status' => $order['status'] ?? $order['Status'] ?? 'pending'
                ]
            ];
        } catch (\Exception $e) {
            error_log("getSystemOrderInfo Error: " . $e->getMessage());
            return ['found' => false];
        }
    }

    /**
     * So sánh thông tin thanh toán API với hệ thống
     * @param array $apiPayment - Thanh toán từ API
     * @param array $systemOrder - Thông tin từ hệ thống
     * @return array
     */
    private function comparePaymentInfo($apiPayment, $systemOrder)
    {
        $apiAmount = (int)($apiPayment['Giá trị'] ?? $apiPayment['amount'] ?? 0);
        $systemAmount = (int)($systemOrder['amount'] ?? 0);

        $apiDescription = $apiPayment['Mô tả'] ?? $apiPayment['description'] ?? '';
        $systemDescription = $systemOrder['description'] ?? '';

        $apiAccountNo = preg_replace('/\D+/', '', trim($apiPayment['Số tài khoản'] ?? $apiPayment['account_no'] ?? ''));
        $systemAccountNo = preg_replace('/\D+/', '', trim($systemOrder['account_no'] ?? ''));

        // Chuẩn hóa để so sánh
        $apiDescriptionSimple = preg_replace('/[^A-Z0-9]/i', '', strtoupper($apiDescription));
        $systemDescriptionSimple = preg_replace('/[^A-Z0-9]/i', '', strtoupper($systemDescription));

        // ===== SO SÁNH SỐ TIỀN =====
        $amountMatch = $apiAmount === $systemAmount;

        // ===== SO SÁNH NỘI DUNG =====
        // Yêu cầu: Nếu API description chứa bất kỳ từ khóa nào từ hệ thống HOẶC
        // Nếu hệ thống description đơn giản, chỉ cần API description chứa các ký tự quan trọng
        $descriptionMatch = false;
        
        if (!empty($apiDescriptionSimple) && !empty($systemDescriptionSimple)) {
            // Nếu API chứa hệ thống, hoặc hệ thống chứa API
            $descriptionMatch = (strpos($apiDescriptionSimple, $systemDescriptionSimple) !== false ||
                               strpos($systemDescriptionSimple, $apiDescriptionSimple) !== false);
            
            // HOẶC nếu API description chứa các từ khóa quan trọng từ hệ thống
            if (!$descriptionMatch) {
                // Extract keywords từ system description (bỏ các từ chung)
                $systemKeywords = array_filter(explode(' ', $systemDescriptionSimple));
                $apiKeywords = array_filter(explode(' ', $apiDescriptionSimple));
                
                // Nếu hơn 50% từ khóa hệ thống có trong API description
                if (count($systemKeywords) > 0) {
                    $matchCount = 0;
                    foreach ($systemKeywords as $keyword) {
                        if (strlen($keyword) > 2 && strpos($apiDescriptionSimple, $keyword) !== false) {
                            $matchCount++;
                        }
                    }
                    $matchPercentage = count($systemKeywords) > 0 ? ($matchCount / count($systemKeywords)) : 0;
                    $descriptionMatch = $matchPercentage >= 0.5; // 50% match là ok
                }
            }
        } else {
            // Nếu một trong hai trống, chỉ match nếu cả hai trống hoặc không yêu cầu
            $descriptionMatch = empty($systemDescriptionSimple) || !empty($apiDescriptionSimple);
        }

        // ===== SO SÁNH TÀI KHOẢN =====
        $accountMatch = (empty($apiAccountNo) || empty($systemAccountNo) || $apiAccountNo === $systemAccountNo);

        // Tất cả phải match
        $allMatch = $amountMatch && $descriptionMatch && $accountMatch;

        if (!$allMatch) {
            $reasons = [];
            if (!$amountMatch) {
                $reasons[] = "Số tiền không khớp (API: {$apiAmount}, Hệ thống: {$systemAmount})";
            }
            if (!$descriptionMatch) {
                $reasons[] = "Nội dung không khớp đủ từ khóa (API: {$apiDescription}, Hệ thống: {$systemDescription})";
            }
            if (!$accountMatch) {
                $reasons[] = "Số tài khoản không khớp (API: {$apiAccountNo}, Hệ thống: {$systemAccountNo})";
            }

            return [
                'match' => false,
                'reason' => implode('; ', $reasons),
                'details' => [
                    'amount' => $amountMatch,
                    'description' => $descriptionMatch,
                    'account_no' => $accountMatch
                ]
            ];
        }

        return [
            'match' => true,
            'details' => [
                'amount' => $amountMatch,
                'description' => $descriptionMatch,
                'account_no' => $accountMatch
            ]
        ];
    }

    /**
     * Tự động tạo đơn hàng từ thông tin thanh toán
     * @param array $payment
     * @param string $userId
     * @return array
     */
    private function autoCreateOrderFromPayment($payment, $userId)
    {
        try {
            $cartModel = new \Models\Cart();
            $orderModel = new \Models\Order();
            $variantModel = new \Models\Product_Varirant();
            $productModel = new \Models\Product();

            // Lấy các item trong giỏ hàng
            $cartItems = $cartModel->getCartByUserId($userId);
            if (empty($cartItems)) {
                return [
                    'success' => false,
                    'message' => 'Giỏ hàng trống'
                ];
            }

            // Chuẩn bị dữ liệu
            $orderData = [
                'user_id' => $userId,
                'address' => isset($_SESSION['user']['address']) ? $_SESSION['user']['address'] : 'Chưa cấp nhật địa chỉ',
                'note' => 'Thanh toán tự động: ' . ($payment['Mã GD'] ?? $payment['payment_id'] ?? ''),
                'status' => 'confirmed',
                'payment_status' => 'completed',
                'payment_id' => $payment['Mã GD'] ?? $payment['payment_id'] ?? '',
                'order_date' => date('Y-m-d')
            ];

            $orderDetails = [];
            $totalAmount = 0;

            foreach ($cartItems as $cartItem) {
                $variantId = (int)$cartItem['Variant_Id'];
                $quantity = (int)$cartItem['Quantity'];

                $variant = $variantModel->getById($variantId);
                if (!$variant) continue;

                $productId = $variant['product_id'] ?? $variant['Product_Id'] ?? null;
                if (!$productId) continue;

                $product = $productModel->getById($productId);
                if (!$product) continue;

                $price = (int)($variant['price'] ?? $product['price'] ?? 0);
                $stock = (int)($variant['stock'] ?? $variant['Quantity_In_Stock'] ?? 0);

                if ($stock <= 0 || $quantity > $stock) {
                    $quantity = min($quantity, $stock);
                }

                if ($quantity <= 0) continue;

                $subtotal = $price * $quantity;
                $totalAmount += $subtotal;

                $orderDetails[] = [
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal
                ];
            }

            if (empty($orderDetails)) {
                return [
                    'success' => false,
                    'message' => 'Không có sản phẩm hợp lệ trong giỏ hàng'
                ];
            }

            // Tạo đơn hàng
            $orderId = $orderModel->createWithDetails($orderData, $orderDetails);

            if (!$orderId) {
                return [
                    'success' => false,
                    'message' => 'Không thể tạo đơn hàng'
                ];
            }

            // Xóa giỏ hàng
            foreach ($cartItems as $cartItem) {
                $cartModel->deleteCart($cartItem['_Cart_Id']);
            }

            $order = $orderModel->getByIdWithUser($orderId);

            return [
                'success' => true,
                'order_id' => $orderId,
                'order_data' => $order,
                'total_amount' => $totalAmount
            ];

        } catch (\Exception $e) {
            error_log("autoCreateOrderFromPayment Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Ghi log polling activity với retry info
     * @param string $apiUrl
     * @param string $response
     * @param int $httpCode
     * @param string $error
     * @param int $attempt
     * @param int $maxRetries
     */
    private function logPaymentPolling($apiUrl, $response, $httpCode, $error, $attempt = 1, $maxRetries = 1)
    {
        try {
            $logDir = ROOT_PATH . '/storage';
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0755, true);
            }
            
            $logFile = $logDir . '/payment_polling.log';
            $logEntry = "---\n";
            $logEntry .= "TIMESTAMP: " . date('Y-m-d H:i:s') . "\n";
            $logEntry .= "API_URL: " . $apiUrl . "\n";
            $logEntry .= "ATTEMPT: " . $attempt . "/" . $maxRetries . "\n";
            $logEntry .= "HTTP_CODE: " . $httpCode . "\n";
            $logEntry .= "CURL_ERROR: " . $error . "\n";
            $logEntry .= "RESPONSE_LENGTH: " . strlen($response) . " bytes\n";
            $logEntry .= "RESPONSE: " . (strlen($response) > 500 ? substr($response, 0, 500) . '...' : $response) . "\n";
            $logEntry .= "---\n\n";
            
            @file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
        } catch (\Exception $e) {
            // Ignore logging errors
        }
    }
}
