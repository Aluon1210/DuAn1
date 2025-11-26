<?php

namespace Controllers;
use Core\Controller;
use Models\Color;

class ColorController extends Controller {

	public function index() {
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
	public function adminEditColor($id = null) {
		$this->requireAdmin();
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

	public function adminSaveColor() {
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
			$_SESSION['error'] = 'Tên màu không được bỏ trống';
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

	public function adminDeleteColor($id = null) {
		$this->requireAdmin();
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

}

