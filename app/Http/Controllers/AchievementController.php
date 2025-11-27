<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Jobs\AddAchievementPointJob; // <--- JANGAN LUPA IMPORT INI

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data siswa untuk dropdown
        $students = Student::with('schoolClass')->orderBy('name')->get();

        // Ambil data prestasi dengan filter & sorting
        $achievements = Achievement::with('student')
            ->when($request->search, function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('name_manual', 'like', '%'.$request->search.'%');
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

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
        } else {
            $request->validate(['name_manual' => 'required|string']);
            $data['name_manual'] = $request->name_manual;
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
        // Hapus foto dari storage jika ada
        if ($achievement->photo_path && Storage::disk('public')->exists($achievement->photo_path)) {
            Storage::disk('public')->delete($achievement->photo_path);
        }
        
        // Opsional: Jika ingin menghapus poin kebaikannya juga saat prestasi dihapus,
        // Anda bisa menambah logika di sini. Tapi biasanya poin tetap dibiarkan sebagai history.
        
        $achievement->delete();
        return redirect()->route('achievements.index')->with('success', 'Data prestasi dihapus.');
    }
}