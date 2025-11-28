<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Seller;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Penjual', Seller::count())
                ->description('Penjual terdaftar')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('success')
                ->chart([7, 15, 22, 30, 35, 40, 45]),
            
            Stat::make('Penjual Aktif', Seller::where('status_verifikasi', 'diterima')->where('is_active', true)->count())
                ->description('Sudah diverifikasi & aktif')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Penjual Tidak Aktif', Seller::where('status_verifikasi', 'diterima')->where('is_active', false)->count())
                ->description('Perlu diaktifkan kembali')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
            
            Stat::make('Menunggu Verifikasi', Seller::where('status_verifikasi', 'menunggu')->count())
                ->description('Perlu ditinjau')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            
            Stat::make('Total Produk', Product::count())
                ->description('Produk terdaftar')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info')
                ->chart([50, 100, 150, 200, 250, 300, 350]),
            
            Stat::make('Produk Aktif', Product::where('is_active', true)->count())
                ->description('Ditampilkan di frontend')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success'),
            
            Stat::make('Total Review', ProductReview::where('is_approved', true)->count())
                ->description('Review disetujui')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning')
                ->chart([10, 25, 40, 55, 70, 85, 100]),

            Stat::make('Pengunjung Memberi Review', ProductReview::where('is_approved', true)->distinct('email_pengunjung')->count('email_pengunjung'))
                ->description('Jumlah pengunjung unik')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
