<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Cek routes
echo "=== Checking Seller Report Routes ===\n";
$routes = \Illuminate\Support\Facades\Route::getRoutes();

foreach ($routes as $route) {
    $uri = $route->uri();
    if (str_contains($uri, 'seller/reports')) {
        echo "Route: " . $route->methods()[0] . " " . $uri;
        echo " -> " . ($route->getName() ?? 'no-name');
        echo " -> " . ($route->getActionName() ?? 'no-action');
        echo "\n";
    }
}

echo "\n=== Checking Auth Guard Seller ===\n";
$config = config('auth.guards.seller');
echo "Guard config: " . json_encode($config, JSON_PRETTY_PRINT) . "\n";

echo "\n=== Checking if SellerReportController exists ===\n";
if (class_exists(\App\Http\Controllers\SellerReportController::class)) {
    echo "✓ SellerReportController exists\n";
    $reflection = new ReflectionClass(\App\Http\Controllers\SellerReportController::class);
    echo "Methods:\n";
    foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
        if (!$method->isConstructor() && !$method->isDestructor()) {
            echo "  - " . $method->getName() . "\n";
        }
    }
} else {
    echo "✗ SellerReportController NOT found\n";
}

echo "\n=== Checking PDF Views ===\n";
$views = [
    'reports.seller.srs-12-stock',
    'reports.seller.srs-13-rating',
    'reports.seller.srs-14-lowstock',
];

foreach ($views as $view) {
    if (\Illuminate\Support\Facades\View::exists($view)) {
        echo "✓ View exists: $view\n";
    } else {
        echo "✗ View NOT found: $view\n";
    }
}
