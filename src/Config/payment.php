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
        'payment_api_url' => 'http://localhost/DuAn1/tools/mock_gas_api.php',
        'enabled' => true,
    ],
];
