// ========== TRANG GIỎ HÀNG ==========
document.addEventListener('DOMContentLoaded', function() {
    displayCart();
});

// Hiển thị giỏ hàng
function displayCart() {
    const container = document.getElementById('cartContainer');
    if (!container) return;

    if (cart.length === 0) {
        container.innerHTML = `
            <div class="cart-empty">
                <div class="cart-empty-icon">🛒</div>
                <h3>Giỏ hàng của bạn trống</h3>
                <p>Hãy thêm sản phẩm vào giỏ hàng</p>
                <a href="index.html" class="btn btn-primary" style="margin-top: 20px;">← Tiếp tục mua hàng</a>
            </div>
        `;
        return;
    }

    let total = 0;
    const cartItems = cart.map(item => {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        return `
            <tr>
                <td>
                    <div class="cart-item">
                        <div class="cart-item-image">${item.image}</div>
                        <div class="cart-item-details">
                            <h4>${item.name}</h4>
                            <a href="product-detail.html?id=${item.id}">Xem chi tiết</a>
                        </div>
                    </div>
                </td>
                <td style="text-align: center;">${formatPrice(item.price)}</td>
                <td style="text-align: center;">
                    <div class="cart-item-quantity">
                        <input type="number" value="${item.quantity}" min="1" onchange="updateQty(${item.id}, this.value)">
                    </div>
                </td>
                <td style="text-align: right; font-weight: bold; color: #e74c3c;">
                    ${formatPrice(subtotal)}
                </td>
                <td style="text-align: center;">
                    <button onclick="removeFromCart(${item.id}); displayCart();" class="btn btn-danger btn-small">🗑️</button>
                </td>
            </tr>
        `;
    }).join('');

    container.innerHTML = `
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th style="text-align: center;">Giá</th>
                    <th style="text-align: center;">Số lượng</th>
                    <th style="text-align: right;">Thành tiền</th>
                    <th style="text-align: center;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                ${cartItems}
            </tbody>
        </table>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <a href="index.html" class="btn btn-primary" style="width: 100%; text-align: center;">← Tiếp tục mua hàng</a>
            </div>
            <div class="cart-summary">
                <h3>Tóm tắt đơn hàng</h3>
                <div class="cart-summary-row">
                    <span>Tạm tính:</span>
                    <span>${formatPrice(total)}</span>
                </div>
                <div class="cart-summary-row">
                    <span>Phí vận chuyển:</span>
                    <span>Miễn phí</span>
                </div>
                <div class="cart-summary-row total">
                    <span>Tổng cộng:</span>
                    <span>${formatPrice(total)}</span>
                </div>
                <button onclick="checkout()" class="btn btn-success" style="width: 100%; margin-top: 15px;">✓ Thanh toán</button>
            </div>
        </div>
    `;
}

// Cập nhật số lượng
function updateQty(productId, quantity) {
    quantity = parseInt(quantity) || 1;
    if (quantity <= 0) {
        removeFromCart(productId);
    } else {
        updateCartQuantity(productId, quantity);
    }
    displayCart();
}

// Thanh toán
function checkout() {
    if (cart.length === 0) {
        alert('Giỏ hàng trống!');
        return;
    }
    
    alert('Cảm ơn bạn đã đặt hàng! Chức năng thanh toán sẽ được kích hoạt sau.');
    cart = [];
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    displayCart();
}

// Định dạng giá
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0
    }).format(price);
}
