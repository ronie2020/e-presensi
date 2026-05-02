<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role; // Tambahkan import Role dari Spatie

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan Role-nya terdaftar di tabel Spatie terlebih dahulu
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Guru Piket']);

        // 2. Buat Akun Admin
        $admin = User::create([
            'name' => 'Ronie Rodiana',
            'email' => 'ronie.rodiana@gmail.com',
            'password' => Hash::make('20211583'),
            'role' => 'Admin', // Biarkan kolom ini jika aplikasimu juga membutuhkannya
        ]);
        
        // 3. Tautkan akun tersebut ke Role Spatie "Admin"
        $admin->assignRole('Admin');

        // Opsional: Buat akun dummy lain
        $guruPiket = User::create([
            'name' => 'Guru Piket',
            'email' => 'piket@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'Guru Piket',
        ]);
        
        // Tautkan ke Role Spatie "Guru Piket"
        $guruPiket->assignRole('Guru Piket');
    }
}