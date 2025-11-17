// ========== DỮ LIỆU TẠM THỜI ==========
const categories = [
    { id: 1, name: 'Áo thun' },
    { id: 2, name: 'Áo sơ mi' },
    { id: 3, name: 'Quần' },
    { id: 4, name: 'Váy' },
    { id: 5, name: 'Giày dép' },
    { id: 6, name: 'Phụ kiện' }
];

const products = [
    {
        id: 1,
        name: 'Áo thun cơ bản Nam',
        price: 99000,
        quantity: 50,
        category: 1,
        image: '👕',
        description: 'Áo thun 100% cotton, thoáng mát, phù hợp với mọi lứa tuổi.'
    },
    {
        id: 2,
        name: 'Áo thun nữ tay ngắn',
        price: 89000,
        quantity: 45,
        category: 1,
        image: '👕',
        description: 'Áo thun nữ form fitting, chất liệu cotton cao cấp.'
    },
    {
        id: 3,
        name: 'Áo sơ mi nam trắng',
        price: 199000,
        quantity: 30,
        category: 2,
        image: '👔',
        description: 'Áo sơ mi nam chất liệu linen thoáng mát, phù hợp công sở.'
    },
    {
        id: 4,
        name: 'Áo sơ mi nữ màu hồng',
        price: 179000,
        quantity: 25,
        category: 2,
        image: '👔',
        description: 'Áo sơ mi nữ kiểu dáng cổ điển, thoải mái suốt ngày.'
    },
    {
        id: 5,
        name: 'Quần jean nam xanh',
        price: 249000,
        quantity: 40,
        category: 3,
        image: '👖',
        description: 'Quần jean nam màu xanh đen, bền bỉ, phong cách.'
    },
    {
        id: 6,
        name: 'Quần kaki nữ',
        price: 229000,
        quantity: 35,
        category: 3,
        image: '👖',
        description: 'Quần kaki nữ form thon gọn, thoải mái và sang trọng.'
    },
    {
        id: 7,
        name: 'Váy xòe nữ',
        price: 199000,
        quantity: 20,
        category: 4,
        image: '👗',
        description: 'Váy xòe nữ màu đen thanh lịch, phù hợp dạo phố.'
    },
    {
        id: 8,
        name: 'Váy chữ A nữ',
        price: 179000,
        quantity: 15,
        category: 4,
        image: '👗',
        description: 'Váy chữ A nữ hoa văn họa tiết, nữ tính và thoải mái.'
    },
    {
        id: 9,
        name: 'Giày thể thao nam',
        price: 299000,
        quantity: 25,
        category: 5,
        image: '👟',
        description: 'Giày thể thao nam đế cao su bền bỉ, êm chân.'
    },
    {
        id: 10,
        name: 'Giày cao gót nữ',
        price: 349000,
        quantity: 18,
        category: 5,
        image: '👠',
        description: 'Giày cao gót nữ màu đen kinh điển, phù hợp công sở.'
    },
    {
        id: 11,
        name: 'Mũ lưỡi trai',
        price: 59000,
        quantity: 80,
        category: 6,
        image: '🧢',
        description: 'Mũ lưỡi trai chất liệu cotton, bảo vệ từ nắng.'
    },
    {
        id: 12,
        name: 'Dây lưng da',
        price: 89000,
        quantity: 50,
        category: 6,
        image: '🎀',
        description: 'Dây lưng da thật, thiết kế sang trọng và bền lâu.'
    }
];

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
