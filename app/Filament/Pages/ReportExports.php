<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ReportExports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static ?string $navigationLabel = 'Laporan PDF';

    protected static ?string $title = 'Ekspor Laporan PDF';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.report-exports';

    protected function getHeaderWidgets(): array
    {
        return [];
    }
}
