<?php
// File: src/Controllers/AdminController.php
namespace Controllers;

use Core\Controller;
use Models\Product;
use Models\Category;
use Models\Branch;
use Models\Color;
use Models\Size;
use Models\Product_Varirant;
use Models\User;

class AdminController extends Controller {

    // Trong AdminController

public function users() {
    $this->requireAdmin();
    $userModel = new User();
    $users = $userModel->getAllUsers();
    $data = [
        'title' => 'Quản lý người dùng',
        'users' => $users,
        'totalUsers' => count($users),
        'editing' => false
    ];

    // Nếu có tham số ?edit=ID thì load user đó để điền vào form
    if (isset($_GET['edit']) && $_GET['edit'] !== '') {
        $editId = trim($_GET['edit']);
        $user = $userModel->getById($editId);
        if ($user) {
            $data['editing'] = true;
            $data['user'] = $user;
            $data['title'] = 'Sửa người dùng';
        } else {
            $_SESSION['error'] = 'Người dùng không tồn tại';
            // giữ nguyên danh sách, không dừng
        }
    }

    $this->renderView('admin/user', $data);
}

/**
 * Hiển thị form sửa user
 * URL: /admin/editUser/{id}
 */
public function editUser($id) {
    $userModel = new User();
    $user = $userModel->getById($id);
    
    if (!$user) {
        $_SESSION['error'] = 'Người dùng không tồn tại';
        header('Location: ' . ROOT_URL . 'admin/users');
        exit;
    }
    
    $users = $userModel->getAllUsers();
    
    $data = [
        'title' => 'Sửa người dùng',
        'user' => $user,
        'users' => $users,
        'totalUsers' => count($users),
        'editing' => true
    ];
    
    $this->renderView('admin/user', $data);
}

/**
 * Xử lý thêm / cập nhật user
 * URL: /admin/saveUser (POST)
 */
public function saveUser() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . ROOT_URL . 'admin/users');
        exit;
    }
    
    $userModel = new User();
    
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : 'user';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    // Validation
    if ($email === '' || $name === '') {
        $_SESSION['error'] = 'Email và Họ tên không được để trống';
        header('Location: ' . ROOT_URL . 'admin/users');
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Email không hợp lệ';
        header('Location: ' . ROOT_URL . 'admin/users');
        exit;
    }
    
    try {
        if ($id) {
            // Cập nhật
            $updateData = [
                'email' => $email,
                'name' => $name,
                'phone' => $phone,
                'role' => $role,
                'address' => $address
            ];
            if ($password !== '') {
                $updateData['password'] = $password;
            }
            $success = $userModel->updateUser($id, $updateData);
            $_SESSION['message'] = $success ? 'Cập nhật người dùng thành công' : 'Không thể cập nhật người dùng';
        } else {
            // Thêm mới
            if ($password === '') {
                $_SESSION['error'] = 'Mật khẩu không được để trống khi thêm người dùng mới';
                header('Location: ' . ROOT_URL . 'admin/users');
                exit;
            }
            
            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Mật khẩu phải có ít nhất 6 ký tự';
                header('Location: ' . ROOT_URL . 'admin/users');
                exit;
            }
            
            $createData = [
                'email' => $email,
                'name' => $name,
                'phone' => $phone,
                'role' => $role,
                'address' => $address,
                'password' => $password
            ];
            $newId = $userModel->createUser($createData);
            $_SESSION['message'] = $newId ? 'Thêm người dùng thành công' : 'Không thể thêm người dùng';
        }
    } catch (\Exception $e) {
        $_SESSION['error'] = 'Lỗi xử lý người dùng: ' . $e->getMessage();
    }
    
    header('Location: ' . ROOT_URL . 'admin/users');
    exit;
}

/**
 * Xóa user
 * URL: /admin/deleteUser/{id}
 */
public function deleteUser($id) {
    $userModel = new User();
    
    $user = $userModel->getById($id);
    if (!$user) {
        $_SESSION['error'] = 'Người dùng không tồn tại';
        header('Location: ' . ROOT_URL . 'admin/users');
        exit;
    }
    
    try {
        $success = $userModel->deleteUser($id);
        $_SESSION['message'] = $success ? 'Đã xóa người dùng' : 'Không thể xóa người dùng';
    } catch (\Exception $e) {
        $_SESSION['error'] = 'Lỗi khi xóa người dùng: ' . $e->getMessage();
    }
    
    header('Location: ' . ROOT_URL . 'admin/users');
    exit;
}

public function orders() {
    $this->renderView('admin/order');
}

public function branch() {
    // delegate to BranchController
    $bc = new BranchController();
    return $bc->index();
}

/**
 * Hiển thị form sửa hãng
 * URL: /admin/editBranch/{id}
 */
public function editBranch($id) {
    $bc = new BranchController();
    return $bc->adminEditBranch($id);
}

/**
 * Xử lý thêm / cập nhật hãng
 * URL: /admin/saveBranch (POST)
 */
public function saveBranch() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . ROOT_URL . 'admin/branch');
        exit;
    }
    
    $bc = new BranchController();
    return $bc->adminSaveBranch();
}

/**
 * Xóa hãng
 * URL: /admin/deleteBranch/{id}
 */
public function deleteBranch($id = null) {
    $bc = new BranchController();
    return $bc->adminDeleteBranch($id);
}

public function categories() {
    $this->requireAdmin();
    $cc = new CategoryController();
    return $cc->index();
}

/**
 * Hiển thị form sửa danh mục
 * URL: /admin/editCategory/{id}
 */
public function editCategory($id = null) {
    $cc = new CategoryController();
    return $cc->adminEditCategory($id);
}

/**
 * Xử lý thêm / cập nhật danh mục
 * URL: /admin/saveCategory (POST)
 */
public function saveCategory() {
    $cc = new CategoryController();
    return $cc->adminSaveCategory();
}


/**
 * Xóa danh mục
 * URL: /admin/deleteCategory/{id}
 */
public function deleteCategory($id) {
    $cc = new CategoryController();
    return $cc->adminDeleteCategory($id);
}


    /**
     * Trang quản lý màu sắc.
     */
    public function colors() {
        $cc = new ColorController();
        return $cc->index();
    }

    public function editColor($id = null) {
        $cc = new ColorController();
        return $cc->adminEditColor($id);
    }

    public function saveColor() {
        $cc = new ColorController();
        return $cc->adminSaveColor();
    }

    public function deleteColor($id = null) {
        $cc = new ColorController();
        return $cc->adminDeleteColor($id);
    }

    /**
     * Trang quản lý kích cỡ.
     */
    public function sizes() {
        $sc = new SizeController();
        return $sc->index();
    }

    public function editSize($id = null) {
        $sc = new SizeController();
        return $sc->adminEditSize($id);
    }

    public function saveSize() {
        $sc = new SizeController();
        return $sc->adminSaveSize();
    }

    public function deleteSize($id = null) {
        $sc = new SizeController();
        return $sc->adminDeleteSize($id);
    }

    /**
     * Trang quản lý biến thể sản phẩm.
     */
    public function productVariants() {
        $mvc = new ProductVarirantController();
        return $mvc->index();
    }

    public function editProductVariant($id = null) {
        $mvc = new ProductVarirantController();
        return $mvc->adminEditVariant($id);
    }

    public function saveProductVariant() {
        $mvc = new ProductVarirantController();
        return $mvc->adminSaveVariant();
    }

    public function deleteProductVariant($id = null) {
        $mvc = new ProductVarirantController();
        return $mvc->adminDeleteVariant($id);
    }

public function comments() {
    $this->renderView('admin/comment');
}

public function stats() {
    $this->renderView('admin/stat');
}

public function dashboard() {
    $this->requireAdmin();
    $this->renderView('admin/dashboard');
}

    /**
     * Mặc định chuyển vào quản lý sản phẩm
     * URL: /admin hoặc /admin/index
     */
    public function index() {
        $this->requireAdmin();
        $this->products();
    }

    /**
     * Trang danh sách + form thêm sản phẩm
     * URL: /admin/products
     */
    public function products() {
        // Delegate product admin pages to ProductController
        $pc = new ProductController();
        return $pc->adminProducts();
    }

    /**
     * Hiển thị form sửa sản phẩm
     * URL: /admin/editProduct/{id}
     */
    public function editProduct($id) {
        $pc = new ProductController();
        return $pc->adminEditProduct($id);
    }

    /**
     * Xử lý thêm / cập nhật sản phẩm
     * URL: /admin/saveProduct (POST)
     */
    public function saveProduct() {
        $pc = new ProductController();
        return $pc->adminSaveProduct();
    }

    /**
     * Xóa sản phẩm
     * URL: /admin/deleteProduct/{id}
     */
    public function deleteProduct($id) {
        $pc = new ProductController();
        return $pc->adminDeleteProduct($id);
    }
}

?>


