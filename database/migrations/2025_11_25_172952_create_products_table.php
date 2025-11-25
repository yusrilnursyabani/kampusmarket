<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Tabel produk
     * Menyimpan informasi produk yang dijual oleh seller
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke seller dan kategori
            $table->foreignId('seller_id')->constrained('sellers')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            
            // Informasi Produk (mengacu struktur Tokopedia sederhana)
            $table->string('nama_produk'); // Nama produk
            $table->string('slug')->unique(); // URL-friendly identifier
            $table->text('deskripsi'); // Deskripsi lengkap produk
            $table->decimal('harga', 15, 2); // Harga produk (support harga besar)
            $table->integer('stok')->default(0); // Jumlah stok tersedia
            $table->integer('berat')->nullable(); // Berat produk dalam gram (opsional)
            
            // Media Produk
            $table->string('gambar_utama')->nullable(); // Path gambar utama produk
            $table->json('galeri_gambar')->nullable(); // Array path gambar tambahan (JSON)
            
            // Status & Metadata
            $table->boolean('is_active')->default(true); // Produk aktif/tidak
            $table->integer('total_terjual')->default(0); // Total terjual (untuk statistik)
            $table->integer('views')->default(0); // Jumlah view produk
            
            $table->timestamps();
            
            // Index untuk performa pencarian
            $table->index(['seller_id', 'is_active']);
            $table->index(['category_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
