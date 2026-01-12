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
// TAMBAHAN: Import Job Notifikasi Manual
use App\Jobs\SendWaManualNotificationJob; 

class ReportController extends Controller
{
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

    /**
     * 1. REKAP ABSENSI HARIAN (Web View)
     */
    public function dailyReport(Request $request)
    {
        $dateStr = $request->input('date', Carbon::today()->toDateString());
        $selectedDate_db = Carbon::parse($dateStr); 

        // [FIX] Ambil Data Absensi HANYA untuk siswa AKTIF (bukan alumni)
        $attendances = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', function($q) {
                $q->where('status', '!=', 'graduated');
            })
            ->whereDate('attendance_date', $selectedDate_db)
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->get();

        $attendancesHadirRaw = $attendances->whereIn('status', ['Hadir', 'Terlambat']);
        $attendancesLainRaw = $attendances->whereIn('status', ['Sakit', 'Izin', 'Alfa']);

        // Hitung Statistik
        $hadirCount = $attendances->where('status', 'Hadir')->count();
        $terlambatCount = $attendances->where('status', 'Terlambat')->count();
        $sakitCount = $attendances->where('status', 'Sakit')->count();
        $izinCount = $attendances->where('status', 'Izin')->count();
        $alfaCount = $attendances->where('status', 'Alfa')->count();

        // [FIX] Data Belum Absen: Hanya ambil siswa yang statusnya != graduated
        $existingStudentIds = $attendances->pluck('student_id')->toArray();
        $belumAbsenListRaw = Student::with('schoolClass')
            ->where('status', '!=', 'graduated') // Filter Alumni
            ->whereNotIn('id', $existingStudentIds)
            ->get();
            
        // SORTING: Kelas dulu, baru Nama
        $belumAbsenList = $belumAbsenListRaw->sortBy([
            fn ($q) => $q->schoolClass->name ?? 'ZZZ', 
            fn ($q) => $q->name                        
        ])->values();

        // Mapping Data View
        $mappedHadir = $attendancesHadirRaw->map(function($item) {
            $item->status_final = $item->status;
            $item->time_in_final = $item->time_in;
            $item->time_out_final = $item->time_out;
            $item->notes_final = $item->notes;
            return $item;
        });

        $mappedLain = $attendancesLainRaw->map(function($item) {
            $item->status_final = $item->status;
            $item->notes_final = $item->notes;
            return $item;
        });

        // SORTING DATA HADIR (Kelas -> Nama)
        $sortedHadir = $mappedHadir->sortBy([
            fn ($q) => $q->student->schoolClass->name ?? 'ZZZ',
            fn ($q) => $q->student->name
        ])->values();

        // SORTING DATA LAIN (Kelas -> Nama)
        $sortedLain = $mappedLain->sortBy([
            fn ($q) => $q->student->schoolClass->name ?? 'ZZZ',
            fn ($q) => $q->student->name
        ])->values();

        // Pagination
        $attendancesHadir = $this->paginate($sortedHadir, 20);
        $attendancesLain = $this->paginate($sortedLain, 20);
        
        $attendancesHadir->appends($request->all());
        $attendancesLain->appends($request->all());

        return view('reports.daily', compact(
            'selectedDate_db',
            'attendancesHadir',
            'attendancesLain',
            'belumAbsenList',
            'hadirCount',
            'terlambatCount',
            'sakitCount',
            'izinCount',
            'alfaCount'
        ));
    }

    /**
     * 2. CETAK LAPORAN HARIAN (Print View)
     */
    public function printDaily(Request $request)
    {
        $dateStr = $request->input('date', Carbon::today()->toDateString());
        $selectedDate_db = Carbon::parse($dateStr); 

        // [FIX] Filter Alumni di Print
        $attendances = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', function($q) {
                $q->where('status', '!=', 'graduated');
            })
            ->whereDate('attendance_date', $selectedDate_db)
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->get();

        // Grouping & SORTING (Kelas -> Nama)
        $attendancesHadir = $attendances->whereIn('status', ['Hadir', 'Terlambat'])
            ->sortBy([
                fn ($q) => $q->student->schoolClass->name ?? 'ZZZ',
                fn ($q) => $q->student->name
            ])->values();

        $attendancesLain = $attendances->whereIn('status', ['Sakit', 'Izin', 'Alfa'])
            ->sortBy([
                fn ($q) => $q->student->schoolClass->name ?? 'ZZZ',
                fn ($q) => $q->student->name
            ])->values();
        
        // [FIX] Data Belum Absen (Filter Alumni)
        $existingStudentIds = $attendances->pluck('student_id')->toArray();
        $belumAbsenListRaw = Student::with('schoolClass')
            ->where('status', '!=', 'graduated') // Filter Alumni
            ->whereNotIn('id', $existingStudentIds)
            ->get();

        $belumAbsenList = $belumAbsenListRaw->sortBy([
            fn ($q) => $q->schoolClass->name ?? 'ZZZ',
            fn ($q) => $q->name
        ])->values();

        // Hitung Statistik
        $stats = [
            'hadir' => $attendances->where('status', 'Hadir')->count(),
            'terlambat' => $attendances->where('status', 'Terlambat')->count(),
            'sakit' => $attendances->where('status', 'Sakit')->count(),
            'izin' => $attendances->where('status', 'Izin')->count(),
            'alfa' => $attendances->where('status', 'Alfa')->count(),
            'belum' => $belumAbsenList->count()
        ];

        return view('reports.print_daily', compact(
            'selectedDate_db',
            'attendancesHadir',
            'attendancesLain',
            'belumAbsenList',
            'stats'
        ));
    }

    /**
     * 3. REKAP KEAGAMAAN (Web View)
     */
    public function religiousReport(Request $request)
    {
        $dateStr = $request->input('date', Carbon::today()->toDateString());
        $selectedDate_db = Carbon::parse($dateStr);
        $selectedActivity = $request->input('activity', 'Dhuha'); 

        // [FIX] Ambil Data (Filter Alumni)
        $attendances = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', function($q) {
                $q->where('status', '!=', 'graduated');
            })
            ->whereDate('attendance_date', $selectedDate_db)
            ->where('type', 'Keagamaan')
            ->where('activity', $selectedActivity)
            ->get();

        $attendancesHadirRaw = $attendances->where('status', 'Hadir');
        $attendancesUzurRaw = $attendances->whereIn('status', ["Uzur Syar'i", "Alfa", "Izin", "Sakit"]);

        // Hitung Statistik
        $hadirCount = $attendancesHadirRaw->count();
        $izinUzurCount = $attendances->whereIn('status', ["Uzur Syar'i", "Izin", "Sakit"])->count();
        $alfaCount = $attendances->where('status', 'Alfa')->count();

        // [FIX] Data Belum Absen (Filter Alumni)
        $existingIds = $attendances->pluck('student_id')->toArray();
        $belumAbsenListRaw = Student::with('schoolClass')
            ->where('status', '!=', 'graduated') // Filter Alumni
            ->whereNotIn('id', $existingIds)
            ->get();
            
        $belumAbsenList = $belumAbsenListRaw->sortBy([
            fn ($q) => $q->schoolClass->name ?? 'ZZZ',
            fn ($q) => $q->name
        ])->values();
        
        $belumAbsenCount = $belumAbsenList->count();

        // Mapping
        $mappedHadir = $attendancesHadirRaw->map(function($item) {
            $item->status_final = $item->status;
            $item->notes_final = $item->notes;
            return $item;
        });
        
        $mappedUzur = $attendancesUzurRaw->map(function($item) {
            $item->status_final = $item->status;
            $item->notes_final = $item->notes;
            return $item;
        });

        // SORTING DATA (Kelas -> Nama)
        $sortedHadir = $mappedHadir->sortBy([
            fn ($q) => $q->student->schoolClass->name ?? 'ZZZ',
            fn ($q) => $q->student->name
        ])->values();

        $sortedUzur = $mappedUzur->sortBy([
            fn ($q) => $q->student->schoolClass->name ?? 'ZZZ',
            fn ($q) => $q->student->name
        ])->values();

        // Pagination
        $attendancesHadir = $this->paginate($sortedHadir, 20);
        $attendancesUzur = $this->paginate($sortedUzur, 20);

        $attendancesHadir->appends($request->all());
        $attendancesUzur->appends($request->all());

        return view('reports.religious', compact(
            'selectedDate_db',
            'selectedActivity',
            'attendancesHadir',
            'attendancesUzur',
            'belumAbsenList',
            'hadirCount',
            'izinUzurCount',
            'alfaCount',
            'belumAbsenCount'
        ));
    }

    /**
     * 4. CETAK LAPORAN KEAGAMAAN (Print View)
     */
    public function printReligious(Request $request)
    {
        $dateStr = $request->input('date', Carbon::today()->toDateString());
        $selectedDate_db = Carbon::parse($dateStr);
        $selectedActivity = $request->input('activity', 'Dhuha'); 

        // [FIX] Ambil Data (Filter Alumni)
        $attendances = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', function($q) {
                $q->where('status', '!=', 'graduated');
            })
            ->whereDate('attendance_date', $selectedDate_db)
            ->where('type', 'Keagamaan')
            ->where('activity', $selectedActivity)
            ->get();

        $attendancesHadirRaw = $attendances->where('status', 'Hadir');
        $attendancesUzurRaw = $attendances->whereIn('status', ["Uzur Syar'i", "Alfa", "Izin", "Sakit"]);

        // Mapping
        $attendancesHadirMap = $attendancesHadirRaw->map(function($item) {
            $item->status_final = $item->status;
            $item->notes_final = $item->notes;
            return $item;
        });
        
        $attendancesUzurMap = $attendancesUzurRaw->map(function($item) {
            $item->status_final = $item->status;
            $item->notes_final = $item->notes;
            return $item;
        });

        // SORTING (Kelas -> Nama)
        $attendancesHadir = $attendancesHadirMap->sortBy([
            fn ($q) => $q->student->schoolClass->name ?? 'ZZZ',
            fn ($q) => $q->student->name
        ])->values();

        $attendancesUzur = $attendancesUzurMap->sortBy([
            fn ($q) => $q->student->schoolClass->name ?? 'ZZZ',
            fn ($q) => $q->student->name
        ])->values();

        // Statistik
        $hadirCount = $attendancesHadirRaw->count();
        $izinUzurCount = $attendances->whereIn('status', ["Uzur Syar'i", "Izin", "Sakit"])->count();
        $alfaCount = $attendances->where('status', 'Alfa')->count();

        // [FIX] Data Belum Absen (Filter Alumni)
        $existingIds = $attendances->pluck('student_id')->toArray();
        $belumAbsenListRaw = Student::with('schoolClass')
            ->where('status', '!=', 'graduated') // Filter Alumni
            ->whereNotIn('id', $existingIds)
            ->get();

        $belumAbsenList = $belumAbsenListRaw->sortBy([
            fn ($q) => $q->schoolClass->name ?? 'ZZZ',
            fn ($q) => $q->name
        ])->values();

        $belumAbsenCount = $belumAbsenList->count();

        return view('reports.print_religious', compact(
            'selectedDate_db',
            'selectedActivity',
            'attendancesHadir',
            'attendancesUzur',
            'belumAbsenList',
            'hadirCount',
            'izinUzurCount',
            'alfaCount',
            'belumAbsenCount'
        ));
    }

    /**
     * 5. MONITORING JURNAL MENGAJAR
     */
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

    /**
     * 6. PROSES BULK ALPHA (Tandai Masal)
     */
    public function bulkAlpha(Request $request)
    {
        $date = $request->input('date');
        $type = $request->input('type');
        $activity = $request->input('activity');

        $query = AttendanceSiswa::whereDate('attendance_date', $date)
                ->where('type', $type);
        
        if($type == 'Keagamaan' && $activity) {
            $query->where('activity', $activity);
        }

        $presentIds = $query->pluck('student_id')->toArray();
        
        // [FIX] Bulk Alpha: Jangan tandai alumni sebagai alfa
        $absentStudents = Student::where('status', '!=', 'graduated') // Filter Alumni
            ->whereNotIn('id', $presentIds)
            ->get();

        $insertData = [];
        $now = now();
        
        $disciplineType = null;
        if($type == 'Harian') {
            $disciplineType = DisciplineType::firstOrCreate(
                ['name' => 'Tidak Masuk Sekolah (Alfa)'],
                ['point_value' => 10, 'type' => 'Pelanggaran']
            );
        }

        foreach ($absentStudents as $student) {
            $insertData[] = [
                'student_id' => $student->id,
                'attendance_date' => $date,
                'type' => $type,
                'activity' => $activity,
                'status' => 'Alfa',
                'time_in' => null,
                'time_out' => null,
                'notes' => 'Otomatis oleh Sistem',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if($type == 'Harian' && $disciplineType) {
                DisciplineRecord::create([
                    'student_id' => $student->id,
                    'discipline_type_id' => $disciplineType->id,
                    'date' => $date,
                    'notes' => 'Alfa Harian',
                    'recorded_by_user_id' => auth()->id() ?? 1
                ]);
            }
        }

        if (!empty($insertData)) {
            AttendanceSiswa::insert($insertData);
        }

        return back()->with('success', count($insertData) . ' siswa berhasil ditandai Alfa.');
    }

    /**
     * 7. SIMPAN MANUAL (DENGAN WA NOTIFIKASI)
     */
    public function storeManualEntry(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'date' => 'required|date',
            'status' => 'required',
            'attendance_type' => 'required'
        ]);

        $attendance = AttendanceSiswa::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'attendance_date' => $request->date,
                'type' => $request->attendance_type,
                'activity' => $request->activity 
            ],
            [
                'status' => $request->status,
                'time_in' => $request->time_in,
                'time_out' => $request->time_out,
                'notes' => $request->notes,
            ]
        );

        // --- TAMBAHAN: Kirim Notifikasi WA ke Orang Tua ---
        try {
            SendWaManualNotificationJob::dispatch($attendance);
        } catch (\Exception $e) {
            Log::error("Gagal dispatch WA Manual: " . $e->getMessage());
        }
        // --------------------------------------------------

        return back()->with('success', 'Data absensi berhasil disimpan dan notifikasi diproses.');
    }

    /**
     * 8. UPDATE DATA
     */
    public function updateAttendance(Request $request, $id)
    {
        $att = AttendanceSiswa::findOrFail($id);
        $att->update([
            'status' => $request->status,
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
            'notes' => $request->notes
        ]);
        return back()->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * 9. HAPUS DATA KEAGAMAAN
     */
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