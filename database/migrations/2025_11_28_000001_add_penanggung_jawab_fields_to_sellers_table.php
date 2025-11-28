<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->string('rt', 3)->nullable()->after('alamat_lengkap');
            $table->string('rw', 3)->nullable()->after('rt');
            $table->string('kelurahan', 50)->nullable()->after('rw');
            $table->string('kecamatan', 50)->nullable()->after('kelurahan');
            $table->string('nomor_ktp', 16)->nullable()->unique()->after('kecamatan');
            $table->string('foto_seller')->nullable()->after('nomor_ktp');
            $table->string('foto_ktp')->nullable()->after('foto_seller');
        });
    }

    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn([
                'rt',
                'rw',
                'kelurahan',
                'kecamatan',
                'nomor_ktp',
                'foto_seller',
                'foto_ktp',
            ]);
        });
    }
};
