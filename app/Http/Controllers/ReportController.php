<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// [PENTING] Import Semua Model agar tidak error "Class not found"
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

class ReportController extends Controller
{
    /**
     * Helper function untuk mempaginasi Collection secara manual.
     * Ini mengubah Collection biasa menjadi LengthAwarePaginator agar bisa pakai ->links() di Blade.
     */
    public function paginate($items, $perPage = 20, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        
        // Pastikan path saat ini diambil agar link pagination benar
        $options = array_merge(['path' => Paginator::resolveCurrentPath()], $options);

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage), // Ambil item hanya untuk halaman ini
            $items->count(), // Total item
            $perPage, // Item per halaman
            $page, // Halaman saat ini
            $options // Opsi path, query, dll
        );
    }

    /**
     * 1. REKAP ABSENSI HARIAN (Masuk/Pulang)
     */
    public function dailyReport(Request $request)
    {
        // 1. Tentukan Tanggal (Default: Hari Ini)
        $dateStr = $request->input('date', Carbon::today()->toDateString());
        $selectedDate_db = Carbon::parse($dateStr); 

        // 2. Ambil Data Kehadiran Harian
        // Kita tetap pakai get() agar bisa menghitung statistik global dulu
        $attendances = AttendanceSiswa::with(['student.schoolClass'])
            ->whereDate('attendance_date', $selectedDate_db)
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->get();

        // 3. Kelompokkan Data
        // Hadir & Terlambat masuk kategori "Hadir"
        $attendancesHadirRaw = $attendances->whereIn('status', ['Hadir', 'Terlambat']);
        // Sakit, Izin, Alfa masuk kategori "Lainnya"
        $attendancesLainRaw = $attendances->whereIn('status', ['Sakit', 'Izin', 'Alfa']);

        // 4. Hitung Statistik
        $hadirCount = $attendances->where('status', 'Hadir')->count();
        $terlambatCount = $attendances->where('status', 'Terlambat')->count();
        $sakitCount = $attendances->where('status', 'Sakit')->count();
        $izinCount = $attendances->where('status', 'Izin')->count();
        $alfaCount = $attendances->where('status', 'Alfa')->count();

        // 5. Cari Siswa yang BELUM ABSEN
        $existingStudentIds = $attendances->pluck('student_id')->toArray();
        $belumAbsenList = Student::with('schoolClass')
            ->whereNotIn('id', $existingStudentIds)
            ->orderBy('name')
            ->get();

        // 6. Mapping Data agar kompatibel dengan View (menambahkan properti _final)
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

        // 7. [FIX ERROR] Convert Collection ke Paginator Manual
        // Agar method ->hasPages() dan ->links() di Blade bisa bekerja
        $attendancesHadir = $this->paginate($mappedHadir, 20);
        $attendancesLain = $this->paginate($mappedLain, 20);
        
        // Tambahkan query string saat ini ke link pagination agar filter tanggal tidak hilang saat ganti halaman
        $attendancesHadir->appends($request->all());
        $attendancesLain->appends($request->all());

        // Kirim semua variabel ke View
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
     * 2. REKAP KEAGAMAAN (Dhuha / Dhuhur)
     */
    public function religiousReport(Request $request)
    {
        // 1. Filter Tanggal & Kegiatan
        $dateStr = $request->input('date', Carbon::today()->toDateString());
        $selectedDate_db = Carbon::parse($dateStr);
        $selectedActivity = $request->input('activity', 'Dhuha'); 

        // 2. Ambil Data
        $attendances = AttendanceSiswa::with(['student.schoolClass'])
            ->whereDate('attendance_date', $selectedDate_db)
            ->where('type', 'Keagamaan')
            ->where('activity', $selectedActivity)
            ->get();

        // 3. Pisahkan Kategori
        $attendancesHadirRaw = $attendances->where('status', 'Hadir');
        $attendancesUzurRaw = $attendances->whereIn('status', ["Uzur Syar'i", "Alfa", "Izin", "Sakit"]);

        // 4. Statistik
        $hadirCount = $attendancesHadirRaw->count();
        $izinUzurCount = $attendances->whereIn('status', ["Uzur Syar'i", "Izin", "Sakit"])->count();
        $alfaCount = $attendances->where('status', 'Alfa')->count();

        // 5. Belum Absen
        $existingIds = $attendances->pluck('student_id')->toArray();
        $belumAbsenList = Student::with('schoolClass')
            ->whereNotIn('id', $existingIds)
            ->orderBy('name')
            ->get();
        $belumAbsenCount = $belumAbsenList->count();

        // 6. Mapping Data View
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

        // 7. [FIX ERROR] Convert Collection ke Paginator Manual
        $attendancesHadir = $this->paginate($mappedHadir, 20);
        $attendancesUzur = $this->paginate($mappedUzur, 20);

        // Tambahkan query string agar filter tidak reset
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
     * 3. MONITORING JURNAL MENGAJAR
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
     * 4. PROSES BULK ALPHA (Tandai Masal)
     */
    public function bulkAlpha(Request $request)
    {
        $date = $request->input('date');
        $type = $request->input('type'); // Harian / Keagamaan
        $activity = $request->input('activity'); // Dhuha/Dhuhur (opsional)

        // 1. Ambil Siswa yg SUDAH Absen
        $query = AttendanceSiswa::whereDate('attendance_date', $date)
                ->where('type', $type);
        
        if($type == 'Keagamaan' && $activity) {
            $query->where('activity', $activity);
        }

        $presentIds = $query->pluck('student_id')->toArray();

        // 2. Ambil Siswa yg BELUM Absen
        $absentStudents = Student::whereNotIn('id', $presentIds)->get();

        // 3. Insert Masal
        $insertData = [];
        $now = now();
        
        // Cari/Buat Tipe Pelanggaran untuk Poin (Hanya untuk Harian)
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
     * 5. SIMPAN MANUAL (Input dari Modal)
     */
    public function storeManualEntry(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'date' => 'required|date',
            'status' => 'required',
            'attendance_type' => 'required'
        ]);

        $att = AttendanceSiswa::updateOrCreate(
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

        return back()->with('success', 'Data absensi berhasil disimpan.');
    }

    /**
     * 6. UPDATE DATA (Edit Modal)
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
     * 7. HAPUS DATA (Reset)
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