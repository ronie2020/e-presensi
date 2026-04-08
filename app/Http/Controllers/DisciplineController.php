<?php

namespace App\Http\Controllers;

use App\Models\DisciplineRecord;
use App\Models\DisciplineType;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DisciplineController extends Controller
{
    /**
     * Menampilkan halaman utama Catatan Disiplin (Dashboard Guru).
     */
    public function index(Request $request)
    {
        // ==========================================
        // 1. DATA MASTER (DROPDOWN & FORM)
        // ==========================================
        
        $studentsRaw = Student::with(['schoolClass', 'disciplineRecords.disciplineType'])
            ->where('status', '!=', 'graduated') 
            ->get();

        // Data Dropdown (Sorted by Class then Name)
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
        // 2. STATISTIK HARI INI
        // ==========================================
        $todayViolations = DisciplineRecord::whereDate('created_at', Carbon::today())
            ->whereHas('disciplineType', fn($q) => $q->where('type', 'Pelanggaran'))
            ->count();
            
        $todayMerits = DisciplineRecord::whereDate('created_at', Carbon::today())
            ->whereHas('disciplineType', fn($q) => $q->where('type', 'Kebaikan'))
            ->count();

        // ==========================================
        // 3. LOGIKA REKAP PER KELAS
        // ==========================================
        $classSummaries = $studentsRaw->groupBy(function ($student) {
                return $student->schoolClass->name ?? 'Tanpa Kelas';
            })
            ->map(function ($studentsInClass, $className) {
                $totalViolation = $studentsInClass->sum(function ($student) {
                    return $student->disciplineRecords->filter(fn($r) => optional($r->disciplineType)->type == 'Pelanggaran')
                        ->sum(fn($r) => optional($r->disciplineType)->point_value);
                });

                $totalMerit = $studentsInClass->sum(function ($student) {
                    return $student->disciplineRecords->filter(fn($r) => optional($r->disciplineType)->type == 'Kebaikan')
                        ->sum(fn($r) => optional($r->disciplineType)->point_value);
                });

                return (object) [
                    'class_name' => $className,
                    'student_count' => $studentsInClass->count(),
                    'total_violation' => $totalViolation,
                    'total_merit' => $totalMerit,
                ];
            })
            ->sortBy('class_name', SORT_NATURAL);

        // ==========================================
        // 4. LOGIKA TOP RANK SISWA (PELANGGARAN VS PRESTASI)
        // ==========================================
        
        // A. Hitung dulu poin semua siswa
        $allStudentPoints = $studentsRaw->map(function ($student) {
            $violationPoints = $student->disciplineRecords->filter(fn($r) => optional($r->disciplineType)->type == 'Pelanggaran')
                ->sum(fn($r) => optional($r->disciplineType)->point_value);

            $meritPoints = $student->disciplineRecords->filter(fn($r) => optional($r->disciplineType)->type == 'Kebaikan')
                ->sum(fn($r) => optional($r->disciplineType)->point_value);

            return (object) [
                'id' => $student->id,
                'name' => $student->name,
                'class' => $student->schoolClass->name ?? '-',
                'total_violation' => $violationPoints,
                'total_merit' => $meritPoints,
            ];
        });

        // B. Ambil Top 10 Pelanggaran
        $topViolators = $allStudentPoints
            ->where('total_violation', '>', 0)
            ->sortByDesc('total_violation')
            ->take(10);

        // C. Ambil Top 10 Prestasi (SISWA TELADAN)
        $topMerits = $allStudentPoints
            ->where('total_merit', '>', 0)
            ->sortByDesc('total_merit')
            ->take(10);

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

    public function create() { return redirect()->route('discipline.index'); }

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
        // Cukup panggil method dari model Student agar Controller bersih (DRY Principle)
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