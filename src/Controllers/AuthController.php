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
        // If this request is a Google OAuth callback (Google redirected here), handle it
        if (isset($_GET['code']) && isset($_GET['state'])) {
            // Delegate to existing googleCallback handler which will validate state and exchange code
            $this->googleCallback();
            return;
        }

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
     * Chuyển hướng đến Google OAuth
     * URL: /auth/google
     */
    public function google() {
        $config = require ROOT_PATH . '/src/Config/google_oauth.php';
        
        if (empty($config['client_id']) || empty($config['client_secret'])) {
            $_SESSION['error'] = 'Google OAuth chưa được cấu hình. Vui lòng liên hệ quản trị viên.';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        $params = [
            'client_id' => $config['client_id'],
            'redirect_uri' => $config['redirect_uri'],
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => bin2hex(random_bytes(16))
        ];
        
        // Lưu state vào session để xác minh sau
        $_SESSION['oauth_state'] = $params['state'];
        
        $authUrl = $config['auth_url'] . '?' . http_build_query($params);
        header('Location: ' . $authUrl);
        exit;
    }

    /**
     * Xử lý callback từ Google
     * URL: /auth/google_callback
     */
    public function googleCallback() {
        $config = require ROOT_PATH . '/src/Config/google_oauth.php';
        
        // Kiểm tra state để tránh CSRF attack
        if (empty($_GET['state']) || $_GET['state'] !== ($_SESSION['oauth_state'] ?? '')) {
            $_SESSION['error'] = 'Yêu cầu OAuth không hợp lệ. Vui lòng thử lại.';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        if (empty($_GET['code'])) {
            $_SESSION['error'] = 'Không thể lấy mã xác thực từ Google. Vui lòng thử lại.';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        // Đổi authorization code lấy access token
        $tokenData = [
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'code' => $_GET['code'],
            'grant_type' => 'authorization_code',
            'redirect_uri' => $config['redirect_uri']
        ];

        $ch = curl_init($config['token_url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        curl_close($ch);

        $tokenResponse = json_decode($response, true);

        if (empty($tokenResponse['access_token'])) {
            $_SESSION['error'] = 'Không thể lấy access token từ Google.';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        // Lấy thông tin user từ Google
        $ch = curl_init($config['userinfo_url'] . '?access_token=' . $tokenResponse['access_token']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $userInfoResponse = curl_exec($ch);
        curl_close($ch);

        $googleUser = json_decode($userInfoResponse, true);

        if (empty($googleUser['email'])) {
            $_SESSION['error'] = 'Không thể lấy email từ Google.';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }

        // Tìm hoặc tạo user trong database
        $userModel = new User();
        $user = $userModel->getByEmail($googleUser['email']);

        if (!$user) {
            // Tạo user mới từ Google
            $newUsername = $userModel->createUser([
                'name' => $googleUser['name'] ?? $googleUser['email'],
                'email' => $googleUser['email'],
                'password' => bin2hex(random_bytes(16)), // Random password, user không thể dùng để login thường
                'phone' => '',
                'role' => 'user',
                'address' => ''
            ]);

            if ($newUsername) {
                $user = $userModel->getById($newUsername);
            }
        }

        if ($user) {
            unset($user['password']);
            $_SESSION['user'] = $user;
            $_SESSION['message'] = 'Đăng nhập thành công qua Google!';
            
            // Redirect theo role
            if ($user['role'] === 'admin') {
                header('Location: ' . ROOT_URL . 'admin/dashboard');
            } else {
                header('Location: ' . ROOT_URL);
            }
            exit;
        } else {
            $_SESSION['error'] = 'Không thể tạo hoặc lấy tài khoản từ Google.';
            header('Location: ' . ROOT_URL . 'login');
            exit;
        }
    }
}