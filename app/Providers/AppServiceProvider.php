<?php

namespace App\Providers;

use App\Models\Seller;
use App\Observers\SellerObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Seller Observer untuk email notifikasi verifikasi
        Seller::observe(SellerObserver::class);
    }
}
