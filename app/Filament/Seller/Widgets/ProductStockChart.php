<?php

namespace App\Filament\Seller\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class ProductStockChart extends ChartWidget
{
    protected static ?string $heading = 'Sebaran Stok Produk';
    
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $sellerId = Auth::guard('seller')->id();
        
        $products = Product::where('seller_id', $sellerId)
            ->orderBy('stok', 'desc')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Stok',
                    'data' => $products->pluck('stok')->toArray(),
                    'backgroundColor' => $products->pluck('stok')->map(function ($stok) {
                        if ($stok == 0) return 'rgba(239, 68, 68, 0.5)'; // red
                        if ($stok < 5) return 'rgba(251, 191, 36, 0.5)'; // yellow
                        return 'rgba(34, 197, 94, 0.5)'; // green
                    })->toArray(),
                    'borderColor' => $products->pluck('stok')->map(function ($stok) {
                        if ($stok == 0) return 'rgba(239, 68, 68, 1)';
                        if ($stok < 5) return 'rgba(251, 191, 36, 1)';
                        return 'rgba(34, 197, 94, 1)';
                    })->toArray(),
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $products->pluck('nama_produk')->map(fn ($name) => 
                strlen($name) > 20 ? substr($name, 0, 20) . '...' : $name
            )->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
