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
     * Menampilkan halaman utama Catatan Disiplin.
     */
    public function index(Request $request)
    {
        // 1. Ambil data untuk dropdown form
        // --- PERBAIKAN URUTAN ---
        // Ambil semua siswa beserta kelasnya, lalu urutkan koleksinya:
        // Prioritas 1: Nama Kelas (7A, 7B, dst)
        // Prioritas 2: Nama Siswa
        $students = Student::with('schoolClass')
            ->get()
            ->sortBy(function ($student) {
                // Kita gabungkan Nama Kelas dan Nama Siswa untuk menjadi kunci pengurutan
                // Contoh hasil: "7A - Ahmad", "7A - Budi", "7B - Caca"
                $className = $student->schoolClass->name ?? 'ZZZ'; // 'ZZZ' agar siswa tanpa kelas ada di paling bawah
                return $className . $student->name;
            });
        
        $violationTypes = DisciplineType::where('type', 'Pelanggaran')->orderBy('name', 'asc')->get();
        $meritTypes = DisciplineType::where('type', 'Kebaikan')->orderBy('name', 'asc')->get();

        // 2. LOGIKA RINGKASAN POIN (Summary)
        $studentSummaries = Student::with(['schoolClass', 'disciplineRecords.disciplineType'])
            ->get()
            ->map(function ($student) {
                // Hitung Poin Pelanggaran
                $violationPoints = $student->disciplineRecords->filter(function ($record) {
                    return optional($record->disciplineType)->type == 'Pelanggaran';
                })->sum(function ($record) {
                    return optional($record->disciplineType)->point_value;
                });

                // Hitung Poin Kebaikan
                $meritPoints = $student->disciplineRecords->filter(function ($record) {
                    return optional($record->disciplineType)->type == 'Kebaikan';
                })->sum(function ($record) {
                    return optional($record->disciplineType)->point_value;
                });

                return (object) [
                    'id' => $student->id,
                    'name' => $student->name,
                    'class' => $student->schoolClass->name ?? '-',
                    'total_violation' => $violationPoints,
                    'total_merit' => $meritPoints,
                ];
            })
            ->filter(function ($summary) {
                return $summary->total_violation > 0 || $summary->total_merit > 0;
            })
            ->sortByDesc('total_violation') 
            ->take(10); 

        // 3. LOGIKA RIWAYAT (History) dengan FILTER
        $query = DisciplineRecord::with(['student.schoolClass', 'disciplineType', 'recorder'])
            ->latest(); 

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('filter_date') && $request->filter_date != '') {
            $query->whereDate('date', $request->filter_date);
        }

        $historyRecords = $query->paginate(10)->withQueryString();

        // 4. Kirim semua data ke view
        return view('discipline.index', [
            'students' => $students,
            'violationTypes' => $violationTypes,
            'meritTypes' => $meritTypes,
            'studentSummaries' => $studentSummaries, 
            'historyRecords' => $historyRecords,     
        ]);
    }

    public function create()
    {
        return redirect()->route('discipline.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'discipline_type_id' => 'required|integer|exists:discipline_types,id',
            'notes' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $data = $request->all();
        $data['recorded_by_user_id'] = Auth::id();

        DisciplineRecord::create($data);

        return redirect()->route('discipline.index')->with('success', 'Catatan disiplin berhasil disimpan.');
    }

    public function destroy(DisciplineRecord $discipline)
    {
        $discipline->delete();
        return redirect()->route('discipline.index')->with('success', 'Catatan disiplin berhasil dihapus.');
    }
}