<?php
/**
 * Mock Payment Data Store
 * Simulates GAS API responses for testing
 */

// This file stores mock payment data
return [
    'transactions' => [
        [
            'Mã GD' => '1001',
            'Mô tả' => 'Thanh toan - Test User - Tui Speedy',
            'Giá trị' => 8000,
            'Ngày diễn ra' => date('Y-m-d H:i:s'),
            'Số tài khoản' => '0833268346'
        ],
        [
            'Mã GD' => '1002',
            'Mô tả' => 'Thanh toan - Customer2',
            'Giá trị' => 50000,
            'Ngày diễn ra' => date('Y-m-d H:i:s', strtotime('-1 hour')),
            'Số tài khoản' => '0833268346'
        ]
    ]
];
?>
