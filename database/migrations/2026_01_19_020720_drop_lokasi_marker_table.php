<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menghapus tabel lokasi_marker karena sistem sudah diganti dengan grid_kotak
     */
    public function up(): void
    {
        Schema::dropIfExists('lokasi_marker');
    }

    /**
     * Reverse the migrations.
     * Recreate tabel lokasi_marker jika rollback
     */
    public function down(): void
    {
        Schema::create('lokasi_marker', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_data_survei')->nullable()->constrained('data_survei')->onDelete('cascade');
            $table->decimal('pusat_lintang', 10, 7)->nullable();
            $table->decimal('pusat_bujur', 11, 7)->nullable();
            $table->timestamps();
        });
    }
};
