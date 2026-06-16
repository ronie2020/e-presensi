<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AlumniProfile;
use App\Models\Achievement; 
use App\Models\Borrowing;
use App\Models\GradeRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumniController extends Controller
{
    /**
     * Dashboard Utama Alumni
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // --- Load relasi classHistories untuk Jejak Kelas ---
        $student->load('classHistories.schoolClass');
        
        // 1. Data Tracer Study
        $profile = AlumniProfile::where('student_id', $student->id)->first();
        $isTracerFilled = $profile ? true : false;

        // 2. Data Histori Prestasi        
        $achievements = [];
        $total_merit_points = 0;

       // Cek apakah class Achievement ada di App\Models
        if (class_exists(\App\Models\Achievement::class)) {
            $achievements = \App\Models\Achievement::where('student_id', $student->id)
                                ->latest('date')
                                ->get();
                                
            // Poin di-set 0 karena data tidak lagi mengambil dari tabel disiplin
            $total_merit_points = 0; 
        }

        // 3. Data Perpustakaan
        $library_history = collect([]);
        $library_visits = 0;
        if (class_exists(\App\Models\Borrowing::class)) {       
             $library_history = \App\Models\Borrowing::with('book')
                                ->where('student_id', $student->id)
                                ->orderBy('borrow_date', 'desc')
                                ->get();
             $library_visits = $library_history->count();
        }

        // 4. Data Akademik / Nilai Rapor
        $academic_record = null;
        if (class_exists(\App\Models\GradeRecord::class)) {
             $academic_record = \App\Models\GradeRecord::with(['items.subject'])
                                ->where('student_id', $student->id)
                                ->latest()
                                ->first();
        }

        return view('alumni.dashboard', compact(
            'student', 
            'profile', 
            'isTracerFilled',
            'achievements',
            'total_merit_points',
            'library_history',
            'library_visits',
            'academic_record'
        ));
    }

    /**
     * Halaman Form Tracer Study
     */
    public function tracer()
    {
        $student = Auth::guard('student')->user();
        $profile = AlumniProfile::where('student_id', $student->id)->first();

        return view('alumni.tracer', compact('student', 'profile'));
    }

    /**
     * Simpan Data Tracer Study
     */
    public function storeTracer(Request $request)
    {
        $student = Auth::guard('student')->user();

        $request->validate([
            'phone_number' => 'required|string|max:15',
            'email' => 'required|email',
            'activity_status' => 'required|string',
            'testimony' => 'nullable|string|max:1000',
            'campus_entry_year' => 'nullable|integer|min:2000',
        ]);

        AlumniProfile::updateOrCreate(
            ['student_id' => $student->id],
            [
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'activity_status' => $request->activity_status,
                
                // Data Kampus
                'campus_name' => $request->campus_name,
                'campus_major' => $request->campus_major,
                'campus_entry_year' => $request->campus_entry_year,
                
                // Data Kerja
                'company_name' => $request->company_name,
                'position' => $request->position,
                
                'testimony' => $request->testimony,
                'rating' => $request->rating ?? 5,
            ]
        );

        return redirect()->route('alumni.dashboard')->with('success', 'Terima kasih! Data tracer study berhasil diperbarui.');
    }
}