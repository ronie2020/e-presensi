<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AttendanceSiswa;
use App\Models\DisciplineRecord;
use App\Models\LibraryVisit;
use App\Models\Borrowing;
use App\Models\GradeRecord;
use App\Models\ExtracurricularMember; 
// IMPORT MODEL LMS
use App\Models\LmsAssignment;
use App\Models\LmsSubmission;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentPortalController extends Controller
{
    public function index()
    {
        return view('portal.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
        ]);

        $student = Student::where('student_id', $request->student_id)
                    ->orWhere('nis', $request->student_id)
                    ->first();

        if (!$student) {
            return back()->with('error', 'Data siswa tidak ditemukan. Periksa kembali Nomor Induk Anda.');
        }

        return redirect()->route('portal.show', $student->id);
    }

    public function show($id)
    {
        // 1. LOAD SISWA
        $student = Student::with(['schoolClass', 'disciplineRecords.disciplineType'])->findOrFail($id);
        $year = date('Y');

        // 2. ABSENSI
        $hadir = AttendanceSiswa::where('student_id', $student->id)->whereYear('attendance_date', $year)->where('type', 'Harian')->whereIn('status', ['Hadir', 'Terlambat'])->count();
        $sakit = AttendanceSiswa::where('student_id', $student->id)->whereYear('attendance_date', $year)->where('status', 'Sakit')->count();
        $izin = AttendanceSiswa::where('student_id', $student->id)->whereYear('attendance_date', $year)->where('status', 'Izin')->count();
        $alpa = AttendanceSiswa::where('student_id', $student->id)->whereYear('attendance_date', $year)->where('status', 'Alfa')->count();
        
        $attendance_history = AttendanceSiswa::where('student_id', $student->id)
                    ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
                    ->latest('attendance_date')
                    ->limit(7)
                    ->get();

        $attendanceChart = [
            'hadir' => $hadir,
            'sakit' => $sakit,
            'izin' => $izin,
            'alpa' => $alpa
        ];

        // 3. KEAGAMAAN
        $sholat_dhuha = AttendanceSiswa::where('student_id', $student->id)->whereYear('attendance_date', $year)->where('type', 'Keagamaan')->where('activity', 'Dhuha')->count();
        $sholat_dhuhur = AttendanceSiswa::where('student_id', $student->id)->whereYear('attendance_date', $year)->where('type', 'Keagamaan')->whereIn('activity', ['Dhuhur', 'Duhur'])->count();
        $religious_history = AttendanceSiswa::where('student_id', $student->id)->where('type', 'Keagamaan')->latest('created_at')->limit(5)->get();

        // 4. DISIPLIN & PRESTASI
        $violations = $student->disciplineRecords
            ->filter(fn($r) => $r->disciplineType && $r->disciplineType->type == 'Pelanggaran')
            ->sortByDesc('date');
        $total_violation_points = $violations->sum(fn($r) => $r->disciplineType->point_value ?? 0);

        $achievements = $student->disciplineRecords
            ->filter(fn($r) => $r->disciplineType && $r->disciplineType->type == 'Kebaikan')
            ->sortByDesc('date');
        $total_merit_points = $achievements->sum(fn($r) => $r->disciplineType->point_value ?? 0);

        // 5. PERPUSTAKAAN
        $library_visits = class_exists('App\Models\LibraryVisit') ? LibraryVisit::where('student_id', $student->id)->count() : 0;
        $library_history = class_exists('App\Models\Borrowing') ? Borrowing::with('book')->where('student_id', $student->id)->orderBy('borrow_date', 'desc')->limit(10)->get() : [];

        // 6. EKSTRAKURIKULER
        $extracurriculars_joined = [];
        if (class_exists('App\Models\ExtracurricularMember')) {
            $extracurriculars_joined = ExtracurricularMember::with('extracurricular')
                ->where('student_id', $student->id)
                ->get();
        }

        // 7. DATA AKADEMIK (NILAI RAPOR)
        $academic_record = null;
        $chartData = ['labels' => [], 'scores' => []]; 
        if (class_exists('App\Models\GradeRecord')) {
            $academic_record = GradeRecord::with(['items.subject'])
                ->where('student_id', $student->id)
                ->latest('academic_year')
                ->latest('semester')
                ->first();
            if ($academic_record) {
                foreach ($academic_record->items as $item) {
                    $name = $item->subject->name ?? 'Mapel';
                    $chartData['labels'][] = Str::limit($name, 15);
                    $chartData['scores'][] = $item->score;
                }
            }
        }

        // 8. [BARU] DATA LMS (TUGAS & KUIS HARIAN)
        // Ambil tugas yang ditujukan untuk kelas siswa ini
        $lms_assignments_grouped = [];
        $lms_grades = [];
        
        if ($student->class_id) {
            // Ambil semua tugas untuk kelas ini, load relasi Subject
            $assignments = LmsAssignment::with('subject')
                ->where('class_id', $student->class_id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Kelompokkan berdasarkan Nama Mapel
            $lms_assignments_grouped = $assignments->groupBy(function($item) {
                return $item->subject->name ?? 'Lainnya';
            });

            // Ambil nilai (submission) siswa untuk tugas-tugas tersebut
            $lms_grades = LmsSubmission::where('student_id', $student->id)
                ->whereIn('assignment_id', $assignments->pluck('id'))
                ->pluck('grade', 'assignment_id')
                ->toArray(); 
        }

        return view('portal.show', compact(
            'student', 
            'hadir', 'sakit', 'izin', 'alpa', 'attendance_history', 'attendanceChart',
            'sholat_dhuha', 'sholat_dhuhur', 'religious_history',
            'violations', 'total_violation_points', 
            'achievements', 'total_merit_points',
            'library_visits', 'library_history',
            'extracurriculars_joined', 
            'academic_record', 'chartData',
            // Variabel Baru
            'lms_assignments_grouped', 'lms_grades'
        ));
    }

    /**
     * Menampilkan Kartu OSIS untuk dicetak oleh siswa via Portal
     */
    public function printCard($id)
    {
        $student = Student::with('schoolClass')->findOrFail($id);
        return view('students.osis_card', compact('student'));
    }
}