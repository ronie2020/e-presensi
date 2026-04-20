<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookCategory;

class BookCategorySeeder extends Seeder
{
    public function run(): void
    {
        // 10 Kelas Utama DDC (Dewey Decimal Classification)
        $categories = [
            ['code' => '000', 'name' => 'Karya Umum, Komputer, dan Informasi'],
            ['code' => '100', 'name' => 'Filsafat dan Psikologi'],
            ['code' => '200', 'name' => 'Agama'],
            ['code' => '300', 'name' => 'Ilmu Sosial (Sosiologi, Pendidikan, Hukum)'],
            ['code' => '400', 'name' => 'Bahasa'],
            ['code' => '500', 'name' => 'Sains dan Ilmu Murni (Matematika, Fisika, dll)'],
            ['code' => '600', 'name' => 'Teknologi dan Ilmu Terapan (Kedokteran, Pertanian)'],
            ['code' => '700', 'name' => 'Kesenian, Hiburan, dan Olahraga'],
            ['code' => '800', 'name' => 'Sastra dan Kesusastraan'],
            ['code' => '900', 'name' => 'Sejarah dan Geografi'],
        ];

        foreach ($categories as $cat) {
            // Menggunakan updateOrCreate agar jika di-seed ulang tidak terjadi duplikasi (error)
            BookCategory::updateOrCreate(
                ['code' => $cat['code']], 
                ['name' => $cat['name']]
            );
        }
    }
}