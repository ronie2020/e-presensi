<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AlumniProfile;
use App\Models\Achievement; 
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

        // 3. Data Perpustakaan (Opsional, set kosong dulu agar aman)
        $library_history = [];
        $library_visits = 0;

        return view('alumni.dashboard', compact(
            'student', 
            'profile', 
            'isTracerFilled',
            'achievements',
            'total_merit_points',
            'library_history',
            'library_visits'
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