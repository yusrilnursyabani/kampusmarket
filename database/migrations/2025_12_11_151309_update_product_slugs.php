<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all products that don't have slug
        $products = Product::whereNull('slug')->orWhere('slug', '')->get();
        
        foreach ($products as $product) {
            $product->slug = Str::slug($product->nama_produk) . '-' . Str::random(6);
            $product->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse - slugs can stay
    }
};
