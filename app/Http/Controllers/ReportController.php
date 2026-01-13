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
     * Helper Sorting Siswa (Kelas -> Nama)
     */
    private function sortStudents($collection)
    {
        return $collection->sortBy(function ($item) {
            $student = $item instanceof Student ? $item : $item->student;
            $className = $student->schoolClass->name ?? 'ZZZ';
            return $className . ' ' . $student->name;
        }, SORT_NATURAL | SORT_FLAG_CASE)->values();
    }

    /**
     * ==========================================
     * LOGIKA OTOMATIS POIN PELANGGARAN (ROBUST)
     * ==========================================
     */
    private function handleAutoPunishment($studentId, $date, $status, $typeContext = 'Harian')
    {
        // 1. Validasi Status
        if (!in_array($status, ['Alfa', 'Alpa', 'Alpha'])) {
            return;
        }

        // 2. Cari Tipe Pelanggaran yang Valid
        $violationType = DisciplineType::where('type', 'Pelanggaran')
            ->where(function($q) {
                $q->where('name', 'Tidak Masuk Sekolah (Alfa)')
                  ->orWhere('name', 'Alfa')
                  ->orWhere('name', 'Alpa')
                  ->orWhere('name', 'Tanpa Keterangan');
            })->first();

        // Fallback: Cari yang mirip
        if (!$violationType) {
            $violationType = DisciplineType::where('type', 'Pelanggaran')
                ->where('name', 'LIKE', '%Alfa%')
                ->first();
        }

        // Auto-Create Tipe jika tidak ada
        if (!$violationType) {
            $violationType = DisciplineType::create([
                'name' => 'Tidak Masuk Sekolah (Alfa)',
                'type' => 'Pelanggaran',
                'point_value' => 10
            ]);
        }

        // 3. Cek Duplikasi (Agar tidak double poin di hari yang sama)
        $exists = DisciplineRecord::where('student_id', $studentId)
            ->where('date', $date)
            ->where('discipline_type_id', $violationType->id)
            ->exists();

        // 4. Eksekusi Simpan
        if (!$exists) {
            // Pastikan ada User ID yang mencatat (Fallback ke User pertama jika Auth null)
            $recorderId = auth()->id() ?? (User::first()->id ?? 1);

            DisciplineRecord::create([
                'student_id' => $studentId,
                'discipline_type_id' => $violationType->id,
                'date' => $date,
                'notes' => "Otomatis: Tidak hadir ($typeContext)",
                'recorded_by_user_id' => $recorderId
            ]);
        }
    }

    /**
     * 1. REKAP ABSENSI HARIAN (Web View)
     */
    public function dailyReport(Request $request)
    {
        $dateStr = $request->input('date', Carbon::today()->toDateString());
        $selectedDate_db = Carbon::parse($dateStr); 

        $attendances = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', function($q) {
                $q->where('status', '!=', 'graduated');
            })
            ->whereDate('attendance_date', $selectedDate_db)
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->get();

        $attendancesHadirRaw = $attendances->whereIn('status', ['Hadir', 'Terlambat']);
        $attendancesLainRaw = $attendances->whereIn('status', ['Sakit', 'Izin', 'Alfa']);

        $hadirCount = $attendances->where('status', 'Hadir')->count();
        $terlambatCount = $attendances->where('status', 'Terlambat')->count();
        $sakitCount = $attendances->where('status', 'Sakit')->count();
        $izinCount = $attendances->where('status', 'Izin')->count();
        $alfaCount = $attendances->where('status', 'Alfa')->count();

        $existingStudentIds = $attendances->pluck('student_id')->toArray();
        $belumAbsenListRaw = Student::with('schoolClass')
            ->where('status', '!=', 'graduated')
            ->whereNotIn('id', $existingStudentIds)
            ->get();
            
        $belumAbsenList = $this->sortStudents($belumAbsenListRaw);

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

        $sortedHadir = $this->sortStudents($mappedHadir);
        $sortedLain = $this->sortStudents($mappedLain);

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

        $attendances = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', function($q) {
                $q->where('status', '!=', 'graduated');
            })
            ->whereDate('attendance_date', $selectedDate_db)
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->get();

        $attendancesHadir = $this->sortStudents($attendances->whereIn('status', ['Hadir', 'Terlambat']));
        $attendancesLain = $this->sortStudents($attendances->whereIn('status', ['Sakit', 'Izin', 'Alfa']));
        
        $existingStudentIds = $attendances->pluck('student_id')->toArray();
        $belumAbsenListRaw = Student::with('schoolClass')
            ->where('status', '!=', 'graduated')
            ->whereNotIn('id', $existingStudentIds)
            ->get();

        $belumAbsenList = $this->sortStudents($belumAbsenListRaw);

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

        $hadirCount = $attendancesHadirRaw->count();
        $izinUzurCount = $attendances->whereIn('status', ["Uzur Syar'i", "Izin", "Sakit"])->count();
        $alfaCount = $attendances->where('status', 'Alfa')->count();

        $existingIds = $attendances->pluck('student_id')->toArray();
        $belumAbsenListRaw = Student::with('schoolClass')
            ->where('status', '!=', 'graduated')
            ->whereNotIn('id', $existingIds)
            ->get();
            
        $belumAbsenList = $this->sortStudents($belumAbsenListRaw);
        $belumAbsenCount = $belumAbsenList->count();

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

        $sortedHadir = $this->sortStudents($mappedHadir);
        $sortedUzur = $this->sortStudents($mappedUzur);

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

        $attendancesHadir = $this->sortStudents($attendancesHadirMap);
        $attendancesUzur = $this->sortStudents($attendancesUzurMap);

        $hadirCount = $attendancesHadirRaw->count();
        $izinUzurCount = $attendances->whereIn('status', ["Uzur Syar'i", "Izin", "Sakit"])->count();
        $alfaCount = $attendances->where('status', 'Alfa')->count();

        $existingIds = $attendances->pluck('student_id')->toArray();
        $belumAbsenListRaw = Student::with('schoolClass')
            ->where('status', '!=', 'graduated')
            ->whereNotIn('id', $existingIds)
            ->get();

        $belumAbsenList = $this->sortStudents($belumAbsenListRaw);
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
     * 6. PROSES BULK ALPHA (FIXED: Dengan Sync Data Existing)
     */
    public function bulkAlpha(Request $request)
    {
        $date = $request->input('date');
        $type = $request->input('type');
        $activity = $request->input('activity');

        // Gunakan Transaksi untuk memastikan data konsisten
        return DB::transaction(function () use ($date, $type, $activity) {
            $query = AttendanceSiswa::whereDate('attendance_date', $date)
                    ->where('type', $type);
            
            if($type == 'Keagamaan' && $activity) {
                $query->where('activity', $activity);
            }

            // 1. PROSES SISWA BARU (Belum Absen)
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
                    'time_in' => null,
                    'time_out' => null,
                    'notes' => 'Otomatis oleh Sistem',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                // Auto Punishment untuk data baru
                $this->handleAutoPunishment($student->id, $date, 'Alfa', $type);
            }

            if (!empty($insertData)) {
                AttendanceSiswa::insert($insertData);
            }

            // 2. [PENTING] PROSES SISWA LAMA (Sudah Alfa tapi mungkin belum dapat Poin)
            // Ini menangani kasus dimana absensi sudah ada, tapi poin gagal terbuat sebelumnya.
            $existingAlphas = AttendanceSiswa::whereDate('attendance_date', $date)
                ->where('type', $type)
                ->whereIn('status', ['Alfa', 'Alpa', 'Alpha']);
            
            if($type == 'Keagamaan' && $activity) {
                $existingAlphas->where('activity', $activity);
            }

            foreach ($existingAlphas->get() as $existing) {
                // Fungsi ini aman dipanggil berulang karena ada cek $exists didalamnya
                $this->handleAutoPunishment($existing->student_id, $date, 'Alfa', $type);
            }

            return back()->with('success', count($insertData) . ' siswa baru ditandai Alfa (Data lama disinkronisasi).');
        });
    }

    /**
     * 7. SIMPAN MANUAL (DENGAN AUTO PUNISHMENT + WA)
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

        // [FIX] Panggil Auto Punishment
        $this->handleAutoPunishment($request->student_id, $request->date, $request->status, $request->attendance_type);

        // Kirim Notifikasi WA
        try {
            SendWaManualNotificationJob::dispatch($attendance);
        } catch (\Exception $e) {
            Log::error("Gagal dispatch WA Manual: " . $e->getMessage());
        }

        return back()->with('success', 'Data absensi berhasil disimpan dan notifikasi diproses.');
    }

    /**
     * 8. UPDATE DATA (DENGAN AUTO PUNISHMENT)
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

        // [FIX] Panggil Auto Punishment
        $this->handleAutoPunishment($att->student_id, $att->attendance_date, $request->status, $att->type);

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