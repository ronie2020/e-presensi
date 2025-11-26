<?php

    namespace Database\Seeders;

    use Illuminate\Database\Seeder;
    use App\Models\BookCategory;

    class BookCategorySeeder extends Seeder
    {
        public function run(): void
        {
            $categories = [
                ['name' => 'Buku Pelajaran', 'code' => 'BP'],
                ['name' => 'Fiksi / Novel', 'code' => 'FN'],
                ['name' => 'Ensiklopedia', 'code' => 'EN'],
                ['name' => 'Kamus', 'code' => 'KM'],
                ['name' => 'Majalah', 'code' => 'MJ'],
            ];

            foreach ($categories as $cat) {
                BookCategory::create($cat);
            }
        }
    }