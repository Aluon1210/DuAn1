# 📊 PHÂN TÍCH CHI TIẾT: LOAD BRANCH/CATEGORY → THÊM → CHỈNH SỬA → XÓA SẢN PHẨM

---

## 🔄 QUY TRÌNH TỪNG BƯỚC

```
┌─────────────────────────────────────────────────────────────┐
│         ADMIN CLICK VÀO "QUẢN LÝ SẢN PHẨM"                 │
└────────────────────────┬────────────────────────────────────┘
                         │
                    GET REQUEST
                         │
        ┌────────────────▼──────────────────┐
        │  AdminController::products()       │
        │  (Bước 1: LOAD DỮ LIỆU)           │
        └────────────────┬──────────────────┘
                         │
         ┌───────────────┴────────────────┐
         │                                │
    ┌────▼──────────────┐     ┌──────────▼────────────┐
    │ Load CATEGORIES   │     │ Load BRANCHES         │
    │ (từ DB table      │     │ (từ DB table branch)  │
    │  catogory)        │     │                       │
    └────┬──────────────┘     └──────────┬────────────┘
         │                              │
         └──────────┬───────────────────┘
                    │
         ┌──────────▼──────────────┐
         │  Load SẢN PHẨM HIỆN CÓ   │
         │  (với tên category/      │
         │   branch kèm theo)       │
         └──────────┬───────────────┘
                    │
         ┌──────────▼──────────────┐
         │  RENDER VIEW             │
         │  admin/product.php       │
         │  (gửi $categories,       │
         │   $branches,             │
         │   $products)             │
         └──────────┬───────────────┘
                    │
                GIAO DIỆN
         ┌──────────────────────┐
         │ FORM THÊM/SỬA        │ ← Dropdown categories
         │                      │ ← Dropdown branches
         ├──────────────────────┤
         │ DANH SÁCH SẢN PHẨM   │
         │ [Sửa] [Xóa]          │
         └──────────────────────┘
```

---

## 📍 BƯỚC 1: LOAD BRANCH & CATEGORY (Khởi Tạo Trang)

### 1.1 URL Người Dùng Truy Cập

```
GET http://localhost/DuAn1/admin/products
```

### 1.2 Router Xử Lý

```
Điều định tới: AdminController::products()
```

### 1.3 Code trong `AdminController::products()`

**File**: `src/Controllers/AdminController.php`

```php
public function products() {
    $this->requireAdmin(); // Kiểm tra quyền admin

    // ====== BƯỚC 1: LOAD CATEGORIES ======
    $categoryModel = new Category();
    $categories = $categoryModel->getAll();
    // Kết quả:
    // Array([
    //   ['id' => 'DA+0000000001', 'name' => 'Áo Sơ Mi'],
    //   ['id' => 'DA+0000000002', 'name' => 'Quần Jean'],
    //   ...
    // ])

    // ====== BƯỚC 2: LOAD BRANCHES ======
    $branchModel = new Branch();
    $branches = $branchModel->getAll();
    // Kết quả:
    // Array([
    //   ['id' => 'HA+0000000001', 'name' => 'Gucci'],
    //   ['id' => 'HA+0000000002', 'name' => 'Nike'],
    //   ...
    // ])

    // ====== BƯỚC 3: LOAD PRODUCTS ======
    $productModel = new Product();
    $products = $productModel->getAllWithCategory();
    // Kết quả:
    // Array([
    //   [
    //     'id' => 'SP+0000000001',
    //     'name' => 'Áo sơ mi xanh',
    //     'price' => 500000,
    //     'category_name' => 'Áo Sơ Mi',    ← Đã join từ category
    //     'branch_name' => 'Gucci',          ← Đã join từ branch
    //     ...
    //   ],
    //   ...
    // ])

    // ====== BƯỚC 4: TRUYỀN DỮ LIỆU ĐẾN VIEW ======
    $data = [
        'title' => 'Quản lý sản phẩm',
        'products' => $products,           // Danh sách sản phẩm
        'categories' => $categories,       // Dùng cho dropdown
        'branches' => $branches,           // Dùng cho dropdown
        'totalProducts' => count($products),
        'editing' => false                 // Chế độ thêm mới (không phải sửa)
    ];

    $this->renderView('admin/product', $data);
}
```

### 1.4 SQL Queries Chạy Phía Backend

**Query 1: Load Categories**

```sql
SELECT * FROM catogory ORDER BY Category_Id ASC;
-- Kết quả: [DA+0000000001, Áo Sơ Mi], [DA+0000000002, Quần Jean], ...
```

**Query 2: Load Branches**

```sql
SELECT * FROM branch ORDER BY Branch_Id ASC;
-- Kết quả: [HA+0000000001, Gucci], [HA+0000000002, Nike], ...
```

**Query 3: Load Products với thông tin liên quan**

```sql
SELECT p.*,
       c.Name as category_name,
       b.Name as branch_name
FROM products p
LEFT JOIN catogory c ON p.Category_Id = c.Category_Id
LEFT JOIN branch b ON p.Branch_Id = b.Branch_Id
ORDER BY p.Create_at DESC;
-- Kết quả: [SP+0000000001, Áo sơ mi xanh, ..., category_name='Áo Sơ Mi', branch_name='Gucci']
```

### 1.5 Dữ Liệu Truyền Đến View

```php
$data = [
    'categories' => [
        ['id' => 'DA+0000000001', 'name' => 'Áo Sơ Mi'],
        ['id' => 'DA+0000000002', 'name' => 'Quần Jean'],
    ],
    'branches' => [
        ['id' => 'HA+0000000001', 'name' => 'Gucci'],
        ['id' => 'HA+0000000002', 'name' => 'Nike'],
    ],
    'products' => [
        [
            'id' => 'SP+0000000001',
            'name' => 'Áo sơ mi xanh',
            'price' => 500000,
            'quantity' => 50,
            'category_id' => 'DA+0000000001',
            'category_name' => 'Áo Sơ Mi',     ← Từ join
            'branch_id' => 'HA+0000000001',
            'branch_name' => 'Gucci',          ← Từ join
            'image' => 'prod_abc123.jpg',
            'description' => 'Áo sơ mi cao cấp'
        ]
    ]
];
```

---

## 📍 BƯỚC 2: POPULATE DROPDOWN TRONG FORM

### 2.1 Trong View (`admin/product.php`)

```html
<div class="form-group">
  <label>Danh mục</label>
  <select name="category_id" required>
    <option value="">-- Chọn danh mục --</option>

    <!-- LOOP CATEGORIES -->
    <?php if (!empty($categories)): ?>
    <?php foreach ($categories as $category): ?>
    <option value="<?php echo $category['id']; ?>">
      <?php echo $category['name']; ?>
    </option>
    <?php endforeach; ?>
    <?php endif; ?>
  </select>
</div>

<!-- Kết quả HTML: -->
<!-- 
<select name="category_id">
    <option value="">-- Chọn danh mục --</option>
    <option value="DA+0000000001">Áo Sơ Mi</option>
    <option value="DA+0000000002">Quần Jean</option>
</select>
-->
```

**Tương tự cho Branch:**

```html
<select name="branch_id" required>
  <option value="">-- Chọn hãng --</option>
  <?php foreach ($branches as $branch): ?>
  <option value="<?php echo $branch['id']; ?>">
    <?php echo $branch['name']; ?>
  </option>
  <?php endforeach; ?>
</select>
```

---

## ➕ BƯỚC 3: THÊM SẢN PHẨM MỚI

### 3.1 Người Dùng Điền Form

```
┌─────────────────────────────────────┐
│  Form Thêm Sản Phẩm                 │
├─────────────────────────────────────┤
│ Tên: [Áo sơ mi đỏ                 ] │
│ Giá: [750000                      ] │
│ Kho: [100                         ] │
│ Danh mục: [Áo Sơ Mi ▼]            │ ← Chọn từ dropdown
│ Hãng: [Gucci ▼]                   │ ← Chọn từ dropdown
│ Mô tả: [Áo sơ mi cao cấp...     ] │
│ Ảnh: [Chọn file...              ] │
├─────────────────────────────────────┤
│ [Thêm mới]  [Hủy]                  │
└─────────────────────────────────────┘
```

### 3.2 Form Submit (POST)

```html
<form
  action="/DuAn1/admin/saveProduct"
  method="POST"
  enctype="multipart/form-data"
>
  <!-- Khi thêm mới: KHÔNG có input hidden "id" -->

  <input type="text" name="name" value="Áo sơ mi đỏ" />
  <input type="number" name="price" value="750000" />
  <input type="number" name="quantity" value="100" />
  <select name="category_id">
    <option value="DA+0000000001" selected>Áo Sơ Mi</option>
  </select>
  <select name="branch_id">
    <option value="HA+0000000001" selected>Gucci</option>
  </select>
  <textarea name="description">Áo sơ mi cao cấp...</textarea>
  <input type="file" name="image" />

  <button type="submit">Thêm mới</button>
</form>
```

### 3.3 POST Data Gửi Tới Server

```
POST /DuAn1/admin/saveProduct HTTP/1.1
Content-Type: multipart/form-data

name=Áo sơ mi đỏ
price=750000
quantity=100
category_id=DA+0000000001
branch_id=HA+0000000001
description=Áo sơ mi cao cấp...
image=[Binary file data]
<!-- Lưu ý: KHÔNG có trường "id" → Hệ thống biết đây là THÊM MỚI -->
```

### 3.4 Server Xử Lý (`AdminController::saveProduct()`)

**File**: `src/Controllers/AdminController.php`

```php
public function saveProduct() {
    // Kiểm tra method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . ROOT_URL . 'admin/products');
        exit;
    }

    $productModel = new Product();

    // ====== LẤY DỮ LIỆU TỪ FORM ======
    $id = $_POST['id'] ?? ''; // Nếu có ID → Chế độ UPDATE
    $name = trim($_POST['name'] ?? '');
    $price = (int)($_POST['price'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 0);
    $categoryId = trim($_POST['category_id'] ?? '');
    $branchId = trim($_POST['branch_id'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // ====== VALIDATION ======
    if ($name === '' || $price < 0 || $quantity < 0 ||
        $categoryId === '' || $branchId === '') {
        $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin hợp lệ';
        header('Location: ' . ROOT_URL . 'admin/products');
        exit;
    }

    // ====== XỬ LÝ UPLOAD ẢNH ======
    $imageName = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = ROOT_PATH . '/public/images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid('prod_') . '.' . $ext; // prod_62a7c8d9.jpg
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
    }

    try {
        if ($id) {
            // ===== CHẾ ĐỘ UPDATE (Sửa) =====
            // ...xử lý UPDATE...
        } else {
            // ===== CHẾ ĐỘ CREATE (Thêm Mới) =====
            // Gọi hàm tạo sản phẩm mới
            $newId = $productModel->createProduct([
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'category_id' => $categoryId,      // ← Lưu FK
                'branch_id' => $branchId,          // ← Lưu FK
                'description' => $description,
                'image' => $imageName
            ]);

            // Nếu thành công
            if ($newId) {
                $_SESSION['message'] = 'Thêm sản phẩm thành công';
                // Sản phẩm mới được tạo với ID: SP+0000000001
            } else {
                $_SESSION['error'] = 'Không thể thêm sản phẩm';
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
    }

    // Redirect về danh sách
    header('Location: ' . ROOT_URL . 'admin/products');
    exit;
}
```

### 3.5 Model Tạo Sản Phẩm (`Product::createProduct()`)

**File**: `src/Models/Product.php`

```php
public function createProduct($data) {
    // ====== SINH MÃ SẢN PHẨM TỰ ĐỘNG ======
    // Gọi IdGenerator::generate() để tạo ID như: SP+0000000001
    $productId = IdGenerator::generate(
        'SP+',           // Prefix
        $this->table,    // Table: 'products'
        'Product_Id',    // Column: 'Product_Id'
        10               // Độ dài: 10 ký tự
    );

    // ====== CHUẨN BỊ DỮ LIỆU ĐẢ VÀO DATABASE ======
    $insertData = [
        'Product_Id' => $productId,                    // SP+0000000001
        'Name' => $data['name'],                       // Áo sơ mi đỏ
        'Price' => $data['price'],                     // 750000
        'Quantity' => $data['quantity'],               // 100
        'Category_Id' => $data['category_id'],         // DA+0000000001
        'Branch_Id' => $data['branch_id'],             // HA+0000000001
        'Description' => $data['description'],         // Áo sơ mi cao cấp...
        'Image' => $data['image'],                     // prod_62a7c8d9.jpg
        'Create_at' => date('Y-m-d H:i:s'),           // 2025-11-24 14:30:00
        'Product_View' => 0                            // 0 lượt xem
    ];

    // ====== THỰC HIỆN INSERT ======
    // Gọi phương thức create() của Model (kế thừa từ Core\Model)
    if ($this->create($insertData)) {
        return $productId; // Trả về ID mới tạo
    }
    return false; // Nếu lỗi
}
```

### 3.6 SQL Được Chạy

```sql
INSERT INTO products (
    Product_Id,
    Name,
    Price,
    Quantity,
    Category_Id,
    Branch_Id,
    Description,
    Image,
    Create_at,
    Product_View
) VALUES (
    'SP+0000000001',
    'Áo sơ mi đỏ',
    750000,
    100,
    'DA+0000000001',      ← Foreign Key tới catogory
    'HA+0000000001',      ← Foreign Key tới branch
    'Áo sơ mi cao cấp...',
    'prod_62a7c8d9.jpg',
    '2025-11-24 14:30:00',
    0
);
```

### 3.7 Kết Quả

- ✅ Sản phẩm được tạo thành công
- ✅ Redirect về `/admin/products`
- ✅ Hiển thị message: "Thêm sản phẩm thành công"
- ✅ Sản phẩm mới xuất hiện trong danh sách

---

## ✏️ BƯỚC 4: CHỈNH SỬA SẢN PHẨM

### 4.1 Người Dùng Click "Sửa"

```html
<!-- Trong bảng danh sách sản phẩm -->
<tr>
  <td>SP+0000000001</td>
  <td>Áo sơ mi đỏ</td>
  <td>
    <!-- Link sửa -->
    <a href="/DuAn1/admin/editProduct/SP+0000000001">Sửa</a>
    <a href="/DuAn1/admin/deleteProduct/SP+0000000001">Xóa</a>
  </td>
</tr>
```

### 4.2 URL Yêu Cầu

```
GET /DuAn1/admin/editProduct/SP+0000000001
```

### 4.3 Server Xử Lý (`AdminController::editProduct()`)

```php
public function editProduct($id) {
    // $id = 'SP+0000000001'

    $productModel = new Product();

    // ====== LOAD SẢN PHẨM CẦN SỬA ======
    $product = $productModel->getById($id);

    if (!$product) {
        $_SESSION['error'] = 'Sản phẩm không tồn tại';
        header('Location: ' . ROOT_URL . 'admin/products');
        exit;
    }

    // Kết quả $product:
    // [
    //   'id' => 'SP+0000000001',
    //   'name' => 'Áo sơ mi đỏ',
    //   'price' => 750000,
    //   'quantity' => 100,
    //   'category_id' => 'DA+0000000001',
    //   'branch_id' => 'HA+0000000001',
    //   'description' => 'Áo sơ mi cao cấp...',
    //   'image' => 'prod_62a7c8d9.jpg'
    // ]

    // ====== LOAD CATEGORIES & BRANCHES CHO DROPDOWN ======
    $categoryModel = new Category();
    $branchModel = new Branch();

    $categories = $categoryModel->getAll();
    $branches = $branchModel->getAll();

    // ====== TRUYỀN DỮ LIỆU ĐẾN VIEW ======
    $data = [
        'product' => $product,           // Sản phẩm cần sửa
        'categories' => $categories,
        'branches' => $branches,
        'editing' => true                // ← Chế độ SỬA
    ];

    $this->renderView('admin/product', $data);
}
```

### 4.4 View Tự Động Điền Dữ Liệu Cũ

```html
<!-- Trong view admin/product.php -->

<?php if (isset($editing) && $editing && !empty($product)): ?>
<!-- Khi chế độ SỬA: Thêm hidden input với ID -->
<input type="hidden" name="id" value="<?php echo $product['id']; ?>" />
<?php endif; ?>

<!-- Form sẽ được điền sẵn dữ liệu cũ -->
<input
  type="text"
  name="name"
  value="<?php echo isset($product['name']) ? $product['name'] : ''; ?>"
/>
<!-- Kết quả: <input value="Áo sơ mi đỏ"> -->

<input
  type="number"
  name="price"
  value="<?php echo isset($product['price']) ? $product['price'] : 0; ?>"
/>
<!-- Kết quả: <input value="750000"> -->

<select name="category_id">
  <?php foreach ($categories as $category): ?>
  <option
    value="<?php echo $category['id']; ?>"
    <!--
    Khi
    category_id
    khớp
    với
    sản
    phẩm
    hiện
    tại
    →
    selected
    --
  >
    <?php echo (isset($product['category_id']) && 
                        $product['category_id'] == $category['id']) ? 
                   'selected' : ''; ?>>
    <?php echo $category['name']; ?>
  </option>
  <?php endforeach; ?>
</select>
<!-- Kết quả: <option value="DA+0000000001" selected>Áo Sơ Mi</option> -->

<!-- Nút Submit sẽ hiển thị "Cập nhật" thay vì "Thêm mới" -->
<button type="submit">
  <?php echo (isset($editing) && $editing) ? 'Cập nhật' : 'Thêm mới'; ?>
</button>
```

### 4.5 Người Dùng Chỉnh Sửa & Submit

```html
<!-- Form sau khi chỉnh sửa -->
<form action="/DuAn1/admin/saveProduct" method="POST">
  <!-- Hidden input với ID sản phẩm -->
  <input type="hidden" name="id" value="SP+0000000001" />

  <!-- Thay đổi tên từ "Áo sơ mi đỏ" → "Áo sơ mi xanh" -->
  <input type="text" name="name" value="Áo sơ mi xanh" />

  <!-- Thay đổi giá từ 750000 → 850000 -->
  <input type="number" name="price" value="850000" />

  <!-- Giữ nguyên các thông tin khác -->
  <input type="number" name="quantity" value="100" />
  <select name="category_id">
    <option selected>Áo Sơ Mi</option>
  </select>
  <select name="branch_id">
    <option selected>Gucci</option>
  </select>

  <button type="submit">Cập nhật</button>
</form>
```

### 4.6 POST Data Gửi

```
POST /DuAn1/admin/saveProduct HTTP/1.1

id=SP+0000000001              ← ✅ CÓ ID → CHẾ ĐỘ UPDATE
name=Áo sơ mi xanh
price=850000
quantity=100
category_id=DA+0000000001
branch_id=HA+0000000001
description=Áo sơ mi cao cấp...
```

### 4.7 Server Xử Lý Update

```php
public function saveProduct() {
    $id = $_POST['id'] ?? ''; // 'SP+0000000001'
    $name = $_POST['name']; // 'Áo sơ mi xanh'
    $price = $_POST['price']; // 850000
    // ...

    if ($id) {
        // ===== CHẾ ĐỘ UPDATE =====
        $updateData = [
            'name' => $name,           // Cập nhật tên
            'price' => $price,         // Cập nhật giá
            'quantity' => $quantity,
            'category_id' => $categoryId,
            'branch_id' => $branchId,
            'description' => $description
        ];

        // Gọi phương thức update()
        $success = $productModel->update($id, $updateData, 'Product_Id');

        $_SESSION['message'] = 'Cập nhật sản phẩm thành công';
    }

    header('Location: ' . ROOT_URL . 'admin/products');
}
```

### 4.8 SQL Update

```sql
UPDATE products
SET
    Name = 'Áo sơ mi xanh',
    Price = 850000,
    Quantity = 100,
    Category_Id = 'DA+0000000001',
    Branch_Id = 'HA+0000000001',
    Description = 'Áo sơ mi cao cấp...'
WHERE Product_Id = 'SP+0000000001';
```

### 4.9 Kết Quả

- ✅ Sản phẩm được cập nhật
- ✅ Redirect về danh sách
- ✅ Hiển thị message: "Cập nhật sản phẩm thành công"

---

## 🗑️ BƯỚC 5: XÓA SẢN PHẨM

### 5.1 Người Dùng Click "Xóa"

```html
<!-- Nút xóa trong bảng danh sách -->
<a
  href="/DuAn1/admin/deleteProduct/SP+0000000001"
  onclick="return confirm('Xóa sản phẩm này?');"
>
  Xóa
</a>
```

### 5.2 Confirm Dialog

```
┌───────────────────────────────┐
│  Xóa sản phẩm này?            │
├───────────────────────────────┤
│  [OK]  [Hủy]                  │
└───────────────────────────────┘

Nếu click "OK" → Chuyển tới URL
GET /DuAn1/admin/deleteProduct/SP+0000000001
```

### 5.3 Server Xử Lý (`AdminController::deleteProduct()`)

```php
public function deleteProduct($id) {
    // $id = 'SP+0000000001'

    $productModel = new Product();

    // ====== KIỂM TRA SẢN PHẨM CÓ TỒN TẠI ======
    $product = $productModel->getById($id);

    if (!$product) {
        $_SESSION['error'] = 'Sản phẩm không tồn tại';
        header('Location: ' . ROOT_URL . 'admin/products');
        exit;
    }

    try {
        // ====== THỰC HIỆN XÓA ======
        $success = $productModel->deleteById($id);

        // Nếu xóa thành công
        $_SESSION['message'] = 'Đã xóa sản phẩm';
    } catch (Exception $e) {
        $_SESSION['error'] = 'Lỗi khi xóa sản phẩm: ' . $e->getMessage();
    }

    // Redirect về danh sách
    header('Location: ' . ROOT_URL . 'admin/products');
    exit;
}
```

### 5.4 Model Xóa (`Product::deleteById()`)

```php
public function deleteById($id) {
    // $id = 'SP+0000000001'

    $sql = "DELETE FROM products WHERE Product_Id = :id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute(['id' => $id]);
}
```

### 5.5 SQL Được Chạy

```sql
DELETE FROM products
WHERE Product_Id = 'SP+0000000001';

-- Kết quả: Dòng có Product_Id = SP+0000000001 được xóa
```

### 5.6 Kết Quả

- ✅ Sản phẩm bị xóa khỏi database
- ✅ Redirect về danh sách
- ✅ Hiển thị message: "Đã xóa sản phẩm"
- ✅ Sản phẩm không còn xuất hiện trong danh sách

---

## 📊 TÓML BẢNG TÓMT TẮT

| Bước | Hành Động     | HTTP Method | URL                         | Hàm               | Query                                               |
| ---- | ------------- | ----------- | --------------------------- | ----------------- | --------------------------------------------------- |
| 1    | Load trang    | GET         | `/admin/products`           | `products()`      | `SELECT * FROM catogory`, `branch`, `products` JOIN |
| 3    | Thêm sản phẩm | POST        | `/admin/saveProduct`        | `saveProduct()`   | `INSERT INTO products`                              |
| 4    | Sửa sản phẩm  | GET         | `/admin/editProduct/{id}`   | `editProduct()`   | `SELECT * FROM products WHERE id`                   |
| 4    | Cập nhật      | POST        | `/admin/saveProduct`        | `saveProduct()`   | `UPDATE products SET ... WHERE id`                  |
| 5    | Xóa sản phẩm  | GET         | `/admin/deleteProduct/{id}` | `deleteProduct()` | `DELETE FROM products WHERE id`                     |

---

## 🔑 ĐIỂM CHÍNH

### ✅ Load Branch & Category Trước

- **Tại sao?** Vì form dropdown cần có dữ liệu để hiển thị
- **Khi nào?** Mỗi lần vào trang `/admin/products`
- **Cách?** Model `getAll()` → foreach loop → `<option>`

### ✅ Thêm Sản Phẩm

- **Điều kiện**: Không có trường `id` trong form
- **ID mới**: Tự sinh `SP+0000000001` via `IdGenerator`
- **Foreign Keys**: `category_id` và `branch_id` được lưu

### ✅ Chỉnh Sửa

- **Điều kiện**: Có trường `id` (hidden input)
- **Load dữ liệu cũ**: `editProduct()` truyền `$product` cho view
- **Form pre-fill**: `value="..."` và `selected`

### ✅ Xóa

- **Confirm**: `onclick="return confirm()"`
- **Kiểm tra**: Sản phẩm có tồn tại không
- **SQL**: `DELETE WHERE Product_Id = :id`

---

## 📁 FILE LIÊN QUAN

```
src/
├── Controllers/
│   └── AdminController.php (products, editProduct, saveProduct, deleteProduct)
├── Models/
│   └── Product.php (getById, getAllWithCategory, createProduct, update, deleteById)
└── Views/admin/
    └── product.php (form + danh sách)
```

---

**Tác Giả**: AI Assistant | **Ngày**: 2025-11-24
