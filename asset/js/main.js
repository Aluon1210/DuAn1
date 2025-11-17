// ========== TRANG CHỦ ==========
document.addEventListener('DOMContentLoaded', async function() {
    // Khởi tạo dữ liệu tĩnh để hiển thị
    await initData();

    if (document.getElementById('productGrid')) {
        displayProducts(products);
    }

    // Lọc theo danh mục
    const categoryItems = document.querySelectorAll('.category-item');
    categoryItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const category = this.dataset.category;
            let filtered = category === 'all'
                ? products
                : products.filter(p => String(p.category) === String(category));
            displayProducts(filtered);
        });
    });

    // Sắp xếp
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            let sorted = [...products];
            switch (this.value) {
                case 'price-asc':  sorted.sort((a,b)=>a.price-b.price); break;
                case 'price-desc': sorted.sort((a,b)=>b.price-a.price); break;
                case 'newest':     sorted.reverse(); break;
            }
            displayProducts(sorted);
        });
    }

    // Tìm kiếm
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const keyword = this.value.toLowerCase();
            const filtered = keyword.trim() === ''
                ? products
                : products.filter(p =>
                    p.name.toLowerCase().includes(keyword) ||
                    p.description.toLowerCase().includes(keyword)
                  );
            displayProducts(filtered);
        });
    }
});

// Hàm hiển thị sản phẩm (hỗ trợ link ảnh hoặc emoji)
function displayProducts(productList) {
    const grid = document.getElementById('productGrid');
    if (!grid) return;

    if (productList.length === 0) {
        grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #7f8c8d; padding: 40px;">Không tìm thấy sản phẩm</p>';
        return;
    }

    grid.innerHTML = productList.map(product => `
        <div class="product-card">
            <div class="product-image">
                ${isImageUrl(product.image)
                    ? `<img src="${product.image}" alt="${product.name}" style="width:100%;height:100%;object-fit:cover;">`
                    : `${product.image}`}
            </div>
            <div class="product-info">
                <div class="product-name">${product.name}</div>
                <div class="product-price">${formatPrice(product.price)}</div>
                <div class="product-quantity">Kho: ${product.quantity}</div>
                <div class="product-actions">
                    <a href="product-detail.html?id=${product.id}" class="btn btn-primary">Xem</a>
                    <button onclick="addToCart(${product.id}, 1)" class="btn btn-success">Thêm</button>
                </div>
            </div>
        </div>
    `).join('');
}

/**
 * isImageUrl(src)
 * Kiểm tra chuỗi có phải link ảnh (http/https hoặc đường dẫn /) hay không.
 */
function isImageUrl(src) {
    return typeof src === 'string' && /^(https?:\/\/|\/)/.test(src);
}

// Hàm định dạng giá
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0
    }).format(price);
}
