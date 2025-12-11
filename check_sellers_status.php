<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== All Sellers ===\n";
$sellers = \App\Models\Seller::all(['id', 'nama_pic', 'email_pic', 'status_verifikasi']);

echo "Total sellers: " . $sellers->count() . "\n\n";

foreach ($sellers as $seller) {
    echo "ID: {$seller->id}\n";
    echo "Name: {$seller->nama_pic}\n";
    echo "Email: {$seller->email_pic}\n";
    echo "Status: {$seller->status_verifikasi}\n";
    
    // Hitung produk
    $productCount = \App\Models\Product::where('seller_id', $seller->id)->count();
    echo "Products: {$productCount}\n";
    echo "---\n";
}
