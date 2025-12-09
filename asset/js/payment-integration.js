/**
 * Payment Integration Script
 * Xử lý thanh toán QR và tự động tạo đơn hàng
 * 
 * File này nên được thêm vào CheckoutConfirm.php hoặc payment page
 */

class PaymentIntegration {
  constructor() {
    this.qrModal = document.getElementById('paymentModal');
    this.checkPaymentBtn = document.getElementById('checkPaymentBtn');
    this.createOrderBtn = document.getElementById('createOrderBtn');
    this.paymentMethod = 'qr'; // default
  }

  /**
   * Khởi tạo event listeners
   */
  init() {
    // Kiểm tra thanh toán
    if (this.checkPaymentBtn) {
      this.checkPaymentBtn.addEventListener('click', () => {
        this.checkPayment();
      });
    }

    // Tạo đơn hàng
    if (this.createOrderBtn) {
      this.createOrderBtn.addEventListener('click', () => {
        this.createOrderAfterPayment();
      });
    }

    // Radio buttons để chọn phương thức
    const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
    paymentMethodRadios.forEach(radio => {
      radio.addEventListener('change', (e) => {
        this.paymentMethod = e.target.value;
      });
    });
  }

  /**
   * Kiểm tra giao dịch thanh toán
   */
  async checkPayment() {
    const amount = this.getCartTotal();
    const description = this.getPaymentDescription();
    const accountNo = document.querySelector('[data-account-no]')?.textContent || '';
    const bankId = document.querySelector('[data-bank-id]')?.textContent || '';

    if (!amount) {
      alert('Không tìm thấy thông tin giỏ hàng');
      return;
    }

    try {
      this.setLoading(this.checkPaymentBtn, true);

      const response = await fetch('/payment/check-payment', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          order_id: this.generateOrderId(),
          amount: amount,
          description: description,
          account_no: accountNo,
          bank_id: bankId
        })
      });

      const result = await response.json();

      if (result.success) {
        // Thanh toán thành công
        this.handlePaymentSuccess(result);
      } else {
        alert('Giao dịch chưa được phát hiện: ' + result.message);
      }
    } catch (error) {
      console.error('Lỗi kiểm tra thanh toán:', error);
      alert('Lỗi kết nối: ' + error.message);
    } finally {
      this.setLoading(this.checkPaymentBtn, false);
    }
  }

  /**
   * Xử lý khi thanh toán thành công
   */
  async handlePaymentSuccess(paymentResult) {
    const confirmCreate = confirm(
      'Giao dịch đã được xác nhận!\n\n' +
      'Số tiền: ' + this.formatCurrency(paymentResult.transaction?.amount || 0) + '\n\n' +
      'Bây giờ tạo đơn hàng?'
    );

    if (confirmCreate) {
      await this.createOrderAfterPayment();
    }
  }

  /**
   * Tạo đơn hàng sau khi thanh toán thành công
   */
  async createOrderAfterPayment() {
    const amount = this.getCartTotal();
    const address = document.getElementById('addressInput')?.value || '';
    const note = document.getElementById('noteInput')?.value || '';

    // Validate
    if (!amount) {
      alert('Không tìm thấy thông tin giỏ hàng');
      return;
    }

    if (!address) {
      alert('Vui lòng nhập địa chỉ giao hàng');
      document.getElementById('addressInput')?.focus();
      return;
    }

    try {
      this.setLoading(this.createOrderBtn, true);

      const response = await fetch('/payment/create-order-on-payment', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          amount: amount,
          description: 'Thanh toán đơn hàng',
          address: address,
          note: note
        })
      });

      const result = await response.json();

      if (result.success) {
        // Tạo đơn hàng thành công
        this.showSuccessModal(result);
        // Chuyển hướng sau 2 giây
        setTimeout(() => {
          window.location.href = `/order/${result.order_id}`;
        }, 2000);
      } else {
        alert('Tạo đơn hàng thất bại:\n' + result.message);
      }
    } catch (error) {
      console.error('Lỗi tạo đơn hàng:', error);
      alert('Lỗi kết nối: ' + error.message);
    } finally {
      this.setLoading(this.createOrderBtn, false);
    }
  }

  /**
   * Hiển thị modal thành công
   */
  showSuccessModal(orderData) {
    const modal = document.createElement('div');
    modal.className = 'success-modal';
    modal.innerHTML = `
      <div class="success-modal-content">
        <div class="success-icon">✓</div>
        <h2>Đơn hàng được tạo thành công!</h2>
        <p class="order-id">Mã đơn hàng: <strong>${orderData.order_id}</strong></p>
        <p class="order-info">
          Số lượng sản phẩm: ${orderData.items_count}<br>
          Tổng tiền: ${this.formatCurrency(orderData.total_amount)}
        </p>
        <p class="redirect-msg">Đang chuyển hướng trang...</p>
      </div>
    `;

    // CSS cho modal
    const style = document.createElement('style');
    style.textContent = `
      .success-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
      }

      .success-modal-content {
        background: white;
        border-radius: 16px;
        padding: 40px;
        text-align: center;
        min-width: 350px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        animation: slideUp 0.3s ease;
      }

      .success-icon {
        font-size: 60px;
        color: #4CAF50;
        margin-bottom: 20px;
        animation: scaleIn 0.3s ease;
      }

      .success-modal-content h2 {
        color: #333;
        margin-bottom: 15px;
        font-family: 'Playfair Display', serif;
      }

      .order-id {
        font-size: 16px;
        color: #666;
        margin-bottom: 10px;
      }

      .order-id strong {
        color: #000;
        font-weight: 700;
      }

      .order-info {
        background: #f5f5f5;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        font-size: 14px;
        line-height: 1.6;
        color: #666;
      }

      .redirect-msg {
        font-size: 12px;
        color: #999;
        margin-top: 15px;
      }

      @keyframes slideUp {
        from {
          opacity: 0;
          transform: translateY(20px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      @keyframes scaleIn {
        from {
          transform: scale(0);
        }
        to {
          transform: scale(1);
        }
      }
    `;

    document.head.appendChild(style);
    document.body.appendChild(modal);
  }

  /**
   * Lấy tổng tiền giỏ hàng
   */
  getCartTotal() {
    const totalElement = document.querySelector('[data-cart-total]');
    if (totalElement) {
      const totalText = totalElement.textContent;
      // Extract số từ chuỗi (VD: "1,500,000" hoặc "1500000")
      const total = parseInt(totalText.replace(/[^0-9]/g, ''), 10);
      return isNaN(total) ? 0 : total;
    }
    return 0;
  }

  /**
   * Tạo mô tả thanh toán
   */
  getPaymentDescription() {
    const user = document.querySelector('[data-user-name]')?.textContent || 'User';
    return `Thanh toan don hang - ${user}`;
  }

  /**
   * Generate Order ID (temp)
   */
  generateOrderId() {
    return 'TMP' + Date.now();
  }

  /**
   * Format tiền VND
   */
  formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
      style: 'currency',
      currency: 'VND'
    }).format(amount);
  }

  /**
   * Set loading state
   */
  setLoading(element, isLoading) {
    if (!element) return;
    
    if (isLoading) {
      element.disabled = true;
      element.dataset.originalText = element.textContent;
      element.textContent = '⏳ Đang xử lý...';
    } else {
      element.disabled = false;
      element.textContent = element.dataset.originalText || 'Thực hiện';
    }
  }
}

// Khởi tạo khi DOM ready
document.addEventListener('DOMContentLoaded', () => {
  const paymentIntegration = new PaymentIntegration();
  paymentIntegration.init();
});
