<?php

namespace App\Filament\Widgets;

use App\Models\ProductReview;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ReviewSubmissionsChart extends ChartWidget
{
    protected static ?string $heading = 'Komentar & Rating per Bulan';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $startMonth = Carbon::now()->subMonths(5)->startOfMonth();

        $monthlyTotals = ProductReview::query()
            ->where('is_approved', true)
            ->where('created_at', '>=', $startMonth)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as total')
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym');

        $labels = [];
        $data = [];

        for ($i = 0; $i < 6; $i++) {
            $month = $startMonth->copy()->addMonths($i);
            $labels[] = $month->format('M Y');
            $data[] = $monthlyTotals[$month->format('Y-m')] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Review',
                    'data' => $data,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.35)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
