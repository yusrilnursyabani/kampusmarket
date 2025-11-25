<?php

namespace App\Filament\Seller\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class ProductRatingChart extends ChartWidget
{
    protected static ?string $heading = 'Rating Produk';
    
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $sellerId = Auth::guard('seller')->id();
        
        $products = Product::where('seller_id', $sellerId)
            ->withCount('reviews')
            ->having('reviews_count', '>', 0)
            ->get()
            ->map(function ($product) {
                return [
                    'nama' => $product->nama_produk,
                    'rating' => $product->average_rating,
                    'reviews' => $product->reviews_count,
                ];
            })
            ->sortByDesc('rating')
            ->take(10);

        return [
            'datasets' => [
                [
                    'label' => 'Rating (1-5)',
                    'data' => $products->pluck('rating')->map(fn ($r) => round($r, 1))->toArray(),
                    'backgroundColor' => 'rgba(251, 191, 36, 0.5)',
                    'borderColor' => 'rgba(251, 191, 36, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $products->pluck('nama')->map(fn ($name) => 
                strlen($name) > 20 ? substr($name, 0, 20) . '...' : $name
            )->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'max' => 5,
                ],
            ],
        ];
    }
}
