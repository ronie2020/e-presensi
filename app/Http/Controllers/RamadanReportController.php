<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolClass;
use App\Models\Student;
use Carbon\Carbon;
use App\Http\Controllers\RamadanLogController; // Tambahkan ini untuk akses konstanta

class RamadanReportController extends Controller
{    
    /**
     * PERBAIKAN: Method untuk rekap Full Ramadhan (Browser Print)
     */
    public function exportPdf(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        
        // AMBIL PERIODE FULL RAMADHAN (Lintas Bulan)
        $startDate = Carbon::parse(RamadanLogController::RAMADAN_START_DATE);
        $endDate = $startDate->copy()->addDays(30); // 30 Hari Ramadhan

        // Ambil data siswa beserta rekap mutabaahnya selama periode tersebut
        // PERBAIKAN: Menggunakan kolom 'date' alih-alih 'created_at' agar lebih akurat
        $students = Student::where('class_id', $class->id)
            ->with(['ramadanLogs' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            }])
            ->get();

        // Olah data untuk rekapitulasi
        $reportData = $students->map(function ($student) {
            $logs = $student->ramadanLogs;
            
            // Hitung total puasa
            $totalPuasa = $logs->where('is_fasting', true)->count();
            
            // Hitung total shalat
            $totalShalat = 0;
            foreach ($logs as $log) {
                // Hapus pengecekan is_string jika sudah di-cast array di model
                $prayers = $log->prayers;
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

    /**
     * FITUR BARU: Export Rekapitulasi ke Excel (Format CSV)
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        
        // Logika Periode Sama dengan PDF
        $startDate = Carbon::parse(RamadanLogController::RAMADAN_START_DATE);
        $endDate = $startDate->copy()->addDays(30);

        $students = Student::where('class_id', $class->id)
            ->with(['ramadanLogs' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            }])
            ->get();

        $fileName = 'Rekap_Ramadhan_Kelas_' . str_replace(' ', '_', $class->name) . '.csv';

        // Set Headers untuk file CSV/Excel
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No', 'NIS', 'Nama Siswa', 'Total Pengisian (Hari)', 'Total Puasa (Hari)', 'Total Shalat (Waktu)', 'Rata-rata Nilai'];

        $callback = function() use($students, $columns) {
            $file = fopen('php://output', 'w');
            
            // Tambahkan BOM untuk format UTF-8 agar Excel tidak error baca karakter spesial
            fputs($file, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
            
            // Header baris pertama
            fputcsv($file, $columns);

            foreach ($students as $index => $student) {
                $logs = $student->ramadanLogs;
                $totalPuasa = $logs->where('is_fasting', true)->count();
                
                $totalShalat = 0;
                foreach ($logs as $log) {
                    $prayers = $log->prayers;
                    if(is_array($prayers)) {
                        $totalShalat += count(array_filter($prayers)); 
                    }
                }
                $rataNilai = $logs->avg('teacher_score') ?? 0;

                fputcsv($file, [
                    $index + 1,
                    $student->student_id,
                    $student->name,
                    $logs->count(),
                    $totalPuasa,
                    $totalShalat,
                    number_format($rataNilai, 2)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}