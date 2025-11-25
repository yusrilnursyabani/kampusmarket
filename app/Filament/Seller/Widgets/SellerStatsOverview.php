<?php

namespace App\Filament\Seller\Widgets;

use App\Models\Product;
use App\Models\ProductReview;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class SellerStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $sellerId = Auth::guard('seller')->id();
        
        $totalProducts = Product::where('seller_id', $sellerId)->count();
        $activeProducts = Product::where('seller_id', $sellerId)->where('is_active', true)->count();
        $lowStockProducts = Product::where('seller_id', $sellerId)->where('stok', '<', 5)->where('stok', '>', 0)->count();
        $outOfStockProducts = Product::where('seller_id', $sellerId)->where('stok', 0)->count();
        
        $totalReviews = ProductReview::whereHas('product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->where('is_approved', true)->count();
        
        $averageRating = ProductReview::whereHas('product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->where('is_approved', true)->avg('rating') ?? 0;

        return [
            Stat::make('Total Produk', $totalProducts)
                ->description('Produk terdaftar')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),
            
            Stat::make('Produk Aktif', $activeProducts)
                ->description('Ditampilkan di frontend')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success'),
            
            Stat::make('Stok Rendah', $lowStockProducts)
                ->description('Produk dengan stok < 5')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning'),
            
            Stat::make('Stok Habis', $outOfStockProducts)
                ->description('Segera isi ulang stok')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
            
            Stat::make('Total Review', $totalReviews)
                ->description('Review dari pelanggan')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('info'),
            
            Stat::make('Rating Rata-rata', number_format($averageRating, 1))
                ->description('Dari semua produk')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}
