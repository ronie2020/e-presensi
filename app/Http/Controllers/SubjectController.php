<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Menampilkan daftar semua mata pelajaran.
     * PERBAIKAN: Menggunakan get() bukan paginate() agar semua data tampil.
     */
    public function index()
    {
        // Mengambil semua data diurutkan berdasarkan 'order' (No. Urut)
        $subjects = Subject::orderBy('order', 'asc')->get();
        
        // Pastikan nama view sesuai dengan lokasi file blade Anda
        // Jika file ada di resources/views/subjects.blade.php gunakan 'subjects'
        // Jika file ada di resources/views/subjects/index.blade.php gunakan 'subjects.index'
        return view('settings.subjects', compact('subjects'));
    }

    /**
     * Menyimpan data mapel baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
            'order' => 'required|integer',
            'group' => 'required|in:A,B,C,P5', // Validasi sesuai opsi di form
        ]);

        Subject::create([
            'name' => $request->name,
            'code' => strtoupper($request->code), // Paksa huruf besar untuk kode
            'order' => $request->order,
            'group' => $request->group,
        ]);

        return redirect()->back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Update data (diperlukan karena route resource, meski belum ada UI edit).
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        $subject->update($request->all());

        return redirect()->back()->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Menghapus mapel.
     */
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->back()->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}