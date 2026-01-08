<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Auth;

class StudentScheduleController extends Controller
{
    /**
     * Menampilkan halaman jadwal pelajaran khusus Siswa
     */
    public function index(Request $request)
    {
        // 1. Ambil Data Kelas untuk Dropdown Filter (untuk Admin/Umum)
        $classes = SchoolClass::orderBy('name', 'asc')->get();

        // 2. Siapkan Query Jadwal
        $query = Schedule::with(['subject', 'teacher', 'schoolClass']);

        // 3. Logika Filter Otomatis untuk Siswa Login
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            
            // Cek apakah kolomnya school_class_id atau class_id
            $classId = $student->school_class_id ?? $student->class_id;

            if ($classId) {
                $query->where('school_class_id', $classId);
            }
        }

        // 4. Jika ada filter manual dari request (opsional)
        if ($request->filled('class_id')) {
            $query->where('school_class_id', $request->class_id);
        }

        // 5. Ambil data
        $schedules = $query->orderBy('start_time', 'asc')->get();

        // 6. PERBAIKAN: Menggunakan 'students' (dengan s) sesuai struktur folder kamu
        return view('students.schedule.index', compact('schedules', 'classes'));
    }
}