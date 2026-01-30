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
    /**
     * Helper untuk menentukan rentang tanggal berdasarkan input request.
     * PERBAIKAN: Menambahkan default value jika input kosong agar tidak fallback ke daily.
     */
    private function getDateRange(Request $request)
    {
        $reportType = $request->input('report_type', 'daily');
        $start = null;
        $end = null;
        $label = "";

        // Logika Mingguan
        if ($reportType === 'weekly') {
            // Jika user tidak pilih minggu, default ke minggu ini
            $weekStr = $request->input('week', Carbon::now()->format('Y-\WW'));
            
            // Format input HTML type="week" adalah 2024-W01
            $parts = explode('-W', $weekStr);
            // Validasi format
            if(count($parts) === 2) {
                $year = $parts[0];
                $week = $parts[1];
                $dt = Carbon::now()->setISODate($year, $week);
                $start = $dt->startOfWeek()->toDateString();
                $end = $dt->endOfWeek()->toDateString();
                $label = "Minggu ke-" . $week . " Tahun " . $year . " (" . $dt->startOfWeek()->format('d M') . " - " . $dt->endOfWeek()->format('d M Y') . ")";
            } else {
                // Fallback jika format salah
                $start = Carbon::now()->startOfWeek()->toDateString();
                $end = Carbon::now()->endOfWeek()->toDateString();
                $label = "Minggu Ini";
            }
        } 
        // Logika Bulanan
        elseif ($reportType === 'monthly') {
            // Jika user tidak pilih bulan, default ke bulan ini
            $monthStr = $request->input('month', Carbon::now()->format('Y-m'));
            
            $dt = Carbon::parse($monthStr . '-01');
            $start = $dt->startOfMonth()->toDateString();
            $end = $dt->endOfMonth()->toDateString();
            $label = "Bulan " . $dt->translatedFormat('F Y');
        } 
        // Logika Harian (Default)
        else {
            $reportType = 'daily'; // Paksa set daily jika bukan weekly/monthly
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
     */
    public function paginate($items, $perPage = 20, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $options = array_merge(['path' => Paginator::resolveCurrentPath()], $options);

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
            // Sort by Date (desc) then Class then Name agar data history urut tanggal
            $date = $item->attendance_date ?? '0000-00-00';
            return $date . $className . ' ' . $student->name;
        }, SORT_NATURAL | SORT_FLAG_CASE)->values();
    }

    private function handleAutoPunishment($studentId, $date, $status, $typeContext = 'Harian', $preloadedViolationType = null)
    {
        if (!in_array($status, ['Alfa', 'Alpa', 'Alpha'])) return;

        $violationType = $preloadedViolationType ?: DisciplineType::where('type', 'Pelanggaran')
            ->where(function($q) {
                $q->where('name', 'Tidak Masuk Sekolah (Alfa)')
                  ->orWhere('name', 'Alfa');
            })->first();

        if (!$violationType) {
            $violationType = DisciplineType::create([
                'name' => 'Tidak Masuk Sekolah (Alfa)',
                'type' => 'Pelanggaran',
                'point_value' => 10
            ]);
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

    // --- REKAP ABSENSI HARIAN ---
     public function dailyReport(Request $request)
    {
        $range = $this->getDateRange($request);
        $selectedDate_db = Carbon::parse($range['start']);

        $attendances = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', function($q) { $q->where('status', '!=', 'graduated'); })
            ->whereBetween('attendance_date', [$range['start'], $range['end']])
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->orderBy('attendance_date', 'desc') // Urutkan tanggal terbaru
            ->get();

        $hadirCount = $attendances->where('status', 'Hadir')->count();
        $terlambatCount = $attendances->where('status', 'Terlambat')->count();
        $sakitCount = $attendances->where('status', 'Sakit')->count();
        $izinCount = $attendances->where('status', 'Izin')->count();
        $alfaCount = $attendances->where('status', 'Alfa')->count();

        // Untuk "Belum Absen", kita hanya cek jika ini mode HARIAN.
        // Jika mingguan/bulanan, "Belum Absen" kurang relevan ditampilkan sebagai list kecuali siswa yang tidak hadir sama sekali dalam periode tersebut.
        // Di sini kita tetap load logicnya tapi filter berdasarkan hari ini jika range-nya daily.
        
        $existingStudentIds = $attendances->pluck('student_id')->unique()->toArray();
        
        // Jika range lebih dari 1 hari, logika "Belum Absen" akan menampilkan siswa yang BELUM PERNAH absen sekalipun dalam range itu.
        $belumAbsenListRaw = Student::with('schoolClass')
            ->where('status', '!=', 'graduated')
            ->whereNotIn('id', $existingStudentIds)
            ->get();
            
        $belumAbsenList = $this->sortStudents($belumAbsenListRaw);

        $mappedHadir = $this->sortStudents($attendances->whereIn('status', ['Hadir', 'Terlambat']));
        $mappedLain = $this->sortStudents($attendances->whereIn('status', ['Sakit', 'Izin', 'Alfa']));

        $attendancesHadir = $this->paginate($mappedHadir, 20)->appends($request->all());
        $attendancesLain = $this->paginate($mappedLain, 20)->appends($request->all());

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
    
    // --- REKAP KEAGAMAAN ---
    public function religiousReport(Request $request)
    {
        $range = $this->getDateRange($request);
        $selectedDate_db = Carbon::parse($range['start']);
        $selectedActivity = $request->input('activity', 'Dhuha'); 

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

        $existingIds = $attendances->pluck('student_id')->unique()->toArray();
        $belumAbsenList = $this->sortStudents(Student::with('schoolClass')->where('status', '!=', 'graduated')->whereNotIn('id', $existingIds)->get());
        $belumAbsenCount = $belumAbsenList->count();

        $attendancesHadir = $this->paginate($this->sortStudents($attendances->where('status', 'Hadir')), 20)->appends($request->all());
        $attendancesUzur = $this->paginate($this->sortStudents($attendances->whereIn('status', ["Uzur Syar'i", "Alfa", "Izin", "Sakit"])), 20)->appends($request->all());

        return view('reports.religious', compact(
            'selectedDate_db', 'selectedActivity', 'attendancesHadir', 'attendancesUzur',
            'belumAbsenList', 'hadirCount', 'izinUzurCount', 'alfaCount', 'belumAbsenCount', 'range'
        ));
    }

     public function printReligious(Request $request)
    {
        $range = $this->getDateRange($request);
        $selectedDate_db = Carbon::parse($range['start']);
        $selectedActivity = $request->input('activity', 'Dhuha'); 

        $attendances = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', function($q) { $q->where('status', '!=', 'graduated'); })
            ->whereBetween('attendance_date', [$range['start'], $range['end']])
            ->where('type', 'Keagamaan')
            ->where('activity', $selectedActivity)
            ->orderBy('attendance_date', 'asc')
            ->get();

        $attendancesHadir = $this->sortStudents($attendances->where('status', 'Hadir'));
        $attendancesUzur = $this->sortStudents($attendances->whereIn('status', ["Uzur Syar'i", "Alfa", "Izin", "Sakit"]));

        $hadirCount = $attendancesHadir->count();
        $izinUzurCount = $attendancesUzur->whereIn('status', ["Uzur Syar'i", "Izin", "Sakit"])->count();
        $alfaCount = $attendances->where('status', 'Alfa')->count();

        $existingIds = $attendances->pluck('student_id')->unique()->toArray();
        $belumAbsenList = $this->sortStudents(Student::with('schoolClass')->where('status', '!=', 'graduated')->whereNotIn('id', $existingIds)->get());
        $belumAbsenCount = $belumAbsenList->count();

        return view('reports.print_religious', compact(
            'selectedDate_db', 'selectedActivity', 'attendancesHadir', 'attendancesUzur',
            'belumAbsenList', 'hadirCount', 'izinUzurCount', 'alfaCount', 'belumAbsenCount', 'range'
        ));
    }

    // --- FITUR BARU: REKAP PER KELAS (MATRIX VIEW) ---

    /**
     * Helper private untuk mengambil data laporan kelas
     * Digunakan oleh classReport (Web) dan printClassReport (Cetak)
     */
    private function getClassReportData(Request $request)
    {
        $monthStr = $request->input('month', Carbon::now()->format('Y-m'));
        $classId = $request->input('class_id');
        
        $startDate = Carbon::parse($monthStr . '-01')->startOfMonth();
        $endDate = Carbon::parse($monthStr . '-01')->endOfMonth();
        
        // 1. Generate Array Tanggal (Header Tabel)
        $dates = [];
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $dates[] = $date;
        }

        // 2. Ambil List Kelas untuk Dropdown
        $classes = SchoolClass::orderBy('name')->get();

        // 3. Ambil Data Siswa & Absensi (Jika Kelas Dipilih)
        $students = collect([]);
        $selectedClass = null;

        if ($classId) {
            $selectedClass = SchoolClass::find($classId);

            // Ambil semua siswa di kelas tersebut (Gunakan whereHas untuk keamanan relasi)
            $students = Student::whereHas('schoolClass', function($q) use ($classId) {
                $q->where('id', $classId);
            })
            ->where('status', '!=', 'graduated') // Filter siswa aktif
            ->orderBy('name')
            ->get();

            // Ambil semua data absensi dalam rentang tanggal & kelas
            $attendances = AttendanceSiswa::whereIn('student_id', $students->pluck('id'))
                ->whereBetween('attendance_date', [$startDate->toDateString(), $endDate->toDateString()])
                ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
                ->get()
                ->groupBy('student_id');

            // Mapping Absensi ke Siswa
            foreach ($students as $student) {
                $attendanceMap = [];
                $summary = ['H' => 0, 'S' => 0, 'I' => 0, 'A' => 0];
                $studentAttendances = $attendances->get($student->id, collect());

                foreach ($dates as $date) {
                    $dateStr = $date->format('Y-m-d');
                    $record = $studentAttendances->where('attendance_date', $dateStr)->first();
                    
                    $code = '-'; 
                    $color = 'text-slate-300';
                    
                    if ($record) {
                        switch ($record->status) {
                            case 'Hadir':
                                $code = 'H'; $color = 'text-black font-bold'; $summary['H']++; break;
                            case 'Terlambat':
                                $code = 'T'; $color = 'text-black font-bold'; $summary['H']++; break; // Terlambat dihitung Hadir
                            case 'Sakit':
                                $code = 'S'; $color = 'text-black font-bold'; $summary['S']++; break;
                            case 'Izin':
                                $code = 'I'; $color = 'text-black font-bold'; $summary['I']++; break;
                            case 'Alfa':
                            case 'Alpa':
                            case 'Alpha':
                                $code = 'A'; $color = 'text-black font-bold'; $summary['A']++; break;
                        }
                    }

                    if ($code === '-' && ($date->isSaturday() || $date->isSunday())) {
                         $code = ''; 
                         $color = 'bg-gray-200';
                    }

                    $attendanceMap[$dateStr] = ['code' => $code, 'class' => $color];
                }

                $student->attendance_map = $attendanceMap;
                $student->summary = $summary;
            }
        }

        return compact('classes', 'classId', 'students', 'dates', 'monthStr', 'startDate', 'selectedClass');
    }

    public function classReport(Request $request)
    {
        $data = $this->getClassReportData($request);
        return view('reports.class_report', $data);
    }

    public function printClassReport(Request $request)
    {
        $data = $this->getClassReportData($request);
        // Pastikan kelas dipilih sebelum cetak
        if(!$data['classId']) return redirect()->back()->with('error', 'Pilih kelas terlebih dahulu');
        
        return view('reports.print_class_report', $data);
    }

    // --- FITUR LAIN (TIDAK BERUBAH) ---
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

    public function bulkAlpha(Request $request)
    {
        $date = $request->input('date');
        $type = $request->input('type');
        $activity = $request->input('activity');

        $violationType = DisciplineType::firstOrCreate(
            ['name' => 'Tidak Masuk Sekolah (Alfa)'],
            [
                'type' => 'Pelanggaran', 
                'point_value' => 10
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
}