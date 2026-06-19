<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan melakukan request ini.
     */
    public function authorize(): bool
    {
        // Sesuaikan dengan logic otorisasi kamu, true berarti diizinkan
        return true; 
    }

    /**
     * Aturan validasi untuk registrasi cepat siswa.
     */
    public function rules(): array
    {
        return [
            'student_id' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('students', 'student_id')->whereNull('deleted_at')
            ],
            'name' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'rfid_id' => [
                'nullable', 
                'string', 
                'max:255', 
                Rule::unique('students', 'rfid_id')->whereNotNull('rfid_id')->whereNull('deleted_at')
            ],
            'parent_wa_number' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'gender' => 'required|in:L,P',
        ];
    }

    /**
     * Pesan error kustom (Opsional).
     */
    public function messages(): array
    {
        return [
            'student_id.unique' => 'NIS/NISN ini sudah terdaftar pada siswa aktif (belum dihapus).',
            'rfid_id.unique' => 'Kartu RFID ini sudah digunakan oleh siswa lain.',
            'photo.max' => 'Ukuran foto terlalu besar, maksimal 2MB.',
        ];
    }
}