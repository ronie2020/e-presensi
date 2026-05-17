<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\LmsAssignment;
use App\Models\Student;
use App\Models\LmsSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// ===> IMPORT PACKAGES <===
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GradeRecapExport; 

class LmsGradeController extends Controller
{
    /**
     * Helper: Ambil Data Nilai (Agar tidak duplikasi kode)
     */
    private function getGradeData($levelId, $classId, $subjectId, $period = 'semester')
    {
        $user = Auth::user();
        
        $students = collect();
        $assignments = collect();
        $gradeBook = [];
        $selectedClass = null;
        $selectedLevel = null;
        $selectedSubject = null;

        // Validasi: Minimal Level ATAU Class dipilih, DAN Subject dipilih
        if (($levelId || $classId) && $subjectId) {
            $selectedSubject = Subject::find($subjectId);

            $classIds = [];

            // 1. Tentukan target ID kelas berdasarkan filter
            if ($classId) {
                $selectedClass = SchoolClass::find($classId);
                if ($selectedClass) {
                    $classIds = [$selectedClass->id];
                }
            } elseif ($levelId) {
                // Deteksi tingkat dari string nama kelas (Tanpa butuh tabel Level)
                $romawi = [
                    '7' => 'VII', '8' => 'VIII', '9' => 'IX'
                ][$levelId] ?? $levelId;

                // Cari kelas yang diawali angka (misal '7') atau romawi (misal 'VII')
                $classIds = SchoolClass::where('name', 'LIKE', $levelId . '%')
                                    ->orWhere('name', 'LIKE', $romawi . '%')
                                    ->pluck('id')->toArray();

                // Buat object virtual agar tidak error saat dipanggil namanya di Blade/PDF
                $selectedLevel = (object)['name' => 'Tingkat ' . $levelId];
            }

            // Jika Mapel tidak ditemukan ATAU tidak ada kelas sama sekali, return kosong
            if (!$selectedSubject || empty($classIds)) {
                return compact('students', 'assignments', 'gradeBook', 'selectedClass', 'selectedLevel', 'selectedSubject');
            }

            // 2. Ambil Tugas berdasarkan array classIds
            $queryAssignments = LmsAssignment::whereIn('class_id', $classIds)
                ->where('subject_id', $subjectId);
            
            if ($user->role !== 'admin') {
                $queryAssignments->where(function($q) use ($user) {
                    // Guru bisa melihat tugas yang dia buat SENDIRI
                    $q->where('teacher_id', $user->id)
                      // ATAU, guru bisa melihat hasil ujian dari CBT (karena CBT bertipe 'quiz')
                      ->orWhere('assignment_type', 'quiz'); 
                });
            }
            
            // Filter Waktu/Periode
            if ($period === 'daily') {
                $queryAssignments->whereDate('created_at', Carbon::today());
            } elseif ($period === 'weekly') {
                $queryAssignments->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($period === 'monthly') {
                $queryAssignments->whereMonth('created_at', Carbon::now()->month)
                                 ->whereYear('created_at', Carbon::now()->year);
            }

            $rawAssignments = $queryAssignments->orderBy('created_at')->get();

            // ====================================================================================
            // FITUR BARU: Grouping Tugas Berdasarkan Judul (Mencegah Kolom Duplikat di Multi-Kelas)
            // ====================================================================================
            $assignments = collect();
            $groupIdMapping = [];
            $titleToIds = [];

            foreach ($rawAssignments as $task) {
                // Buat kunci unik berdasarkan judul (huruf kecil & hapus spasi ekstra) dan tipe tugas
                $key = strtolower(trim($task->title)) . '_' . $task->assignment_type;
                
                if (!isset($titleToIds[$key])) {
                    $assignments->push($task); // Simpan tugas pertama sebagai "Perwakilan Kolom"
                    $titleToIds[$key] = [];
                }
                $titleToIds[$key][] = $task->id;
            }

            // Buat mapping agar nilai dari kelas lain masuk ke ID tugas "Perwakilan" yang sama
            foreach ($titleToIds as $key => $ids) {
                $representativeId = $ids[0];
                foreach ($ids as $id) {
                    $groupIdMapping[$id] = $representativeId;
                }
            }
            // ====================================================================================

            // 3. Ambil Siswa
            $students = Student::with('schoolClass') 
                ->whereIn('class_id', $classIds)
                ->orderBy('class_id') // Urutkan berdasarkan kelas dulu
                ->orderBy('name')     // Baru urutkan sesuai abjad nama
                ->get();

            // 4. Ambil Nilai Siswa
            $rawAssignmentIds = $rawAssignments->pluck('id');
            $submissions = LmsSubmission::whereIn('assignment_id', $rawAssignmentIds)->get();

            foreach ($submissions as $sub) {
                // PERBAIKAN: Petakan ID tugas dari nilai siswa ke ID Perwakilan (Kolom yang digabungkan)
                $repId = $groupIdMapping[$sub->assignment_id] ?? $sub->assignment_id;
                $gradeBook[$sub->student_id][$repId] = $sub->grade;
            }

            // 5. Hitung Kalkulasi Rata-rata
            foreach ($students as $student) {
                $studentScores = $gradeBook[$student->id] ?? [];
                
                $totalScore = array_sum($studentScores);
                $countScore = count($studentScores);
                
                $student->total_score = $totalScore;
                $student->average_score = $countScore > 0 ? round($totalScore / $countScore, 1) : 0;
            }
        }

        return compact('students', 'assignments', 'gradeBook', 'selectedClass', 'selectedLevel', 'selectedSubject');
    }

    /**
     * Halaman Utama Gradebook
     */
    public function index(Request $request)
    {
        $classes = SchoolClass::select('id', 'name')->orderBy('name')->get();
        $subjects = Subject::select('id', 'name')->orderBy('name')->get();

        $period = $request->period ?? 'semester';
        
        $data = $this->getGradeData($request->level_id, $request->class_id, $request->subject_id, $period);

        return view('lms.grades.index', array_merge($data, [
            'classes' => $classes,
            'subjects' => $subjects,
            'selectedLevelId' => $request->level_id,
            'selectedClassId' => $request->class_id,
            'selectedSubjectId' => $request->subject_id,
            'selectedPeriod' => $period
        ]));
    }

    /**
     * Export ke Excel
     */
    public function exportExcel(Request $request)
    {
        if ((!$request->level_id && !$request->class_id) || !$request->subject_id) {
            return back()->with('error', 'Pilih Tingkat/Kelas dan Mapel terlebih dahulu.');
        }

        $period = $request->period ?? 'semester';
        $data = $this->getGradeData($request->level_id, $request->class_id, $request->subject_id, $period);
        
        if (!$data['selectedSubject'] || (!$data['selectedClass'] && !$data['selectedLevel'])) {
             return back()->with('error', 'Data tidak valid atau tidak ditemukan.');
        }
        
        $data['selectedPeriod'] = $period;

        $targetName = $data['selectedClass'] ? $data['selectedClass']->name : 'Tingkat_' . $data['selectedLevel']->name;
        $filename = 'Rekap_Nilai_' . str_replace(' ', '_', $targetName) . '_' . date('Ymd') . '.xlsx';

        return Excel::download(new GradeRecapExport($data), $filename);
    }

    /**
     * Download PDF Laporan
     */
    public function printReport(Request $request)
    {
        if ((!$request->level_id && !$request->class_id) || !$request->subject_id) {
            return back()->with('error', 'Pilih Tingkat/Kelas dan Mapel terlebih dahulu.');
        }

        $period = $request->period ?? 'semester';
        $data = $this->getGradeData($request->level_id, $request->class_id, $request->subject_id, $period);
        
        if (!$data['selectedSubject'] || (!$data['selectedClass'] && !$data['selectedLevel'])) {
             return back()->with('error', 'Data tidak valid atau tidak ditemukan.');
        }

        $data['selectedPeriod'] = $period;
        $data['teacher'] = Auth::user();
        
        $data['headmaster'] = config('school.headmaster_name', 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.'); 
        $data['headmaster_nip'] = config('school.headmaster_nip', '197xxxxxxxxxxxxx'); 

        $pdf = Pdf::loadView('lms.grades.pdf', $data);
        $pdf->setPaper('a4', 'landscape');

        $targetName = $data['selectedClass'] ? $data['selectedClass']->name : 'Tingkat_' . $data['selectedLevel']->name;
        $filename = 'Laporan_Nilai_' . str_replace(' ', '_', $targetName) . '.pdf';

        return $pdf->stream($filename);
    }
}