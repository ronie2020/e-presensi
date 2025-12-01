<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AttendanceSiswa;
use App\Models\DisciplineRecord;
use App\Models\LibraryVisit;
use App\Models\Borrowing;
use App\Models\Achievement;
use App\Models\GradeRecord;
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

        // [BARU] SIAPKAN DATA CHART KEHADIRAN
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

        // 4. DISIPLIN
        $poin_pelanggaran = $student->disciplineRecords->filter(fn($r) => $r->disciplineType && $r->disciplineType->type == 'Pelanggaran')->sum(fn($r) => $r->disciplineType->point_value);
        $poin_kebaikan = $student->disciplineRecords->filter(fn($r) => $r->disciplineType && $r->disciplineType->type == 'Kebaikan')->sum(fn($r) => $r->disciplineType->point_value);
        $discipline_history = DisciplineRecord::with('disciplineType')->where('student_id', $student->id)->orderBy('date', 'desc')->limit(10)->get();

        // 5. LAINNYA
        $achievements = class_exists('App\Models\Achievement') ? Achievement::where('student_id', $student->id)->orderBy('date', 'desc')->get() : [];
        $library_visits = class_exists('App\Models\LibraryVisit') ? LibraryVisit::where('student_id', $student->id)->count() : 0;
        $borrowing_history = class_exists('App\Models\Borrowing') ? Borrowing::with('book')->where('student_id', $student->id)->orderBy('borrow_date', 'desc')->limit(10)->get() : [];

        // 6. DATA AKADEMIK (NILAI)
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
                    $shortName = Str::limit($name, 15);
                    $chartData['labels'][] = $shortName;
                    $chartData['scores'][] = $item->score;
                }
            }
        }

        return view('portal.show', compact(
            'student', 
            'hadir', 'sakit', 'izin', 'alpa', 'attendance_history', 'attendanceChart', // <-- Tambahkan ini
            'sholat_dhuha', 'sholat_dhuhur', 'religious_history',
            'poin_pelanggaran', 'poin_kebaikan', 'discipline_history',
            'achievements',
            'library_visits', 'borrowing_history',
            'academic_record', 'chartData'
        ));
    }
}