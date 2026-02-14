<?php

namespace App\Http\Controllers;

use App\Models\DisciplineType;
use Illuminate\Http\Request;

class DisciplineTypeController extends Controller
{
    /**
     * Menampilkan halaman manajemen jenis disiplin.
     */
    public function index()
    {
        $violationTypes = DisciplineType::where('type', 'Pelanggaran')->orderBy('name')->get();
        $meritTypes = DisciplineType::where('type', 'Kebaikan')->orderBy('name')->get();

        return view('discipline.types_index', compact('violationTypes', 'meritTypes'));
    }

    /**
     * Menyimpan jenis baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Pelanggaran,Kebaikan',
            'point_value' => 'required|integer|min:1',
        ]);

        DisciplineType::create([
            'name' => $request->name,
            'type' => $request->type,
            'point_value' => $request->point_value,
        ]);

        return redirect()->back()->with('success', 'Jenis ' . $request->type . ' berhasil ditambahkan.');
    }

    /**
     * Menghapus jenis.
     */
    public function destroy($id)
    {
        $type = DisciplineType::findOrFail($id);
        
        // PROTEKSI SYSTEM DEFAULT
        // Mencegah user menghapus kategori "Alfa" yang dibutuhkan oleh ReportController (Absensi)
        $protectedKeywords = ['Alfa', 'Alpa', 'Alpha', 'Tidak Masuk', 'Tanpa Keterangan'];
        foreach ($protectedKeywords as $keyword) {
            if (stripos($type->name, $keyword) !== false) {
                return redirect()->back()->with('error', 'GAGAL: Jenis pelanggaran Sistem (Absensi/Alfa) tidak boleh dihapus manual!');
            }
        }

        // PENTING: Cek apakah tipe ini sudah pernah dipakai di catatan siswa
        // Ini mencegah data poin siswa rusak/hilang referensinya
        if ($type->records()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal hapus! Jenis ini sedang digunakan dalam catatan siswa.');
        }

        $type->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}