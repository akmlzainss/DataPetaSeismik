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
        Schema::create('pegawai_internal', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique(); // Harus @esdm.go.id
            $table->string('kata_sandi');
            $table->string('nip', 50)->nullable(); // Nomor Induk Pegawai
            $table->string('jabatan', 100)->nullable();
            
            // Email verification
            $table->timestamp('email_verified_at')->nullable();
            $table->string('verification_token', 100)->nullable();
            $table->timestamp('verification_token_expires_at')->nullable();
            
            // Manual approval by admin (backup jika email tidak masuk)
            $table->boolean('is_approved')->default(false);
            $table->unsignedBigInteger('approved_by')->nullable(); // Admin ID yang approve
            $table->timestamp('approved_at')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
            
            // Index for faster queries
            $table->index('email');
            $table->index('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai_internal');
    }
};
