<?php

namespace App\Filament\Widgets;

use App\Models\Seller;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SellersByProvinceChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Toko per Provinsi';
    
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = Seller::select('provinsi', DB::raw('count(*) as total'))
            ->groupBy('provinsi')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Toko',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)',
                    ],
                ],
            ],
            'labels' => $data->pluck('provinsi')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
