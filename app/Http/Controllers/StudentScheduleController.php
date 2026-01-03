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
        // 1. Ambil Data Kelas untuk Dropdown Filter
        $classes = SchoolClass::orderBy('name', 'asc')->get();

        // 2. Siapkan Query Jadwal
        // Load relasi subject (mapel), teacher (guru), dan schoolClass (kelas)
        $query = Schedule::with(['subject', 'teacher', 'schoolClass']);

        // 3. Logika Filter Kelas
        if ($request->filled('class_id')) {
            // Jika user memilih filter dropdown, gunakan itu
            $query->where('school_class_id', $request->class_id);
        } elseif (Auth::guard('student')->check()) {
            // Jika user adalah siswa login & punya kelas, otomatis filter jadwal kelasnya
            $student = Auth::guard('student')->user();
            if ($student->class_id) {
                $query->where('school_class_id', $student->class_id);
            }
        }

        // 4. Ambil data (Urutkan berdasarkan jam mulai)
        $schedules = $query->orderBy('start_time', 'asc')->get();

        // 5. Tampilkan View
        return view('student.schedule.index', compact('schedules', 'classes'));
    }
}