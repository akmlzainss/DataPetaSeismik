<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil AdminSeeder di sini
        $this->call(AdminSeeder::class);
        
        // Panggil DataSurveiSeeder untuk data survei dan lokasi marker
        $this->call(DataSurveiSeeder::class);

        // Seeder User bawaan (jika masih ingin dijalankan)
        // User::factory(10)->create(); 

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}