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

        // 1. Simpan Data Prestasi
        $achievement = Achievement::create($data);

        // 2. Jalankan Job Otomatisasi Poin (Hanya jika tipe Siswa)
        if ($achievement->type === 'Siswa') {
            AddAchievementPointJob::dispatch($achievement);
        }

        return redirect()->route('achievements.index')->with('success', 'Prestasi berhasil ditambahkan & Poin Kebaikan dicatat!');
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

        $achievements = $query->orderBy('date', 'desc')->get();
        
        // Generate PDF
        $pdf = Pdf::loadView('achievements.pdf_export', compact('achievements'));
        
        // Atur ukuran kertas (A4 Landscape agar muat tabel lebar)
        $pdf->setPaper('a4', 'landscape');

        // Stream: Membuka di browser (tab baru)        
        return $pdf->stream('Laporan_Prestasi_' . date('Y-m-d') . '.pdf');
    }
}