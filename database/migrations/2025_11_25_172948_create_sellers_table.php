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
     * Tabel penjual/toko
     * Menyimpan informasi toko yang mendaftar di platform
     */
    public function up(): void
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            
            // Informasi Toko
            $table->string('nama_toko'); // Nama lengkap toko
            $table->string('singkatan_toko')->unique(); // Username/singkatan toko (unique)
            $table->text('deskripsi_toko')->nullable(); // Deskripsi singkat toko
            $table->string('logo_toko')->nullable(); // Path logo toko
            
            // Informasi PIC (Person In Charge)
            $table->string('nama_pic'); // Nama penanggung jawab
            $table->string('email_pic')->unique(); // Email PIC untuk login & notifikasi
            $table->string('no_hp_pic'); // Nomor HP PIC
            $table->string('password'); // Password untuk login seller
            
            // Lokasi Toko
            $table->string('provinsi'); // Provinsi toko
            $table->string('kota_kabupaten'); // Kota/Kabupaten toko
            $table->text('alamat_lengkap'); // Alamat detail toko
            $table->string('kode_pos')->nullable(); // Kode pos (opsional)
            
            // Status & Verifikasi
            $table->enum('status_verifikasi', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('alasan_penolakan')->nullable(); // Alasan jika ditolak
            $table->boolean('is_active')->default(false); // Aktif setelah verifikasi diterima
            $table->timestamp('verified_at')->nullable(); // Waktu verifikasi
            
            $table->rememberToken(); // Untuk remember me login
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
