<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\DisciplineRecord;
use App\Models\DisciplineType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RecoveryController extends Controller
{
    /**
     * Dashboard Monitoring Pemulihan Perilaku
     */
    public function index()
    {
        // Ambil tipe amnesti untuk dropdown di modal
        $recoveryTypes = DisciplineType::where('type', 'Kebaikan')
            ->where(function($q) {
                $q->where('name', 'like', '%Amnesti%')
                  ->orWhere('name', 'like', '%Pemutihan%');
            })->get();

        // Ambil riwayat pemulihan (Amnesti & Decay)
        $recoveryRecords = DisciplineRecord::with(['student.schoolClass', 'disciplineType', 'recorder'])
            ->whereHas('disciplineType', function($q) {
                $q->where('name', 'like', '%Amnesti%')
                  ->orWhere('name', 'like', '%Pemutihan%')
                  ->orWhere('name', 'like', '%Decay%');
            })
            ->latest()
            ->paginate(15);

        // Statistik
        $totalRecovered = $recoveryRecords->sum(fn($r) => $r->disciplineType->point_value);
        $activeCount = DisciplineRecord::whereHas('disciplineType', function($q) {
                $q->where('name', 'like', '%Amnesti%')
                  ->orWhere('name', 'like', '%Pemutihan%');
            })->distinct('student_id')->count();

        // PERBAIKAN: Data siswa untuk modal input (Mengatasi status null)
        $students = Student::with('schoolClass')
            ->where(function($q) {
                $q->where('status', '!=', 'graduated')
                  ->orWhereNull('status');
            })
            ->orderBy('name', 'asc')
            ->get();

        return view('discipline.recovery_monitoring', compact(
            'recoveryRecords', 'totalRecovered', 'activeCount', 'recoveryTypes', 'students'
        ));
    }

    /**
     * Simpan Validasi Tugas Positif (Amnesti Manual)
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'discipline_type_id' => 'required|exists:discipline_types,id',
            'notes' => 'required|string|min:10',
        ]);

        DisciplineRecord::create([
            'student_id' => $request->student_id,
            'discipline_type_id' => $request->discipline_type_id,
            'notes' => "[AMNESTI GURU] " . $request->notes,
            'date' => Carbon::today(),
            'recorded_by_user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Tugas pemulihan berhasil divalidasi. Poin pelanggaran siswa akan otomatis berkurang.');
    }
}