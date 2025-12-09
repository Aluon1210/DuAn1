<?php

namespace Controllers;
use Core\Controller;
use Models\Product_Varirant;
use Models\Product;
use Models\Color;
use Models\Size;

class ProductVarirantController extends Controller {

    public function index() {
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

    public function adminEditVariant($id = null) {
        $this->requireAdmin();
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

    public function adminSaveVariant() {
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

    public function adminDeleteVariant($id = null) {
        $this->requireAdmin();
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

}
