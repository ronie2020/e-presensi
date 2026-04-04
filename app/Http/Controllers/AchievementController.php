<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Jobs\AddAchievementPointJob; 
use Barryvdh\DomPDF\Facade\Pdf; 

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        // === SORTING SISWA ===
        $students = Student::with('schoolClass')
            ->get()
            ->sortBy(function ($student) {
                $className = $student->schoolClass->name ?? 'ZZZ'; 
                return $className . $student->name;
            });

        // Ambil data prestasi dengan filter & sorting
        $achievements = Achievement::with('student')
            ->when($request->search, function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('name_manual', 'like', '%'.$request->search.'%');
            })
            // MENGURUTKAN STATUS PENDING AGAR BERADA PALING ATAS
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected') ASC")
            ->orderBy('date', 'desc')
            ->paginate(10)
            ->withQueryString(); 

        return view('achievements.index', compact('students', 'achievements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:Siswa,Guru,Sekolah',
            'title' => 'required|string|max:255',
            'level' => 'required',
            'date' => 'required|date',
            'photo' => 'nullable|image|max:2048', 
            'video_link' => 'nullable|url',
        ]);

        $data = $request->except('photo');

        // Validasi nama berdasarkan tipe
        if ($request->type === 'Siswa') {
            $request->validate(['student_id' => 'required|exists:students,id']);
            $data['student_id'] = $request->student_id;
            $student = Student::find($request->student_id);
            $data['achiever_name'] = $student->name;
        } else {
            $request->validate(['name_manual' => 'required|string']);
            $data['name_manual'] = $request->name_manual;
            $data['achiever_name'] = $request->name_manual; 
        }

        // Upload Foto
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('achievements', 'public');
        }

        // Prestasi yang diinput langsung oleh admin otomatis valid
        $data['status'] = 'approved';

        // 1. Simpan Data Prestasi
        $achievement = Achievement::create($data);

        // 2. Jalankan Job Otomatisasi Poin (Hanya jika tipe Siswa)
        if ($achievement->type === 'Siswa') {
            AddAchievementPointJob::dispatch($achievement);
        }

        return redirect()->route('achievements.index')->with('success', 'Prestasi berhasil ditambahkan & Poin Kebaikan dicatat!');
    }

    // FUNGSI VERIFIKASI (TERIMA / TOLAK LAPORAN SISWA)
    public function verify(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);

        $achievement = Achievement::findOrFail($id);
        $achievement->update(['status' => $request->status]);

        // Kalau laporan disetujui (valid), barulah berikan poin prestasi ke siswa
        if ($request->status === 'approved' && $achievement->type === 'Siswa') {
            AddAchievementPointJob::dispatch($achievement);
        }

        $message = $request->status === 'approved' 
            ? 'Laporan Prestasi disetujui dan poin siswa telah ditambahkan.' 
            : 'Laporan Prestasi berhasil ditolak.';

        return redirect()->back()->with('success', $message);
    }

    public function destroy(Achievement $achievement)
    {
        if ($achievement->photo_path && Storage::disk('public')->exists($achievement->photo_path)) {
            Storage::disk('public')->delete($achievement->photo_path);
        }
        
        $achievement->delete();
        return redirect()->route('achievements.index')->with('success', 'Data prestasi dihapus.');
    }

    /**
     * Export data ke PDF (Menggantikan CSV).
     */
    public function export(Request $request)
    {
        // Gunakan logic query yang sama persis dengan index()
        $query = Achievement::with(['student', 'student.schoolClass']);

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('name_manual', 'like', '%'.$request->search.'%');
            });
        }

        // Hanya export yang statusnya sudah valid (approved)
        $achievements = $query->where('status', 'approved')->orderBy('date', 'desc')->get();
        
        // Generate PDF
        $pdf = Pdf::loadView('achievements.pdf_export', compact('achievements'));
        
        // Atur ukuran kertas (A4 Landscape agar muat tabel lebar)
        $pdf->setPaper('a4', 'landscape');

        // Stream: Membuka di browser (tab baru)        
        return $pdf->stream('Laporan_Prestasi_' . date('Y-m-d') . '.pdf');
    }
}