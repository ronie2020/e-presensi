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
        // 1. Validasi Input (Diperketat untuk target_action)
        $request->validate([
            'from_class_id' => 'required|exists:classes,id',
            'student_ids'   => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'academic_year' => ['required', 'string', 'regex:/^\d{4}\/\d{4}$/'], // Format YYYY/YYYY
            'target_action' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Jika valuenya 'alumni', berarti valid
                    if ($value === 'alumni') {
                        return;
                    }
                    // Jika bukan 'alumni', pastikan ID kelas tujuan benar-benar ada di database
                    if (!SchoolClass::where('id', $value)->exists()) {
                        $fail('Tujuan pemindahan kelas tidak valid atau tidak ditemukan.');
                    }
                },
            ],
        ], [
            'student_ids.required'   => 'Pilih minimal satu siswa yang akan diproses.',
            'target_action.required' => 'Pilih kelas tujuan atau kelulusan.',
            'academic_year.required' => 'Tahun ajaran wajib diisi.',
            'academic_year.regex'    => 'Format Tahun Ajaran tidak valid (Gunakan format: 2024/2025).'
        ]);

        $studentIds = $request->student_ids;
        $count = count($studentIds);

        // Menggunakan Database Transaction
        DB::beginTransaction();
        try {
            if ($request->target_action === 'alumni') {
                // ==========================================
                // PROSES KELULUSAN / ALUMNI
                // ==========================================
                
                // 1. Update status siswa di tabel utama
                Student::whereIn('id', $studentIds)->update([
                    'status'         => 'graduated',
                    'class_id'       => null, // Dikeluarkan dari kelas aktif
                    'graduated_date' => now()
                ]);
                
                // 2. Rekam ke tabel Riwayat (Mencatat mereka lulus dari kelas mana)
                $historyData = [];
                $now = now()->toDateTimeString();
                foreach ($studentIds as $id) {
                    $historyData[] = [
                        'student_id'    => $id,
                        'class_id'      => $request->from_class_id, // Mencatat kelas terakhir (kelas asal)
                        'academic_year' => $request->academic_year,
                        // 'status' => 'graduated', // Hilangkan komentar ini jika tabel history punya kolom status
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ];
                }
                \App\Models\StudentClassHistory::insert($historyData);
                
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
                    'class_id' => $targetClass->id,
                    'status'   => 'active' // Pastikan statusnya aktif
                ]);

                // 2. Rekam ke tabel Riwayat (Mencatat mereka masuk ke kelas baru)
                $historyData = [];
                $now = now()->toDateTimeString();
                foreach ($studentIds as $id) {
                    $historyData[] = [
                        'student_id'    => $id,
                        'class_id'      => $targetClass->id, // Mencatat kelas tujuan baru
                        'academic_year' => $request->academic_year,
                        'created_at'    => $now,
                        'updated_at'    => $now,
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