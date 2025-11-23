<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendGeneralWaJob;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('author')->latest()->get();
        $classes = SchoolClass::orderBy('name')->get();
        
        // Template Pesan Cepat
        $templates = [
            "Pemberitahuan Libur" => "Yth. Bapak/Ibu Wali Murid, diberitahukan bahwa besok sekolah diliburkan karena...",
            "Undangan Rapat" => "Yth. Bapak/Ibu Wali Murid, kami mengundang Anda untuk hadir pada rapat wali murid tanggal...",
            "Pengingat Ujian" => "Yth. Bapak/Ibu, minggu depan akan dilaksanakan Ujian Tengah Semester. Mohon bimbingan belajar di rumah.",
            "Tunggakan Administrasi" => "Yth. Bapak/Ibu, kami mengingatkan untuk segera melunasi administrasi sekolah sebelum tanggal...",
        ];

        return view('announcements.index', compact('announcements', 'classes', 'templates'));
    }

    // Simpan Pengumuman Website
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Pengumuman berhasil dipublikasikan.');
    }

    // Hapus Pengumuman
    public function destroy($id)
    {
        Announcement::findOrFail($id)->delete();
        return back()->with('success', 'Pengumuman dihapus.');
    }

    // Kirim Notifikasi WA
    public function sendNotification(Request $request)
    {
        $request->validate([
            'target_type' => 'required|in:class,all',
            'class_id' => 'nullable|required_if:target_type,class',
            'message' => 'required|string|min:10',
        ]);

        $students = collect();

        if ($request->target_type == 'all') {
            $students = Student::whereNotNull('parent_wa_number')->get();
        } elseif ($request->target_type == 'class') {
            // PERBAIKAN DISINI: Mengganti 'school_class_id' menjadi 'class_id'
            $students = Student::where('class_id', $request->class_id)
                               ->whereNotNull('parent_wa_number')
                               ->get();
        }

        if ($students->isEmpty()) {
            return back()->with('error', 'Tidak ada siswa dengan nomor WA yang ditemukan pada target yang dipilih.');
        }

        // Dispatch Job untuk setiap siswa (agar proses cepat & background)
        $count = 0;
        foreach ($students as $student) {
            // Personalisasi pesan sedikit (opsional)
            // $finalMsg = str_replace("{nama_siswa}", $student->name, $request->message); 
            
            SendGeneralWaJob::dispatch($student->parent_wa_number, $request->message);
            $count++;
        }

        return back()->with('success', "Notifikasi sedang dikirim ke {$count} orang tua (antrian).");
    }
}