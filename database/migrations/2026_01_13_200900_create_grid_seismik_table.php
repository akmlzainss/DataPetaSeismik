<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Pivot table untuk many-to-many relationship antara grid_kotak dan data_survei.
     * Satu kotak bisa punya banyak data seismik, satu data seismik bisa ada di banyak kotak.
     */
    public function up(): void
    {
        Schema::create('grid_seismik', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('grid_kotak_id')
                ->constrained('grid_kotak')
                ->onDelete('cascade');
                
            $table->foreignId('data_survei_id')
                ->constrained('data_survei')
                ->onDelete('cascade');
            
            // Metadata untuk assignment
            $table->foreignId('assigned_by')->nullable()
                ->constrained('admin')
                ->onDelete('set null'); // Admin yang assign data ke kotak
            
            $table->timestamp('assigned_at')->useCurrent();
            
            // Prevent duplicate assignments
            $table->unique(['grid_kotak_id', 'data_survei_id']);
            
            // Index untuk performa
            $table->index('grid_kotak_id');
            $table->index('data_survei_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grid_seismik');
    }
};
