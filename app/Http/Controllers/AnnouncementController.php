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
        
        // PERBAIKAN DISINI: Mengganti 'school_classes' menjadi 'classes'
        // Jika masih error, cek database Anda, apakah nama tabelnya 'classes', 'tb_kelas', atau lainnya.
        // Sesuaikan parameter pertama join('NAMA_TABEL', ...)
        
        $students = Student::join('classes', 'students.class_id', '=', 'classes.id')
            ->orderBy('classes.name', 'asc') // Urutkan berdasarkan nama kelas
            ->orderBy('students.name', 'asc') // Lalu urutkan berdasarkan nama siswa
            ->select('students.*')            // Ambil data siswa saja agar ID tidak tertimpa
            ->with('schoolClass')
            ->get();
        
        // Template Pesan Cepat
        $templates = [
            "Pemberitahuan Libur" => "Yth. Bapak/Ibu Wali Murid, diberitahukan bahwa besok sekolah diliburkan karena...",
            "Undangan Rapat" => "Yth. Bapak/Ibu Wali Murid, kami mengundang Anda untuk hadir pada rapat wali murid tanggal...",
            "Pengingat Ujian" => "Yth. Bapak/Ibu, minggu depan akan dilaksanakan Ujian Tengah Semester. Mohon bimbingan belajar di rumah.",
            "Tunggakan Administrasi" => "Yth. Bapak/Ibu, kami mengingatkan untuk segera melunasi administrasi sekolah sebelum tanggal...",
            "Panggilan Orang Tua (BK)" => "Yth. Bapak/Ibu, kami mengundang Bapak/Ibu untuk hadir ke sekolah besok terkait perkembangan ananda di sekolah. Mohon kehadirannya.",
        ];

        return view('announcements.index', compact('announcements', 'classes', 'students', 'templates'));
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
            'target_type' => 'required|in:class,all,student',
            'class_id' => 'nullable|required_if:target_type,class',
            'student_id' => 'nullable|required_if:target_type,student',
            'message' => 'required|string|min:10',
        ]);

        $students = collect();

        if ($request->target_type == 'all') {
            // Semua Siswa
            $students = Student::whereNotNull('parent_wa_number')->get();
            
        } elseif ($request->target_type == 'class') {
            // Per Kelas
            $students = Student::where('class_id', $request->class_id)
                               ->whereNotNull('parent_wa_number')
                               ->get();
                               
        } elseif ($request->target_type == 'student') {
            // Personal (Satu Siswa)
            $singleStudent = Student::where('id', $request->student_id)
                                    ->whereNotNull('parent_wa_number')
                                    ->first();
            
            if ($singleStudent) {
                $students->push($singleStudent);
            }
        }

        if ($students->isEmpty()) {
            $msg = $request->target_type == 'student' 
                ? 'Nomor WA orang tua siswa ini tidak ditemukan di database.' 
                : 'Tidak ada siswa dengan nomor WA yang ditemukan pada target yang dipilih.';
                
            return back()->with('error', $msg);
        }

        // Dispatch Job untuk setiap siswa
        $count = 0;
        foreach ($students as $student) {
            SendGeneralWaJob::dispatch($student->parent_wa_number, $request->message);
            $count++;
        }

        return back()->with('success', "Notifikasi sedang dikirim ke {$count} penerima (antrian).");
    }
}