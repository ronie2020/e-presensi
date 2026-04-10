<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceSiswa;
use App\Models\Student;
use App\Models\TeachingSession; 
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\DisciplineRecord;
use App\Models\DisciplineType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendWaManualNotificationJob; 

class ReportController extends Controller
{
    // =========================================================================
    // PRIVATE HELPERS (Fungsi Bantuan)
    // =========================================================================

    /**
     * Helper untuk menentukan rentang tanggal berdasarkan input request.
     */
    private function getDateRange(Request $request)
    {
        $reportType = $request->input('report_type', 'daily');
        $start = null;
        $end = null;
        $label = "";

        // Logika Mingguan
        if ($reportType === 'weekly') {
            $weekStr = $request->input('week', Carbon::now()->format('Y-\WW'));
            
            $parts = explode('-W', $weekStr);
            if(count($parts) === 2) {
                $year = $parts[0];
                $week = $parts[1];
                $dt = Carbon::now()->setISODate($year, $week);
                $start = $dt->startOfWeek()->toDateString();
                $end = $dt->endOfWeek()->toDateString();
                $label = "Minggu ke-" . $week . " Tahun " . $year . " (" . $dt->startOfWeek()->format('d M') . " - " . $dt->endOfWeek()->format('d M Y') . ")";
            } else {
                $start = Carbon::now()->startOfWeek()->toDateString();
                $end = Carbon::now()->endOfWeek()->toDateString();
                $label = "Minggu Ini";
            }
        } 
        // Logika Bulanan
        elseif ($reportType === 'monthly') {
            $monthStr = $request->input('month', Carbon::now()->format('Y-m'));
            $dt = Carbon::parse($monthStr . '-01');
            $start = $dt->startOfMonth()->toDateString();
            $end = $dt->endOfMonth()->toDateString();
            $label = "Bulan " . $dt->translatedFormat('F Y');
        } 
        // Logika Harian (Default)
        else {
            $reportType = 'daily'; 
            $dateStr = $request->input('date', Carbon::today()->toDateString());
            $dt = Carbon::parse($dateStr);
            $start = $dt->toDateString();
            $end = $dt->toDateString();
            $label = $dt->translatedFormat('l, d F Y');
        }

        return [
            'start' => $start,
            'end' => $end,
            'label' => $label,
            'type' => $reportType
        ];
    }

    /**
     * Helper function untuk mempaginasi Collection secara manual.
     * Diperbarui untuk mendukung penamaan query string yang berbeda-beda.
     */
    private function paginate($items, $perPage = 20, $page = null, $options = [])
    {
        $pageName = $options['pageName'] ?? 'page';
        $page = $page ?: (Paginator::resolveCurrentPage($pageName) ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $options = array_merge(['path' => Paginator::resolveCurrentPath(), 'pageName' => $pageName], $options);

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage), 
            $items->count(), 
            $perPage, 
            $page, 
            $options 
        );
    }

    private function sortStudents($collection)
    {
        return $collection->sortBy(function ($item) {
            $student = $item instanceof Student ? $item : $item->student;
            $className = $student->schoolClass->name ?? 'ZZZ';
            // Sort by Date (desc) then Class then Name
            $date = $item->attendance_date ?? '0000-00-00';
            return $date . $className . ' ' . $student->name;
        }, SORT_NATURAL | SORT_FLAG_CASE)->values();
    }

    private function handleAutoPunishment($studentId, $date, $status, $typeContext = 'Harian', $preloadedViolationType = null)
    {
        if (!in_array($status, ['Alfa', 'Alpa', 'Alpha'])) return;

        // PERBAIKAN: Pisahkan nama pelanggaran dan poin berdasarkan tipe kegiatannya
        if ($preloadedViolationType) {
            $violationType = $preloadedViolationType;
        } else {
            $violationName = ($typeContext == 'Keagamaan') ? 'Tidak Ikut Kegiatan Keagamaan' : 'Tidak Masuk Sekolah (Alfa)';
            $violationPoints = ($typeContext == 'Keagamaan') ? 5 : 10;

            $violationType = DisciplineType::firstOrCreate(
                ['name' => $violationName],
                ['type' => 'Pelanggaran', 'point_value' => $violationPoints]
            );
        }

        $exists = DisciplineRecord::where('student_id', $studentId)
            ->where('date', $date)
            ->where('discipline_type_id', $violationType->id)
            ->exists();

        if (!$exists) {
            DisciplineRecord::create([
                'student_id' => $studentId,
                'discipline_type_id' => $violationType->id,
                'date' => $date,
                'notes' => "Otomatis: Tidak hadir ($typeContext)",
                'recorded_by_user_id' => auth()->id() ?? 1
            ]);
        }
    }
    // =========================================================================
    // 1. REKAP ABSENSI HARIAN 
    // =========================================================================
    
     public function dailyReport(Request $request)
    {
        $range = $this->getDateRange($request);
        $selectedDate_db = Carbon::parse($range['start']);

        // BUG FIX: Menggunakan get() bukan paginate(50) karena pagination akan di-handle per Tab.
        $attendances = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', function($q) { $q->where('status', '!=', 'graduated'); })
            ->whereBetween('attendance_date', [$range['start'], $range['end']])
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->orderBy('attendance_date', 'desc')
            ->get();


        $hadirCount = $attendances->where('status', 'Hadir')->count();
        $terlambatCount = $attendances->where('status', 'Terlambat')->count();
        $sakitCount = $attendances->where('status', 'Sakit')->count();
        $izinCount = $attendances->where('status', 'Izin')->count();
        $alfaCount = $attendances->where('status', 'Alfa')->count();
        
        $existingStudentIds = $attendances->pluck('student_id')->unique()->toArray();
        
        $belumAbsenListRaw = Student::with('schoolClass')
            ->where('status', '!=', 'graduated')
            ->whereNotIn('id', $existingStudentIds)
            ->get();
            
        $belumAbsenListAll = $this->sortStudents($belumAbsenListRaw);
        $mappedHadir = $this->sortStudents($attendances->whereIn('status', ['Hadir', 'Terlambat']));
        $mappedLain = $this->sortStudents($attendances->whereIn('status', ['Sakit', 'Izin', 'Alfa']));

        // Paginasi dengan "Page Name" unik per tab, dan mempertahankan Status Tab saat klik nomor halaman
        $attendancesHadir = $this->paginate($mappedHadir, 20, null, ['pageName' => 'page_hadir'])
            ->appends(array_merge($request->all(), ['activeTab' => 'hadir']));
            
        $attendancesLain = $this->paginate($mappedLain, 20, null, ['pageName' => 'page_lain'])
            ->appends(array_merge($request->all(), ['activeTab' => 'lain']));
            
        $belumAbsenList = $this->paginate($belumAbsenListAll, 20, null, ['pageName' => 'page_belum'])
            ->appends(array_merge($request->all(), ['activeTab' => 'belum']));

        return view('reports.daily', compact(
            'selectedDate_db', 'attendancesHadir', 'attendancesLain', 'belumAbsenList',
            'hadirCount', 'terlambatCount', 'sakitCount', 'izinCount', 'alfaCount', 'range'
        ));
    }

   public function printDaily(Request $request)
    {
        $range = $this->getDateRange($request);
        $selectedDate_db = Carbon::parse($range['start']);

        $attendances = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', function($q) { $q->where('status', '!=', 'graduated'); })
            ->whereBetween('attendance_date', [$range['start'], $range['end']])
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->orderBy('attendance_date', 'asc')
            ->orderBy('student_id', 'asc')
            ->get();

        $attendancesHadir = $this->sortStudents($attendances->whereIn('status', ['Hadir', 'Terlambat']));
        $attendancesLain = $this->sortStudents($attendances->whereIn('status', ['Sakit', 'Izin', 'Alfa']));
        
        $existingStudentIds = $attendances->pluck('student_id')->unique()->toArray();
        $belumAbsenList = $this->sortStudents(Student::with('schoolClass')->where('status', '!=', 'graduated')->whereNotIn('id', $existingStudentIds)->get());

        $stats = [
            'hadir' => $attendances->where('status', 'Hadir')->count(),
            'terlambat' => $attendances->where('status', 'Terlambat')->count(),
            'sakit' => $attendances->where('status', 'Sakit')->count(),
            'izin' => $attendances->where('status', 'Izin')->count(),
            'alfa' => $attendances->where('status', 'Alfa')->count(),
            'belum' => $belumAbsenList->count()
        ];

        return view('reports.print_daily', compact('selectedDate_db', 'attendancesHadir', 'attendancesLain', 'belumAbsenList', 'stats', 'range'));
    }
    
    // =========================================================================
    // 2. REKAP KEAGAMAAN
    // =========================================================================

    /**
     * PRIVATE METHOD: Mengambil semua data keagamaan.
     * Digunakan oleh Web View dan Print View agar data konsisten.
     */
   private function getReligiousData(Request $request)
    {
        $range = $this->getDateRange($request);
        $selectedDate_db = Carbon::parse($range['start']);
        $selectedActivity = $request->input('activity', 'Dhuha'); 

        // 1. DATA ATTENDANCE DETAIL (Hanya ambil sesuai Activity yang dipilih untuk list view)
        $attendances = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', function($q) { $q->where('status', '!=', 'graduated'); })
            ->whereBetween('attendance_date', [$range['start'], $range['end']])
            ->where('type', 'Keagamaan')
            ->where('activity', $selectedActivity) 
            ->orderBy('attendance_date', 'desc')
            ->get();

        $hadirCount = $attendances->where('status', 'Hadir')->count();
        $izinUzurCount = $attendances->whereIn('status', ["Uzur Syar'i", "Izin", "Sakit"])->count();
        $alfaCount = $attendances->where('status', 'Alfa')->count();

        // 2. MENCARI SISWA BELUM ABSEN
        $existingIds = $attendances->pluck('student_id')->unique()->toArray();
        $belumAbsenList = $this->sortStudents(Student::with('schoolClass')->where('status', '!=', 'graduated')->whereNotIn('id', $existingIds)->get());
        $belumAbsenCount = $belumAbsenList->count();

        $attendancesHadir = $this->sortStudents($attendances->where('status', 'Hadir'));
        $attendancesUzur = $this->sortStudents($attendances->whereIn('status', ["Uzur Syar'i", "Alfa", "Izin", "Sakit"]));

        // 3. REKAP PER KELAS (OPTIMIZED N+1 QUERY)
        // Ambil semua kelas
        $allClasses = SchoolClass::orderBy('name')->get();

        // Ambil Total Siswa per Kelas dalam 1 Query (Group By) agar tidak looping query
        $studentCounts = Student::where('status', '!=', 'graduated')
            ->select('class_id', DB::raw('count(*) as total'))
            ->groupBy('class_id')
            ->pluck('total', 'class_id');

        // Ambil Statistik Dhuha & Dhuhur sekaligus dalam 1 Query
        $rawStats = AttendanceSiswa::with('student')
            ->whereHas('student', function($q) { $q->where('status', '!=', 'graduated'); })
            ->whereBetween('attendance_date', [$range['start'], $range['end']])
            ->where('type', 'Keagamaan')
            ->whereIn('activity', ['Dhuha', 'Dhuhur'])
            ->get()
            ->groupBy(['student.class_id', 'activity', 'status']);

        $classRecap = $allClasses->map(function ($kelas) use ($range, $rawStats, $studentCounts) {
            // Menggunakan data yang sudah di-preload
            $totalSiswa = $studentCounts[$kelas->id] ?? 0;
            
            $getCount = function($activity, $status) use ($rawStats, $kelas) {
                return $rawStats->get($kelas->id)?->get($activity)?->get($status)?->count() ?? 0;
            };

            // Dhuha Data
            $dhuha_hadir = $getCount('Dhuha', 'Hadir');
            $dhuha_izin  = $getCount('Dhuha', 'Izin') + $getCount('Dhuha', 'Sakit') + $getCount('Dhuha', "Uzur Syar'i");
            $dhuha_alfa  = $getCount('Dhuha', 'Alfa');
            $dhuha_total = $dhuha_hadir + $dhuha_izin + $dhuha_alfa;
            
            // Dhuhur Data
            $dhuhur_hadir = $getCount('Dhuhur', 'Hadir');
            $dhuhur_izin  = $getCount('Dhuhur', 'Izin') + $getCount('Dhuhur', 'Sakit') + $getCount('Dhuhur', "Uzur Syar'i");
            $dhuhur_alfa  = $getCount('Dhuhur', 'Alfa');
            $dhuhur_total = $dhuhur_hadir + $dhuhur_izin + $dhuhur_alfa;

            // Perhitungan Persentase
            $d_percent = ($range['type'] === 'daily' ? $totalSiswa : $dhuha_total);
            $dhuha_percent = $d_percent > 0 ? round(($dhuha_hadir / $d_percent) * 100) : 0;

            $dh_percent = ($range['type'] === 'daily' ? $totalSiswa : $dhuhur_total);
            $dhuhur_percent = $dh_percent > 0 ? round(($dhuhur_hadir / $dh_percent) * 100) : 0;

            return (object) [
                'className' => $kelas->name,
                'total_siswa' => $totalSiswa,
                // Fallback field untuk kompatibilitas jika view print lama
                'hadir' => $dhuha_hadir, 
                'izin_sakit' => $dhuha_izin, 
                'alfa' => $dhuha_alfa, 
                'belum' => $totalSiswa - ($dhuha_hadir + $dhuha_izin + $dhuha_alfa), 
                'persentase' => $dhuha_percent, 
                'is_daily' => $range['type'] === 'daily',
                
                // Data Struktural Baru untuk Tampilan Web
                'dhuha' => ['hadir' => $dhuha_hadir, 'izin' => $dhuha_izin, 'alfa' => $dhuha_alfa, 'percent' => $dhuha_percent],
                'dhuhur' => ['hadir' => $dhuhur_hadir, 'izin' => $dhuhur_izin, 'alfa' => $dhuhur_alfa, 'percent' => $dhuhur_percent]
            ];
        });

        // 4. CHART DATA
        $chartStart = ($range['type'] === 'daily') ? Carbon::parse($range['end'])->subDays(6)->toDateString() : $range['start'];
        $chartEnd = $range['end'];
        $trendLabel = ($range['type'] === 'daily') ? "Tren 7 Hari Terakhir" : "Statistik Periode Ini";

        $rawTrendData = AttendanceSiswa::selectRaw('DATE(attendance_date) as date, status, count(*) as count')
            ->whereBetween('attendance_date', [$chartStart, $chartEnd])
            ->where('type', 'Keagamaan')
            ->where('activity', $selectedActivity) 
            ->groupBy('date', 'status')
            ->get();

        $trendDates = []; $dataHadir = []; $dataTidakHadir = [];
        $period = \Carbon\CarbonPeriod::create($chartStart, $chartEnd);
        foreach ($period as $date) {
            $d = $date->format('Y-m-d');
            $trendDates[] = $date->format('d M');
            $dataHadir[] = $rawTrendData->where('date', $d)->where('status', 'Hadir')->sum('count');
            $dataTidakHadir[] = $rawTrendData->where('date', $d)->whereIn('status', ['Sakit', 'Izin', "Uzur Syar'i", 'Alfa'])->sum('count');
        }

        $chartData = [
            'labels' => $trendDates,
            'series' => [['name' => 'Hadir', 'data' => $dataHadir], ['name' => 'Tidak Hadir/Uzur', 'data' => $dataTidakHadir]],
            'trendLabel' => $trendLabel,
            'composition' => ['hadir' => $hadirCount, 'uzur' => $izinUzurCount, 'alfa' => $alfaCount, 'belum' => $belumAbsenCount]
        ];
        
        // Pass All Classes for checklist mode
        $allClasses = SchoolClass::orderBy('name')->get();

        // Return array of data
        return compact(
            'selectedDate_db', 'selectedActivity', 'attendancesHadir', 'attendancesUzur',
            'belumAbsenList', 'hadirCount', 'izinUzurCount', 'alfaCount', 'belumAbsenCount', 'range',
            'classRecap', 'chartData', 'allClasses'
        );
    }

    /**
     * DASHBOARD VIEW KEAGAMAAN
     */
   public function religiousReport(Request $request)
    {
        $data = $this->getReligiousData($request);
        
        // Paginate manual untuk view Dashboard agar tidak berat saat load page
        $data['attendancesHadir'] = $this->paginate($data['attendancesHadir'], 20, null, ['pageName' => 'page_hadir'])->appends($request->all());
        $data['attendancesUzur'] = $this->paginate($data['attendancesUzur'], 20, null, ['pageName' => 'page_uzur'])->appends($request->all());
                
        $data['belumAbsenList'] = $this->paginate($data['belumAbsenList'], 20, null, ['pageName' => 'page_belum'])->appends($request->all());
        
        return view('reports.religious', $data);
    }


    /**
     * PRINT VIEW KEAGAMAAN
     * Perbaikan: Return view print_religious, bukan redirect ke dashboard.
     */
    public function printReligious(Request $request)
    {
        // Ambil data yang sama persis dengan Dashboard
        $data = $this->getReligiousData($request);
        
        // Tambahkan variabel viewMode untuk logic di Blade
        $data['viewMode'] = $request->view_mode ?? 'list';

        return view('reports.print_religious', $data);
    }

    // =========================================================================
    // 3. FITUR REKAPITULASI KELAS (SUMMARY & MATRIX VIEW)
    // =========================================================================

    /** * Mengisi variabel $reportData untuk view 'reports.class_attendance'
     */
     public function indexClass(Request $request)
    {
        // Default tanggal: 1 bulan terakhir atau bulan berjalan
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        // Ambil data kelas + siswa aktif + absensi mereka dalam range tanggal       
        $classes = SchoolClass::with(['students' => function($q) {
                $q->where('status', 'active'); 
            }, 'students.attendances' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('attendance_date', [$startDate, $endDate])
                  ->whereIn('type', ['Harian', 'Masuk']);
            }])
            ->orderBy('name')
            ->get();

        // Proses kalkulasi rekapitulasi
        // REVISI: Mengembalikan object stdClass eksplisit agar properti 'hadir' dkk terbaca di View
        $reportData = $classes->map(function ($kelas) {
            $hadir = 0;
            $telat = 0;
            $izin_sakit = 0;
            $alpha = 0;

            foreach ($kelas->students as $student) {
                // Hitung dari collection yang sudah di-eager-load (Memory efficient)
                $hadir += $student->attendances->where('status', 'Hadir')->count();
                $telat += $student->attendances->where('status', 'Terlambat')->count();
                $izin_sakit += $student->attendances->whereIn('status', ['Izin', 'Sakit', "Uzur Syar'i"])->count();
                $alpha += $student->attendances->whereIn('status', ['Alfa', 'Alpa', 'Alpha'])->count();
            }

            // FIX: Return object baru yang bersih untuk memastikan properti tersedia 
            // dan menghindari error Undefined property pada stdClass
            return (object) [
                'id' => $kelas->id,
                'name' => $kelas->name,
                'total_students' => $kelas->students->count(),
                'hadir' => $hadir,
                'telat' => $telat,
                'izin_sakit' => $izin_sakit,
                'alpha' => $alpha
            ];
        });

        // Kirim ke View 'class_attendance.blade.php'       
        return view('reports.class_attendance', compact('reportData', 'startDate', 'endDate'));
    }
    
    private function getClassReportData(Request $request)
    {
        $monthStr = $request->input('month', Carbon::now()->format('Y-m'));
        $classId = $request->input('class_id');
        
        $startDate = Carbon::parse($monthStr . '-01')->startOfMonth();
        $endDate = Carbon::parse($monthStr . '-01')->endOfMonth();
        
        $dates = [];
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $dates[] = $date;
        }

        $classes = SchoolClass::orderBy('name')->get();
        $students = collect([]);
        $selectedClass = null;

        if ($classId) {
            $selectedClass = SchoolClass::find($classId);

            $students = Student::whereHas('schoolClass', function($q) use ($classId) {
                $q->where('id', $classId);
            })
            ->where('status', '!=', 'graduated')
            ->orderBy('name')
            ->get();

            $attendances = AttendanceSiswa::whereIn('student_id', $students->pluck('id'))
                ->whereBetween('attendance_date', [$startDate->toDateString(), $endDate->toDateString()])
                ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
                ->get()
                ->groupBy('student_id');

            foreach ($students as $student) {
                $attendanceMap = [];
                // PERBAIKAN: Ganti kunci H_half menjadi B (Bolos) untuk Masuk tapi Tidak Pulang
                $summary = ['H' => 0, 'B' => 0, 'S' => 0, 'I' => 0, 'A' => 0];
                $studentAttendances = $attendances->get($student->id, collect());

                foreach ($dates as $date) {
                    $dateStr = $date->format('Y-m-d');
                    $record = $studentAttendances->where('attendance_date', $dateStr)->first();
                    
                    // LOGIKA BARU: Dua kolom per tanggal (Masuk & Pulang)
                    $inCode = '-'; $outCode = '-'; 
                    $inColor = 'text-slate-300'; $outColor = 'text-slate-300';
                    
                    if ($record) {
                        $isHalfDay = empty($record->time_out) || $record->time_out == '00:00:00';

                        switch ($record->status) {
                            case 'Hadir':
                                $inCode = 'H'; $inColor = 'text-emerald-600 font-bold';
                                if ($isHalfDay) {
                                    $outCode = 'B'; $outColor = 'text-rose-500 font-bold'; 
                                    $summary['B']++; // Bolos pulang
                                } else {
                                    $outCode = 'H'; $outColor = 'text-emerald-600 font-bold'; 
                                    $summary['H']++;
                                }
                                break;
                            case 'Terlambat':
                                $inCode = 'T'; $inColor = 'text-amber-600 font-bold';
                                if ($isHalfDay) {
                                    $outCode = 'B'; $outColor = 'text-rose-500 font-bold'; 
                                    $summary['B']++; // Bolos pulang
                                } else {
                                    $outCode = 'H'; $outColor = 'text-emerald-600 font-bold'; 
                                    $summary['H']++;
                                }
                                break; 
                            case 'Sakit':
                                $inCode = 'S'; $outCode = 'S';
                                $inColor = 'text-blue-600 font-bold'; $outColor = 'text-blue-600 font-bold';
                                $summary['S']++; break;
                            case 'Izin':
                                $inCode = 'I'; $outCode = 'I';
                                $inColor = 'text-indigo-600 font-bold'; $outColor = 'text-indigo-600 font-bold';
                                $summary['I']++; break;
                            case 'Alfa':
                            case 'Alpa':
                            case 'Alpha':
                                $inCode = 'A'; $outCode = 'A';
                                $inColor = 'text-rose-600 font-bold'; $outColor = 'text-rose-600 font-bold';
                                $summary['A']++; break;
                        }
                    }

                    if ($inCode === '-' && ($date->isSaturday() || $date->isSunday())) {
                         $inCode = ''; $outCode = '';
                         $inColor = 'bg-gray-200'; $outColor = 'bg-gray-200';
                    }

                    $attendanceMap[$dateStr] = [
                        'in_code' => $inCode, 'in_class' => $inColor,
                        'out_code' => $outCode, 'out_class' => $outColor
                    ];
                }

                $student->attendance_map = $attendanceMap;
                $student->summary = $summary;
            }
        }

        return compact('classes', 'classId', 'students', 'dates', 'monthStr', 'startDate', 'selectedClass');
    }

    /**
     * Menampilkan Detail Matrix Absensi per Kelas (Detail Page)
     */
    public function classReport(Request $request)
    {
        $data = $this->getClassReportData($request);
        return view('reports.class_report', $data);
    }

    public function printClassReport(Request $request)
    {
        $data = $this->getClassReportData($request);
        if(!$data['classId']) return redirect()->back()->with('error', 'Pilih kelas terlebih dahulu');
        
        return view('reports.print_class_report', $data);
    }
    
   /**
     * Export Excel Rekapitulasi Kelas (Sudah Diaktifkan)
     */
    public function exportClassExcel(Request $request)
    {
        // 1. Ambil tanggal dari request
        $startDate = $request->input('start_date', \Carbon\Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', \Carbon\Carbon::now()->toDateString());

        // 2. Download file Excel menggunakan class Export
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\ClassAttendanceExport($startDate, $endDate), 
            'Rekapitulasi_Kelas_' . $startDate . '_sd_' . $endDate . '.xlsx'
        );
    }

    /**
     * Stub untuk Print Summary
     */
    public function printClassSummary(Request $request)
    {
        // Menggunakan logic indexClass, bisa diarahkan ke view khusus print jika ada
        return $this->indexClass($request); 
    }

    // =========================================================================
    // 4. TEACHING JOURNAL (LOGIKA LAMA UTUH)
    // =========================================================================

    public function teachingJournal(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        
        $teacherId = $request->input('teacher_id');
        $classId = $request->input('class_id');
        $subjectId = $request->input('subject_id');

        $query = TeachingSession::with(['teacher', 'schedule.schoolClass', 'schedule.subject'])
            ->withCount([
                'attendances as hadir_count' => function ($q) { $q->where('status', 'present'); },
                'attendances as late_count' => function ($q) { $q->where('status', 'late'); },
                'attendances as alpha_count' => function ($q) { $q->where('status', 'alpha'); },
            ])
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->orderBy('started_at', 'desc');

        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }
        if ($classId) {
            $query->whereHas('schedule', function($q) use ($classId) {
                $q->where('school_class_id', $classId);
            });
        }
        if ($subjectId) {
            $query->whereHas('schedule', function($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            });
        }

        $sessions = $query->paginate(20)->withQueryString();

        $teachers = User::whereIn('role', ['Guru', 'Wali Kelas', 'Kepala Sekolah'])->orderBy('name')->get();
        if($teachers->isEmpty()) $teachers = User::all();
        
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('reports.teaching_journal', compact(
            'sessions', 'teachers', 'classes', 'subjects', 
            'startDate', 'endDate', 'teacherId', 'classId', 'subjectId'
        ));
    }

    // =========================================================================
    // 5. OPERASI CRUD & HELPERS (LOGIKA LAMA UTUH)
    // =========================================================================

    public function bulkAlpha(Request $request)
    {
        $date = $request->input('date');
        $type = $request->input('type');
        $activity = $request->input('activity');

        // PERBAIKAN: Pisahkan nama pelanggaran dan poin untuk tombol Alfa Massal
        $violationName = ($type == 'Keagamaan') ? 'Tidak Ikut Kegiatan Keagamaan' : 'Tidak Masuk Sekolah (Alfa)';
        $violationPoints = ($type == 'Keagamaan') ? 5 : 10;

        $violationType = DisciplineType::firstOrCreate(
            ['name' => $violationName],
            [
                'type' => 'Pelanggaran', 
                'point_value' => $violationPoints
            ]
        );

        return DB::transaction(function () use ($date, $type, $activity, $violationType) {
            $query = AttendanceSiswa::whereDate('attendance_date', $date)
                    ->where('type', $type);
            
            if($type == 'Keagamaan' && $activity) {
                $query->where('activity', $activity);
            }

            $presentIds = $query->pluck('student_id')->toArray();
            
            $absentStudents = Student::where('status', '!=', 'graduated')
                ->whereNotIn('id', $presentIds)
                ->get();

            $insertData = [];
            $now = now();
            
            foreach ($absentStudents as $student) {
                $insertData[] = [
                    'student_id' => $student->id,
                    'attendance_date' => $date,
                    'type' => $type,
                    'activity' => $activity,
                    'status' => 'Alfa',
                    'time_in' => '00:00:00', 
                    'time_out' => null,
                    'notes' => 'Otomatis oleh Sistem',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                
                $this->handleAutoPunishment($student->id, $date, 'Alfa', $type, $violationType);
            }

            if (!empty($insertData)) {
                AttendanceSiswa::insert($insertData);
            }
            
            $existingAlphas = AttendanceSiswa::whereDate('attendance_date', $date)
                ->where('type', $type)
                ->whereIn('status', ['Alfa', 'Alpa', 'Alpha']);
            
            if($type == 'Keagamaan' && $activity) {
                $existingAlphas->where('activity', $activity);
            }

            foreach ($existingAlphas->get() as $existing) {            
                $this->handleAutoPunishment($existing->student_id, $date, 'Alfa', $type, $violationType);
            }

            return back()->with('success', count($insertData) . ' siswa baru ditandai Alfa (Data lama disinkronisasi).');
        });
    }

    public function storeManualEntry(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'date' => 'required|date',
            'status' => 'required',
            'attendance_type' => 'required'
        ]);
       
        $defaultTime = '00:00:00'; 
        
        $inputTime = $request->time_in ? $request->time_in : now()->format('H:i:s');
        
        $timeIn = in_array($request->status, ['Hadir', 'Terlambat']) 
                  ? $inputTime 
                  : $defaultTime;

        $attendance = AttendanceSiswa::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'attendance_date' => $request->date,
                'type' => $request->attendance_type,
                'activity' => $request->activity 
            ],
            [
                'status' => $request->status,
                'time_in' => $timeIn,
                'time_out' => $request->time_out,
                'notes' => $request->notes,
            ]
        );
    
        $this->handleAutoPunishment($request->student_id, $request->date, $request->status, $request->attendance_type);

        try {
            SendWaManualNotificationJob::dispatch($attendance);
        } catch (\Exception $e) {
            Log::error("Gagal dispatch WA Manual: " . $e->getMessage());
        }

        return back()->with('success', 'Data absensi berhasil disimpan dan notifikasi diproses.');
    }

    public function updateAttendance(Request $request, $id)
    {
        $att = AttendanceSiswa::findOrFail($id);        
       
        $defaultTime = '00:00:00';
                
        $existingOrNow = $att->time_in ?? now()->format('H:i:s');
        $inputTime = $request->time_in ? $request->time_in : $existingOrNow;

        $timeIn = in_array($request->status, ['Hadir', 'Terlambat']) 
                  ? $inputTime 
                  : $defaultTime;

        $att->update([
            'status' => $request->status,
            'time_in' => $timeIn,
            'time_out' => $request->time_out,
            'notes' => $request->notes
        ]);

        $this->handleAutoPunishment($att->student_id, $att->attendance_date, $request->status, $att->type);

        return back()->with('success', 'Data berhasil diperbarui.');
    }

    public function destroyReligious(Request $request)
    {
        $date = $request->date;
        $activity = $request->activity;

        AttendanceSiswa::whereDate('attendance_date', $date)
            ->where('type', 'Keagamaan')
            ->where('activity', $activity)
            ->delete();

        return back()->with('success', "Semua data $activity tanggal $date berhasil direset.");
    }

    public function getStudentReligiousHistory(Request $request)
    {
        $studentId = $request->student_id;
        $activity = $request->activity; 
        
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        $student = Student::with('schoolClass')->find($studentId);
        
        if (!$student) {
            return '<div class="p-4 text-center text-rose-500">Siswa tidak ditemukan.</div>';
        }

        $histories = AttendanceSiswa::where('student_id', $studentId)
            ->where('type', 'Keagamaan')
            ->where('activity', $activity)
            ->whereBetween('attendance_date', [$startOfMonth, $endOfMonth])
            ->orderBy('attendance_date', 'desc')
            ->get();

        $html = '<div class="p-4 space-y-3">';
        $html .= '<div class="flex items-center gap-3 mb-4 bg-slate-50 p-3 rounded-xl border border-slate-100">';
        $html .= '<div class="w-10 h-10 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center font-bold text-sm shrink-0">'.substr($student->name,0,1).'</div>';
        $html .= '<div><div class="font-bold text-slate-800 text-sm">'.$student->name.'</div><div class="text-xs text-slate-500">'.$student->schoolClass->name.' • '.$activity.' Bulan Ini</div></div>';
        $html .= '</div>';

        if ($histories->count() > 0) {
            $html .= '<div class="space-y-2">';
            foreach ($histories as $h) {
                $color = match($h->status) {
                    'Hadir' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
                    'Alfa' => 'text-rose-600 bg-rose-50 border-rose-100',
                    'Sakit' => 'text-blue-600 bg-blue-50 border-blue-100',
                    'Izin' => 'text-amber-600 bg-amber-50 border-amber-100',
                    default => 'text-slate-600 bg-slate-50 border-slate-100'
                };
                
                $date = Carbon::parse($h->attendance_date)->translatedFormat('d F Y');
                $jam = Carbon::parse($h->created_at)->format('H:i');

                $html .= '<div class="flex justify-between items-center p-3 rounded-xl border '.$color.'">';
                $html .= '<div><div class="text-[10px] font-bold opacity-70 uppercase tracking-wider">'.$date.'</div><div class="font-bold text-sm">'.$h->status.'</div></div>';
                if($h->status == 'Hadir') {
                    $html .= '<div class="text-xs font-bold bg-white/60 px-2 py-1 rounded flex items-center gap-1"><i class=\"ph-bold ph-clock\"></i> '.$jam.'</div>';
                }
                $html .= '</div>';
            }
            $html .= '</div>';
        } else {
            $html .= '<div class="text-center py-6 text-slate-400 italic text-sm">Belum ada riwayat bulan ini.</div>';
        }
        $html .= '</div>';
        return $html;
    }

     // =========================================================================
    // FITUR BARU: MODE CHECKLIST PER KELAS (DINAMIS UNTUK HARIAN & KEAGAMAAN)
    // =========================================================================

    /**
     * API untuk mengambil daftar siswa di kelas tertentu beserta status absen hari ini
     */
    public function getStudentsByClass(Request $request)
    {
        $classId = $request->class_id;
        $date = $request->date;
        $type = $request->type ?? 'Keagamaan'; // Default Keagamaan untuk kompatibilitas lama
        $activity = $request->activity; 

        if (!$classId || !$date) {
            return response()->json(['error' => 'Parameter tidak lengkap'], 400);
        }

        $students = Student::where('class_id', $classId)
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();

        // Ambil data absensi yang sudah ada (Dinamis berdasarkan Tipe)
        $query = AttendanceSiswa::whereIn('student_id', $students->pluck('id'))
            ->where('attendance_date', $date)
            ->where('type', $type);
            
        // Jika tipe Keagamaan, filter spesifik activity (Dhuha/Dhuhur)
        if ($type == 'Keagamaan' && $activity) {
            $query->where('activity', $activity);
        }

        $attendances = $query->get()->keyBy('student_id');

        $data = $students->map(function ($student) use ($attendances) {
            $att = $attendances->get($student->id);
            return [
                'id' => $student->id,
                'name' => $student->name,
                'status' => $att ? $att->status : 'Hadir', // Default 'Hadir'
                'notes' => $att ? $att->notes : '',
                'has_record' => $att ? true : false 
            ];
        });

        return response()->json($data);
    }

    /**
     * Menyimpan data checklist satu kelas sekaligus
     */
    public function storeClassAttendance(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'date' => 'required|date',
            'students' => 'required|array',
            'type' => 'nullable'
        ]);

        $date = $request->date;
        $type = $request->input('type', 'Keagamaan'); // Ambil tipe dari form
        $activity = $request->activity;
        
        // PERBAIKAN: Pisahkan nama pelanggaran dan poin untuk input Checklist
        $violationName = ($type == 'Keagamaan') ? 'Tidak Ikut Kegiatan Keagamaan' : 'Tidak Masuk Sekolah (Alfa)';
        $violationPoints = ($type == 'Keagamaan') ? 5 : 10;

        $violationType = DisciplineType::firstOrCreate(
            ['name' => $violationName],
            ['type' => 'Pelanggaran', 'point_value' => $violationPoints]
        );

        DB::transaction(function () use ($request, $date, $activity, $type, $violationType) {
            foreach ($request->students as $item) {
                // Kondisi pencarian data (Where Clause)
                $conditions = [
                    'student_id' => $item['id'],
                    'attendance_date' => $date,
                    'type' => $type
                ];
                
                // Tambahkan activity hanya jika Keagamaan
                if ($type == 'Keagamaan') {
                    $conditions['activity'] = $activity;
                }

                // Tentukan Jam Masuk (Jika Hadir/Telat set NOW, jika tidak 00:00:00)
                $timeIn = '00:00:00';
                if(in_array($item['status'], ['Hadir', 'Terlambat'])) {
                    // Cek apakah sudah ada jam masuk sebelumnya agar tidak tertimpa
                    $existing = AttendanceSiswa::where($conditions)->first();
                    $timeIn = $existing && $existing->time_in != '00:00:00' ? $existing->time_in : now()->format('H:i:s');
                }

                // Update atau Create Absensi
                AttendanceSiswa::updateOrCreate(
                    $conditions,
                    [
                        'status' => $item['status'],
                        'time_in' => $timeIn,
                        'notes' => $item['notes'] ?? null,
                    ]
                );

                $this->handleAutoPunishment($item['id'], $date, $item['status'], $type, $violationType);
            }
        });

        return back()->with('success', 'Data absensi kelas berhasil disimpan!');
    }
}