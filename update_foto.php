<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Produk;

$p = Produk::find(1);
if ($p) {
    $p->foto_produk = 'produk/tiket_mandi_bola.jpg';
    $p->save();
    echo "SUCCESS: " . $p->nama_produk . " updated with " . $p->foto_produk;
} else {
    echo "ERROR: Product not found";
}
