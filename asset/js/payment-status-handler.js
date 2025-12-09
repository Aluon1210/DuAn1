/**
 * Payment Status Message Handler
 * Xử lý và hiển thị thông báo thanh toán
 */

class PaymentStatusHandler {
  /**
   * Xử lý response từ polling API
   */
  static handlePollingResponse(response) {
    if (response.success) {
      return {
        type: 'success',
        title: '✅ Thanh Toán Thành Công',
        message: response.message,
        payment: response.payment,
        orderId: response.order_id,
        orderData: response.order_data
      };
    }

    // Xử lý các loại lỗi khác nhau
    const message = response.message || 'Lỗi không xác định';

    // Kiểm tra xem là lỗi nào
    if (message.includes('không khớp')) {
      return this.handleMismatchError(response);
    } else if (message.includes('đã được xử lý')) {
      return {
        type: 'info',
        title: '⏳ Thanh Toán Đã Xử Lý',
        message: 'Giao dịch này đã được xử lý trước đó. Vui lòng không quét lại.',
        payment: response.payment
      };
    } else if (message.includes('chưa được phát hiện')) {
      return {
        type: 'warning',
        title: '⏳ Đang Chờ Thanh Toán',
        message: 'Hệ thống vẫn đang chờ phát hiện giao dịch của bạn. Vui lòng quét mã QR và thanh toán.',
        needsWaiting: true
      };
    } else {
      return {
        type: 'error',
        title: '❌ Lỗi Thanh Toán',
        message: message,
        payment: response.payment,
        comparison: response.comparison
      };
    }
  }

  /**
   * Xử lý lỗi khi thông tin không khớp
   */
  static handleMismatchError(response) {
    const comparison = response.comparison || {};
    const details = comparison.details || {};
    const reasons = [];

    // Build chi tiết lỗi
    const systemInfo = response.system_info || {};
    const payment = response.payment || {};

    if (!details.amount) {
      reasons.push({
        icon: '💰',
        label: 'Số Tiền',
        expected: this.formatCurrency(systemInfo.amount),
        actual: this.formatCurrency(payment['Giá trị'] || payment.amount || 0),
        status: '❌'
      });
    }

    if (!details.description) {
      reasons.push({
        icon: '📝',
        label: 'Nội Dung',
        expected: systemInfo.description || 'Không xác định',
        actual: payment['Mô tả'] || payment.description || 'Không xác định',
        status: '❌'
      });
    }

    if (!details.account_no) {
      reasons.push({
        icon: '🏦',
        label: 'Số Tài Khoản',
        expected: systemInfo.account_no || 'Không xác định',
        actual: payment['Số tài khoản'] || payment.account_no || 'Không xác định',
        status: '❌'
      });
    }

    return {
      type: 'mismatch',
      title: '⚠️ Thông Tin Thanh Toán Không Khớp',
      message: 'Giao dịch được phát hiện nhưng thông tin không khớp với yêu cầu. Vui lòng kiểm tra chi tiết dưới đây:',
      reasons: reasons,
      suggestion: 'Vui lòng thanh toán với đúng thông tin được yêu cầu hoặc liên hệ quản trị viên nếu có thắc mắc.'
    };
  }

  /**
   * Format tiền
   */
  static formatCurrency(amount) {
    const num = parseInt(amount) || 0;
    return new Intl.NumberFormat('vi-VN', {
      style: 'currency',
      currency: 'VND'
    }).format(num);
  }

  /**
   * Hiển thị modal thông báo
   */
  static showNotificationModal(statusInfo) {
    const modal = this.createModal(statusInfo);
    document.body.appendChild(modal);
    return modal;
  }

  /**
   * Tạo modal HTML
   */
  static createModal(statusInfo) {
    const modal = document.createElement('div');
    modal.className = `payment-status-modal ${statusInfo.type}`;

    let content = `
      <div class="payment-status-content">
        <div class="payment-status-header">
          <div class="payment-status-title">${statusInfo.title}</div>
          <button class="payment-status-close" onclick="this.closest('.payment-status-modal').remove()">✕</button>
        </div>

        <div class="payment-status-body">
          <div class="payment-status-message">${statusInfo.message}</div>
    `;

    // Nếu là lỗi không khớp, hiển thị chi tiết
    if (statusInfo.type === 'mismatch' && statusInfo.reasons) {
      content += '<div class="payment-mismatch-details">';
      statusInfo.reasons.forEach(reason => {
        content += `
          <div class="mismatch-item">
            <div class="mismatch-label">${reason.icon} ${reason.label}</div>
            <div class="mismatch-comparison">
              <div class="mismatch-part">
                <div class="mismatch-what">Yêu Cầu:</div>
                <div class="mismatch-value">${reason.expected}</div>
              </div>
              <div class="mismatch-arrow">→</div>
              <div class="mismatch-part">
                <div class="mismatch-what">Thực Tế:</div>
                <div class="mismatch-value error">${reason.actual}</div>
              </div>
            </div>
          </div>
        `;
      });
      content += '</div>';

      if (statusInfo.suggestion) {
        content += `
          <div class="payment-suggestion">
            <strong>💡 Gợi Ý:</strong> ${statusInfo.suggestion}
          </div>
        `;
      }
    }

    content += `
        </div>

        <div class="payment-status-footer">
    `;

    if (statusInfo.type === 'success') {
      content += `
        <button class="btn btn-success" onclick="this.closest('.payment-status-modal').remove()">
          Xác Nhận
        </button>
      `;
    } else if (statusInfo.type === 'mismatch') {
      content += `
        <button class="btn btn-primary" onclick="window.location.reload()">
          Thanh Toán Lại
        </button>
        <button class="btn btn-secondary" onclick="this.closest('.payment-status-modal').remove()">
          Đóng
        </button>
      `;
    } else {
      content += `
        <button class="btn btn-secondary" onclick="this.closest('.payment-status-modal').remove()">
          Đóng
        </button>
      `;
    }

    content += `
        </div>
      </div>
    `;

    modal.innerHTML = content;

    // Add CSS nếu chưa có
    this.injectStyles();

    return modal;
  }

  /**
   * Inject CSS styles
   */
  static injectStyles() {
    if (document.getElementById('payment-status-styles')) return;

    const style = document.createElement('style');
    style.id = 'payment-status-styles';
    style.textContent = `
      .payment-status-modal {
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
        animation: fadeIn 0.3s ease;
      }

      @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
      }

      .payment-status-content {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        animation: slideUp 0.3s ease;
      }

      @keyframes slideUp {
        from {
          transform: translateY(20px);
          opacity: 0;
        }
        to {
          transform: translateY(0);
          opacity: 1;
        }
      }

      .payment-status-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #eee;
      }

      .payment-status-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
      }

      .payment-status-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #999;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .payment-status-close:hover {
        color: #333;
      }

      .payment-status-body {
        padding: 20px;
      }

      .payment-status-message {
        font-size: 14px;
        color: #666;
        line-height: 1.6;
        margin-bottom: 20px;
      }

      .payment-mismatch-details {
        background: #f9f9f9;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
      }

      .mismatch-item {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
      }

      .mismatch-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
      }

      .mismatch-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        font-size: 14px;
      }

      .mismatch-comparison {
        display: flex;
        gap: 10px;
        align-items: center;
      }

      .mismatch-part {
        flex: 1;
        background: white;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ddd;
      }

      .mismatch-what {
        font-size: 11px;
        color: #999;
        text-transform: uppercase;
        margin-bottom: 5px;
      }

      .mismatch-value {
        font-size: 14px;
        font-weight: 500;
        color: #333;
        word-break: break-word;
      }

      .mismatch-value.error {
        color: #f44336;
      }

      .mismatch-arrow {
        color: #ddd;
        font-size: 20px;
      }

      .payment-suggestion {
        background: #fff3cd;
        border: 1px solid #ffeeba;
        border-radius: 6px;
        padding: 12px;
        font-size: 13px;
        color: #856404;
        margin-bottom: 15px;
      }

      .payment-status-footer {
        display: flex;
        gap: 10px;
        padding: 15px 20px;
        border-top: 1px solid #eee;
        background: #f9f9f9;
        justify-content: flex-end;
      }

      .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
      }

      .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
      }

      .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
      }

      .btn-success {
        background: #4caf50;
        color: white;
      }

      .btn-success:hover {
        background: #45a049;
      }

      .btn-secondary {
        background: #e0e0e0;
        color: #333;
      }

      .btn-secondary:hover {
        background: #d0d0d0;
      }

      /* Status-specific colors */
      .payment-status-modal.success .payment-status-header {
        background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
        color: white;
      }

      .payment-status-modal.success .payment-status-title {
        color: white;
      }

      .payment-status-modal.error .payment-status-header {
        background: linear-gradient(135deg, #f44336 0%, #e53935 100%);
        color: white;
      }

      .payment-status-modal.error .payment-status-title {
        color: white;
      }

      .payment-status-modal.mismatch .payment-status-header {
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        color: white;
      }

      .payment-status-modal.mismatch .payment-status-title {
        color: white;
      }

      .payment-status-modal.warning .payment-status-header {
        background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
        color: white;
      }

      .payment-status-modal.warning .payment-status-title {
        color: white;
      }

      @media (max-width: 600px) {
        .payment-status-content {
          width: 95%;
        }

        .mismatch-comparison {
          flex-direction: column;
        }

        .mismatch-arrow {
          transform: rotate(90deg);
        }

        .payment-status-footer {
          flex-direction: column;
        }

        .btn {
          width: 100%;
        }
      }
    `;

    document.head.appendChild(style);
  }
}

// Export
if (typeof module !== 'undefined' && module.exports) {
  module.exports = PaymentStatusHandler;
}
