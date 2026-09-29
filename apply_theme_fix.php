<?php

$dir = __DIR__;
$admin = file_get_contents($dir . '/resources/views/layouts/admin.blade.php');

$css_override = <<<CSS
        /* --- GLOBAL THEME FIXES --- */
        body { background-color: #F0E2D2 !important; }
        
        /* Sidebar Recolor */
        #sidebar { background: #5B6D92 !important; }
        #sidebar .sidebar-header { background: #4A5978 !important; border-bottom: 1px solid #4A5978 !important; }
        #sidebar a { color: #D5E3E6 !important; }
        #sidebar a:hover, #sidebar a.active {
            background: #D5E3E6 !important;
            color: #5B6D92 !important;
            border-left: 4px solid #F0E2D2 !important;
        }

        /* Enforce White Backgrounds on Tables and Forms */
        .table { background-color: #ffffff !important; border-collapse: collapse; }
        .table tbody tr, .table td, .table th { background-color: #ffffff !important; }
        .table thead th { background-color: #D5E3E6 !important; color: #5B6D92 !important; border-bottom: 2px solid #5B6D92 !important; }
        
        .form-control, .form-select { background-color: #ffffff !important; border: 1px solid #D5E3E6 !important; }
        .form-control:focus, .form-select:focus { border-color: #5B6D92 !important; box-shadow: 0 0 0 0.25rem rgba(91, 109, 146, 0.25) !important; }
        
        /* Ensure responsive table containers also have white background */
        .table-responsive { background-color: #ffffff !important; border-radius: var(--card-border-radius); padding: 10px; box-shadow: var(--card-box-shadow); }
        
        /* Wrap empty states in a white box */
        .text-center.py-5 { background-color: #ffffff !important; border-radius: var(--card-border-radius); padding: 2rem !important; box-shadow: var(--card-box-shadow); }

        /* Buttons & Badges */
        .btn-primary, .bg-primary, .text-bg-primary { background-color: #5B6D92 !important; border-color: #5B6D92 !important; color: white !important; }
        .btn-primary:hover { background-color: #4A5978 !important; border-color: #4A5978 !important; }
        .text-primary { color: #5B6D92 !important; }
        
        /* Fix the purple gradient header in Produk */
        .bg-gradient { background: linear-gradient(135deg, #5B6D92 0%, #4A5978 100%) !important; }
CSS;

$admin = str_replace('</style>', $css_override . "\n    </style>", $admin);
file_put_contents($dir . '/resources/views/layouts/admin.blade.php', $admin);

echo "Admin Theme Fixed!";
