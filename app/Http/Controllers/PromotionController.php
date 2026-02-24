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
        // 1. Validasi Input
        $request->validate([
            'from_class_id' => 'required|exists:classes,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'target_action' => 'required|exists:classes,id' 
        ], [
            'student_ids.required' => 'Pilih minimal satu siswa yang akan diproses.',
            'target_action.required' => 'Pilih kelas tujuan.'
        ]);

        $studentIds = $request->student_ids;
        $count = count($studentIds);

        DB::beginTransaction();
        try {
            // ==========================================
            // NAIK KELAS / PINDAH KELAS
            // ==========================================
            $targetClass = SchoolClass::findOrFail($request->target_action);
            
            // Proteksi: Jangan pindahkan ke kelas yang sama
            if ($request->from_class_id == $targetClass->id) {
                return back()->with('error', 'Kelas tujuan tidak boleh sama dengan kelas asal!');
            }

            Student::whereIn('id', $studentIds)->update([
                'class_id' => $targetClass->id
            ]);

            DB::commit();
            return redirect()->route('promotions.index')->with('success', "Berhasil! {$count} Siswa telah dipindahkan ke kelas {$targetClass->name}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}