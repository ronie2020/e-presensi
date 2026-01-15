<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\GradeRecord;
use App\Models\GradeItem;
use App\Models\AcademicYear; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Library Excel
use Maatwebsite\Excel\Facades\Excel;

// Import & Export Classes (Pastikan file-file ini sudah dibuat di folder App/Imports dan App/Exports)
use App\Imports\GradesImport;         
use App\Imports\StudentGradesImport;  
use App\Exports\TemplateMapelExport;   
use App\Exports\TemplateStudentExport; 

class GradeController extends Controller
{
    /**
     * Halaman Utama (Dashboard Input Nilai)
     */
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('order')->get();
        // Mengambil tahun ajaran, urutkan dari yang terbaru
        $years = AcademicYear::select('name')->distinct()->orderBy('name', 'desc')->get();
        $activeYear = AcademicYear::where('is_active', true)->first();

        return view('grades.index', [
            'classes' => $classes,
            'subjects' => $subjects,
            'years' => $years,
            'activeYear' => $activeYear
        ]);
    }

    // =========================================================================
    //  MODE 1: INPUT PER MAPEL (Satu Mapel, Banyak Siswa)
    // =========================================================================

    /**
     * Form Input Manual Per Mapel
     */
    public function create(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'semester' => 'required',
            'academic_year' => 'required',
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        $subject = Subject::findOrFail($request->subject_id);
        $students = Student::where('class_id', $class->id)->orderBy('name')->get();

        // Ambil nilai existing (jika guru edit kembali)
        $existingGrades = [];
        foreach ($students as $student) {
            $record = GradeRecord::where('student_id', $student->id)
                        ->where('academic_year', $request->academic_year)
                        ->where('semester', $request->semester)
                        ->first();
            
            if ($record) {
                $item = GradeItem::where('grade_record_id', $record->id)
                            ->where('subject_id', $subject->id)
                            ->first();
                if ($item) {
                    $existingGrades[$student->id] = $item;
                }
            }
        }

        return view('grades.create', [
            'class' => $class,
            'subject' => $subject,
            'students' => $students,
            'existingGrades' => $existingGrades,
            'semester' => $request->semester,
            'academic_year' => $request->academic_year,
        ]);
    }

    /**
     * Simpan Nilai Per Mapel
     */
    public function store(Request $request)
    {
        $request->validate([
            'grades' => 'array',
            'descriptions' => 'array',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->grades as $studentId => $score) {
                // Skip jika nilai kosong
                if ($score === null) continue;

                // 1. Buat atau Ambil Record Rapor Utama
                $record = GradeRecord::firstOrCreate(
                    [
                        'student_id' => $studentId,
                        'academic_year' => $request->academic_year,
                        'semester' => $request->semester,
                    ],
                    [
                        'class_name' => SchoolClass::find($request->class_id)->name,
                        'report_date' => now(),
                    ]
                );

                // 2. Simpan Item Nilai (Mapel)
                GradeItem::updateOrCreate(
                    [
                        'grade_record_id' => $record->id,
                        'subject_id' => $request->subject_id,
                    ],
                    [
                        'score' => $score,
                        'description' => $request->descriptions[$studentId] ?? null,
                        'predicate' => $this->calculatePredicate($score),
                    ]
                );
            }
        });

        return redirect()->route('grades.index')->with('success', 'Nilai Mapel berhasil disimpan!');
    }


    // =========================================================================
    //  MODE 2: INPUT PER SISWA (Satu Siswa, Banyak Mapel)
    // =========================================================================

    /**
     * Form Input Manual Per Siswa
     */
    public function createByStudent(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'academic_year' => 'required',
            'semester' => 'required',
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        
        // Ambil siswa sekelas untuk dropdown navigasi
        $students = Student::where('class_id', $class->id)->orderBy('name')->get();

        if ($students->isEmpty()) {
            return redirect()->back()->with('error', 'Kelas ini belum memiliki siswa.');
        }

        // Tentukan siswa target (dari request atau default siswa pertama)
        $targetStudentId = $request->student_id ?? $students->first()->id;
        $student = $students->find($targetStudentId);

        if (!$student) {
             return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
        }

        $subjects = Subject::orderBy('order')->get();

        // Ambil nilai existing siswa tersebut
        $existingGrades = [];
        $record = GradeRecord::where('student_id', $student->id)
                    ->where('academic_year', $request->academic_year)
                    ->where('semester', $request->semester)
                    ->first();

        if ($record) {
            foreach($record->items as $item) {
                $existingGrades[$item->subject_id] = $item;
            }
        }

        return view('grades.create-by-student', [
            'class' => $class,
            'student' => $student,
            'students' => $students,
            'subjects' => $subjects,
            'existingGrades' => $existingGrades,
            'semester' => $request->semester,
            'academic_year' => $request->academic_year,
        ]);
    }

    /**
     * Simpan Nilai Per Siswa
     */
    public function storeByStudent(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'student_id' => 'required',
            'academic_year' => 'required',
            'semester' => 'required',
            'grades' => 'array', // [subject_id => score]
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat Record Rapor
            $record = GradeRecord::firstOrCreate(
                [
                    'student_id' => $request->student_id,
                    'academic_year' => $request->academic_year,
                    'semester' => $request->semester,
                ],
                [
                    'class_name' => SchoolClass::find($request->class_id)->name,
                    'report_date' => now(),
                ]
            );

            // 2. Loop semua mapel
            foreach ($request->grades as $subjectId => $score) {
                if ($score === null || $score === '') continue;

                GradeItem::updateOrCreate(
                    [
                        'grade_record_id' => $record->id,
                        'subject_id' => $subjectId,
                    ],
                    [
                        'score' => $score,
                        'description' => $request->descriptions[$subjectId] ?? null,
                        'predicate' => $this->calculatePredicate($score),
                    ]
                );
            }
        });

        // Redirect kembali ke halaman input siswa yang sama
        return redirect()
            ->route('grades.create_by_student', [
                'class_id' => $request->class_id,
                'student_id' => $request->student_id,
                'academic_year' => $request->academic_year,
                'semester' => $request->semester
            ])
            ->with('success', 'Nilai untuk siswa ' . Student::find($request->student_id)->name . ' berhasil disimpan!');
    }


    // =========================================================================
    //  FITUR IMPORT EXCEL & AJAX HELPER
    // =========================================================================

    /**
     * [AJAX] Mengambil daftar siswa berdasarkan kelas (Untuk Dropdown Frontend)
     */
    public function getStudentsByClass($class_id)
    {
        $students = Student::where('class_id', $class_id)
                           ->select('id', 'name', 'student_id')
                           ->orderBy('name')
                           ->get();
        return response()->json($students);
    }

    /**
     * Download Template Excel PER MAPEL
     */
    public function downloadTemplate()
    {
        return Excel::download(new TemplateMapelExport, 'template_nilai_mapel.xlsx');
    }

    /**
     * Download Template Excel PER SISWA
     */
    public function downloadStudentTemplate()
    {
        return Excel::download(new TemplateStudentExport, 'template_nilai_siswa.xlsx');
    }

    /**
     * Proses Import Excel PER MAPEL
     */
    public function importGrades(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'class_id' => 'required',
            'subject_id' => 'required',
            'academic_year' => 'required',
            'semester' => 'required',
        ]);

        try {
            // Menggunakan GradesImport
            Excel::import(new GradesImport(
                $request->class_id,
                $request->subject_id,
                $request->academic_year,
                $request->semester
            ), $request->file('file'));

            return redirect()->route('grades.index')->with('success', 'Nilai Mapel berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * Proses Import Excel PER SISWA
     */
    public function importStudentGrades(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'class_id' => 'required',
            'student_id' => 'required',
            'academic_year' => 'required',
            'semester' => 'required',
        ]);

        try {
            // Menggunakan StudentGradesImport
            Excel::import(new StudentGradesImport(
                $request->class_id,
                $request->student_id,
                $request->academic_year,
                $request->semester
            ), $request->file('file'));

            return redirect()->route('grades.index')->with('success', 'Nilai Siswa berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }


    // =========================================================================
    //  UTILITIES & REPORTING
    // =========================================================================

    /**
     * Helper: Menghitung Predikat Nilai
     */
    private function calculatePredicate($score)
    {
        // Sesuaikan interval ini dengan kebijakan sekolah
        if ($score >= 92) return 'A';
        if ($score >= 83) return 'B';
        if ($score >= 75) return 'C';
        return 'D';
    }

    /**
     * Menampilkan Daftar Siswa untuk Cetak Rapor
     */
    public function listStudents(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'academic_year' => 'required',
            'semester' => 'required',
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        $students = Student::where('class_id', $class->id)->orderBy('name')->get();

        // Hitung progress kelengkapan nilai
        $progress = [];
        foreach($students as $student) {
            $record = GradeRecord::where('student_id', $student->id)
                        ->where('academic_year', $request->academic_year)
                        ->where('semester', $request->semester)
                        ->first();
            $count = $record ? $record->items()->count() : 0;
            $progress[$student->id] = $count; 
        }

        return view('grades.list', [
            'class' => $class,
            'students' => $students,
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'progress' => $progress
        ]);
    }

    /**
     * Halaman Detail/Preview Rapor Siswa
     * UPDATE: Penambahan logika Navigasi Siswa (Prev/Next)
     */
    public function reportCard($student_id)
    {
        $academic_year = request('year') ?? '2024/2025';
        $semester = request('semester') ?? '1';

        $student = Student::with('schoolClass')->findOrFail($student_id);
        
        $record = GradeRecord::with('extracurriculars')
                    ->where('student_id', $student_id)
                    ->where('academic_year', $academic_year)
                    ->where('semester', $semester)
                    ->first();

        // Ambil semua mapel, lalu map dengan nilai siswa jika ada
        $subjects = Subject::orderBy('order')->get()->map(function($subject) use ($record) {
            $grade = null;
            if ($record) {
                $grade = GradeItem::where('grade_record_id', $record->id)
                            ->where('subject_id', $subject->id)
                            ->first();
            }
            $subject->grade = $grade;
            return $subject;
        });

        // ------------------------------------------------------------------
        // LOGIKA NAVIGASI PREV/NEXT (Update Baru)
        // ------------------------------------------------------------------
        
        // 1. Ambil daftar ID siswa sekelas, urut abjad
        // Pastikan 'name' asc agar urutannya sama dengan di daftar
        $classmates = Student::where('class_id', $student->class_id)
                        ->orderBy('name', 'asc')
                        ->pluck('id')
                        ->toArray();

        // 2. Cari posisi siswa saat ini di array
        $currentPos = array_search($student->id, $classmates);

        // 3. Tentukan ID Next dan Prev
        $prevStudentId = null;
        $nextStudentId = null;

        if ($currentPos !== false) {
            // Jika bukan siswa pertama, ambil ID sebelumnya
            if ($currentPos > 0) {
                $prevStudentId = $classmates[$currentPos - 1];
            }
            
            // Jika bukan siswa terakhir, ambil ID selanjutnya
            if ($currentPos < count($classmates) - 1) {
                $nextStudentId = $classmates[$currentPos + 1];
            }
        }

        return view('grades.report', [
            'student' => $student,
            'record' => $record,
            'subjects' => $subjects,
            'year' => $academic_year,
            'semester' => $semester,
            'prevStudentId' => $prevStudentId, // Variable baru untuk tombol Prev
            'nextStudentId' => $nextStudentId  // Variable baru untuk tombol Next
        ]);
    }
}