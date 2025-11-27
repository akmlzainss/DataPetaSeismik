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
            $table->string('ketua_tim', 100)->after('judul');
        });
    }

    public function down(): void
    {
        Schema::table('data_survei', function (Blueprint $table) {
            $table->dropColumn('ketua_tim');
        });
    }
};
