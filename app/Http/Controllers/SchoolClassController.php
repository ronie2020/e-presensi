<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\User; // 1. TAMBAHKAN IMPORT USER
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolClassController extends Controller
{
    /**
     * Menampilkan halaman daftar kelas.
     */
    public function index()
    {
        // 2. UBAH BAGIAN INI
        $classes = SchoolClass::with('homeroomTeacher') // Ambil juga relasi Wali Kelas
            ->orderBy('name', 'asc')
            ->get();
        
        // 3. Ambil semua user yang memiliki peran 'Wali Kelas'
        $teachers = User::where('role', 'Wali Kelas')->orderBy('name', 'asc')->get();

        return view('classes.index', [
            'classes' => $classes,
            'teachers' => $teachers // 4. Kirim data guru ke view
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('classes.index');
    }

    /**
     * Menyimpan data kelas baru.
     */
    public function store(Request $request)
    {
        // 5. UBAH BAGIAN INI
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('classes', 'name') // Pastikan nama kelas unik
            ],
            'homeroom_teacher_id' => 'nullable|integer|exists:users,id' // Validasi Wali Kelas
        ]);

        SchoolClass::create([
            'name' => $request->name,
            'homeroom_teacher_id' => $request->homeroom_teacher_id // Simpan ID Wali Kelas
        ]);

        return redirect()->route('classes.index')->with('success', 'Kelas baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolClass $schoolClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolClass $class) // Laravel akan otomatis mencari kelas berdasarkan ID
    {
        // Kita juga butuh daftar guru untuk dropdown, sama seperti di 'index'
        $teachers = User::where('role', 'Wali Kelas')->orderBy('name', 'asc')->get();

        return view('classes.edit', [
            'class' => $class, // Kirim data kelas yang mau diedit
            'teachers' => $teachers // Kirim data guru untuk dropdown
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, SchoolClass $class)
    {
        // Validasi data (mirip 'store', tapi 'unique' harus mengabaikan ID kelas ini sendiri)
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('classes', 'name')->ignore($class->id) // Abaikan ID saat ini
            ],
            'homeroom_teacher_id' => 'nullable|integer|exists:users,id'
        ]);

        // Update data di database
        $class->update([
            'name' => $request->name,
            'homeroom_teacher_id' => $request->homeroom_teacher_id
        ]);

        // Redirect kembali ke halaman index
        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Menghapus data kelas.
     */
    public function destroy(SchoolClass $schoolClass)
    {
        try {
            $schoolClass->delete();
            return redirect()->route('classes.index')->with('success', 'Kelas berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap error jika ada foreign key constraint (misal: masih ada siswa di kelas tsb)
            return redirect()->route('classes.index')->with('error', 'Gagal menghapus kelas. Pastikan tidak ada siswa yang terdaftar di kelas ini.');
        }
    }
}