<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\LmsAssignment;
use App\Models\Student;
use App\Models\LmsSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // <== FITUR BARU: Import Carbon untuk filter kalender

// ===> IMPORT PACKAGES <===
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GradeRecapExport; 

class LmsGradeController extends Controller
{
    /**
     * Helper: Ambil Data Nilai (Agar tidak duplikasi kode)
     */
    private function getGradeData($classId, $subjectId, $period = 'semester')
    {
        $user = Auth::user();
        
        $students = collect();
        $assignments = collect();
        $gradeBook = [];
        $selectedClass = null;
        $selectedSubject = null;

        if ($classId && $subjectId) {
            $selectedClass = SchoolClass::find($classId);
            $selectedSubject = Subject::find($subjectId);

            // Jika Kelas/Mapel tidak ditemukan, return data kosong
            if (!$selectedClass || !$selectedSubject) {
                return compact('students', 'assignments', 'gradeBook', 'selectedClass', 'selectedSubject');
            }

            // Ambil Tugas
            $queryAssignments = LmsAssignment::where('class_id', $classId)
                ->where('subject_id', $subjectId);
            
            if ($user->role !== 'admin') {
                $queryAssignments->where('teacher_id', $user->id);
            }
            
            // FITUR BARU: Filter Berdasarkan Periode
            if ($period === 'daily') {
                $queryAssignments->whereDate('created_at', Carbon::today());
            } elseif ($period === 'weekly') {
                $queryAssignments->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($period === 'monthly') {
                $queryAssignments->whereMonth('created_at', Carbon::now()->month)
                                 ->whereYear('created_at', Carbon::now()->year);
            }
            // Jika period === 'semester', biarkan tampil semua (tanpa filter waktu)

            $assignments = $queryAssignments->orderBy('created_at')->get();

            // Ambil Siswa
            $students = Student::where('class_id', $classId)
                ->orderBy('name')
                ->get();

            // Ambil Nilai (Menghindari N+1 Query dengan whereIn)
            $assignmentIds = $assignments->pluck('id');
            $submissions = LmsSubmission::whereIn('assignment_id', $assignmentIds)->get();

            // Mapping Nilai ke Array GradeBook
            foreach ($submissions as $sub) {
                $gradeBook[$sub->student_id][$sub->assignment_id] = $sub->grade;
            }

            // PERBAIKAN: Hitung Total dan Rata-rata di Controller (Bukan di Blade View)
            foreach ($students as $student) {
                $studentScores = $gradeBook[$student->id] ?? [];
                
                // Hanya hitung jika ada nilai yang masuk
                $totalScore = array_sum($studentScores);
                $countScore = count($studentScores);
                
                // Sisipkan properti tambahan ke objek student
                $student->total_score = $totalScore;
                $student->average_score = $countScore > 0 ? round($totalScore / $countScore, 1) : 0;
            }
        }

        return compact('students', 'assignments', 'gradeBook', 'selectedClass', 'selectedSubject');
    }

    /**
     * Halaman Utama Gradebook
     */
    public function index(Request $request)
    {
        // PERBAIKAN: Gunakan select() agar query lebih ringan jika hanya butuh nama dan id
        $classes = SchoolClass::select('id', 'name')->orderBy('name')->get();
        $subjects = Subject::select('id', 'name')->orderBy('name')->get();

        $period = $request->period ?? 'semester';
        $data = $this->getGradeData($request->class_id, $request->subject_id, $period);

        return view('lms.grades.index', array_merge($data, [
            'classes' => $classes,
            'subjects' => $subjects,
            'selectedClassId' => $request->class_id,
            'selectedSubjectId' => $request->subject_id,
            'selectedPeriod' => $period // <== Kirim ke Blade
        ]));
    }

    /**
     * Export ke Excel
     */
    public function exportExcel(Request $request)
    {
        if (!$request->class_id || !$request->subject_id) {
            return back()->with('error', 'Pilih Kelas dan Mapel terlebih dahulu.');
        }

        $period = $request->period ?? 'semester';
        $data = $this->getGradeData($request->class_id, $request->subject_id, $period);
        
        if (!$data['selectedClass'] || !$data['selectedSubject']) {
             return back()->with('error', 'Data Kelas atau Mapel tidak valid.');
        }
        
        // PERBAIKAN: Masukkan periode ke data agar bisa dibaca oleh template Excel
        $data['selectedPeriod'] = $period;

        $filename = 'Rekap_Nilai_' . str_replace(' ', '_', $data['selectedClass']->name) . '_' . date('Ymd') . '.xlsx';

        return Excel::download(new GradeRecapExport($data), $filename);
    }

    /**
     * Download PDF Laporan
     */
    public function printReport(Request $request)
    {
        if (!$request->class_id || !$request->subject_id) {
            return back()->with('error', 'Pilih Kelas dan Mapel terlebih dahulu.');
        }

        $period = $request->period ?? 'semester';
        $data = $this->getGradeData($request->class_id, $request->subject_id, $period);
        
        if (!$data['selectedClass']) {
             return back()->with('error', 'Data tidak valid.');
        }

        // PERBAIKAN: Masukkan periode ke data agar bisa dibaca oleh template PDF
        $data['selectedPeriod'] = $period;

        $data['teacher'] = Auth::user();
        
        // PERBAIKAN: Hindari Hardcode nama Kepala Sekolah.
        // Sebaiknya panggil dari Database pengaturan sekolah, atau setidaknya file .env
        // Contoh jika menggunakan table settings atau helper function (sementara fallback ke hardcode jika kosong):
        $data['headmaster'] = config('school.headmaster_name', 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.'); 
        $data['headmaster_nip'] = config('school.headmaster_nip', '197xxxxxxxxxxxxx'); 

        $pdf = Pdf::loadView('lms.grades.pdf', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Nilai_' . $data['selectedClass']->name . '.pdf');
    }
}