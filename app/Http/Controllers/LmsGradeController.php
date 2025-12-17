<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\LmsAssignment;
use App\Models\Student;
use App\Models\LmsSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ===> IMPORT PACKAGES <===
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GradeRecapExport; 

class LmsGradeController extends Controller
{
    /**
     * Helper: Ambil Data Nilai (Agar tidak duplikasi kode)
     */
    private function getGradeData($classId, $subjectId)
    {
        $user = Auth::user();
        
        $students = [];
        $assignments = [];
        $gradeBook = [];
        $selectedClass = null;
        $selectedSubject = null;

        if ($classId && $subjectId) {
            $selectedClass = SchoolClass::find($classId);
            $selectedSubject = Subject::find($subjectId);

            // Jika Kelas/Mapel tidak ditemukan, return data kosong agar tidak error
            if (!$selectedClass || !$selectedSubject) {
                return compact('students', 'assignments', 'gradeBook', 'selectedClass', 'selectedSubject');
            }

            // Ambil Tugas
            $queryAssignments = LmsAssignment::where('class_id', $classId)
                ->where('subject_id', $subjectId);
            
            if ($user->role !== 'admin') {
                $queryAssignments->where('teacher_id', $user->id);
            }
            
            $assignments = $queryAssignments->orderBy('created_at')->get();

            // Ambil Siswa
            $students = Student::where('class_id', $classId)
                ->orderBy('name')
                ->get();

            // Ambil Nilai
            $assignmentIds = $assignments->pluck('id');
            $submissions = LmsSubmission::whereIn('assignment_id', $assignmentIds)->get();

            foreach ($submissions as $sub) {
                $gradeBook[$sub->student_id][$sub->assignment_id] = $sub->grade;
            }
        }

        return compact('students', 'assignments', 'gradeBook', 'selectedClass', 'selectedSubject');
    }

    /**
     * Halaman Utama Gradebook
     */
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        $data = $this->getGradeData($request->class_id, $request->subject_id);

        return view('lms.grades.index', array_merge($data, [
            'classes' => $classes,
            'subjects' => $subjects,
            'selectedClassId' => $request->class_id,
            'selectedSubjectId' => $request->subject_id
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

        $data = $this->getGradeData($request->class_id, $request->subject_id);
        
        // Validasi jika data tidak ditemukan
        if (!$data['selectedClass'] || !$data['selectedSubject']) {
             return back()->with('error', 'Data Kelas atau Mapel tidak valid.');
        }
        
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

        $data = $this->getGradeData($request->class_id, $request->subject_id);
        
        if (!$data['selectedClass']) {
             return back()->with('error', 'Data tidak valid.');
        }

        // Data Tambahan untuk Tanda Tangan
        $data['teacher'] = Auth::user();
        $data['headmaster'] = 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.'; 
        $data['headmaster_nip'] = '197xxxxxxxxxxxxx'; 

        $pdf = Pdf::loadView('lms.grades.pdf', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Nilai_' . $data['selectedClass']->name . '.pdf');
    }
}