<?php
/**
 * Payment Configuration
 * Cấu hình thanh toán QR Code cho VietQR
 * 
 * Link VietQR: https://img.vietqr.io/image/{BANK_ID}-{ACCOUNT_NO}-{TEMPLATE}.png
 */

return [
    'qr' => [
        // Thông tin ngân hàng
        'bank_id' => 'MB',  // Mã ngân hàng (VD: ACB, VIETCOMBANK, BIDV, v.v.)
        'account_no' => '0833268346',  // Số tài khoản
        'account_name' => 'DUONG THANH CONG',  // Tên chủ tài khoản
        
        // Template QR
        'template' => 'print',  // Có thể là: print, compact, v.v.
        
        // Cấu hình mặc định
        'enabled' => true,  // Bật/tắt tính năng QR
    ],
    
    // Các mã ngân hàng phổ biến (tham khảo)
    'bank_codes' => [
        'ACB' => 'Asia Commercial Bank',
        'VIETCOMBANK' => 'Vietcombank',
        'BIDV' => 'BIDV',
        'TECHCOMBANK' => 'Techcombank',
        'SACOMBANK' => 'Sacombank',
        'VPBANK' => 'VPBank',
        'TPBANK' => 'TPBank',
        'AGRIBANK' => 'AgriBank',
        'VIETINBANK' => 'Vietinbank',
        'MB' => 'MB Bank',
        'MSBANK' => 'Maritime Bank',
        'OCB' => 'OCB',
        'SHINHAN' => 'Shinhan Bank',
        'SCB' => 'SCB',
        'SHB' => 'Shinhan Bank',
    ],
    
    // Thêm thông tin khác nếu cần
    'api_base_url' => 'https://img.vietqr.io/image/',
    
    // Google Apps Script API - Thanh toán
    'google_apps_script' => [
        'payment_api_url' => 'https://script.googleusercontent.com/macros/echo?user_content_key=AehSKLg3u5UbB6PNOWTQKbTUFJqPh2VipnK61rKdtQkTO5DS6X3GtiCRetLtc01RFPR4CiahvR76Ffe2G5L4gqG7bJDPKTUEi1ob1vqL7QLWOpIuxxF16fe4BtNXje_XSBtg0f7YmyLnC2PhQS_lvIt_Ke0tJsE6k5vuEQ8sy5dVLD-RXqsbBvDWvQyHBy8nqlsA_UYGyH91t75-JRVRnDRq4BXMyDBJ3y-jJCqRECZFfrX6DNC_LQgXwAIBqva07gPbJPuRgHBCaYIYJAtYj6Ywsl5-yy60Pw&lib=MUoExfrMQjyQ36Yszw56Uf0FNOc6D1c8x',
        'enabled' => true,
    ],
];
