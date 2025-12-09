// ========== TRANG ADMIN ==========
document.addEventListener('DOMContentLoaded', async function() {
    // Khởi tạo dữ liệu từ API
    await initData();

    // Menu navigation
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active từ tất cả items
            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            // Hide tất cả sections
            document.querySelectorAll('.admin-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Show section được chọn
            const section = this.dataset.section + '-section';
            const targetSection = document.getElementById(section);
            if (targetSection) {
                targetSection.classList.add('active');
                
                // Load dữ liệu nếu là section products
                if (this.dataset.section === 'products') {
                    loadProductsTable();
                }
            }
        });
    });

    // Modal
    const productModal = document.getElementById('productModal');
    const addProductBtn = document.getElementById('addProductBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const closeBtn = document.querySelector('.close');
    const productForm = document.getElementById('productForm');

    if (addProductBtn) {
        addProductBtn.addEventListener('click', function() {
            document.getElementById('modalTitle').textContent = 'Thêm sản phẩm mới';
            productForm.reset();
            productModal.style.display = 'flex';
        });
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            productModal.style.display = 'none';
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            productModal.style.display = 'none';
        });
    }

    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Sản phẩm đã được lưu!');
            productModal.style.display = 'none';
            loadProductsTable();
        });
    }

    // Close modal khi click bên ngoài
    window.addEventListener('click', function(e) {
        if (e.target === productModal) {
            productModal.style.display = 'none';
        }
    });
});

// Load bảng sản phẩm
function loadProductsTable() {
    const table = document.getElementById('productsTable');
    if (!table) return;

    let rows = '<tr><th>ID</th><th>Tên sản phẩm</th><th>Danh mục</th><th>Giá</th><th>Kho</th><th>Hành động</th></tr>';
    
    products.forEach(product => {
        const category = categories.find(c => c.id === product.category);
        rows += `
            <tr>
                <td>${product.id}</td>
                <td>${product.name}</td>
                <td>${category ? category.name : 'N/A'}</td>
                <td>${formatPrice(product.price)}</td>
                <td>${product.quantity}</td>
                <td>
                    <button class="btn btn-small btn-warning" onclick="editProduct(${product.id})">✏️ Sửa</button>
                    <button class="btn btn-small btn-danger" onclick="deleteProduct(${product.id})">🗑️ Xóa</button>
                </td>
            </tr>
        `;
    });

    table.innerHTML = rows;
}

// Edit sản phẩm
function editProduct(productId) {
    const product = products.find(p => p.id === productId);
    if (!product) return;

    document.getElementById('modalTitle').textContent = 'Sửa sản phẩm';
    document.getElementById('productName').value = product.name;
    document.getElementById('productCategory').value = product.category;
    document.getElementById('productPrice').value = product.price;
    document.getElementById('productQuantity').value = product.quantity;
    document.getElementById('productDescription').value = product.description;

    document.getElementById('productModal').style.display = 'flex';
}

// Delete sản phẩm
function deleteProduct(productId) {
    if (confirm('Bạn chắc chắn muốn xóa sản phẩm này?')) {
        const index = products.findIndex(p => p.id === productId);
        if (index > -1) {
            products.splice(index, 1);
            alert('Sản phẩm đã được xóa!');
            loadProductsTable();
        }
    }
}

// Định dạng giá
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0
    }).format(price);
}
