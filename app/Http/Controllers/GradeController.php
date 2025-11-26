<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\GradeRecord;
use App\Models\GradeItem;
use App\Models\AcademicYear; // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    /**
     * Halaman Utama: Pilih Kelas & Mapel untuk Input Nilai
     */
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('order')->get();
        
        // AMBIL DATA TAHUN AJARAN DARI DATABASE
        // Grouping biar tampilan dropdown rapi (misal: 2024/2025 sendiri)
        $years = AcademicYear::select('name')->distinct()->orderBy('name', 'desc')->get();
        
        // Ambil Tahun Aktif untuk default selected
        $activeYear = AcademicYear::where('is_active', true)->first();

        return view('grades.index', [
            'classes' => $classes,
            'subjects' => $subjects,
            'years' => $years,      // Kirim daftar tahun
            'activeYear' => $activeYear // Kirim tahun aktif
        ]);
    }

    /**
     * Form Input Nilai (Batch Input per Kelas per Mapel)
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
        
        // Ambil semua siswa di kelas ini
        $students = Student::where('class_id', $class->id)->orderBy('name')->get();

        // Ambil nilai yang sudah ada (jika mau edit)
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
     * Simpan Nilai ke Database
     */
    public function store(Request $request)
    {
        // ... (Logika store TETAP SAMA seperti sebelumnya) ...
        
        $request->validate([
            'grades' => 'array',
            'descriptions' => 'array',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->grades as $studentId => $score) {
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
        if ($score >= 70) return 'C';
        return 'D';
    }

    public function reportCard($student_id)
    {
        // Ambil Semester Aktif dari Database
        $active = AcademicYear::where('is_active', true)->first();
        
        // Fallback jika belum disetting
        $academic_year = $active ? $active->name : '2024/2025'; 
        $semester = $active ? ($active->semester == 'Ganjil' ? '1' : '2') : '1';

        $student = Student::with('schoolClass')->findOrFail($student_id);
        
        $record = GradeRecord::where('student_id', $student_id)
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