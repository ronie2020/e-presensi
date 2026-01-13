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
     * Menggunakan SORT_NATURAL agar "9 A" urut sebelum "9 B" dengan benar
     * Memastikan data tidak acak-acakan.
     */
    private function sortStudents($collection)
    {
        return $collection->sortBy(function ($item) {
            // Cek apakah item adalah model Student langsung atau AttendanceSiswa (yang punya relasi student)
            $student = $item instanceof Student ? $item : $item->student;
            
            // Urutkan berdasarkan Nama Kelas dulu, baru Nama Siswa
            $className = $student->schoolClass->name ?? 'ZZZ';
            return $className . ' ' . $student->name;
        }, SORT_NATURAL | SORT_FLAG_CASE)->values();
    }

    /**
     * LOGIKA OTOMATIS POIN PELANGGARAN
     * Menambahkan poin jika status Alfa, dan mencegah duplikasi hari yang sama.
     */
    private function handleAutoPunishment($studentId, $date, $status, $typeContext = 'Harian')
    {
        // Hanya proses jika status adalah Alfa
        if (!in_array($status, ['Alfa', 'Alpa', 'Alpha'])) {
            return;
        }

        // Cari Jenis Pelanggaran yang cocok di database
        $violationType = DisciplineType::where('type', 'Pelanggaran')
            ->where(function($q) {
                $q->where('name', 'LIKE', '%Alfa%')
                  ->orWhere('name', 'LIKE', '%Alpa%')
                  ->orWhere('name', 'LIKE', '%Tanpa Keterangan%')
                  ->orWhere('name', 'LIKE', '%Tidak Masuk Sekolah%');
            })->first();

        // Jika tidak ketemu, buat default
        if (!$violationType) {
            $violationType = DisciplineType::create([
                'name' => 'Tidak Masuk Sekolah (Alfa)',
                'type' => 'Pelanggaran',
                'point_value' => 10 // Poin default
            ]);
        }

        // CEK DUPLIKASI: Jangan input poin jika hari itu sudah ada pelanggaran Alfa
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
                'type' => 'violation', // Pastikan kolom ini ada di tabel discipline_records
                'recorded_by_user_id' => auth()->id() ?? 1
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

        // Ambil data absensi (Filter Alumni)
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

        // Data Belum Absen (Filter Alumni)
        $existingStudentIds = $attendances->pluck('student_id')->toArray();
        $belumAbsenListRaw = Student::with('schoolClass')
            ->where('status', '!=', 'graduated')
            ->whereNotIn('id', $existingStudentIds)
            ->get();
            
        // --- TERAPKAN SORTING PADA BELUM ABSEN ---
        $belumAbsenList = $this->sortStudents($belumAbsenListRaw);

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

        // --- TERAPKAN SORTING PADA HADIR & LAIN ---
        $sortedHadir = $this->sortStudents($mappedHadir);
        $sortedLain = $this->sortStudents($mappedLain);

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

        $attendances = AttendanceSiswa::with(['student.schoolClass'])
            ->whereHas('student', function($q) {
                $q->where('status', '!=', 'graduated');
            })
            ->whereDate('attendance_date', $selectedDate_db)
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->get();

        // --- TERAPKAN SORTING UNTUK CETAK ---
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

        // Statistik
        $hadirCount = $attendancesHadirRaw->count();
        $izinUzurCount = $attendances->whereIn('status', ["Uzur Syar'i", "Izin", "Sakit"])->count();
        $alfaCount = $attendances->where('status', 'Alfa')->count();

        // Data Belum Absen
        $existingIds = $attendances->pluck('student_id')->toArray();
        $belumAbsenListRaw = Student::with('schoolClass')
            ->where('status', '!=', 'graduated')
            ->whereNotIn('id', $existingIds)
            ->get();
            
        // --- TERAPKAN SORTING ---
        $belumAbsenList = $this->sortStudents($belumAbsenListRaw);
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

        // --- TERAPKAN SORTING ---
        $sortedHadir = $this->sortStudents($mappedHadir);
        $sortedUzur = $this->sortStudents($mappedUzur);

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

        // --- TERAPKAN SORTING ---
        $attendancesHadir = $this->sortStudents($attendancesHadirMap);
        $attendancesUzur = $this->sortStudents($attendancesUzurMap);

        // Statistik
        $hadirCount = $attendancesHadirRaw->count();
        $izinUzurCount = $attendances->whereIn('status', ["Uzur Syar'i", "Izin", "Sakit"])->count();
        $alfaCount = $attendances->where('status', 'Alfa')->count();

        // Data Belum Absen
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
     * 5. MONITORING JURNAL MENGAJAR (Tidak berubah)
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
     * 6. BULK ALPHA (Dengan Auto Punishment)
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
        
        // Filter alumni
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

            // AUTO PUNISHMENT
            $this->handleAutoPunishment($student->id, $date, 'Alfa', $type);
        }

        if (!empty($insertData)) {
            AttendanceSiswa::insert($insertData);
        }

        return back()->with('success', count($insertData) . ' siswa berhasil ditandai Alfa dan diberikan Poin Pelanggaran.');
    }

    /**
     * 7. SIMPAN MANUAL (Dengan Notif WA & Auto Punishment)
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

        // AUTO PUNISHMENT
        $this->handleAutoPunishment($request->student_id, $request->date, $request->status, $request->attendance_type);

        // KIRIM WA
        try {
            SendWaManualNotificationJob::dispatch($attendance);
        } catch (\Exception $e) {
            Log::error("Gagal dispatch WA Manual: " . $e->getMessage());
        }

        return back()->with('success', 'Data absensi berhasil disimpan dan notifikasi diproses.');
    }

    /**
     * 8. UPDATE ATTENDANCE (Dengan Auto Punishment)
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

        // AUTO PUNISHMENT
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