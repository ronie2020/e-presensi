<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BkCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Pribadi', 'color' => 'purple', 'description' => 'Masalah personal & emosi'],
            ['name' => 'Sosial', 'color' => 'blue', 'description' => 'Hubungan teman & bullying'],
            ['name' => 'Belajar', 'color' => 'yellow', 'description' => 'Kesulitan akademik'],
            ['name' => 'Karir', 'color' => 'green', 'description' => 'Jurusan & masa depan'],
            ['name' => 'Keluarga', 'color' => 'red', 'description' => 'Masalah rumah tangga'],
        ];

        foreach ($categories as $cat) {
            \App\Models\BkCategory::create($cat);
        }
    }
}