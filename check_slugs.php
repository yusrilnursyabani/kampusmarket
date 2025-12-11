<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== CEK SLUG PRODUK ===\n\n";

$totalProducts = Product::count();
$productsWithSlug = Product::whereNotNull('slug')->where('slug', '!=', '')->count();
$productsWithoutSlug = Product::whereNull('slug')->orWhere('slug', '')->count();

echo "Total products: $totalProducts\n";
echo "Products with slug: $productsWithSlug\n";
echo "Products without slug: $productsWithoutSlug\n\n";

echo "=== SAMPLE PRODUCTS ===\n";
$products = Product::take(5)->get(['id', 'nama_produk', 'slug']);

foreach ($products as $product) {
    $slug = $product->slug ?? 'NULL';
    echo "ID: {$product->id} | {$product->nama_produk} | Slug: {$slug}\n";
}
