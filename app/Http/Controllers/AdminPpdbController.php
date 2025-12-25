<?php

namespace App\Http\Controllers;

use App\Models\PpdbRegistrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPpdbController extends Controller
{
    /**
     * Menampilkan daftar pendaftar.
     */
    public function index(Request $request)
    {
        $query = PpdbRegistrant::query();

        // Filter berdasarkan Tahun Ajaran (Default tahun ini)
        $year = $request->input('year', date('Y'));
        $query->where('academic_year', $year);

        // Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Pencarian Nama/NISN
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        $registrants = $query->latest()->paginate(10);
        
        // Statistik Singkat
        $stats = [
            'total' => PpdbRegistrant::where('academic_year', $year)->count(),
            'pending' => PpdbRegistrant::where('academic_year', $year)->where('status', 'pending')->count(),
            'verified' => PpdbRegistrant::where('academic_year', $year)->where('status', 'verified')->count(),
            'accepted' => PpdbRegistrant::where('academic_year', $year)->where('status', 'accepted')->count(),
        ];

        return view('admin.ppdb.index', compact('registrants', 'stats', 'year'));
    }

    /**
     * Menampilkan detail pendaftar untuk verifikasi.
     */
    public function show($id)
    {
        $registrant = PpdbRegistrant::findOrFail($id);
        return view('admin.ppdb.show', compact('registrant'));
    }

    /**
     * Mengupdate status pendaftaran (Verifikasi/Terima/Tolak).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,accepted,rejected',
            'admin_note' => 'nullable|string'
        ]);

        $registrant = PpdbRegistrant::findOrFail($id);
        $registrant->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note
        ]);

        return redirect()->back()->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    /**
     * Menghapus data pendaftar (Hati-hati).
     */
    public function destroy($id)
    {
        $registrant = PpdbRegistrant::findOrFail($id);
        
        // Hapus file-file terkait
        if ($registrant->file_photo) Storage::disk('public')->delete($registrant->file_photo);
        if ($registrant->file_kk) Storage::disk('public')->delete($registrant->file_kk);
        if ($registrant->file_akta) Storage::disk('public')->delete($registrant->file_akta);
        if ($registrant->file_grades) Storage::disk('public')->delete($registrant->file_grades);
        
        $registrant->delete();

        return redirect()->route('admin.ppdb.index')->with('success', 'Data pendaftar berhasil dihapus.');
    }

    /**
     * Cetak Bukti Pendaftaran (Versi Admin).
     */
    public function print($id)
    {
        $registrant = PpdbRegistrant::findOrFail($id);
        return view('ppdb.success', compact('registrant')); // Gunakan view yang sama dengan publik
    }
}