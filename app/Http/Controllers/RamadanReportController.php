<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolClass;
use App\Models\Student;
use Carbon\Carbon;

class RamadanReportController extends Controller
{    
    /**
     * Method baru untuk rekap 1 Bulan (Browser Print)
     */
    public function exportPdf(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'month' => 'required|date_format:Y-m' // Format contoh: 2024-03
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        $month = Carbon::createFromFormat('Y-m', $request->month);
        
        $startDate = $month->copy()->startOfMonth();
        $endDate = $month->copy()->endOfMonth();

        // Ambil data siswa beserta rekap mutabaahnya selama bulan tersebut
        $students = Student::where('class_id', $class->id)
            ->with(['ramadanLogs' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get();

        // Olah data untuk rekapitulasi
        $reportData = $students->map(function ($student) {
            $logs = $student->ramadanLogs;
            
            // Hitung total puasa
            $totalPuasa = $logs->where('is_fasting', true)->count();
            
            // Hitung total shalat (dari array/JSON prayers)
            $totalShalat = 0;
            foreach ($logs as $log) {
                $prayers = is_string($log->prayers) ? json_decode($log->prayers, true) : $log->prayers;
                if(is_array($prayers)) {
                    $totalShalat += count(array_filter($prayers)); 
                }
            }

            // Rata-rata Nilai Guru
            $rataNilai = $logs->avg('teacher_score') ?? 0;

            return (object) [
                'name' => $student->name,
                'nis' => $student->student_id,
                'total_puasa' => $totalPuasa,
                'total_shalat' => $totalShalat,
                'rata_nilai' => number_format($rataNilai, 2),
                'total_log' => $logs->count()
            ];
        });

        $title = 'Rekap Ramadhan - ' . $class->name;

        // Return langsung ke view untuk diprint lewat browser
        return view('ramadan.export-pdf', compact('title', 'class', 'startDate', 'endDate', 'reportData'));
    }
}