<?php
require_once 'models/User.php';

class HomeController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index() {
        // Lấy dữ liệu từ model
        $users = $this->userModel->getAll();
        
        // Load view
        require_once 'views/home.php';
    }

    public function about() {
        require_once 'views/about.php';
    }
}
?>
