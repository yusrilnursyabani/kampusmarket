<?php

namespace App\Filament\Seller\Widgets;

use App\Models\ProductReview;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class ReviewerProvinceChart extends ChartWidget
{
    protected static ?string $heading = 'Sebaran Reviewer per Provinsi';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $sellerId = Auth::guard('seller')->id();

        $provinceTotals = ProductReview::query()
            ->select('provinsi_pengunjung')
            ->selectRaw('COUNT(*) as total')
            ->where('is_approved', true)
            ->whereNotNull('provinsi_pengunjung')
            ->where('provinsi_pengunjung', '!=', '')
            ->whereHas('product', fn ($query) => $query->where('seller_id', $sellerId))
            ->groupBy('provinsi_pengunjung')
            ->orderByDesc('total')
            ->get();

        $labels = $provinceTotals->pluck('provinsi_pengunjung')->toArray();
        $data = $provinceTotals->pluck('total')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Reviewer',
                    'data' => $data,
                    'backgroundColor' => $this->generateColors(count($labels)),
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    private function generateColors(int $count): array
    {
        $baseColors = [
            'rgba(251, 191, 36, 0.9)', // amber
            'rgba(248, 113, 113, 0.9)', // red
            'rgba(52, 211, 153, 0.9)',  // green
            'rgba(96, 165, 250, 0.9)',  // blue
            'rgba(167, 139, 250, 0.9)', // violet
            'rgba(248, 180, 0, 0.9)',   // yellow
        ];

        if ($count <= count($baseColors)) {
            return array_slice($baseColors, 0, $count);
        }

        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colors[] = $baseColors[$i % count($baseColors)];
        }

        return $colors;
    }
}
