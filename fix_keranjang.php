<?php
$file = 'resources/views/customer/keranjang.blade.php';
$content = file_get_contents($file);

// Replace $keranjang with $items
$content = str_replace('@if(empty($keranjang))', '@if(empty($items))', $content);
$content = str_replace('@foreach($keranjang as $id => $item)', '@foreach($items as $item)', $content);
$content = str_replace('@if(!empty($keranjang))', '@if(!empty($items))', $content);

// Replace $id in route
$content = str_replace("route('keranjang.hapus', \$id)", "route('keranjang.hapus', \$item['produk_id'])", $content);

// In controller $item['nama'] is not set, we should use $item['nama_produk']
// Wait, the controller sets $item['harga'] but not $item['nama'] if we just copy the session array.
// But we can just change $item['nama'] to $item['nama_produk'] in the view.
$content = str_replace("{{ \$item['nama'] }}", "{{ \$item['nama_produk'] }}", $content);

// Also we should clear the buggy session to prevent error for the user!
file_put_contents($file, $content);

echo "Keranjang blade fixed.";
