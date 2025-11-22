<?php

namespace Controllers;
use Core\Controller;
use Models\Category;

class CategoryController extends Controller {

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new CategoryController();
        }
        return self::$instance;
    }
    function index() {
        $categoryModel = new Category();
        $categories = $categoryModel->getAllWithProductCount();

        $data = [
            'title' => 'Danh mục sản phẩm',
            'categories' => $categories
        ];

        $this->renderView('admin/category', $data);
    }
}
