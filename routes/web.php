<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Homepage redirect ke katalog produk
Route::get('/', function () {
    return redirect()->route('products.index');
});

// Routes Frontend Publik (tanpa autentikasi)
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
    Route::post('/{slug}/review', [ProductController::class, 'storeReview'])->name('review.store');
});
