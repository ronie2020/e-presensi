<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Akun Admin / Guru
        User::create([
            'name' => 'Ronie Rodiana',
            'email' => 'ronie.rodiana@gmail.com', // Email sesuai screenshot Anda
            'password' => Hash::make('20211583'), // Password default
            'role' => 'Admin', // Sesuaikan dengan role di sistem Anda (Admin/Guru/Wali Kelas)
        ]);
        
        // Opsional: Buat akun dummy lain
        User::create([
            'name' => 'Guru Piket',
            'email' => 'piket@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'Guru Piket',
        ]);
    }
}