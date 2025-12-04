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
        Schema::create('data_survei', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->year('tahun'); // Tipe data YEAR
            $table->string('tipe', 50); // 2D, 3D, HR
            $table->string('wilayah');
            $table->text('deskripsi')->nullable();
            
            // Kolom untuk tautan file
            $table->string('gambar_pratinjau')->nullable(); 
            $table->text('tautan_file')->nullable(); // Link file data asli
            
            // Relasi (Optional tapi direkomendasikan untuk audit)
            $table->foreignId('diunggah_oleh')->nullable()->constrained('admin')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_survei');
        // Pastikan untuk menghapus foreign key jika ada
    }
    
};