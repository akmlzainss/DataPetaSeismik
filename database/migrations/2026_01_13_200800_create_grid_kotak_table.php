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
        Schema::create('grid_kotak', function (Blueprint $table) {
            $table->id();
            
            // Nomor kotak (4 digit, contoh: 0621, 1219, 1418)
            $table->string('nomor_kotak', 10)->unique();
            
            // Bounding box coordinates (Southwest & Northeast corners)
            $table->decimal('bounds_sw_lat', 10, 6); // Southwest latitude
            $table->decimal('bounds_sw_lng', 10, 6); // Southwest longitude
            $table->decimal('bounds_ne_lat', 10, 6); // Northeast latitude
            $table->decimal('bounds_ne_lng', 10, 6); // Northeast longitude
            
            // Center point untuk quick reference
            $table->decimal('center_lat', 10, 6);
            $table->decimal('center_lng', 10, 6);
            
            // GeoJSON polygon data (optional, untuk shape yang lebih kompleks)
            $table->text('geojson_polygon')->nullable();
            
            // Status
            $table->enum('status', ['empty', 'filled'])->default('empty');
            
            // Metadata
            $table->integer('total_data')->default(0); // Counter untuk jumlah data survei di kotak ini
            
            $table->timestamps();
            
            // Index untuk performa query
            $table->index('nomor_kotak');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grid_kotak');
    }
};
