<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['name' => 'Pendidikan Agama dan Budi Pekerti', 'group' => 'A', 'order' => 1],
            ['name' => 'Pendidikan Pancasila', 'group' => 'A', 'order' => 2],
            ['name' => 'Bahasa Indonesia', 'group' => 'A', 'order' => 3],
            ['name' => 'Matematika', 'group' => 'A', 'order' => 4],
            ['name' => 'Ilmu Pengetahuan Alam (IPA)', 'group' => 'A', 'order' => 5],
            ['name' => 'Ilmu Pengetahuan Sosial (IPS)', 'group' => 'A', 'order' => 6],
            ['name' => 'Bahasa Inggris', 'group' => 'A', 'order' => 7],
            ['name' => 'PJOK', 'group' => 'A', 'order' => 8],
            ['name' => 'Informatika', 'group' => 'A', 'order' => 9],
            ['name' => 'Seni dan Prakarya', 'group' => 'A', 'order' => 10],
            ['name' => 'Muatan Lokal (Bahasa Sunda)', 'group' => 'B', 'order' => 11],
            ['name' => 'Projek Penguatan Profil Pelajar Pancasila (P5)', 'group' => 'P5', 'order' => 12],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}