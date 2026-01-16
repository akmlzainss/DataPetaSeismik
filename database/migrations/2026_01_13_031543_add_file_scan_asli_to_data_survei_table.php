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
        Schema::table('data_survei', function (Blueprint $table) {
            // File scan asli (optional - bisa sangat besar, 500MB+)
            $table->string('file_scan_asli')->nullable()->after('gambar_medium');
            $table->unsignedBigInteger('ukuran_file_asli')->nullable()->after('file_scan_asli'); // dalam bytes
            $table->string('format_file_asli', 10)->nullable()->after('ukuran_file_asli'); // pdf, tiff, png, dll
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_survei', function (Blueprint $table) {
            $table->dropColumn(['file_scan_asli', 'ukuran_file_asli', 'format_file_asli']);
        });
    }
};
