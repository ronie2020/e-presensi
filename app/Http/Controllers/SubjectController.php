<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Menampilkan daftar mata pelajaran.
     */
    public function index()
    {
        // Urutkan berdasarkan 'urutan cetak' (order) agar rapi di rapor
        $subjects = Subject::orderBy('order', 'asc')->get();
        return view('settings.subjects', compact('subjects'));
    }

    /**
     * Menyimpan mapel baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10',
            'group' => 'required|in:A,B,C,P5', // Sesuaikan dengan kelompok rapor (A=Umum, B=Mulok, P5=Projek)
            'order' => 'required|integer|min:1', // Urutan di rapor
        ]);

        Subject::create($request->all());

        return back()->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    /**
     * Update mapel.
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10',
            'group' => 'required|in:A,B,C,P5',
            'order' => 'required|integer|min:1',
        ]);

        $subject->update($request->all());

        return back()->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    /**
     * Hapus mapel.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return back()->with('success', 'Mata Pelajaran dihapus.');
    }
}