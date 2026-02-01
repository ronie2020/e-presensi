<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolClass;
use Carbon\Carbon;

class ClassReportController extends Controller
{
    /**
     * Logika Inti: Mengambil dan Mengolah Data Statistik
     */
    private function getReportData($startDate, $endDate)
    {
        // Ambil Semua Kelas dengan Siswa Aktif & Absensinya
        $classes = SchoolClass::with(['students' => function($q) {
                // Hanya ambil siswa aktif (bukan alumni)
                $q->where('status', '!=', 'graduated'); 
            }, 'students.attendances' => function($q) use ($startDate, $endDate) {
                // Filter absensi berdasarkan tanggal & tipe
                $q->whereBetween('attendance_date', [$startDate, $endDate])
                  ->whereIn('type', ['Harian', 'Masuk', 'Pulang']);
            }])
            ->withCount(['students' => function($q) {
                $q->where('status', '!=', 'graduated');
            }])
            ->get();

        // Hitung Statistik
        $reportData = $classes->map(function($class) {
            $totalStudents = $class->students_count;
            
            $hadirCount = 0;
            $telatCount = 0;
            $izinSakitCount = 0;
            $alphaCount = 0;
            $totalAttendanceRecords = 0;

            foreach ($class->students as $student) {
                foreach ($student->attendances as $attendance) {
                    $status = strtolower($attendance->status);
                    $totalAttendanceRecords++;

                    if (in_array($status, ['hadir', 'tepat waktu'])) {
                        $hadirCount++;
                    } elseif (in_array($status, ['terlambat', 'telat'])) {
                        $telatCount++;
                    } elseif (in_array($status, ['sakit', 'izin'])) {
                        $izinSakitCount++;
                    } elseif (in_array($status, ['alpha', 'alpa', 'absen'])) {
                        $alphaCount++;
                    }
                }
            }

            // Hitung Persentase (Hadir + Telat) / Total Rekaman
            $attendanceRate = $totalAttendanceRecords > 0 
                ? round((($hadirCount + $telatCount) / $totalAttendanceRecords) * 100, 1) 
                : 0;

            return (object) [
                'id' => $class->id,
                'name' => $class->name,
                'total_students' => $totalStudents,
                'hadir' => $hadirCount,
                'telat' => $telatCount,
                'izin_sakit' => $izinSakitCount,
                'alpha' => $alphaCount,
                'rate' => $attendanceRate
            ];
        });

        // Urutkan berdasarkan Nama Kelas
        return $reportData->sortBy('name');
    }

    /**
     * Halaman Utama Rekap Kelas
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $reportData = $this->getReportData($startDate, $endDate);

        return view('reports.class_attendance', compact('reportData', 'startDate', 'endDate'));
    }

    /**
     * Fitur Cetak PDF (Print View)
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
     * Fitur Export Excel
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $reportData = $this->getReportData($startDate, $endDate);
        $filename = 'Rekap_Kelas_' . $startDate . '_sd_' . $endDate . '.xls';

        return response()->streamDownload(function() use ($reportData, $startDate, $endDate) {
            echo view('reports.excel_class_recap', compact('reportData', 'startDate', 'endDate'));
        }, $filename, [
            "Content-Type" => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=\"$filename\""
        ]);
    }
}