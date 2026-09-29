<?php

$dir = __DIR__;

// 1. FIX ALL VIEW FILES WITH INVALID PADDING p-3.5 -> p-4
$files = glob($dir . '/resources/views/**/*.blade.php');
$files = array_merge($files, glob($dir . '/resources/views/**/**/*.blade.php'));
$files = array_merge($files, glob($dir . '/resources/views/*.blade.php'));

foreach ($files as $file) {
    if (!is_file($file)) continue;
    $content = file_get_contents($file);
    if (strpos($content, 'p-3.5') !== false) {
        $content = str_replace('p-3.5', 'p-4', $content);
        file_put_contents($file, $content);
        echo "Fixed p-3.5 in: " . basename($file) . "\n";
    }
}

// 2. SPECIFICALLY FIX KARYAWAN PAGE (resources/views/owner/karyawan/index.blade.php)
$karyawan_file = $dir . '/resources/views/owner/karyawan/index.blade.php';
if (file_exists($karyawan_file)) {
    $c = file_get_contents($karyawan_file);
    
    // Fix Hero Gradient to match theme (#5B6D92 to #4A5978)
    $c = str_replace(
        '--primary-gradient: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);',
        '--primary-gradient: linear-gradient(135deg, #5B6D92 0%, #4A5978 100%);',
        $c
    );
    
    // Fix Tips Alert Box styling
    $old_tips = '<div class="card border-0 shadow-sm rounded-4 bg-info-subtle border-start border-info border-5 h-100 p-4">';
    $new_tips = '<div class="card border-0 shadow-sm rounded-4 h-100 p-4" style="background-color: #D5E3E6 !important; border-left: 5px solid #5B6D92 !important;">';
    $c = str_replace($old_tips, $new_tips, $c);
    
    // Also handle if p-4 was already changed
    $c = str_replace('bg-info-subtle border-start border-info', '', $c);
    $c = str_replace('text-info-emphasis', 'text-dark', $c);
    
    file_put_contents($karyawan_file, $c);
    echo "Karyawan page specifically refactored.\n";
}

echo "All card padding and alert styling fixed successfully!";
