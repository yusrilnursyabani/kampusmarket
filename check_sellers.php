<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Seller;

echo "=== DATA SELLER DI DATABASE ===\n\n";

$sellers = Seller::all(['id', 'email_pic', 'nama_toko', 'password', 'status_verifikasi', 'is_active']);

echo "Total Sellers: " . $sellers->count() . "\n\n";

foreach ($sellers as $seller) {
    echo "ID: {$seller->id}\n";
    echo "Email: {$seller->email_pic}\n";
    echo "Toko: {$seller->nama_toko}\n";
    echo "Password Hash: " . (strlen($seller->password) > 0 ? 'Ada (' . strlen($seller->password) . ' chars)' : 'KOSONG!') . "\n";
    echo "Status Verifikasi: {$seller->status_verifikasi}\n";
    echo "Aktif: " . ($seller->is_active ? 'Ya' : 'Tidak') . "\n";
    echo str_repeat('-', 50) . "\n";
}

echo "\n=== PENCARIAN SELLER HASAN & AFFAN ===\n";

$hasan = Seller::where('email_pic', 'like', '%hasan%')->first();
if ($hasan) {
    echo "✓ Hasan ditemukan - Email: {$hasan->email_pic}, Status: {$hasan->status_verifikasi}\n";
} else {
    echo "✗ Hasan TIDAK ditemukan\n";
}

$affan = Seller::where('email_pic', 'like', '%affan%')->first();
if ($affan) {
    echo "✓ Affan ditemukan - Email: {$affan->email_pic}, Status: {$affan->status_verifikasi}\n";
} else {
    echo "✗ Affan TIDAK ditemukan\n";
}
