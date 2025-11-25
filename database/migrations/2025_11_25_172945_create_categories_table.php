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
     * Tabel kategori produk
     * Menyimpan daftar kategori untuk klasifikasi produk
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori'); // Nama kategori (misal: Elektronik, Fashion, Makanan)
            $table->string('slug')->unique(); // URL-friendly identifier
            $table->text('deskripsi')->nullable(); // Deskripsi kategori
            $table->string('icon')->nullable(); // Icon kategori (opsional)
            $table->boolean('is_active')->default(true); // Status aktif kategori
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
