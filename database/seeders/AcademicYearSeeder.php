<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        // Non-aktifkan tahun lalu
        AcademicYear::create(['name' => '2023/2024', 'semester' => 'Ganjil', 'is_active' => false]);
        AcademicYear::create(['name' => '2023/2024', 'semester' => 'Genap', 'is_active' => false]);
        
        // Aktifkan tahun ini
        AcademicYear::create(['name' => '2024/2025', 'semester' => 'Ganjil', 'is_active' => true]);
        AcademicYear::create(['name' => '2024/2025', 'semester' => 'Genap', 'is_active' => false]);
    }
}