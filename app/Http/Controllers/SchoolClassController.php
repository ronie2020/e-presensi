<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolClassController extends Controller
{
    /**
     * Menampilkan halaman daftar kelas.
     */
    public function index()
    {
        $classes = SchoolClass::with('homeroomTeacher') 
            ->orderBy('name', 'asc')
            ->get();
        
        // PERBAIKAN SPATIE: Gunakan scope role() bawaan dari Spatie HasRoles
        // Ini akan otomatis mencari user di tabel model_has_roles milik Spatie
        $teachers = User::role('Wali Kelas')
                        ->orderBy('name', 'asc')
                        ->get();

        return view('classes.index', [
            'classes' => $classes,
            'teachers' => $teachers
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
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('classes', 'name') 
            ],
            'homeroom_teacher_id' => 'nullable|integer|exists:users,id'
        ]);

        SchoolClass::create([
            'name' => $request->name,
            'homeroom_teacher_id' => $request->homeroom_teacher_id
        ]);

        return redirect()->route('classes.index')->with('success', 'Kelas baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolClass $class)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $class = SchoolClass::findOrFail($id);
        
        // PERBAIKAN SPATIE: Gunakan scope role() di sini juga
        $teachers = User::role('Wali Kelas')
                        ->orderBy('name', 'asc')
                        ->get();

        return view('classes.edit', [
            'class' => $class,
            'teachers' => $teachers
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('classes', 'name')->ignore($class->id)
            ],
            'homeroom_teacher_id' => 'nullable|integer|exists:users,id'
        ]);

        $class->update([
            'name' => $request->name,
            'homeroom_teacher_id' => $request->homeroom_teacher_id
        ]);

        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Menghapus data kelas.
     */
    public function destroy($id)
    {
        try {
            $class = SchoolClass::findOrFail($id);
            $class->delete();
            
            return redirect()->route('classes.index')->with('success', 'Kelas berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('classes.index')->with('error', 'Gagal menghapus kelas. Pastikan tidak ada siswa di kelas ini.');
        } catch (\Exception $e) {
            return redirect()->route('classes.index')->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}