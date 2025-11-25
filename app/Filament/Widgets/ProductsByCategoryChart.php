<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class ProductsByCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Produk per Kategori';
    
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Category::withCount('products')
            ->get()
            ->sortByDesc('products_count')
            ->take(10);

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Produk',
                    'data' => $data->pluck('products_count')->toArray(),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)',
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $data->pluck('nama_kategori')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
