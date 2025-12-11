<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== TEST ROUTE PRODUK ===\n\n";

$product = Product::first();

if ($product) {
    echo "Product ditemukan:\n";
    echo "ID: {$product->id}\n";
    echo "Nama: {$product->nama_produk}\n";
    echo "Slug: {$product->slug}\n\n";
    
    $url = route('products.show', $product->slug);
    echo "URL yang seharusnya: {$url}\n\n";
    
    // Cek apakah produk bisa diambil dengan slug
    $testProduct = Product::where('slug', $product->slug)->first();
    if ($testProduct) {
        echo "✓ Product bisa diakses dengan slug\n";
    } else {
        echo "✗ Product TIDAK bisa diakses dengan slug\n";
    }
} else {
    echo "Tidak ada produk di database\n";
}
