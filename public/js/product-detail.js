// ========== TRANG CHI TIẾT SẢN PHẨM ==========
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = parseInt(urlParams.get('id')) || 1;
    
    const product = products.find(p => p.id === productId);
    
    if (product) {
        displayProductDetail(product);
        displayRelatedProducts(product.category);
    }
});

// Hiển thị chi tiết sản phẩm
function displayProductDetail(product) {
    const container = document.getElementById('productDetail');
    if (!container) return;

    const category = categories.find(c => c.id === product.category);
    const categoryName = category ? category.name : 'Không xác định';

    container.innerHTML = `
        <div class="product-detail-image">${product.image}</div>
        <div class="product-detail-info">
            <h1>${product.name}</h1>
            <div class="product-detail-price">${formatPrice(product.price)}</div>
            
            <div class="product-specs">
                <p><strong>Danh mục:</strong> ${categoryName}</p>
                <p><strong>Kho hàng:</strong> <span style="color: ${product.quantity > 0 ? '#27ae60' : '#e74c3c'}">${product.quantity}</span></p>
                <p><strong>Mã sản phẩm:</strong> #${product.id}</p>
            </div>

            <h3>Mô tả sản phẩm</h3>
            <p>${product.description}</p>

            ${product.quantity > 0 ? `
                <div class="quantity-selector">
                    <label for="quantity">Số lượng:</label>
                    <input type="number" id="quantity" value="1" min="1" max="${product.quantity}">
                </div>

                <div class="product-actions-detail">
                    <button onclick="handleAddToCart(${product.id})" class="btn btn-success">🛒 Thêm vào giỏ</button>
                    <button class="btn btn-primary">❤️ Yêu thích</button>
                </div>
            ` : `
                <p style="color: #e74c3c; font-weight: bold; margin-top: 20px;">Sản phẩm hết hàng</p>
            `}
        </div>
    `;
}

// Xử lý thêm vào giỏ từ trang chi tiết
function handleAddToCart(productId) {
    const quantityInput = document.getElementById('quantity');
    const quantity = parseInt(quantityInput.value) || 1;
    addToCart(productId, quantity);
}

// Hiển thị sản phẩm liên quan
function displayRelatedProducts(categoryId) {
    const related = products.filter(p => p.category === categoryId).slice(0, 4);
    const container = document.getElementById('relatedProducts');
    if (!container) return;

    container.innerHTML = related.map(product => `
        <div class="product-card">
            <div class="product-image">${product.image}</div>
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

// Định dạng giá
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0
    }).format(price);
}
