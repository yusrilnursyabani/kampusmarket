<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string $view = 'filament.admin.pages.reports';
    
    protected static ?string $navigationLabel = 'Laporan PDF';
    
    protected static ?string $title = 'Laporan PDF';
    
    protected static ?int $navigationSort = 20;
    
    public function getHeading(): string
    {
        return 'Laporan PDF Platform';
    }
}
