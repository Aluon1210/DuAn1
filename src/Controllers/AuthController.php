<?php
// File: src/Controllers/AuthController.php
namespace Controllers;

use Core\Controller;
use Models\User;

class AuthController extends Controller {
    
    /**
     * Hiển thị trang đăng nhập
     * URL: /login
     */
    public function index() {
        // Nếu đã đăng nhập, chuyển về trang thích hợp theo role
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] === 'admin') {
                header('Location: ' . ROOT_URL . 'admin/dashboard');
            } else {
                header('Location: ' . ROOT_URL);
            }
            exit;
        }
        
        $data = [
            'title' => 'Đăng Nhập - Luxury Fashion Store'
        ];
        
        $this->renderView('auth/login_full', $data);
    }
    
    /**
     * Xử lý đăng nhập
     * URL: /login/process
     */
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }
        
        $userModel = new User();
        $user = $userModel->authenticate($email, $password);
        
        if ($user) {
            // If account is blocked (forbident), show blocked page and do not create session
            if (isset($user['role']) && $user['role'] === 'forbident') {
                $data = ['message' => 'Tài khoản của bạn đã bị chặn và không thể đăng nhập vào hệ thống. Vui lòng liên hệ quản trị.'];
                $this->renderView('auth/forbidden', $data);
                exit;
            }

            $_SESSION['user'] = $user;
            $_SESSION['message'] = 'Đăng nhập thành công!';

            // Redirect theo role
            if ($user['role'] === 'admin') {
                header('Location: ' . ROOT_URL . 'admin/dashboard');
            } else {
                header('Location: ' . ROOT_URL);
            }
            exit;
        } else {
            $_SESSION['error'] = 'Email hoặc mật khẩu không đúng';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }
    }
    
    /**
     * Hiển thị trang đăng ký
     * URL: /register
     */
    public function register() {
        // Nếu đã đăng nhập, chuyển về trang chủ
        if (isset($_SESSION['user'])) {
            header('Location: ' . ROOT_URL);
            exit;
        }
        
        $data = [
            'title' => 'Đăng Ký - Luxury Fashion Store'
        ];
        
        $this->renderView('auth/register_full', $data);
    }
    
    /**
     * Xử lý đăng ký
     * URL: /register/process
     */
    public function registerProcess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'register');
            exit;
        }
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        
        // Validation - chỉ kiểm tra các trường bắt buộc
        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin bắt buộc (Họ tên, Email, Mật khẩu)';
            header('Location: ' . ROOT_URL . 'register');
            exit;
        }
        
        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp';
            header('Location: ' . ROOT_URL . 'register');
            exit;
        }
        
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Mật khẩu phải có ít nhất 6 ký tự';
            header('Location: ' . ROOT_URL . 'register');
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email không hợp lệ';
            header('Location: ' . ROOT_URL . 'register');
            exit;
        }
        
        $userModel = new User();
        
        // Kiểm tra email đã tồn tại chưa
        if ($userModel->getByEmail($email)) {
            $_SESSION['error'] = 'Email này đã được sử dụng';
            header('Location: ' . ROOT_URL . 'register');
            exit;
        }
        
        try {
            // Tạo user mới (username sẽ được tự động generate theo format KH + 000000001)
            $usernameCreated = $userModel->createUser([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'phone' => $phone,
                'role' => 'user',
                'address' => $address
            ]);
            
            if ($usernameCreated) {
                // Lấy thông tin user vừa tạo (không có password)
                $user = $userModel->getById($usernameCreated);
                if ($user) {
                    unset($user['password']);
                    $_SESSION['user'] = $user;
                    $_SESSION['message'] = 'Đăng ký thành công! Chào mừng bạn đến với Luxury Fashion Store';
                    header('Location: ' . ROOT_URL);
                    exit;
                } else {
                    $_SESSION['error'] = 'Đăng ký thành công nhưng không thể lấy thông tin tài khoản. Vui lòng đăng nhập.';
                    header('Location: ' . ROOT_URL . 'login');
                    exit;
                }
            } else {
                $_SESSION['error'] = 'Đăng ký thất bại. Không thể tạo tài khoản.';
                header('Location: ' . ROOT_URL . 'register');
                exit;
            }
        } catch (\PDOException $e) {
            // Lỗi SQL chi tiết
            error_log("Registration SQL Error: " . $e->getMessage());
            $_SESSION['error'] = 'Đăng ký thất bại. Lỗi: ' . $e->getMessage();
            header('Location: ' . ROOT_URL . 'register');
            exit;
        } catch (\Exception $e) {
            error_log("Registration Error: " . $e->getMessage());
            $_SESSION['error'] = 'Đăng ký thất bại. Vui lòng thử lại sau.';
            header('Location: ' . ROOT_URL . 'register');
            exit;
        }
    }
    
    /**
     * Đăng xuất
     * URL: /logout
     */
    public function logout() {
        unset($_SESSION['user']);
        $_SESSION['message'] = 'Đăng xuất thành công';
        header('Location: ' . ROOT_URL);
        exit;
    }

    /**
     * Redirect user to Google OAuth consent screen
     * URL: /auth/google
     */
    public function google()
    {
        $config = require ROOT_PATH . '/src/Config/google_oauth.php';
        $clientId = $config['client_id'] ?? '';
        $redirectUri = $config['redirect_uri'] ?? (ROOT_URL . 'auth/google-callback');

        if (empty($clientId)) {
            $_SESSION['error'] = 'Google OAuth chưa được cấu hình.';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        $scope = urlencode('openid email profile');
        $state = bin2hex(random_bytes(8));
        $_SESSION['oauth2_state'] = $state;

        $authUrl = $config['auth_url']
            . '?response_type=code'
            . '&client_id=' . urlencode($clientId)
            . '&redirect_uri=' . urlencode($redirectUri)
            . '&scope=' . $scope
            . '&access_type=online'
            . '&prompt=select_account'
            . '&state=' . $state;

        header('Location: ' . $authUrl);
        exit;
    }

    /**
     * Handle Google OAuth callback
     * URL: /auth/google/callback
     */
    public function googleCallback()
    {
        // Basic validation
        $config = require ROOT_PATH . '/src/Config/google_oauth.php';
        $clientId = $config['client_id'] ?? '';
        $clientSecret = $config['client_secret'] ?? '';
        $tokenUrl = $config['token_url'] ?? 'https://oauth2.googleapis.com/token';
        $userinfoUrl = $config['userinfo_url'] ?? 'https://www.googleapis.com/oauth2/v2/userinfo';
        $redirectUri = $config['redirect_uri'] ?? (ROOT_URL . 'auth/google-callback');

        // Check state
        if (!isset($_GET['state']) || ($_GET['state'] ?? '') !== ($_SESSION['oauth2_state'] ?? '')) {
            $_SESSION['error'] = 'State không hợp lệ. Vui lòng thử lại.';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        if (!isset($_GET['code'])) {
            $_SESSION['error'] = 'Không có mã xác thực từ Google.';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        $code = $_GET['code'];

        // Exchange code for tokens
        $post = http_build_query([
            'code' => $code,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code'
        ]);

        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $post,
                'timeout' => 10
            ]
        ];

        $context = stream_context_create($opts);
        $response = @file_get_contents($tokenUrl, false, $context);
        if ($response === false) {
            $_SESSION['error'] = 'Lỗi khi trao đổi mã với Google.';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        $tokenData = json_decode($response, true);
        if (empty($tokenData['access_token'])) {
            $_SESSION['error'] = 'Không nhận được access token từ Google.';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        $accessToken = $tokenData['access_token'];

        // Get user info
        $optsGet = [
            'http' => [
                'method' => 'GET',
                'header' => "Authorization: Bearer " . $accessToken . "\r\n",
                'timeout' => 10
            ]
        ];
        $ctx = stream_context_create($optsGet);
        $ui = @file_get_contents($userinfoUrl, false, $ctx);
        if ($ui === false) {
            $_SESSION['error'] = 'Không thể lấy thông tin người dùng từ Google.';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        $userInfo = json_decode($ui, true);
        $email = $userInfo['email'] ?? null;
        $name = $userInfo['name'] ?? ($userInfo['given_name'] ?? '');

        if (!$email) {
            $_SESSION['error'] = 'Google không cung cấp email. Vui lòng sử dụng phương thức khác.';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        $userModel = new User();
        $existing = $userModel->getByEmail($email);
        if (!$existing) {
            // Create a new user with random password
            $randomPassword = bin2hex(random_bytes(8));
            $created = $userModel->createUser([
                'name' => $name,
                'email' => $email,
                'password' => $randomPassword,
                'phone' => '',
                'role' => 'user',
                'address' => ''
            ]);

            if (!$created) {
                $_SESSION['error'] = 'Không thể tạo tài khoản từ Google. Vui lòng thử lại.';
                header('Location: ' . ROOT_URL . 'login');
                exit;
            }
            $existing = $userModel->getByEmail($email);
        }

        // Login the user
        if ($existing) {
            unset($existing['password']);
            $_SESSION['user'] = $existing;
            $_SESSION['message'] = 'Đăng nhập thành công bằng Google';
            header('Location: ' . ROOT_URL);
            exit;
        }

        $_SESSION['error'] = 'Đăng nhập bằng Google thất bại.';
        header('Location: ' . ROOT_URL . 'login');
        exit;
    }

    public function sendResetLink()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }
        $email = trim($_POST['email'] ?? '');
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email không hợp lệ';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }
        $userModel = new User();
        $user = $userModel->getByEmail($email);
        $token = bin2hex(random_bytes(32));
        $expires = time() + 1800;
        $storageDir = ROOT_PATH . '/storage';
        if (!is_dir($storageDir)) {
            @mkdir($storageDir, 0755, true);
        }
        $resetFile = $storageDir . '/password_resets.json';
        $resets = [];
        if (file_exists($resetFile)) {
            $json = @file_get_contents($resetFile);
            $arr = json_decode($json, true);
            if (is_array($arr)) {
                $resets = $arr;
            }
        }
        $now = time();
        $resets = array_values(array_filter($resets, function($r) use ($now, $email) {
            return isset($r['email'], $r['token'], $r['expires']) && $r['email'] !== $email && (int)$r['expires'] > $now;
        }));
        $resets[] = ['email' => $email, 'token' => $token, 'expires' => $expires];
        @file_put_contents($resetFile, json_encode($resets), LOCK_EX);
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseUrl = rtrim($scheme . '://' . $host . ROOT_URL, '/');
        $resetUrl = $baseUrl . '/auth/reset-password/' . urlencode($token);
        $subject = 'Dat lai mat khau';
        $body = "Ban da yeu cau dat lai mat khau.\nLien ket: " . $resetUrl . "\nLien ket het han sau 30 phut.";
        $sent = false;
        $mailConf = require ROOT_PATH . '/src/Config/email.php';
        $smtpConf = $mailConf['smtp'] ?? [];
        if (!empty($smtpConf['enabled'])) {
            try {
                $fromEmail = $smtpConf['from_email'] ?? '';
                $fromName = $smtpConf['from_name'] ?? '';
                $sent = \Core\EmailSender::send($email, $subject, $body, $fromEmail, $fromName);
            } catch (\Throwable $e) {
                $sent = false;
            }
        }
        if (!$sent && $user) {
            $headers = 'From: ' . ($smtpConf['from_email'] ?? 'noreply@localhost');
            @mail($email, $subject, $body, $headers);
        }
        if (!$sent) {
            try {
                $sent = \Core\EmailSender::sendCustom($email, $subject, $body, [
                    'host' => $smtpConf['host'] ?? 'smtp.gmail.com',
                    'port' => 465,
                    'username' => $smtpConf['username'] ?? '',
                    'password' => $smtpConf['password'] ?? '',
                    'encryption' => 'ssl',
                    'timeout' => (int)($smtpConf['timeout'] ?? 30),
                    'from_email' => $smtpConf['from_email'] ?? '',
                    'from_name' => $smtpConf['from_name'] ?? ''
                ]);
            } catch (\Throwable $e) {}
        }
        $logFile = $storageDir . '/password_reset_links.log';
        $logEntry = date('Y-m-d H:i:s') . ' | ' . $email . ' | ' . $resetUrl . "\n";
        @file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
        if ($sent) {
            $_SESSION['message'] = 'Đã gửi liên kết đặt lại mật khẩu. Vui lòng kiểm tra email.';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        } else {
            $_SESSION['error'] = 'Không thể gửi email đặt lại mật khẩu. Vui lòng thử lại sau.';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }
    }

    public function resetPassword($token = null)
    {
        $storageDir = ROOT_PATH . '/storage';
        $resetFile = $storageDir . '/password_resets.json';
        $resets = [];
        if (file_exists($resetFile)) {
            $json = @file_get_contents($resetFile);
            $arr = json_decode($json, true);
            if (is_array($arr)) {
                $resets = $arr;
            }
        }
        $now = time();
        $record = null;
        foreach ($resets as $r) {
            if (isset($r['token'], $r['expires']) && $r['token'] === (string)$token && (int)$r['expires'] > $now) {
                $record = $r;
                break;
            }
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tok = $_POST['token'] ?? '';
            $pwd = $_POST['password'] ?? '';
            $cf = $_POST['confirm_password'] ?? '';
            if ($tok === '' || $pwd === '' || $cf === '' || $pwd !== $cf || strlen($pwd) < 6) {
                $_SESSION['error'] = 'Thông tin không hợp lệ';
                header('Location: ' . ROOT_URL . 'login');
                exit;
            }
            $record = null;
            foreach ($resets as $r) {
                if (isset($r['token'], $r['expires']) && $r['token'] === (string)$tok && (int)$r['expires'] > time()) {
                    $record = $r;
                    break;
                }
            }
            if (!$record || empty($record['email'])) {
                $_SESSION['error'] = 'Liên kết không hợp lệ hoặc đã hết hạn';
                header('Location: ' . ROOT_URL . 'login');
                exit;
            }
            $userModel = new User();
            $user = $userModel->getByEmail($record['email']);
            if (!$user || empty($user['id'])) {
                $_SESSION['error'] = 'Không tìm thấy tài khoản';
                header('Location: ' . ROOT_URL . 'login');
                exit;
            }
            $ok = $userModel->updateUser($user['id'], ['password' => $pwd]);
            if ($ok) {
                $resets = array_values(array_filter($resets, function($r) use ($tok) {
                    return $r['token'] !== $tok;
                }));
                @file_put_contents($resetFile, json_encode($resets), LOCK_EX);
                $_SESSION['message'] = 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập.';
                header('Location: ' . ROOT_URL . 'login');
                exit;
            } else {
                $_SESSION['error'] = 'Không thể đặt lại mật khẩu';
                header('Location: ' . ROOT_URL . 'login');
                exit;
            }
        } else {
            if (!$record) {
                $_SESSION['error'] = 'Liên kết không hợp lệ hoặc đã hết hạn';
                header('Location: ' . ROOT_URL . 'login');
                exit;
            }
            $data = [
                'title' => 'Đặt lại mật khẩu',
                'token' => $token
            ];
            $this->renderView('auth/reset_password_full', $data);
        }
    }
}
?>

