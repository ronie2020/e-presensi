<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    /**
     * Menampilkan daftar tahun ajaran.
     */
    public function index()
    {
        $years = AcademicYear::orderBy('name', 'desc')->orderBy('semester', 'asc')->get();
        return view('settings.academic-year', compact('years'));
    }

    /**
     * Menyimpan tahun ajaran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string', // Contoh: "2025/2026"
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        $exists = AcademicYear::where('name', $request->name)
                              ->where('semester', $request->semester)
                              ->exists();

        if ($exists) {
            return back()->with('error', 'Tahun ajaran tersebut sudah ada.');
        }

        AcademicYear::create([
            'name' => $request->name,
            'semester' => $request->semester,
            'is_active' => false
        ]);

        return back()->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    /**
     * Mengaktifkan tahun ajaran tertentu.
     */
    public function activate($id)
    {       
        AcademicYear::query()->update(['is_active' => false]);
       
        $year = AcademicYear::findOrFail($id);
        $year->update(['is_active' => true]);

        return back()->with('success', "Tahun Ajaran {$year->name} Semester {$year->semester} telah diaktifkan.");
    }

    /**
     * Hapus tahun ajaran.
     */
    public function destroy($id)
    {
        $year = AcademicYear::findOrFail($id);
        
        if ($year->is_active) {
            return back()->with('error', 'Tidak bisa menghapus tahun ajaran yang sedang aktif.');
        }

        $year->delete();
        return back()->with('success', 'Tahun ajaran dihapus.');
    }
}