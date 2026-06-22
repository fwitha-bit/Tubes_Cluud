<?php
/**
 * Laundryku Configuration File
 */

// Firebase Configuration
// Replace with your Firebase Realtime Database URL
define('FIREBASE_DB_URL', getenv('FIREBASE_DB_URL') ?: 'https://laundryku-e7072-default-rtdb.asia-southeast1.firebasedatabase.app/');

// Set timezone to GMT+7 (WIB)
date_default_timezone_set('Asia/Jakarta');

// Laundry Services Definition (No typing menus, dropdown options only)
$services = [
    'wash_iron' => [
        'name' => 'Cuci Setrika',
        'price' => 8000,
        'icon' => 'fas fa-tshirt',
        'desc' => 'Cuci bersih + pengering + setrika rapi'
    ],
    'dry_clean' => [
        'name' => 'Cuci Kering (Dry Clean)',
        'price' => 12000,
        'icon' => 'fas fa-wind',
        'desc' => 'Perawatan premium untuk kain halus/jas'
    ],
    'iron_only' => [
        'name' => 'Setrika Saja',
        'price' => 5000,
        'icon' => 'fas fa-fire',
        'desc' => 'Penyetrikaan rapi dengan pelicin pakaian'
    ],
    'bedcover' => [
        'name' => 'Cuci Bedcover/Selimut',
        'price' => 15000,
        'icon' => 'fas fa-bed',
        'desc' => 'Pembersihan mendalam selimut/bedcover besar'
    ]
];
