<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentHabit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class TeacherHabitController extends Controller
{
    /**
     * Halaman Monitoring Utama
     */
    public function index(Request $request)
    {
        // 1. Ambil Filter (Default hari ini)
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));
        $classId = $request->input('class_id');

        // 2. Ambil Daftar Kelas untuk Dropdown
        $classes = SchoolClass::orderBy('name')->get();

        // 3. Default Data Kosong
        $students = collect();
        $stats = [
            'submitted' => 0,
            'missing' => 0,
            'percentage' => 0
        ];

        // 4. Jika Kelas Dipilih, Jalankan Logika
        if ($classId) {
            // Kita gunakan 'class_id' karena sudah dikonfirmasi di file Student.php
            $students = Student::where('class_id', $classId)
                ->orderBy('name')
                ->get()
                ->map(function ($student) use ($date) {
                    
                    // PERBAIKAN UTAMA: Gunakan whereDate()
                    // Ini mengatasi masalah jika di database tersimpan sebagai 'Y-m-d H:i:s'
                    $habit = StudentHabit::where('student_id', $student->id)
                                ->whereDate('report_date', $date) 
                                ->first();
                    
                    // Inject status & data ke object siswa untuk dipakai di View
                    $student->habit_status = $habit ? 'submitted' : 'missing';
                    $student->habit_data = $habit; 
                    
                    return $student;
                });

            // Hitung Statistik Dashboard
            $totalStudents = $students->count();
            $submitted = $students->where('habit_status', 'submitted')->count();
            
            $stats['submitted'] = $submitted;
            $stats['missing'] = $totalStudents - $submitted;
            $stats['percentage'] = $totalStudents > 0 ? round(($submitted / $totalStudents) * 100) : 0;
        }

        return view('habits.teacher_index', compact('classes', 'students', 'date', 'classId', 'stats'));
    }

    /**
     * Modal Detail Siswa (AJAX)
     */
    public function show($id)
    {
        // PERBAIKAN: Eager Load 'schoolClass' agar nama kelas muncul di modal
        // Sesuai relasi di Student.php: public function schoolClass()
        $habit = StudentHabit::with('student.schoolClass')->findOrFail($id);
        
        // Render view partial
        // Pastikan file ini ada di: resources/views/habits/partials/detail_modal.blade.php
        return view('habits.partials.detail_modal', compact('habit'))->render();
    }
    
    /**
     * Simpan Feedback Guru
     */
    public function feedback(Request $request, $id)
    {
        $habit = StudentHabit::findOrFail($id);
        
        $habit->update([
            'teacher_feedback' => $request->feedback,
            'teacher_id' => auth()->id(), // Pastikan guru sudah login
            'validated_at' => now()
        ]);

        return back()->with('success', 'Feedback berhasil dikirim.');
    }

    /**
     * Cetak Laporan PDF/Print
     */
    public function print(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        $classId = $request->class_id;

        if (!$classId) {
            return redirect()->back()->with('error', 'Silakan pilih kelas terlebih dahulu.');
        }

        $class = SchoolClass::findOrFail($classId);
        
        $students = Student::where('class_id', $classId)
            ->orderBy('name', 'asc')
            ->get()
            ->each(function($student) use ($date) {
                // PERBAIKAN: Gunakan whereDate di sini juga
                $student->habit_data = StudentHabit::where('student_id', $student->id)
                    ->whereDate('report_date', $date)
                    ->first();
            });

        // Pastikan Anda sudah membuat view 'habits.print'
        return view('habits.print', compact('students', 'date', 'class'));
    }
}