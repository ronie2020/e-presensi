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
        $this->call([
            MigrateRoleSeeder::class, // Buat semua master role (termasuk Superadmin)
            UserSeeder::class,        // Buat User Admin & Guru Piket
            SubjectSeeder::class,     // Lalu buat Mapel
            SuperadminSeeder::class,  // Buat akun Superadmin
        ]);
    }
}
