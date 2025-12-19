<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RealGuruSeeder extends Seeder
{
    public function run(): void
    {
        // Data dari CSV "Data Isian.csv"
        $teachers = [
            [
                'name' => 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.',
                'nip' => '19820928 201101 1 002',
                'pangkat' => 'Penata, III/c',
                'position' => 'Kepala Sekolah'
            ],
            [
                'name' => 'AGUSTIYANTO SUBEKTI, S.Pd., M.Pd.',
                'nip' => '19670806 199003 1 008',
                'pangkat' => 'Pembina, IV/a',
                'position' => 'Guru'
            ],
            [
                'name' => 'MISMAN, S.Pd.',
                'nip' => '19660312 198902 1 002',
                'pangkat' => 'Pembina, IV/a',
                'position' => 'Guru'
            ],
            [
                'name' => 'RONI RODIANA, S.Pd.',
                'nip' => '19790619 200901 1 007',
                'pangkat' => 'Penata, III/c',
                'position' => 'Guru'
            ],
            [
                'name' => 'Drs. SLAMET ANANTA BOGHA',
                'nip' => '19671109 201408 1 001',
                'pangkat' => 'Penata, III/c',
                'position' => 'Guru'
            ],
            [
                'name' => 'DEDI SUHARDIMAN, S.T.',
                'nip' => '19700804 201408 1 002',
                'pangkat' => 'Penata, III/c',
                'position' => 'Guru'
            ],
            [
                'name' => 'AAN DARWATI, S.Pd.',
                'nip' => '19790615 201408 2 002',
                'pangkat' => 'Penata, III/c',
                'position' => 'Guru'
            ],
            [
                'name' => 'R. M. USMAN ALI, S.Pd.',
                'nip' => '19700920 202121 1 002',
                'pangkat' => 'Penata Muda, III/a',
                'position' => 'Guru'
            ],
            [
                'name' => 'SETIANTO ADI SUPARMAN, S.Pd.',
                'nip' => '19740205 202121 1 004',
                'pangkat' => 'Penata Muda, III/a',
                'position' => 'Guru'
            ],
            [
                'name' => 'ENIH HERYANI, S.Pd.',
                'nip' => '19680912 202221 2 001',
                'pangkat' => 'Penata Muda, III/a',
                'position' => 'Guru'
            ],
            [
                'name' => 'YENI ROHMAYANTI, S.Pd.',
                'nip' => '19801026 202221 2 004',
                'pangkat' => 'Penata Muda, III/a',
                'position' => 'Guru'
            ],
            [
                'name' => 'ATIK HARTIKA, S.Pd.',
                'nip' => '19831215 202221 2 018',
                'pangkat' => 'Penata Muda, III/a',
                'position' => 'Guru'
            ],
            // Tambahkan data guru lainnya sesuai CSV...
        ];

        foreach ($teachers as $data) {
            // Kita gunakan updateOrCreate agar tidak duplikat jika dijalankan berkali-kali
            User::updateOrCreate(
                ['nip' => $data['nip']], // Kunci pencarian berdasarkan NIP
                [
                    'name' => $data['name'],
                    'email' => $data['nip'] . '@smpn3lakbok.sch.id', // Generate email dummy dari NIP
                    'password' => Hash::make('guru123'), // Password default
                    'role' => 'Guru',
                    'position' => $data['position'],
                    'pangkat' => $data['pangkat'],
                ]
            );
        }
    }
}