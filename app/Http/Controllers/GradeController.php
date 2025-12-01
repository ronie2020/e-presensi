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

class GradeController extends Controller
{
    // ... (Method index, create, store TETAP SAMA seperti sebelumnya) ...

    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('order')->get();
        $years = AcademicYear::select('name')->distinct()->orderBy('name', 'desc')->get();
        $activeYear = AcademicYear::where('is_active', true)->first();

        return view('grades.index', [
            'classes' => $classes,
            'subjects' => $subjects,
            'years' => $years,
            'activeYear' => $activeYear
        ]);
    }

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

    public function store(Request $request)
    {
        $request->validate([
            'grades' => 'array',
            'descriptions' => 'array',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->grades as $studentId => $score) {
                if ($score === null) continue;

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

        return redirect()->route('grades.index')->with('success', 'Nilai berhasil disimpan!');
    }

    private function calculatePredicate($score)
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 75) return 'C';
        return 'D';
    }

    /**
     * [BARU] Menampilkan Daftar Siswa per Kelas untuk Cetak Rapor
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

        // Cek kelengkapan nilai per siswa (Opsional, untuk indikator)
        $progress = [];
        foreach($students as $student) {
            $record = GradeRecord::where('student_id', $student->id)
                        ->where('academic_year', $request->academic_year)
                        ->where('semester', $request->semester)
                        ->first();
            $count = $record ? $record->items()->count() : 0;
            $progress[$student->id] = $count; // Jumlah mapel yang sudah dinilai
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
     * Halaman Cetak Rapor
     */
    public function reportCard($student_id)
    {
        // Ambil tahun/semester dari request atau default aktif
        $academic_year = request('year') ?? '2024/2025';
        $semester = request('semester') ?? '1';

        $student = Student::with('schoolClass')->findOrFail($student_id);
        
        $record = GradeRecord::with('extracurriculars')
                    ->where('student_id', $student_id)
                    ->where('academic_year', $academic_year)
                    ->where('semester', $semester)
                    ->first();

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

        return view('grades.report', [
            'student' => $student,
            'record' => $record,
            'subjects' => $subjects,
            'year' => $academic_year,
            'semester' => $semester
        ]);
    }
}