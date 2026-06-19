<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil ID siswa yang sedang diedit dari route (misal: /students/{student})
        $studentId = $this->route('student')->id ?? null;
        
        // Ambil status siswa dari database (karena tidak dikirim via form)
        $studentStatus = $this->route('student')->status ?? null;

        return [
            // TAB A: PRIBADI
            'student_id' => [
                'required', 
                Rule::unique('students', 'student_id')->ignore($studentId)->whereNull('deleted_at')
            ],
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'nis' => 'nullable|string|max:255',
            'nik' => 'nullable|string|max:255',
            'class_id' => $studentStatus === 'graduated' ? 'nullable|integer|exists:classes,id' : 'required|integer|exists:classes,id',
            'pob' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'religion' => 'nullable|string|max:255',
            'citizenship' => 'nullable|string|max:255',
            'birth_order' => 'nullable|integer',
            'orphan_status' => 'nullable|string|max:255',
            'siblings_count' => 'nullable|integer',
            'step_siblings_count' => 'nullable|integer',
            'adoptive_siblings_count' => 'nullable|integer',
            'daily_language' => 'nullable|string|max:255',
            'rfid_id' => [
                'nullable', 
                Rule::unique('students', 'rfid_id')->ignore($studentId)->whereNotNull('rfid_id')->whereNull('deleted_at')
            ],
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // TAB B: TEMPAT TINGGAL
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'living_with' => 'nullable|string|max:255',
            'distance_to_school' => 'nullable|string|max:255',
            'transport_mode' => 'nullable|string|max:255',

            // TAB C: KESEHATAN
            'blood_type' => 'nullable|in:A,B,AB,O',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'history_disease' => 'nullable|string|max:255',
            'physical_abnormalities' => 'nullable|string|max:255',

            // TAB D: PENDIDIKAN
            'school_origin' => 'nullable|string|max:255',
            'prev_diploma_no' => 'nullable|string|max:255',
            'prev_exam_date' => 'nullable|date',
            'accepted_date' => 'nullable|date',
            'transfer_from_school' => 'nullable|string|max:255',

            // TAB E: ORANG TUA
            'father_name' => 'nullable|string|max:255',
            'father_pob' => 'nullable|string|max:255',
            'father_birth_year' => 'nullable|date',
            'father_education' => 'nullable|string|max:255',
            'father_job' => 'nullable|string|max:255',
            'father_income' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_pob' => 'nullable|string|max:255',
            'mother_birth_year' => 'nullable|date',
            'mother_education' => 'nullable|string|max:255',
            'mother_job' => 'nullable|string|max:255',
            'mother_income' => 'nullable|string|max:255',
            'parent_wa_number' => 'nullable|string|max:20',
            'parent_phone' => 'nullable|string|max:20',

            // WALI
            'guardian_name' => 'nullable|string|max:255',
            'guardian_pob' => 'nullable|string|max:255',
            'guardian_dob' => 'nullable|date',
            'guardian_citizenship' => 'nullable|string|max:255',
            'guardian_relationship' => 'nullable|string|max:255',
            'guardian_job' => 'nullable|string|max:255',
            'guardian_income' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_address' => 'nullable|string',

            // TAB F: MUTASI/TAMAT/LAINNYA
            'graduated_date' => 'nullable|date',
            'graduated_diploma_no' => 'nullable|string|max:255',
            'continuing_to_school' => 'nullable|string|max:255',
            'continuing_school_address' => 'nullable|string|max:255',
            'leaving_date' => 'nullable|date',
            'leaving_class' => 'nullable|string|max:255',
            'leaving_to_school' => 'nullable|string|max:255',
            'leaving_reason' => 'nullable|string|max:255',
            'dropout_date' => 'nullable|date',
            'dropout_reason' => 'nullable|string|max:255',
            
            // LAIN-LAIN
            'achievements' => 'nullable|string',
            'scholarship_info' => 'nullable|string',
            'general_notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.unique' => 'NIS/NISN ini sudah terdaftar pada siswa aktif.',
            'rfid_id.unique' => 'Kartu RFID ini sudah digunakan oleh siswa lain.',
        ];
    }
}