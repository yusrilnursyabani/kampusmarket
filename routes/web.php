<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerRegistrationController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\SellerReportController;

// Homepage
Route::get('/', function () {
    return view('welcome');
});

// Routes Frontend Publik (tanpa autentikasi)
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
    Route::post('/{slug}/review', [ProductController::class, 'storeReview'])->name('review.store');
});

// Routes Registrasi Seller
Route::prefix('seller')->name('seller.')->group(function () {
    Route::get('/register', [SellerRegistrationController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [SellerRegistrationController::class, 'register'])->name('register.submit');
});

// Admin PDF Reports (perlu login admin/web) - SRS-09, 10, 11
Route::middleware(['auth'])->prefix('admin/reports')->name('admin.reports.')->group(function () {
    Route::get('/seller-accounts', [AdminReportController::class, 'sellerAccounts'])->name('sellers');
    Route::get('/stores-by-province', [AdminReportController::class, 'storesByProvince'])->name('stores');
    Route::get('/product-ratings', [AdminReportController::class, 'productRatings'])->name('products');
});

// Seller PDF Reports (perlu login seller) - SRS-12, 13, 14
Route::middleware(['auth:seller'])->prefix('seller/reports')->name('seller.reports.')->group(function () {
    Route::get('/stock', [SellerReportController::class, 'stockReport'])->name('stock');
    Route::get('/rating', [SellerReportController::class, 'ratingReport'])->name('rating');
    Route::get('/low-stock', [SellerReportController::class, 'lowStockReport'])->name('lowstock');
});
