<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Agenda; // [PENTING] Jangan lupa import model Agenda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendGeneralWaJob;

class AnnouncementController extends Controller
{
    public function index()
    {
        // Ambil data pengumuman
        $announcements = Announcement::with('author')->latest()->get();
        
        // [BARU] Ambil data agenda (H-1 ke depan)
        $agendas = Agenda::where('event_date', '>=', now()->subDays(1))
                        ->orderBy('event_date', 'asc')
                        ->get();

        // Data untuk dropdown kelas/siswa (WA Gateway)
        $classes = SchoolClass::orderBy('name')->get();
        
        $students = Student::join('classes', 'students.class_id', '=', 'classes.id')
            ->orderBy('classes.name', 'asc')
            ->orderBy('students.name', 'asc')
            ->select('students.*')
            ->with('schoolClass')
            ->get();
        
        $templates = [
            "Pemberitahuan Libur" => "Yth. Bapak/Ibu Wali Murid, diberitahukan bahwa besok sekolah diliburkan karena...",
            "Undangan Rapat" => "Yth. Bapak/Ibu Wali Murid, kami mengundang Anda untuk hadir pada rapat wali murid tanggal...",
            "Pengingat Ujian" => "Yth. Bapak/Ibu, minggu depan akan dilaksanakan Ujian Tengah Semester. Mohon bimbingan belajar di rumah.",
            "Tunggakan Administrasi" => "Yth. Bapak/Ibu, kami mengingatkan untuk segera melunasi administrasi sekolah sebelum tanggal...",
            "Panggilan Orang Tua (BK)" => "Yth. Bapak/Ibu, kami mengundang Bapak/Ibu untuk hadir ke sekolah besok terkait perkembangan ananda di sekolah. Mohon kehadirannya.",
        ];

        // Kirim $agendas ke view
        return view('announcements.index', compact('announcements', 'agendas', 'classes', 'students', 'templates'));
    }

    // -- LOGIKA PENGUMUMAN --
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

    public function destroy($id)
    {
        Announcement::findOrFail($id)->delete();
        return back()->with('success', 'Pengumuman dihapus.');
    }

    // -- [BARU] LOGIKA AGENDA --
    public function storeAgenda(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'event_date' => 'required|date',
            'location' => 'nullable|string|max:100',
        ]);

        Agenda::create($request->all());

        return back()->with('success', 'Agenda kegiatan berhasil dijadwalkan.');
    }

    public function destroyAgenda($id)
    {
        Agenda::findOrFail($id)->delete();
        return back()->with('success', 'Agenda kegiatan dihapus.');
    }

    // -- LOGIKA WA GATEWAY --
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
            $students = Student::whereNotNull('parent_wa_number')->get();
        } elseif ($request->target_type == 'class') {
            $students = Student::where('class_id', $request->class_id)
                               ->whereNotNull('parent_wa_number')
                               ->get();
        } elseif ($request->target_type == 'student') {
            $singleStudent = Student::where('id', $request->student_id)
                                    ->whereNotNull('parent_wa_number')
                                    ->first();
            if ($singleStudent) $students->push($singleStudent);
        }

        if ($students->isEmpty()) {
            return back()->with('error', 'Tidak ada nomor WA tujuan ditemukan.');
        }

        $count = 0;
        foreach ($students as $student) {
            SendGeneralWaJob::dispatch($student->parent_wa_number, $request->message);
            $count++;
        }

        return back()->with('success', "Notifikasi sedang dikirim ke {$count} penerima.");
    }
}