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
        // Ambil data dan pisahkan agar mudah ditampilkan di 2 tabel berbeda
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
            // Baris validasi 'description' dihapus karena kolom tidak ada di database hosting
        ]);

        DisciplineType::create([
            'name' => $request->name,
            'type' => $request->type,
            'point_value' => $request->point_value,
            // Baris 'description' dihapus untuk mencegah error "Column not found: 1054"
        ]);

        return redirect()->back()->with('success', 'Jenis ' . $request->type . ' berhasil ditambahkan.');
    }

    /**
     * Menghapus jenis.
     */
    public function destroy($id)
    {
        $type = DisciplineType::findOrFail($id);
        
        // PENTING: Cek apakah tipe ini sudah pernah dipakai di catatan siswa
        // Ini mencegah data poin siswa rusak/hilang referensinya
        if ($type->records()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal hapus! Jenis ini sedang digunakan dalam catatan siswa.');
        }

        $type->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}