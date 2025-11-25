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
     * Tabel review produk
     * Menyimpan komentar dan rating dari pengunjung (tanpa login)
     */
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke produk
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            // Informasi Pengunjung (tanpa akun)
            $table->string('nama_pengunjung'); // Nama pemberi review
            $table->string('email_pengunjung'); // Email pemberi review
            $table->string('no_hp_pengunjung'); // No HP pemberi review
            
            // Review Content
            $table->text('isi_komentar'); // Isi komentar/review
            $table->integer('rating'); // Rating 1-5
            
            // Status & Moderasi
            $table->boolean('is_approved')->default(true); // Bisa dimoderasi admin jika perlu
            $table->boolean('is_spam')->default(false); // Flag spam
            $table->ipAddress('ip_address')->nullable(); // IP address pemberi review
            
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['product_id', 'is_approved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
