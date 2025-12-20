<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('admin')->insertOrIgnore([
            'nama'           => 'Admin Utama',
            'email'          => 'amay@example.com',
            'kata_sandi'     => Hash::make('12345678'),
            'remember_token' => null,
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);

        // Admin kedua untuk TestSprite password reset test
        DB::table('admin')->insertOrIgnore([
            'nama'           => 'Admin BBSPGL',
            'email'          => 'admin@bbspgl.esdm.go.id',
            'kata_sandi'     => Hash::make('12345678'),
            'remember_token' => null,
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);

        $this->command->info('Admin berhasil dibuat: amay@example.com | password: 12345678');
        $this->command->info('Admin berhasil dibuat: admin@bbspgl.esdm.go.id | password: 12345678');
    }
}