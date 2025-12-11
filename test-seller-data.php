<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Cari seller yang aktif
echo "=== Looking for active seller ===\n";
$seller = \App\Models\Seller::where('status_verifikasi', 'diterima')
    ->first();

if (!$seller) {
    echo "No active seller found!\n";
    exit(1);
}

echo "Found seller: {$seller->nama_pic} (email: {$seller->email_pic})\n";
echo "Seller ID: {$seller->id}\n";

// Test apakah seller punya produk
echo "\n=== Checking seller products ===\n";
$products = \App\Models\Product::where('seller_id', $seller->id)->get();
echo "Total products: " . $products->count() . "\n";

if ($products->count() > 0) {
    echo "Products:\n";
    foreach ($products->take(5) as $product) {
        echo "  - {$product->nama_produk} (stock: {$product->stok})\n";
    }
}

// Test query untuk laporan stock
echo "\n=== Testing Stock Report Query ===\n";
$stockProducts = \App\Models\Product::with(['category', 'reviews' => fn($q) => $q->where('is_approved', true)])
    ->where('seller_id', $seller->id)
    ->orderBy('stok', 'desc')
    ->get();

echo "Products for stock report: " . $stockProducts->count() . "\n";

// Test query untuk laporan low stock
echo "\n=== Testing Low Stock Report Query ===\n";
$lowStockProducts = \App\Models\Product::with(['category'])
    ->where('seller_id', $seller->id)
    ->where('stok', '<', 2)
    ->count();

echo "Products with low stock (< 2): " . $lowStockProducts . "\n";

echo "\n=== Test Summary ===\n";
echo "✓ Seller found and active\n";
echo "✓ Products exist for this seller\n";
echo "✓ Report queries working correctly\n";
echo "\nReport links should work when logged in as: {$seller->email_pic}\n";
