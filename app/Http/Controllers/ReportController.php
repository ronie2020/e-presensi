<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// [PENTING] Import Model yang dibutuhkan agar tidak error "Class not found"
use App\Models\TeachingSession;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\AttendanceSiswa; // Jika Anda menggunakan ini di method lain
use Carbon\Carbon;

class ReportController extends Controller
{
    // ... Method dailyReport, religiousReport, dll biarkan tetap ada ...
    // (Jika method lain tidak saya tulis di sini, jangan dihapus, biarkan saja)

    public function dailyReport() {
        // ... logika dailyReport Anda ...
        return view('reports.daily'); // Contoh dummy
    }

    /**
     * Rekap Jurnal Mengajar (Monitoring Evaluasi)
     */
    public function teachingJournal(Request $request)
    {
        // 1. Setup Filter Tanggal (Default: Bulan Ini)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        
        // 2. Setup Filter Lainnya
        $teacherId = $request->input('teacher_id');
        $classId = $request->input('class_id');
        $subjectId = $request->input('subject_id');

        // 3. Query Data
        $query = TeachingSession::with(['teacher', 'schedule.schoolClass', 'schedule.subject'])
            // Hitung jumlah hadir, sakit, izin, alpha per sesi secara efisien
            ->withCount([
                'attendances as hadir_count' => function ($q) { $q->where('status', 'present'); },
                'attendances as late_count' => function ($q) { $q->where('status', 'late'); },
                'attendances as alpha_count' => function ($q) { $q->where('status', 'alpha'); },
            ])
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->orderBy('started_at', 'desc');

        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }
        if ($classId) {
            $query->whereHas('schedule', function($q) use ($classId) {
                $q->where('school_class_id', $classId);
            });
        }
        if ($subjectId) {
            $query->whereHas('schedule', function($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            });
        }

        $sessions = $query->paginate(20)->withQueryString();

        // 4. Data Pendukung untuk Filter
        $teachers = User::whereIn('role', ['Guru', 'Wali Kelas'])->orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('reports.teaching_journal', compact(
            'sessions', 'teachers', 'classes', 'subjects', 
            'startDate', 'endDate', 'teacherId', 'classId', 'subjectId'
        ));
    }
}