<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SuperadminSeeder extends Seeder
{
    /**
     * Membuat akun Superadmin.
     * Superadmin adalah role tertinggi yang dapat mengatur semua pengguna,
     * termasuk Admin, dan memiliki akses ke seluruh fitur sistem.
     *
     * PERHATIAN: Ganti password default setelah pertama kali login!
     */
    public function run(): void
    {
        // Reset cached roles agar Spatie membaca ulang dari database
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Pastikan role Superadmin sudah ada
        Role::firstOrCreate(['name' => 'Superadmin']);

        // Cek apakah Superadmin sudah ada (berdasarkan email)
        $superadmin = User::where('email', 'superadmin@smpn3lakbok.sch.id')->first();

        if (!$superadmin) {
            $superadmin = User::create([
                'name'     => 'Super Administrator',
                'email'    => 'superadmin@smpn3lakbok.sch.id',
                'password' => Hash::make('Sup3r@dmin#2025!'),
                'role'     => 'Superadmin',
                'position' => 'Super Administrator',
                'nip'      => '000000000000000001',
            ]);

            $this->command->info('Akun Superadmin berhasil dibuat.');
            $this->command->info('   Email   : superadmin@smpn3lakbok.sch.id');
            $this->command->info('   Password: Sup3r@dmin#2025!');
            $this->command->warn('   SEGERA GANTI PASSWORD setelah login pertama kali!');
        } else {
            // Update password dan pastikan role Superadmin aktif
            $superadmin->update([
                'name'     => 'Super Administrator',
                'password' => Hash::make('Sup3r@dmin#2025!'),
                'role'     => 'Superadmin',
            ]);
            $this->command->info('Akun Superadmin sudah ada, password & role berhasil diperbarui/direset.');
        }

        // Pastikan role Superadmin ter-assign ke akun ini
        if (!$superadmin->hasRole('Superadmin')) {
            $superadmin->assignRole('Superadmin');
            $this->command->info('   Role Superadmin berhasil di-assign.');
        }
    }
}
