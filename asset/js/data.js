// ========== API ENDPOINTS ==========
const API_BASE = 'api/';

let categories = [];
let products = [];

// Hàm fetch API
async function fetchAPI(endpoint, options = {}) {
    try {
        const response = await fetch(endpoint, options);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Lỗi API:', error);
        return { success: false, message: error.message };
    }
}

// Khởi tạo dữ liệu
async function initData() {
    // Lấy danh mục
    const catRes = await fetchAPI(API_BASE + 'categories.php?action=getAll');
    if (catRes.success) {
        categories = catRes.data;
    }

    // Lấy sản phẩm
    const prodRes = await fetchAPI(API_BASE + 'products.php?action=getAll');
    if (prodRes.success) {
        products = prodRes.data;
    }
}

// ========== GIỎ HÀNG ==========
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Hàm cập nhật số lượng giỏ hàng
function updateCartCount() {
    const cartCount = document.getElementById('cartCount');
    if (cartCount) {
        cartCount.textContent = cart.length;
    }
}

// Hàm thêm vào giỏ
function addToCart(productId, quantity = 1) {
    const product = products.find(p => p.id === productId);
    if (!product) return;

    const existingItem = cart.find(item => item.id === productId);
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({
            ...product,
            quantity: quantity
        });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    alert('Đã thêm ' + quantity + ' sản phẩm vào giỏ hàng!');
}

// Hàm xóa khỏi giỏ
function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
}

// Hàm cập nhật số lượng
function updateCartQuantity(productId, quantity) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        if (quantity <= 0) {
            removeFromCart(productId);
        } else {
            item.quantity = quantity;
            localStorage.setItem('cart', JSON.stringify(cart));
        }
    }
}

// Khởi tạo
updateCartCount();
