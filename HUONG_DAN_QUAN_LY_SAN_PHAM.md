# 📚 Hướng Dẫn Quản Lý Sản Phẩm, Branch, Category

## 🎯 Tổng Quan Quy Trình

Hệ thống bao gồm 3 entity chính: **Category (Danh Mục)**, **Branch (Hãng)**, và **Product (Sản Phẩm)**

```
Category ──────┐
               ├──→ Product
Branch ────────┘
```

---

## 1️⃣ THÊM CATEGORY (DANH MỤC)

### A. Quy Trình
1. **Vào trang Admin** → Menu → **Danh mục**
2. **Form Thêm Danh Mục** (ở trên cùng)
   - Nhập tên danh mục (bắt buộc)
   - Nhập mô tả (tùy chọn)
3. Click **Lưu**

### B. Dữ Liệu Lưu Trữ
**Bảng Database**: `catogory` (lưu ý: tên bảng có lỗi chính tả)

| Cột | Kiểu | Mô Tả |
|-----|------|-------|
| `Category_Id` | STRING | Mã danh mục (tự sinh: `DA+0000000001`) |
| `Name` | VARCHAR | Tên danh mục |
| `Description` | TEXT | Mô tả |

### C. Code Flow

**Controller**: `src/Controllers/AdminController.php`
```php
// Hiển thị trang danh mục
public function categories() {
    $categoryModel = new Category();
    $categories = $categoryModel->getAll(); // Lấy tất cả
    // ... render view admin/category
}

// Thêm/cập nhật danh mục
public function saveCategory() {
    $name = $_POST['name']; // Lấy từ form
    $description = $_POST['description'];
    
    if ($id) {
        // UPDATE nếu có ID
        $categoryModel->updateCategory($id, [...]);
    } else {
        // CREATE nếu không có ID
        $newId = $categoryModel->createCategory([...]);
    }
    // Redirect về /admin/categories
}

// Xóa danh mục
public function deleteCategory($id) {
    $categoryModel->deleteCategory($id);
    // Redirect về /admin/categories
}
```

**Model**: `src/Models/Category.php`
```php
public function createCategory($data) {
    $categoryId = IdGenerator::generate('DA+', $this->table, 'Category_Id', 10);
    // Tạo mã tự động: DA+0000000001, DA+0000000002, ...
    
    return $this->create([
        'Category_Id' => $categoryId,
        'Name' => $data['name'],
        'Description' => $data['description'] ?? ''
    ]);
}

public function updateCategory($id, $data) {
    return $this->update($id, [
        'Name' => $data['name'],
        'Description' => $data['description']
    ], 'Category_Id'); // Primary key: Category_Id
}

public function deleteCategory($id) {
    return $this->deleteById($id, 'Category_Id');
}
```

---

## 2️⃣ THÊM BRANCH (HÃNG)

### A. Quy Trình
1. **Vào trang Admin** → Menu → **Hãng**
2. **Form Thêm Hãng** (ở trên cùng)
   - Nhập tên hãng (bắt buộc)
3. Click **Lưu**

### B. Dữ Liệu Lưu Trữ
**Bảng Database**: `branch`

| Cột | Kiểu | Mô Tả |
|-----|------|-------|
| `Branch_Id` | STRING | Mã hãng (tự sinh: `HA+0000000001`) |
| `Name` | VARCHAR | Tên hãng |

### C. Code Flow

**Controller**: `src/Controllers/AdminController.php`
```php
public function branch() {
    $branchModel = new Branch();
    $branches = $branchModel->getAll();
    // ... render view admin/branch
}

public function saveBranch() {
    $name = $_POST['name'];
    
    if ($id) {
        $branchModel->updateBranch($id, ['name' => $name]);
    } else {
        $newId = $branchModel->createBranch(['name' => $name]);
    }
}

public function deleteBranch($id) {
    $branchModel->deleteBranch($id);
}
```

**Model**: `src/Models/Branch.php`
```php
public function createBranch($data) {
    $branchId = IdGenerator::generate('HA+', $this->table, 'Branch_Id', 10);
    // Mã tự động: HA+0000000001, HA+0000000002, ...
    
    return $this->create([
        'Branch_Id' => $branchId,
        'Name' => $data['name']
    ]);
}

public function updateBranch($id, $data) {
    return $this->update($id, [
        'Name' => $data['name']
    ], 'Branch_Id');
}

public function deleteBranch($id) {
    return $this->deleteById($id, 'Branch_Id');
}
```

---

## 3️⃣ THÊM SẢN PHẨM MỚI

### A. Quy Trình
1. **Vào trang Admin** → Menu → **Sản phẩm**
2. **Form Thêm Sản Phẩm** (ở trên cùng)
   - **Tên sản phẩm** (bắt buộc)
   - **Giá** (bắt buộc)
   - **Số lượng kho** (bắt buộc)
   - **Danh mục** → Chọn từ dropdown (bắt buộc)
     - Dropdown lấy từ bảng `catogory`
   - **Hãng** → Chọn từ dropdown (bắt buộc)
     - Dropdown lấy từ bảng `branch`
   - **Mô tả** (tùy chọn)
   - **Ảnh** → Upload file hoặc để trống
3. Click **Thêm mới**

### B. Dữ Liệu Lưu Trữ
**Bảng Database**: `products`

| Cột | Kiểu | Mô Tả |
|-----|------|-------|
| `Product_Id` | STRING | Mã sản phẩm (tự sinh: `SP+0000000001`) |
| `Name` | VARCHAR | Tên sản phẩm |
| `Price` | DECIMAL | Giá tiền |
| `Quantity` | INT | Số lượng tồn kho |
| `Category_Id` | STRING | FK → `catogory.Category_Id` |
| `Branch_Id` | STRING | FK → `branch.Branch_Id` |
| `Description` | TEXT | Mô tả chi tiết |
| `Image` | VARCHAR | Tên file ảnh |
| `Create_at` | DATETIME | Ngày tạo |
| `Product_View` | INT | Số lượt xem |

### C. Code Flow

**Controller**: `src/Controllers/AdminController.php`
```php
// 1️⃣ Hiển thị trang danh sách + form thêm
public function products() {
    $productModel = new Product();
    $categoryModel = new Category();
    $branchModel = new Branch();
    
    // Lấy tất cả dữ liệu cần thiết
    $products = $productModel->getAllWithCategory(); // Lấy sản phẩm kèm tên category/branch
    $categories = $categoryModel->getAll(); // Lấy danh sách để populate dropdown
    $branches = $branchModel->getAll(); // Lấy danh sách để populate dropdown
    
    // Truyền vào view
    $data = [
        'products' => $products,
        'categories' => $categories,
        'branches' => $branches,
        'totalProducts' => count($products),
        'editing' => false // Chế độ thêm mới (không phải sửa)
    ];
    
    $this->renderView('admin/product', $data);
}

// 2️⃣ Xử lý thêm/cập nhật sản phẩm (POST)
public function saveProduct() {
    $productModel = new Product();
    
    // Lấy dữ liệu từ form
    $id = $_POST['id'] ?? ''; // Có ID = Sửa, không có ID = Thêm mới
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $categoryId = $_POST['category_id']; // Chọn từ dropdown
    $branchId = $_POST['branch_id'];    // Chọn từ dropdown
    $description = $_POST['description'];
    
    // Xử lý upload ảnh (nếu có)
    $imageName = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = ROOT_PATH . '/public/images/';
        mkdir($uploadDir); // Tạo thư mục nếu chưa có
        
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid('prod_') . '.' . $ext; // prod_62a7c8d9.jpg
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
    }
    
    if ($id) {
        // ✏️ CẬP NHẬT (Edit)
        $success = $productModel->update($id, [
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'category_id' => $categoryId,
            'branch_id' => $branchId,
            'description' => $description,
            'image' => $imageName ?: null
        ], 'Product_Id');
        
        $_SESSION['message'] = 'Cập nhật sản phẩm thành công';
    } else {
        // ➕ THÊM MỚI (Create)
        $newId = $productModel->createProduct([
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'category_id' => $categoryId,
            'branch_id' => $branchId,
            'description' => $description,
            'image' => $imageName
        ]);
        
        $_SESSION['message'] = 'Thêm sản phẩm thành công';
    }
    
    // Redirect về danh sách sản phẩm
    header('Location: ' . ROOT_URL . 'admin/products');
}
```

**Model**: `src/Models/Product.php`
```php
// Lấy tất cả sản phẩm kèm tên category và branch
public function getAllWithCategory() {
    $sql = "SELECT p.*, 
                   c.Name as category_name,
                   b.Name as branch_name
            FROM products p 
            LEFT JOIN catogory c ON p.Category_Id = c.Category_Id 
            LEFT JOIN branch b ON p.Branch_Id = b.Branch_Id 
            ORDER BY p.Create_at DESC";
    return $this->query($sql);
}

// Tạo sản phẩm mới
public function createProduct($data) {
    // Tự sinh mã: SP+0000000001, SP+0000000002, ...
    $productId = IdGenerator::generate('SP+', $this->table, 'Product_Id', 10);
    
    return $this->create([
        'Product_Id' => $productId,
        'Name' => $data['name'],
        'Price' => $data['price'],
        'Quantity' => $data['quantity'],
        'Category_Id' => $data['category_id'],
        'Branch_Id' => $data['branch_id'],
        'Description' => $data['description'],
        'Image' => $data['image'],
        'Create_at' => date('Y-m-d H:i:s'),
        'Product_View' => 0
    ]);
}

// Cập nhật sản phẩm
public function update($id, $data, $primaryKey = 'Product_Id') {
    $set = [];
    $params = [];
    
    foreach ($data as $key => $value) {
        $dbKey = $this->mapColumnName($key); // name → Name
        $set[] = "$dbKey = :$key";
        $params[$key] = $value;
    }
    
    $sql = "UPDATE products SET " . implode(", ", $set) . " WHERE $primaryKey = :id";
    $params['id'] = $id;
    
    $stmt = $this->db->prepare($sql);
    return $stmt->execute($params);
}

// Lấy sản phẩm theo ID
public function getById($id) {
    $sql = "SELECT * FROM products WHERE Product_Id = :id LIMIT 1";
    $result = $this->query($sql, ['id' => $id]);
    return $result ? $result[0] : false;
}
```

---

## 4️⃣ XÓA SẢN PHẨM

### A. Quy Trình
1. **Vào trang Admin** → **Sản phẩm**
2. **Bảng danh sách sản phẩm** → Tìm sản phẩm cần xóa
3. Click nút **Xóa** ở cuối dòng
4. Confirm xóa

### B. Code Flow

**View**: `src/Views/admin/product.php`
```html
<!-- Nút xóa với confirm -->
<a href="/admin/deleteProduct/<?php echo $p['id']; ?>" 
   onclick="return confirm('Xóa sản phẩm này?');">
   Xóa
</a>
```

**Controller**: `src/Controllers/AdminController.php`
```php
public function deleteProduct($id) {
    $productModel = new Product();
    
    // Kiểm tra sản phẩm có tồn tại không
    $product = $productModel->getById($id);
    if (!$product) {
        $_SESSION['error'] = 'Sản phẩm không tồn tại';
        header('Location: ' . ROOT_URL . 'admin/products');
        exit;
    }
    
    // Thực hiện xóa
    try {
        $success = $productModel->deleteById($id); // DELETE FROM products WHERE Product_Id = :id
        $_SESSION['message'] = 'Đã xóa sản phẩm';
    } catch (Exception $e) {
        $_SESSION['error'] = 'Lỗi khi xóa: ' . $e->getMessage();
    }
    
    // Redirect về danh sách
    header('Location: ' . ROOT_URL . 'admin/products');
}
```

**Model**: `src/Models/Product.php`
```php
public function deleteById($id) {
    $sql = "DELETE FROM products WHERE Product_Id = :id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute(['id' => $id]);
}
```

---

## 📋 CHỈNH SỬA SẢN PHẨM

### A. Quy Trình
1. **Vào trang Admin** → **Sản phẩm**
2. **Bảng danh sách** → Tìm sản phẩm → Click **Sửa**
3. Form tự động điền dữ liệu hiện tại
4. Thay đổi thông tin cần thiết
5. Click **Cập nhật**

### B. Code Flow

**Controller**: `src/Controllers/AdminController.php`
```php
public function editProduct($id) {
    $productModel = new Product();
    
    // Lấy sản phẩm cần sửa
    $product = $productModel->getById($id);
    if (!$product) {
        $_SESSION['error'] = 'Sản phẩm không tồn tại';
        header('Location: ' . ROOT_URL . 'admin/products');
        exit;
    }
    
    // Lấy danh sách category, branch để populate dropdown
    $categories = (new Category())->getAll();
    $branches = (new Branch())->getAll();
    
    $data = [
        'product' => $product,
        'categories' => $categories,
        'branches' => $branches,
        'editing' => true // Chế độ sửa (form sẽ hiển thị hidden input với ID)
    ];
    
    $this->renderView('admin/product', $data);
}
```

**View**: `src/Views/admin/product.php`
```html
<!-- Khi chế độ sửa, form sẽ có hidden input với ID sản phẩm -->
<?php if ($editing): ?>
    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
<?php endif; ?>

<!-- Form sẽ được điền sẵn dữ liệu hiện tại -->
<input type="text" name="name" value="<?php echo $product['name']; ?>">
<input type="number" name="price" value="<?php echo $product['price']; ?>">
<!-- v.v. -->
```

---

## 🔄 MAPPING DATABASE

### Foreign Keys (Khóa ngoại)

```sql
-- Sản phẩm liên kết tới Category
products.Category_Id → catogory.Category_Id

-- Sản phẩm liên kết tới Branch
products.Branch_Id → branch.Branch_Id
```

### Query Lấy Dữ Liệu Đầy Đủ

```sql
SELECT p.*, 
       c.Name as category_name,
       b.Name as branch_name
FROM products p 
LEFT JOIN catogory c ON p.Category_Id = c.Category_Id 
LEFT JOIN branch b ON p.Branch_Id = b.Branch_Id;
```

---

## ✅ CHECKLIST TRƯỚC KHI THÊM SẢN PHẨM

- [ ] Đã tạo **Category** chưa? Nếu chưa → Vào **Danh mục** tạo mới
- [ ] Đã tạo **Branch** chưa? Nếu chưa → Vào **Hãng** tạo mới
- [ ] Có ảnh sản phẩm không? Chuẩn bị file `.jpg`, `.png`
- [ ] Kiểm tra **giá**, **số lượng** có hợp lý không

---

## 🚨 CÁC LỖI THƯỜNG GẶP

| Lỗi | Nguyên Nhân | Cách Khắc Phục |
|-----|------------|-----------------|
| "Vui lòng chọn danh mục" | Chưa có category nào | Tạo category trước |
| "Vui lòng chọn hãng" | Chưa có branch nào | Tạo branch trước |
| Ảnh không hiển thị | Upload file sai định dạng | Dùng `.jpg`, `.png`, `.webp` |
| Không xóa được | Sản phẩm đã bị xóa | Refresh trang rồi thử lại |

---

## 📂 CẤU TRÚC THƯ MỤC LIÊN QUAN

```
DuAn1/
├── src/
│   ├── Controllers/
│   │   └── AdminController.php (chứa logic CRUD)
│   ├── Models/
│   │   ├── Product.php
│   │   ├── Category.php
│   │   └── Branch.php
│   └── Views/admin/
│       ├── product.php (form + danh sách)
│       ├── category.php
│       └── branch.php
└── public/
    └── images/ (lưu trữ ảnh sản phẩm)
```

---

## 🎓 TÓML TẮT

| Hành động | Route | Method | Hàm Model |
|-----------|-------|--------|-----------|
| Xem danh sách | `/admin/products` | GET | `getAllWithCategory()` |
| Thêm mới | `/admin/saveProduct` | POST | `createProduct()` |
| Sửa | `/admin/editProduct/{id}` | GET | `getById()` |
| Cập nhật | `/admin/saveProduct` | POST | `update()` |
| Xóa | `/admin/deleteProduct/{id}` | GET | `deleteById()` |

---

**Last Updated**: 2025-11-23
