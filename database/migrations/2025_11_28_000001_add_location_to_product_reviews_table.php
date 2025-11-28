<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->string('provinsi_pengunjung')->nullable()->after('no_hp_pengunjung');
            $table->string('kota_pengunjung')->nullable()->after('provinsi_pengunjung');
        });
    }

    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropColumn(['provinsi_pengunjung', 'kota_pengunjung']);
        });
    }
};
