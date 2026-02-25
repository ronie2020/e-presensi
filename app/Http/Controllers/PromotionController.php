<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    /**
     * Menampilkan Halaman Kenaikan Kelas & Mutasi
     */
    public function index(Request $request)
    {
        // Ambil semua kelas untuk dropdown
        $classes = SchoolClass::orderBy('name', 'asc')->get();
        
        $students = [];
        
        // Jika admin sudah memilih kelas asal, panggil data siswa di kelas tersebut
        if ($request->has('from_class_id') && $request->from_class_id != '') {
            $students = Student::where('class_id', $request->from_class_id)
                ->where(function($q) {
                    // Pastikan tidak memanggil siswa yang sudah lulus
                    $q->where('status', '!=', 'graduated')
                      ->orWhereNull('status');
                })
                ->orderBy('name', 'asc')
                ->get();
        }

        return view('promotions.index', compact('classes', 'students'));
    }

    /**
     * Proses Kenaikan Kelas atau Kelulusan Massal
     */
    public function process(Request $request)
    {
        // 1. Validasi Input (Ditambahkan academic_year dan perbaikan validasi target)
        $request->validate([
            'from_class_id' => 'required|exists:classes,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'target_action' => 'required',
            'academic_year' => 'required|string'
        ], [
            'student_ids.required' => 'Pilih minimal satu siswa yang akan diproses.',
            'target_action.required' => 'Pilih kelas tujuan atau kelulusan.',
            'academic_year.required' => 'Tahun ajaran wajib diisi.'
        ]);

        $studentIds = $request->student_ids;
        $count = count($studentIds);

        DB::beginTransaction();
        try {
            if ($request->target_action === 'alumni') {
                // ==========================================
                // PROSES KELULUSAN / ALUMNI
                // ==========================================
                Student::whereIn('id', $studentIds)->update([
                    'status' => 'graduated',
                    'class_id' => null
                ]);
                
                $message = "Berhasil! {$count} Siswa telah diluluskan dan menjadi alumni.";
            } else {
                // ==========================================
                // NAIK KELAS / PINDAH KELAS
                // ==========================================
                $targetClass = SchoolClass::findOrFail($request->target_action);
                
                // Proteksi: Jangan pindahkan ke kelas yang sama
                if ($request->from_class_id == $targetClass->id) {
                    return back()->with('error', 'Kelas tujuan tidak boleh sama dengan kelas asal!');
                }

                // 1. Update kelas saat ini di tabel Students
                Student::whereIn('id', $studentIds)->update([
                    'class_id' => $targetClass->id
                ]);

                // 2. Rekam ke tabel Riwayat (Bulk Insert)
                $historyData = [];
                foreach ($studentIds as $id) {
                    $historyData[] = [
                        'student_id' => $id,
                        'class_id' => $targetClass->id,
                        'academic_year' => $request->academic_year,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                \App\Models\StudentClassHistory::insert($historyData);

                $message = "Berhasil! {$count} Siswa telah dipindahkan ke kelas {$targetClass->name}.";
            }

            DB::commit();
            return redirect()->route('promotions.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}