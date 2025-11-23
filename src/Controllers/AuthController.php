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
        // Nếu đã đăng nhập, chuyển về trang chủ
        if (isset($_SESSION['user'])) {
            header('Location: ' . ROOT_URL);
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
            $_SESSION['user'] = $user;
            $_SESSION['message'] = 'Đăng nhập thành công!';
            
            // Chuyển về trang trước đó hoặc trang chủ
            $redirect = $_GET['redirect'] ?? ($_POST['redirect'] ?? ROOT_URL);
            header('Location: ' . $redirect);
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
}
?>

