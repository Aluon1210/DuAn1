<?php

namespace Controllers;
use Core\Controller;
use Models\Size;

class SizeController extends Controller {

    public function index() {
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

    public function adminEditSize($id = null) {
        $this->requireAdmin();
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

    public function adminSaveSize() {
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

    public function adminDeleteSize($id = null) {
        $this->requireAdmin();
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
}
