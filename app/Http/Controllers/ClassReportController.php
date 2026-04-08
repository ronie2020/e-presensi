<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolClass;
use Carbon\Carbon;

class ClassReportController extends Controller
{
    /**
     * Mengambil Data & Menghitung Statistik
     */
    private function getReportData($startDate, $endDate)
    {
        // 1. Ambil Data Kelas + Siswa Aktif + Absensi
        $classes = SchoolClass::orderBy('name')            
            ->with(['students' => function($q) use ($startDate, $endDate) {
                $q->where('status', '!=', 'graduated') 
                  ->orderBy('name');
            }, 'students.attendances' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('attendance_date', [$startDate, $endDate])
                  ->whereIn('type', ['Harian', 'Masuk', 'Pulang']); 
            }])
            // Hitung Total Siswa per Kelas secara otomatis
            ->withCount(['students' => function($q) {
                $q->where('status', '!=', 'graduated');
            }])
            ->get();

        // 2. Olah Data (Looping)
        $reportData = $classes->map(function($class) use ($startDate, $endDate) {
            
            // [A] Ambil Total Siswa dari withCount di atas
            $totalStudents = $class->students_count;

            $hadirCount = 0;
            $telatCount = 0;
            $izinSakitCount = 0;
            $alphaCount = 0;
            $totalLogsRecorded = 0;

            // Loop setiap siswa untuk cek absensinya
            foreach ($class->students as $student) {
                // Cek apakah siswa punya data absensi di range tanggal ini
                if ($student->attendances->isNotEmpty()) {
                    foreach ($student->attendances as $attendance) {
                        $status = strtolower($attendance->status);
                        $totalLogsRecorded++;

                        if (in_array($status, ['hadir', 'tepat waktu'])) {
                            $hadirCount++;
                        } elseif (in_array($status, ['terlambat', 'telat'])) {
                            $telatCount++;
                        } elseif (in_array($status, ['sakit', 'izin', "uzur syar'i"])) {
                            $izinSakitCount++;
                        } elseif (in_array($status, ['alpha', 'alpa', 'absen'])) {
                            $alphaCount++;
                        }
                    }
                } else {
                    // Jika siswa tidak punya data absensi sama sekali, bisa dianggap Alpha atau Belum Absen
                    // Tergantung kebijakan. Di sini kita biarkan tidak terhitung di log, 
                    // tapi Total Siswa tetap tercatat sebagai penyebut.
                }
            }

            // [B] Rumus Rate Kehadiran (%)
            // OPSI 1: Berdasarkan Record yang masuk (Dinamis)
            // Rumus: (Hadir + Telat) / Total Log
             $effectivePresence = $hadirCount + $telatCount;
             $attendanceRate = $totalLogsRecorded > 0 
                 ? round(($effectivePresence / $totalLogsRecorded) * 100) 
                 : 0;                    

            return (object) [
                'id' => $class->id,
                'name' => $class->name,
                'total_students' => $totalStudents, 
                'hadir' => $hadirCount, // <--- TAMBAHKAN BARIS INI
                'telat' => $telatCount,
                'izin_sakit' => $izinSakitCount,
                'alpha' => $alphaCount,
                'rate' => $attendanceRate,
                'total_logs' => $totalLogsRecorded
            ];
        });

        return $reportData;
    }

    /**
     * Halaman Web (View)
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $reportData = $this->getReportData($startDate, $endDate);

        return view('reports.class_attendance', compact('reportData', 'startDate', 'endDate'));
    }

    /**
     * Export PDF
     */
    public function print(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $reportData = $this->getReportData($startDate, $endDate);
        $title = 'Rekapitulasi Absensi Kelas';

        return view('reports.pdf_class_recap', compact('reportData', 'startDate', 'endDate', 'title'));
    }

    /**
     * Export Excel
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', \Carbon\Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', \Carbon\Carbon::now()->endOfMonth()->toDateString());

        $reportData = $this->getReportData($startDate, $endDate);
        
        // 1. Ubah ekstensi file dari .xls menjadi .xlsx (Native Excel)
        $filename = 'Rekap_Kelas_' . $startDate . '.xlsx';

        // 2. Gunakan package Maatwebsite Excel untuk mengunduh
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\ClassRecapExport($reportData, $startDate, $endDate), 
            $filename
        );
    }
}