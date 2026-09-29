<?php

$dir = __DIR__;

// 1. ADMIN LAYOUT
$admin = file_get_contents($dir . '/resources/views/layouts/admin.blade.php');
// CSS Variables
$admin = str_replace('--bs-primary: #0d6efd;', '--bs-primary: #5B6D92;', $admin);
$admin = str_replace('--bs-body-bg: #f4f6f9;', '--bs-body-bg: #F0E2D2;', $admin);
// Sidebar
$admin = str_replace('background: #252d3a;', 'background: #5B6D92;', $admin);
// Active Link Hover (Use #D5E3E6)
$admin = preg_replace('/(\.sidebar-link:hover,\s*\.sidebar-link\.active\s*\{)(.*?)(\})/s', '$1 background-color: #D5E3E6; color: #5B6D92; font-weight: bold; $3', $admin);
// Header
$admin = str_replace('background: #fff; border-bottom: 1px solid #e9ecef;', 'background: #F0E2D2; border-bottom: 1px solid #D5E3E6;', $admin);
// Cards
$admin = preg_replace('/\.card\s*\{.*?\}/s', ".card { border-radius: var(--card-border-radius); border: none; box-shadow: var(--card-box-shadow); background-color: #fff; border-top: 4px solid #5B6D92; }", $admin);
file_put_contents($dir . '/resources/views/layouts/admin.blade.php', $admin);


// 2. LOGIN PAGE
$login = file_get_contents($dir . '/resources/views/auth/login.blade.php');
$login = str_replace('background-color: #f0f2f5;', 'background-color: #F0E2D2;', $login);
$login = str_replace('color: #0d6efd;', 'color: #5B6D92;', $login);
$login = str_replace('text-warning', 'text-secondary', $login); // Remove yellow icon
$login_override = "
        .btn-primary { background-color: #5B6D92 !important; border-color: #5B6D92 !important; }
        .btn-primary:hover { background-color: #4A5978 !important; border-color: #4A5978 !important; }
        .text-primary { color: #5B6D92 !important; }
";
$login = str_replace('</style>', $login_override . '</style>', $login);
file_put_contents($dir . '/resources/views/auth/login.blade.php', $login);


// 3. KATALOG CUSTOMER
$katalog = file_get_contents($dir . '/resources/views/customer/katalog.blade.php');
// Body Background
$katalog = str_replace('background-color: #f1ebd9;', 'background-color: #F0E2D2;', $katalog);
// Primary buttons & text
$katalog = str_replace('#2e7d32', '#5B6D92', $katalog);
$katalog = str_replace('#388e3c', '#5B6D92', $katalog);
$katalog = str_replace('text-warning', 'text-light', $katalog); 
$katalog = str_replace('text-success', 'text-primary', $katalog); 
$katalog = str_replace('bg-success', 'bg-primary', $katalog); 
$katalog_override = "
        .btn-primary, .bg-primary { background-color: #5B6D92 !important; border-color: #5B6D92 !important; }
        .text-primary { color: #5B6D92 !important; }
        .cart-header { background: #D5E3E6 !important; }
        .cat-pill:hover, .cat-pill.active { background: #D5E3E6; color: #5B6D92; border: 2px solid #5B6D92; }
";
$katalog = str_replace('</style>', $katalog_override . '</style>', $katalog);
file_put_contents($dir . '/resources/views/customer/katalog.blade.php', $katalog);


// 4. STATUS PESANAN CUSTOMER
$status = file_get_contents($dir . '/resources/views/customer/status.blade.php');
$status = str_replace('background-color: #f0f2f5;', 'background-color: #F0E2D2;', $status);
$status = str_replace('background: #0d6efd;', 'background: #5B6D92;', $status);
$status = str_replace('border-bottom: 30px dotted #f0f2f5;', 'border-bottom: 30px dotted #F0E2D2;', $status);
$status = str_replace('text-primary', '', $status); // Remove default primary class for total price
$status = str_replace('fs-5 text-primary', 'fs-5', $status);
$status = str_replace('text-primary', '', $status);
$status_override = "
        .btn-primary, .bg-primary { background-color: #5B6D92 !important; border-color: #5B6D92 !important; }
        .fs-5 { color: #5B6D92 !important; }
";
$status = str_replace('</style>', $status_override . '</style>', $status);
file_put_contents($dir . '/resources/views/customer/status.blade.php', $status);


echo "Theme Successfully Applied!";
