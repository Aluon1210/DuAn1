/**
 * Payment Polling Manager
 * Kiểm tra thanh toán mới liên tục từ Google Apps Script API
 * 
 * Sử dụng:
 * 1. Import file này vào trang checkout
 * 2. Gọi PaymentPoller.init({orderId: 'Ord0000000001'}) sau khi tạo QR
 * 3. Poller sẽ tự động kiểm tra thanh toán mỗi 2 giây
 * 4. Khi phát hiện thanh toán khớp, sẽ tự động tạo đơn hàng
 */

class PaymentPoller {
    constructor(config = {}) {
        this.config = {
            orderId: config.orderId || null,
            pollingInterval: config.pollingInterval || 2000, // 2 giây
            maxAttempts: config.maxAttempts || 600, // 20 phút
            apiUrl: config.apiUrl || '/payment/poll-latest-payment',
            onSuccess: config.onSuccess || null,
            onError: config.onError || null,
            onPaymentDetected: config.onPaymentDetected || null,
            onPollingStart: config.onPollingStart || null,
            onPollingStop: config.onPollingStop || null,
            autoCreateOrder: config.autoCreateOrder !== false, // Mặc định true
            ...config
        };

        this.pollingActive = false;
        this.attempts = 0;
        this.lastPaymentId = null;
        this.pollingTimer = null;
    }

    /**
     * Bắt đầu polling
     */
    start() {
        if (this.pollingActive) {
            console.warn('Polling đã được bắt đầu');
            return;
        }

        this.pollingActive = true;
        this.attempts = 0;

        if (this.config.onPollingStart) {
            this.config.onPollingStart({
                message: 'Đang kiểm tra thanh toán...',
                orderId: this.config.orderId
            });
        }

        console.log('[PaymentPoller] Bắt đầu polling thanh toán');
        this.poll();
    }

    /**
     * Dừng polling
     */
    stop() {
        if (!this.pollingActive) return;

        this.pollingActive = false;
        if (this.pollingTimer) {
            clearTimeout(this.pollingTimer);
            this.pollingTimer = null;
        }

        if (this.config.onPollingStop) {
            this.config.onPollingStop({
                message: 'Dừng kiểm tra thanh toán',
                attempts: this.attempts
            });
        }

        console.log('[PaymentPoller] Dừng polling sau ' + this.attempts + ' lần kiểm tra');
    }

    /**
     * Kiểm tra thanh toán một lần
     */
    async poll() {
        if (!this.pollingActive) return;

        this.attempts++;

        // Kiểm tra nếu vượt quá số lần thử
        if (this.attempts > this.config.maxAttempts) {
            console.warn('[PaymentPoller] Vượt quá số lần kiểm tra tối đa');
            this.stop();
            
            if (this.config.onError) {
                this.config.onError({
                    message: 'Quá thời gian chờ - Vui lòng thử lại',
                    error: 'Max polling attempts reached'
                });
            }
            return;
        }

        try {
            const response = await this.callPollingAPI();

            if (response.success) {
                // Phát hiện thanh toán khớp
                const payment = response.payment;
                const paymentId = payment['Mã GD'] || payment['payment_id'];

                // Kiểm tra xem đã xử lý thanh toán này chưa
                if (paymentId === this.lastPaymentId) {
                    // Cùng thanh toán, tiếp tục polling
                    this.scheduleNextPoll();
                    return;
                }

                this.lastPaymentId = paymentId;

                if (this.config.onPaymentDetected) {
                    this.config.onPaymentDetected({
                        payment: payment,
                        message: 'Phát hiện thanh toán khớp'
                    });
                }

                this.stop();

                if (this.config.onSuccess) {
                    this.config.onSuccess({
                        success: true,
                        message: response.message,
                        orderId: response.order_id,
                        payment: payment,
                        orderData: response.order_data
                    });
                }
                return;
            } else {
                // Chưa có thanh toán khớp, tiếp tục polling
                if (response.message) {
                    console.log('[PaymentPoller] Lần ' + this.attempts + ': ' + response.message);
                }

                // Gọi callback onPaymentCheck để cập nhật status
                if (this.config.onPaymentCheck) {
                    this.config.onPaymentCheck({
                        attempts: this.attempts,
                        message: response.message,
                        response: response
                    });
                }

                this.scheduleNextPoll();
            }

        } catch (error) {
            console.error('[PaymentPoller] Lỗi khi polling:', error);
            
            if (this.config.onError) {
                this.config.onError({
                    message: 'Lỗi kiểm tra thanh toán: ' + error.message,
                    error: error
                });
            }

            // Tiếp tục polling sau lỗi
            this.scheduleNextPoll();
        }
    }

    /**
     * Lên lịch polling lần tiếp theo
     */
    scheduleNextPoll() {
        if (!this.pollingActive) return;

        this.pollingTimer = setTimeout(() => {
            this.poll();
        }, this.config.pollingInterval);
    }

    /**
     * Gọi API polling
     */
    async callPollingAPI() {
        const payload = {
            order_id: this.config.orderId,
            user_id: this.getUserId()
        };

        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 20000); // 20 giây timeout

            const response = await fetch(this.config.apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload),
                credentials: 'same-origin',
                signal: controller.signal
            });

            clearTimeout(timeoutId);

            if (!response.ok) {
                if (response.status === 401) {
                    throw new Error('Unauthorized - Vui lòng đăng nhập lại');
                }
                throw new Error('HTTP ' + response.status + ': ' + response.statusText);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            if (error.name === 'AbortError') {
                throw new Error('Request timeout - API mất quá lâu để phản hồi');
            }
            throw error;
        }
    }

    /**
     * Lấy user ID từ trang
     */
    getUserId() {
        // Cố gắng lấy từ session/DOM
        const userIdElement = document.querySelector('[data-user-id]');
        if (userIdElement) {
            return userIdElement.getAttribute('data-user-id');
        }

        // Hoặc từ global variable nếu có
        if (window.currentUser && window.currentUser.id) {
            return window.currentUser.id;
        }

        return '';
    }

    /**
     * Static method - Tạo instance và bắt đầu polling
     */
    static create(config) {
        const poller = new PaymentPoller(config);
        return poller;
    }

    /**
     * Static method - Tạo và bắt đầu ngay lập tức
     */
    static startPolling(config) {
        const poller = new PaymentPoller(config);
        poller.start();
        return poller;
    }
}

// Export nếu dùng module
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PaymentPoller;
}
