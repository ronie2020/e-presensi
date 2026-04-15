<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Jobs\AddAchievementPointJob; 
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
   public function index(Request $request)
    {        
        $students = Student::with('schoolClass')
            ->get()
            ->sortBy(function ($student) {
                $className = $student->schoolClass->name ?? 'ZZZ'; 
                return $className . $student->name;
            });

        // Ambil data prestasi dengan filter & sorting
        $achievements = Achievement::with(['student', 'student.schoolClass'])
            ->when($request->search, function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('name_manual', 'like', '%'.$request->search.'%');
            })
            // Mengurutkan status pending agar berada di atas
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
            'certificate' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048', 
        ]);

        $data = $request->except(['photo', 'certificate']); // <-- Exclude certificate agar dihandle manual

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
            $data['photo_path'] = $request->file('photo')->store('achievements/photos', 'public');
        }

        // === PERBAIKAN: Upload Sertifikat ===
        if ($request->hasFile('certificate')) {
            $data['certificate_path'] = $request->file('certificate')->store('achievements/certificates', 'public');
        }

        // Prestasi yang diinput langsung oleh admin otomatis valid
        $data['status'] = 'approved';

        // 1. Simpan Data Prestasi
        $achievement = Achievement::create($data);

        // 2. Jalankan Job Otomatisasi Poin (Hanya jika tipe Siswa)
        if ($achievement->type === 'Siswa') {            
            AddAchievementPointJob::dispatch($achievement, Auth::id());
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
            AddAchievementPointJob::dispatch($achievement, Auth::id());
        }

        $message = $request->status === 'approved' 
            ? 'Laporan Prestasi disetujui dan poin siswa telah ditambahkan.' 
            : 'Laporan Prestasi berhasil ditolak.';

        return redirect()->back()->with('success', $message);
    }

   public function edit(Achievement $achievement)
    {
        // === PERBAIKAN: Hapus ->select(...) ===
        $students = Student::with('schoolClass')
            ->get()
            ->sortBy(function ($student) {
                $className = $student->schoolClass->name ?? 'ZZZ'; 
                return $className . $student->name;
            });

        return view('achievements.edit', compact('achievement', 'students'));
    }

    public function update(Request $request, Achievement $achievement)
    {
        $request->validate([
            'type' => 'required|in:Siswa,Guru,Sekolah',
            'title' => 'required|string|max:255',
            'level' => 'required',
            'date' => 'required|date',
            'photo' => 'nullable|image|max:2048', 
            'video_link' => 'nullable|url',
            'certificate' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['photo', 'certificate']);

        // Validasi nama berdasarkan tipe
        if ($request->type === 'Siswa') {
            $request->validate(['student_id' => 'required|exists:students,id']);
            $data['student_id'] = $request->student_id;
            $student = Student::find($request->student_id);
            $data['achiever_name'] = $student->name;
            $data['name_manual'] = null; // Kosongkan manual name jika tipe Siswa
        } else {
            $request->validate(['name_manual' => 'required|string']);
            $data['name_manual'] = $request->name_manual;
            $data['achiever_name'] = $request->name_manual; 
            $data['student_id'] = null; // Kosongkan student ID jika bukan Siswa
        }

        // Update Foto jika ada yang diupload
        if ($request->hasFile('photo')) {
            // Hapus foto lama
            if ($achievement->photo_path && Storage::disk('public')->exists($achievement->photo_path)) {
                Storage::disk('public')->delete($achievement->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('achievements/photos', 'public');
        }

        // Update Sertifikat jika ada yang diupload
        if ($request->hasFile('certificate')) {
            // Hapus sertifikat lama
            if ($achievement->certificate_path && Storage::disk('public')->exists($achievement->certificate_path)) {
                Storage::disk('public')->delete($achievement->certificate_path);
            }
            $data['certificate_path'] = $request->file('certificate')->store('achievements/certificates', 'public');
        }

        $achievement->update($data);

        return redirect()->route('achievements.index')->with('success', 'Data prestasi berhasil diperbarui!');
    }

    public function destroy(Achievement $achievement)
    {
        if ($achievement->photo_path && Storage::disk('public')->exists($achievement->photo_path)) {
            Storage::disk('public')->delete($achievement->photo_path);
        }
             
        if ($achievement->certificate_path && Storage::disk('public')->exists($achievement->certificate_path)) {
            Storage::disk('public')->delete($achievement->certificate_path);
        }
        
        $achievement->delete();
        return redirect()->route('achievements.index')->with('success', 'Data prestasi dihapus.');
    }

    public function export(Request $request)
    {
        $query = Achievement::with(['student', 'student.schoolClass']);

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('name_manual', 'like', '%'.$request->search.'%');
            });
        }

        $achievements = $query->where('status', 'approved')->orderBy('date', 'desc')->get();
        
        $pdf = Pdf::loadView('achievements.pdf_export', compact('achievements'));
        $pdf->setPaper('a4', 'landscape');
      
        return $pdf->stream('Laporan_Prestasi_' . date('Y-m-d') . '.pdf');
    }
}