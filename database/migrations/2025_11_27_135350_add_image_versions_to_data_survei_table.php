<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('data_survei', function (Blueprint $table) {
            $table->string('gambar_thumbnail')->nullable()->after('gambar_pratinjau');
            $table->string('gambar_medium')->nullable()->after('gambar_thumbnail');
        });
    }

    public function down()
    {
        Schema::table('data_survei', function (Blueprint $table) {
            $table->dropColumn(['gambar_thumbnail', 'gambar_medium']);
        });
    }
};