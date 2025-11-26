<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lokasi_marker', function (Blueprint $table) {
            $table->id();
            
            // Kunci utama untuk menghubungkan ke data survei
            $table->foreignId('id_data_survei')->constrained('data_survei')->onDelete('cascade');
            
            // Koordinat Marker
            $table->decimal('pusat_lintang', 10, 6);
            $table->decimal('pusat_bujur', 10, 6);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi_marker');
    }
};