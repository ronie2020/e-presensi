<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class MigrateRoleSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat Master Role
        $roles = [
            'Admin',                
            'Kepala Sekolah',       
            'TU',                   
            'Wali Kelas',           
            'Guru Mata Pelajaran',  
            'Guru Piket',           
            'Guru' 
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $this->command->info('Master Roles berhasil dibuat.');

        // 2. Migrasi Role dari kolom 'role' (JSON/String) ke Spatie
        $users = User::all();
        $migratedCount = 0;

        foreach ($users as $user) {
            if (!empty($user->role)) {
                // Parse role format lama (JSON atau String biasa)
                $oldRoles = is_string($user->role) ? json_decode($user->role, true) : $user->role;
                
                // Jika gagal decode (format string csv/biasa)
                if (!is_array($oldRoles)) {
                    $oldRoles = is_string($user->role) ? explode(',', $user->role) : [$user->role];
                }

                // Bersihkan array dan ambil role yang valid
                $validRoles = array_filter(array_map('trim', $oldRoles));

                if (!empty($validRoles)) {
                    // Berikan role menggunakan Spatie
                    // Spatie otomatis memvalidasi apakah role tersebut ada di database
                    $user->assignRole($validRoles);
                    $migratedCount++;
                }
            }
        }

        $this->command->info("Berhasil memigrasi role untuk {$migratedCount} pengguna.");
    }
}