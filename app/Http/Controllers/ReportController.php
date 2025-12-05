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
     * 1. REKAP ABSENSI HARIAN (Masuk/Pulang)
     */
    public function dailyReport(Request $request)
    {
        $dateStr = $request->input('date', Carbon::today()->toDateString());
        $selectedDate_db = Carbon::parse($dateStr); 

        // Data statistik global
        $attendances = AttendanceSiswa::with(['student.schoolClass'])
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
        $belumAbsenList = Student::with('schoolClass')
            ->whereNotIn('id', $existingStudentIds)
            ->orderBy('name')
            ->get();

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

        // Pagination untuk View Web
        $attendancesHadir = $this->paginate($mappedHadir, 20);
        $attendancesLain = $this->paginate($mappedLain, 20);
        
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
     * FITUR BARU: CETAK LAPORAN HARIAN (Tanpa Pagination)
     */
    public function printDaily(Request $request)
    {
        $dateStr = $request->input('date', Carbon::today()->toDateString());
        $selectedDate_db = Carbon::parse($dateStr); 

        // Ambil SEMUA data tanpa pagination
        $attendances = AttendanceSiswa::with(['student.schoolClass'])
            ->whereDate('attendance_date', $selectedDate_db)
            ->whereIn('type', ['Harian', 'Masuk', 'Pulang'])
            ->orderBy('updated_at', 'desc') // Urutkan berdasarkan data terbaru masuk
            ->get();

        // Grouping Data
        $attendancesHadir = $attendances->whereIn('status', ['Hadir', 'Terlambat']);
        $attendancesLain = $attendances->whereIn('status', ['Sakit', 'Izin', 'Alfa']);
        
        // Data Belum Absen
        $existingStudentIds = $attendances->pluck('student_id')->toArray();
        $belumAbsenList = Student::with('schoolClass')
            ->whereNotIn('id', $existingStudentIds)
            ->orderBy('school_class_id') // Urutkan per kelas agar rapi saat dicetak
            ->orderBy('name')
            ->get();

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
     * 2. REKAP KEAGAMAAN (Dhuha / Dhuhur)
     */
    public function religiousReport(Request $request)
    {
        $dateStr = $request->input('date', Carbon::today()->toDateString());
        $selectedDate_db = Carbon::parse($dateStr);
        $selectedActivity = $request->input('activity', 'Dhuha'); 

        $attendances = AttendanceSiswa::with(['student.schoolClass'])
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
        $belumAbsenList = Student::with('schoolClass')
            ->whereNotIn('id', $existingIds)
            ->orderBy('name')
            ->get();
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

        $attendancesHadir = $this->paginate($mappedHadir, 20);
        $attendancesUzur = $this->paginate($mappedUzur, 20);

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
        $type = $request->input('type');
        $activity = $request->input('activity');

        $query = AttendanceSiswa::whereDate('attendance_date', $date)
                ->where('type', $type);
        
        if($type == 'Keagamaan' && $activity) {
            $query->where('activity', $activity);
        }

        $presentIds = $query->pluck('student_id')->toArray();
        $absentStudents = Student::whereNotIn('id', $presentIds)->get();

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
     * 5. SIMPAN MANUAL
     */
    public function storeManualEntry(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'date' => 'required|date',
            'status' => 'required',
            'attendance_type' => 'required'
        ]);

        AttendanceSiswa::updateOrCreate(
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
     * 6. UPDATE DATA
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
     * 7. HAPUS DATA
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