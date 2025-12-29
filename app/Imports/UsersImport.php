<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Password default jika di excel kosong: 'password123'
        $password = isset($row['password']) && $row['password'] != '' 
                    ? $row['password'] 
                    : 'password123';

        return new User([
            'name'     => $row['nama_lengkap'], // Sesuaikan dengan header di Excel
            'email'    => $row['email'],
            'password' => Hash::make($password),
            'role'     => $row['role'] ?? 'Guru', // Default Role Guru
            'position' => $row['jabatan'] ?? null,
            'nip'      => $row['nip'] ?? null,
            'phone'    => $row['no_hp'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required',
            'email'        => 'required|email|unique:users,email',
            'role'         => 'nullable|in:Admin,Kepala Sekolah,Wali Kelas,Guru Piket,Guru',
        ];
    }
    
    // Pesan error custom jika validasi excel gagal
    public function customValidationMessages()
    {
        return [
            'email.unique' => 'Email sudah terdaftar di sistem.',
            'role.in'      => 'Role tidak valid (Harus: Admin, Guru, dll).',
        ];
    }
}