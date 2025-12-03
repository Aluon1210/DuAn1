<?php

namespace Controllers;
use Core\Controller;
use Models\Branch;

class BranchController extends Controller {

    public function index() {
        $this->requireAdmin();
        $branchModel = new Branch();
        $branches = $branchModel->getAll();

        $data = [
            'title' => 'Quản lý hãng',
            'branches' => $branches,
            'totalBranches' => count($branches),
            'editing' => false
        ];

        $this->renderView('admin/branch', $data);
    }

    public function adminEditBranch($id) {
        $this->requireAdmin();
        if (!$id) {
            $_SESSION['error'] = 'ID hãng không được bỏ trống';
            header('Location: ' . ROOT_URL . 'admin/branch');
            exit;
        }

        $branchModel = new Branch();
        $branch = $branchModel->getById($id);

        if (!$branch) {
            $_SESSION['error'] = 'Hãng không tồn tại';
            header('Location: ' . ROOT_URL . 'admin/branch');
            exit;
        }

        $branches = $branchModel->getAll();

        $data = [
            'title' => 'Sửa hãng',
            'branch' => $branch,
            'branches' => $branches,
            'totalBranches' => count($branches),
            'editing' => true
        ];

        $this->renderView('admin/branch', $data);
    }

    public function adminSaveBranch() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . ROOT_URL . 'admin/branch');
            exit;
        }

        $branchModel = new Branch();

        $id = isset($_POST['id']) ? trim($_POST['id']) : '';
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';

        if ($name === '') {
            $_SESSION['error'] = 'Tên hãng không được để trống';
            header('Location: ' . ROOT_URL . 'admin/branch');
            exit;
        }

        try {
            if ($id) {
                $success = $branchModel->updateBranch($id, ['name' => $name]);
                $_SESSION['message'] = $success ? 'Cập nhật hãng thành công' : 'Không thể cập nhật hãng';
            } else {
                $newId = $branchModel->createBranch(['name' => $name]);
                $_SESSION['message'] = $newId ? 'Thêm hãng thành công' : 'Không thể thêm hãng';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi xử lý hãng: ' . $e->getMessage();
        }

        header('Location: ' . ROOT_URL . 'admin/branch');
        exit;
    }

    public function adminDeleteBranch($id = null) {
        $this->requireAdmin();
        if (!$id) {
            $_SESSION['error'] = 'ID hãng không được bỏ trống';
            header('Location: ' . ROOT_URL . 'admin/branch');
            exit;
        }

        $branchModel = new Branch();
        $branch = $branchModel->getById($id);

        if (!$branch) {
            $_SESSION['error'] = 'Hãng không tồn tại';
            header('Location: ' . ROOT_URL . 'admin/branch');
            exit;
        }

        try {
            $success = $branchModel->deleteBranch($id);
            $_SESSION['message'] = $success ? 'Đã xóa hãng' : 'Không thể xóa hãng';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi khi xóa hãng: ' . $e->getMessage();
        }

        header('Location: ' . ROOT_URL . 'admin/branch');
        exit;
    }
}
