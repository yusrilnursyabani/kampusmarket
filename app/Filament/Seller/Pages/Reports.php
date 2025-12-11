<?php

namespace App\Filament\Seller\Pages;

use Filament\Pages\Page;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string $view = 'filament.seller.pages.reports';
    
    protected static ?string $navigationLabel = 'Laporan PDF';
    
    protected static ?string $title = 'Laporan PDF';
    
    protected static ?int $navigationSort = 10;
    
    public function getHeading(): string
    {
        return 'Laporan PDF Penjual';
    }
}
