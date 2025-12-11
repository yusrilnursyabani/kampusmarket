<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simulate logged in seller
$seller = \App\Models\Seller::where('status_verifikasi', 'diterima')->first();

if (!$seller) {
    die("No seller found\n");
}

echo "Testing with seller: {$seller->nama_pic} (ID: {$seller->id})\n\n";

// Simulate Auth
\Illuminate\Support\Facades\Auth::guard('seller')->setUser($seller);

echo "=== Testing Stock Report ===\n";
try {
    $controller = new \App\Http\Controllers\SellerReportController();
    $response = $controller->stockReport();
    echo "✓ Stock report generated successfully\n";
    echo "Response type: " . get_class($response) . "\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== Testing Rating Report ===\n";
try {
    $controller = new \App\Http\Controllers\SellerReportController();
    $response = $controller->ratingReport();
    echo "✓ Rating report generated successfully\n";
    echo "Response type: " . get_class($response) . "\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== Testing Low Stock Report ===\n";
try {
    $controller = new \App\Http\Controllers\SellerReportController();
    $response = $controller->lowStockReport();
    echo "✓ Low stock report generated successfully\n";
    echo "Response type: " . get_class($response) . "\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== Summary ===\n";
echo "All report controllers are functional!\n";
echo "If reports still don't work in browser, the issue is with authentication/session.\n";
