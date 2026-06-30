<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timetable;
use App\Models\Timeslot;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Auth;

class StudentScheduleController extends Controller
{
    /**
     * Menampilkan halaman jadwal pelajaran khusus Siswa
     */
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name', 'asc')->get();

        // 1. Siapkan Query mengambil dari TIMETABLE BARU
        $query = Timetable::with(['timeslot', 'subject', 'teacher', 'studentClass']);

        // 2. Logika Filter Otomatis untuk Siswa Login
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $classId = $student->school_class_id ?? $student->class_id;

            if ($classId) {
                $query->where('class_id', $classId);
            }
        }
        // Jika ada filter manual dari request admin/umum (opsional)
        elseif ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // 3. Ambil data jadwal dan format jam dari slot waktu
        $schedules = $query->get();
        $timeslots = Timeslot::orderBy('order_sequence')->get();

        return view('students.schedule.index', compact('schedules', 'classes', 'timeslots'));
    }
}