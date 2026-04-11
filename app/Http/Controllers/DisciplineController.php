<?php

namespace App\Http\Controllers;

use App\Models\DisciplineRecord;
use App\Models\DisciplineType;
use App\Models\Student;
use App\Models\BkSession; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class DisciplineController extends Controller
{
    /**
     * Menampilkan halaman utama Catatan Disiplin (Dashboard Guru).
     */
    public function index(Request $request)
    {
        // ==========================================
        // 1. QUERY OPTIMAL & DATA MASTER
        // ==========================================       
        $studentTable = app(Student::class)->getTable();
        $recordTable = app(DisciplineRecord::class)->getTable();
        $typeTable = app(DisciplineType::class)->getTable();

        // Subquery untuk menghitung total poin per siswa di level SQL 
        $violationSubquery = DisciplineRecord::select(DB::raw("COALESCE(SUM({$typeTable}.point_value), 0)"))
            ->join($typeTable, "{$recordTable}.discipline_type_id", '=', "{$typeTable}.id")
            ->whereColumn("{$recordTable}.student_id", "{$studentTable}.id")
            ->where("{$typeTable}.type", 'Pelanggaran');

        $meritSubquery = DisciplineRecord::select(DB::raw("COALESCE(SUM({$typeTable}.point_value), 0)"))
            ->join($typeTable, "{$recordTable}.discipline_type_id", '=', "{$typeTable}.id")
            ->whereColumn("{$recordTable}.student_id", "{$studentTable}.id")
            ->where("{$typeTable}.type", 'Kebaikan');

        // Ambil data siswa dengan relasi kelas     
        $studentsRaw = Student::with('schoolClass')
            ->where('status', '!=', 'graduated')
            ->select("{$studentTable}.*") 
            ->selectSub($violationSubquery, 'total_violation')
            ->selectSub($meritSubquery, 'total_merit')
            ->get();

        // ==========================================
        // 2. DATA DROPDOWN & FILTER
        // ==========================================
        $students = $studentsRaw->sortBy(function ($student) {
                $className = $student->schoolClass->name ?? 'ZZZ'; 
                return $className . ' ' . $student->name;
            }, SORT_NATURAL | SORT_FLAG_CASE);
        
        $violationTypes = DisciplineType::where('type', 'Pelanggaran')
            ->where('name', 'NOT LIKE', '%Alfa%')
            ->where('name', 'NOT LIKE', '%Alpa%')
            ->where('name', 'NOT LIKE', '%Tidak Masuk%')
            ->where('name', 'NOT LIKE', '%Tanpa Keterangan%')
            ->orderBy('name', 'asc')
            ->get();

        $meritTypes = DisciplineType::where('type', 'Kebaikan')->orderBy('name', 'asc')->get();

        // ==========================================
        // 3. STATISTIK HARI INI
        // ==========================================
        $todayViolations = DisciplineRecord::whereDate('created_at', Carbon::today())
            ->whereHas('disciplineType', fn($q) => $q->where('type', 'Pelanggaran'))
            ->count();
            
        $todayMerits = DisciplineRecord::whereDate('created_at', Carbon::today())
            ->whereHas('disciplineType', fn($q) => $q->where('type', 'Kebaikan'))
            ->count();

                
        // ==========================================
        // 4. REKAP & TOP RANK
        // ==========================================
        // Rekap per kelas
        $classSummaries = $studentsRaw->groupBy(function($student) {
            return $student->schoolClass->name ?? 'Tanpa Kelas';
        })->map(function ($group, $className) {
            return (object) [
                'class_name' => $className,
                'student_count' => $group->count(),
                'total_violation' => $group->sum('total_violation'),
                'total_merit' => $group->sum('total_merit'),
            ];
        })->sortBy('class_name', SORT_NATURAL);

        // Top 10 Pelanggaran
        $topViolators = $studentsRaw->where('total_violation', '>', 0)
            ->sortByDesc('total_violation')
            ->take(10)
            ->map(function($student) {
                return (object) [
                    'name' => $student->name,
                    'class_name' => $student->schoolClass->name ?? '-',
                    'total_violation' => $student->total_violation
                ];
            });

        // Top 10 Prestasi
        $topMerits = $studentsRaw->where('total_merit', '>', 0)
            ->sortByDesc('total_merit')
            ->take(10)
            ->map(function($student) {
                return (object) [
                    'name' => $student->name,
                    'class_name' => $student->schoolClass->name ?? '-',
                    'total_merit' => $student->total_merit
                ];
            });

        // ==========================================
        // 5. DATA RIWAYAT
        // ==========================================
        $query = DisciplineRecord::with(['student.schoolClass', 'disciplineType', 'recorder'])->latest(); 

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('student', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }
        if ($request->has('filter_date') && $request->filter_date != '') {
            $query->whereDate('date', $request->filter_date);
        }

        $historyRecords = $query->paginate(10)->withQueryString();

        return view('discipline.index', compact(
            'students', 'violationTypes', 'meritTypes', 
            'classSummaries', 'topViolators', 'topMerits', 'historyRecords', 
            'todayViolations', 'todayMerits'
        ));
    }

 public function analytics()
    {
        // 1. Ambil data dasar
        $historyRecords = DisciplineRecord::with('disciplineType')->get();
        
        $studentTable = app(Student::class)->getTable();
        $recordTable = app(DisciplineRecord::class)->getTable();
        $typeTable = app(DisciplineType::class)->getTable();

        $violationSubquery = DisciplineRecord::select(DB::raw("COALESCE(SUM({$typeTable}.point_value), 0)"))
            ->join($typeTable, "{$recordTable}.discipline_type_id", '=', "{$typeTable}.id")
            ->whereColumn("{$recordTable}.student_id", "{$studentTable}.id")
            ->where("{$typeTable}.type", 'Pelanggaran');

        $meritSubquery = DisciplineRecord::select(DB::raw("COALESCE(SUM({$typeTable}.point_value), 0)"))
            ->join($typeTable, "{$recordTable}.discipline_type_id", '=', "{$typeTable}.id")
            ->whereColumn("{$recordTable}.student_id", "{$studentTable}.id")
            ->where("{$typeTable}.type", 'Kebaikan');

        $students = Student::with('schoolClass')
            ->where('status', '!=', 'graduated')
            ->select("{$studentTable}.*") 
            ->selectSub($violationSubquery, 'total_violation')
            ->selectSub($meritSubquery, 'total_merit')
            ->get();

        // 2. Rekap Per Kelas
        $classSummaries = $students->groupBy(fn($s) => $s->schoolClass->name ?? 'Tanpa Kelas')
            ->map(fn($group, $key) => (object)[
                'class_name' => $key,
                'total_violation' => $group->sum('total_violation'),
                'total_merit' => $group->sum('total_merit')
            ])->sortBy('class_name', SORT_NATURAL);

        $topViolators = $students->where('total_violation', '>', 0)->sortByDesc('total_violation')->take(10);

        // 3. LOGIKA TREN BULANAN (REAL DATA)
        $monthlyTrend = DisciplineRecord::select(
                DB::raw('MONTH(date) as month'),
                DB::raw("SUM(CASE WHEN {$typeTable}.type = 'Pelanggaran' THEN {$typeTable}.point_value ELSE 0 END) as violations"),
                DB::raw("SUM(CASE WHEN {$typeTable}.type = 'Kebaikan' THEN {$typeTable}.point_value ELSE 0 END) as merits")
            )
            ->join($typeTable, "{$recordTable}.discipline_type_id", '=', "{$typeTable}.id")
            ->whereYear('date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $trendLabels = [];
        $trendViolations = [];
        $trendMerits = [];

        // Loop 12 bulan agar grafik lengkap dari Jan - Des
        for ($m = 1; $m <= 12; $m++) {
            $monthName = Carbon::create()->month($m)->translatedFormat('M');
            $trendLabels[] = $monthName;
            
            $data = $monthlyTrend->firstWhere('month', $m);
            $trendViolations[] = $data ? (int)$data->violations : 0;
            $trendMerits[] = $data ? (int)$data->merits : 0;
        }

        return view('discipline.discipline_analytics', compact(
            'historyRecords', 'students', 'classSummaries', 'topViolators',
            'trendLabels', 'trendViolations', 'trendMerits'
        ));
    }

    /**
     * Cetak Surat Peringatan
     */
    public function spPrint($id)
    {
        $student = Student::with(['disciplineRecords.disciplineType', 'schoolClass'])->findOrFail($id);
        
        // Hitung total_violation secara dinamis untuk view SP
        $student->total_violation = $student->disciplineRecords
            ->where('disciplineType.type', 'Pelanggaran')
            ->sum('disciplineType.point_value');

        return view('discipline.sp_print', compact('student'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'discipline_type_id' => 'required|integer|exists:discipline_types,id',
            'notes' => 'nullable|string',
            'date' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['recorded_by_user_id'] = Auth::id();
        
        if (empty($data['date'])) {
            $data['date'] = Carbon::today()->toDateString();
        }

        // Simpan Data Disiplin
        DisciplineRecord::create($data);
        
        // =========================================================================
        // TRIGGER SISTEM E-COUNSELING OTOMATIS (BK INTEGRATION)
        // =========================================================================
        $student = Student::find($request->student_id);
        if ($student) {
            // Fungsi ini akan menghitung poin dan membuat tiket BK jika mencapai ambang batas
            $student->checkBkThresholds();
        }
        // =========================================================================
        
        $type = DisciplineType::find($request->discipline_type_id);
        $msg = ($type->type == 'Pelanggaran') ? 'Pelanggaran tercatat.' : 'Prestasi berhasil ditambahkan!';

        return redirect()->route('discipline.index')->with('success', $msg);
    }    

    public function destroy(DisciplineRecord $discipline)
    {
        $discipline->delete();
        return redirect()->route('discipline.index')->with('success', 'Catatan disiplin berhasil dihapus.');
    }
}