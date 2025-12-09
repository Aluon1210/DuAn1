<?php
/**
 * Mock GAS API Endpoint for Testing
 * URL: /payment/mock-gas
 * 
 * Returns mock transaction data for testing the payment check flow
 */

header('Content-Type: application/json; charset=utf-8');

// Return mock transaction data in the same format as GAS would return
$mockData = [
    'success' => true,
    'message' => 'Mock data retrieved successfully',
    'data' => [
        [
            'Mã GD' => '1001',
            'Mô tả' => 'Thanh toan - Test User',
            'Giá trị' => 8000,
            'Ngày diễn ra' => date('Y-m-d H:i:s'),
            'Số tài khoản' => '0833268346'
        ],
        [
            'Mã GD' => '1002',
            'Mô tả' => 'Thanh toan - Customer2 - Product',
            'Giá trị' => 50000,
            'Ngày diễn ra' => date('Y-m-d H:i:s', strtotime('-1 hour')),
            'Số tài khoản' => '0833268346'
        ],
        [
            'Mã GD' => '1003',
            'Mô tả' => 'Thanh toan - Another',
            'Giá trị' => 15000,
            'Ngày diễn ra' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            'Số tài khoản' => '0833268346'
        ]
    ]
];

echo json_encode($mockData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
