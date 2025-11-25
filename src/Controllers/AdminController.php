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
        $this->requireAdmin();
        $colorModel = new Color();
        $colors = $colorModel->getAll();

        $data = [
            'title' => 'Quản lý màu sắc',
            'colors' => $colors,
            'totalColors' => count($colors),
            'editing' => false
        ];

        $this->renderView('admin/color', $data);
    }

    public function editColor($id = null) {
        if (!$id) {
            $_SESSION['error'] = 'ID màu không được bỏ trống';
            header('Location: ' . ROOT_URL . 'admin/colors');
            exit;
        }

        $colorModel = new Color();
        $color = $colorModel->getById($id);

        if (!$color) {
            $_SESSION['error'] = 'Màu sắc không tồn tại';
            header('Location: ' . ROOT_URL . 'admin/colors');
            exit;
        }

        $colors = $colorModel->getAll();
        $data = [
            'title' => 'Sửa màu sắc',
            'color' => $color,
            'colors' => $colors,
            'totalColors' => count($colors),
            'editing' => true
        ];

        $this->renderView('admin/color', $data);
    }

    public function saveColor() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'admin/colors');
            exit;
        }

        $colorModel = new Color();
        $id = trim($_POST['id'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $hex = strtoupper(trim($_POST['hex_code'] ?? '#000000'));
        $description = trim($_POST['description'] ?? '');

        if ($name === '') {
            $_SESSION['error'] = 'Tên màu không được để trống';
            header('Location: ' . ROOT_URL . 'admin/colors');
            exit;
        }

        if ($hex && !preg_match('/^#([A-F0-9]{3}|[A-F0-9]{6})$/i', $hex)) {
            $_SESSION['error'] = 'Mã màu không hợp lệ';
            header('Location: ' . ROOT_URL . 'admin/colors');
            exit;
        }

        try {
            if ($id) {
                $success = $colorModel->updateColor($id, [
                    'name' => $name,
                    'hex_code' => $hex,
                    'description' => $description
                ]);
                $_SESSION['message'] = $success ? 'Cập nhật màu thành công' : 'Không thể cập nhật màu';
            } else {
                $newId = $colorModel->createColor([
                    'name' => $name,
                    'hex_code' => $hex,
                    'description' => $description
                ]);
                $_SESSION['message'] = $newId ? 'Thêm màu mới thành công' : 'Không thể thêm màu';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi xử lý màu sắc: ' . $e->getMessage();
        }

        header('Location: ' . ROOT_URL . 'admin/colors');
        exit;
    }

    public function deleteColor($id = null) {
        if (!$id) {
            $_SESSION['error'] = 'ID màu không được bỏ trống';
            header('Location: ' . ROOT_URL . 'admin/colors');
            exit;
        }

        $colorModel = new Color();
        $color = $colorModel->getById($id);

        if (!$color) {
            $_SESSION['error'] = 'Màu sắc không tồn tại';
            header('Location: ' . ROOT_URL . 'admin/colors');
            exit;
        }

        try {
            $success = $colorModel->deleteColor($id);
            $_SESSION['message'] = $success ? 'Đã xóa màu sắc' : 'Không thể xóa màu sắc';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi khi xóa màu: ' . $e->getMessage();
        }

        header('Location: ' . ROOT_URL . 'admin/colors');
        exit;
    }

    /**
     * Trang quản lý kích cỡ.
     */
    public function sizes() {
        $this->requireAdmin();
        $sizeModel = new Size();
        $sizes = $sizeModel->getAll();

        $data = [
            'title' => 'Quản lý kích cỡ',
            'sizes' => $sizes,
            'totalSizes' => count($sizes),
            'editing' => false
        ];

        $this->renderView('admin/size', $data);
    }

    public function editSize($id = null) {
        if (!$id) {
            $_SESSION['error'] = 'ID kích cỡ không được bỏ trống';
            header('Location: ' . ROOT_URL . 'admin/sizes');
            exit;
        }

        $sizeModel = new Size();
        $size = $sizeModel->getById($id);

        if (!$size) {
            $_SESSION['error'] = 'Kích cỡ không tồn tại';
            header('Location: ' . ROOT_URL . 'admin/sizes');
            exit;
        }

        $sizes = $sizeModel->getAll();
        $data = [
            'title' => 'Sửa kích cỡ',
            'size' => $size,
            'sizes' => $sizes,
            'totalSizes' => count($sizes),
            'editing' => true
        ];

        $this->renderView('admin/size', $data);
    }

    public function saveSize() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'admin/sizes');
            exit;
        }

        $sizeModel = new Size();
        $id = trim($_POST['id'] ?? '');
        $value = trim($_POST['value'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($value === '') {
            $_SESSION['error'] = 'Giá trị size không được để trống';
            header('Location: ' . ROOT_URL . 'admin/sizes');
            exit;
        }

        try {
            if ($id) {
                $success = $sizeModel->updateSize($id, [
                    'value' => $value,
                    'description' => $description
                ]);
                $_SESSION['message'] = $success ? 'Cập nhật kích cỡ thành công' : 'Không thể cập nhật kích cỡ';
            } else {
                $newId = $sizeModel->createSize([
                    'value' => $value,
                    'description' => $description
                ]);
                $_SESSION['message'] = $newId ? 'Thêm kích cỡ mới thành công' : 'Không thể thêm kích cỡ';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi xử lý kích cỡ: ' . $e->getMessage();
        }

        header('Location: ' . ROOT_URL . 'admin/sizes');
        exit;
    }

    public function deleteSize($id = null) {
        if (!$id) {
            $_SESSION['error'] = 'ID kích cỡ không được bỏ trống';
            header('Location: ' . ROOT_URL . 'admin/sizes');
            exit;
        }

        $sizeModel = new Size();
        $size = $sizeModel->getById($id);

        if (!$size) {
            $_SESSION['error'] = 'Kích cỡ không tồn tại';
            header('Location: ' . ROOT_URL . 'admin/sizes');
            exit;
        }

        try {
            $success = $sizeModel->deleteSize($id);
            $_SESSION['message'] = $success ? 'Đã xóa kích cỡ' : 'Không thể xóa kích cỡ';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi khi xóa kích cỡ: ' . $e->getMessage();
        }

        header('Location: ' . ROOT_URL . 'admin/sizes');
        exit;
    }

    /**
     * Trang quản lý biến thể sản phẩm.
     */
    public function productVariants() {
        $this->requireAdmin();
        $variantModel = new Product_Varirant();
        $productModel = new Product();
        $colorModel = new Color();
        $sizeModel = new Size();

        $variants = $variantModel->getAllWithRelations();
        $data = [
            'title' => 'Quản lý biến thể',
            'variants' => $variants,
            'products' => $productModel->getAll(),
            'colors' => $colorModel->getAll(),
            'sizes' => $sizeModel->getAll(),
            'totalVariants' => count($variants),
            'editing' => false
        ];

        $this->renderView('admin/product_varirant', $data);
    }

    public function editProductVariant($id = null) {
        if (!$id) {
            $_SESSION['error'] = 'ID biến thể không được bỏ trống';
            header('Location: ' . ROOT_URL . 'admin/productVariants');
            exit;
        }

        $variantModel = new Product_Varirant();
        $productModel = new Product();
        $colorModel = new Color();
        $sizeModel = new Size();

        $variant = $variantModel->getById($id);
        if (!$variant) {
            $_SESSION['error'] = 'Biến thể không tồn tại';
            header('Location: ' . ROOT_URL . 'admin/productVariants');
            exit;
        }

        $variants = $variantModel->getAllWithRelations();
        $data = [
            'title' => 'Sửa biến thể',
            'variant' => $variant,
            'variants' => $variants,
            'products' => $productModel->getAll(),
            'colors' => $colorModel->getAll(),
            'sizes' => $sizeModel->getAll(),
            'totalVariants' => count($variants),
            'editing' => true
        ];

        $this->renderView('admin/product_varirant', $data);
    }

    public function saveProductVariant() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'admin/productVariants');
            exit;
        }

        $variantModel = new Product_Varirant();
        $id = trim($_POST['id'] ?? '');
        $productId = trim($_POST['product_id'] ?? '');
        $variantName = trim($_POST['variant_name'] ?? '');
        $colorId = trim($_POST['color_id'] ?? '');
        $sizeId = trim($_POST['size_id'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);
        $sku = trim($_POST['sku'] ?? '');

        if ($productId === '') {
            $_SESSION['error'] = 'Vui lòng chọn sản phẩm';
            header('Location: ' . ROOT_URL . 'admin/productVariants');
            exit;
        }

        if ($price < 0 || $stock < 0) {
            $_SESSION['error'] = 'Giá và tồn kho phải lớn hơn hoặc bằng 0';
            header('Location: ' . ROOT_URL . 'admin/productVariants');
            exit;
        }

        try {
            $payload = [
                'product_id' => $productId,
                'variant_name' => $variantName,
                'color_id' => $colorId,
                'size_id' => $sizeId,
                'price' => $price,
                'stock' => $stock,
                'sku' => $sku
            ];

            if ($id) {
                $success = $variantModel->updateVariant($id, $payload);
                $_SESSION['message'] = $success ? 'Cập nhật biến thể thành công' : 'Không thể cập nhật biến thể';
            } else {
                $newId = $variantModel->createVariant($payload);
                $_SESSION['message'] = $newId ? 'Thêm biến thể mới thành công' : 'Không thể thêm biến thể';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi xử lý biến thể: ' . $e->getMessage();
        }

        header('Location: ' . ROOT_URL . 'admin/productVariants');
        exit;
    }

    public function deleteProductVariant($id = null) {
        if (!$id) {
            $_SESSION['error'] = 'ID biến thể không được bỏ trống';
            header('Location: ' . ROOT_URL . 'admin/productVariants');
            exit;
        }

        $variantModel = new Product_Varirant();
        $variant = $variantModel->getById($id);

        if (!$variant) {
            $_SESSION['error'] = 'Biến thể không tồn tại';
            header('Location: ' . ROOT_URL . 'admin/productVariants');
            exit;
        }

        try {
            $success = $variantModel->deleteVariant($id);
            $_SESSION['message'] = $success ? 'Đã xóa biến thể' : 'Không thể xóa biến thể';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi khi xóa biến thể: ' . $e->getMessage();
        }

        header('Location: ' . ROOT_URL . 'admin/productVariants');
        exit;
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


