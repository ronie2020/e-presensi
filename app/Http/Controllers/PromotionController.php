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
        
        if ($request->has('from_class_id') && $request->from_class_id != '') {
            $fromClassId = $request->from_class_id;
            
            $query = Student::with('schoolClass')->where(function($q) {
                // Pastikan tidak memanggil siswa yang sudah lulus
                $q->where('status', '!=', 'graduated')
                  ->orWhereNull('status');
            });

            // LOGIKA BARU: Jika memilih "Semua Tingkat" (Contoh: level_7)
            if (str_starts_with($fromClassId, 'level_')) {
                $level = substr($fromClassId, 6); // Ambil angka tingkatnya, misal "7"
                // Cari ID semua kelas yang namanya berawalan "7" (7A, 7B, 7C, dst)
                $levelClassIds = SchoolClass::where('name', 'like', $level . '%')->pluck('id');
                $query->whereIn('class_id', $levelClassIds);
            } else {
                // Jika memilih 1 kelas spesifik
                $query->where('class_id', $fromClassId);
            }

            $students = $query->orderBy('name', 'asc')->get();
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
            'from_class_id' => 'required',
            'student_ids'   => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'academic_year' => ['required', 'string', 'regex:/^\d{4}\/\d{4}$/'], 
            'target_action' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Valid jika tujuannya alumni atau fitur acak (roll_X)
                    if ($value === 'alumni' || str_starts_with($value, 'roll_')) {
                        return;
                    }
                    // Jika bukan keduanya, pastikan ID kelas tujuan ada
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
            
            // ==========================================
            // LOGIKA 1: PROSES KELULUSAN / ALUMNI
            // ==========================================
            if ($request->target_action === 'alumni') {
                
                Student::whereIn('id', $studentIds)->update([
                    'status'         => 'graduated',
                    'class_id'       => null, 
                    'graduated_date' => now()
                ]);
                
                $historyData = [];
                $now = now()->toDateTimeString();
                // Ambil data siswa untuk mengetahui kelas terakhir mereka
                $selectedStudents = Student::whereIn('id', $studentIds)->get();
                
                foreach ($selectedStudents as $student) {
                    $historyData[] = [
                        'student_id'    => $student->id,
                        'class_id'      => $student->class_id, // Kelas asal
                        'academic_year' => $request->academic_year,
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ];
                }
                \App\Models\StudentClassHistory::insert($historyData);
                $message = "Berhasil! {$count} Siswa telah diluluskan dan menjadi alumni.";
                
            } 
            // ==========================================
            // LOGIKA 2: ROLLING/ACAK TINGKAT MASSAL
            // ==========================================
            elseif (str_starts_with($request->target_action, 'roll_')) {
                
                $level = substr($request->target_action, 5); // misal "8"
                $targetClasses = SchoolClass::where('name', 'like', $level . '%')->orderBy('name')->get();

                if ($targetClasses->isEmpty()) {
                    return back()->with('error', "Gagal! Kelas tujuan untuk tingkat {$level} tidak ditemukan (Belum ada kelas 8A, 8B, dst di master data).");
                }

                $selectedStudents = Student::whereIn('id', $studentIds)->get();

                // Pisahkan berdasarkan Gender
                $males = $selectedStudents->where('gender', 'L')->values();
                $females = $selectedStudents->where('gender', 'P')->values();

                // Siapkan Keranjang Kelas
                $classBuckets = [];
                for ($i = 0; $i < $targetClasses->count(); $i++) {
                    $classBuckets[] = collect();
                }

                // Bagikan Laki-laki secara Round-Robin
                $idx = 0;
                foreach ($males as $m) {
                    $classBuckets[$idx]->push($m);
                    $idx = ($idx + 1) % $targetClasses->count();
                }

                // Bagikan Perempuan secara Round-Robin
                $idx = 0;
                foreach ($females as $f) {
                    $classBuckets[$idx]->push($f);
                    $idx = ($idx + 1) % $targetClasses->count();
                }

                $historyData = [];
                $now = now()->toDateTimeString();

                // Simpan ke Database
                foreach ($classBuckets as $i => $bucket) {
                    $targetClassId = $targetClasses[$i]->id;
                    $bucketStudentIds = $bucket->pluck('id')->toArray();

                    if (!empty($bucketStudentIds)) {
                        // Update Kelas Siswa
                        Student::whereIn('id', $bucketStudentIds)->update([
                            'class_id' => $targetClassId,
                            'status' => 'active'
                        ]);

                        // Catat ke History
                        foreach ($bucketStudentIds as $id) {
                            $historyData[] = [
                                'student_id'    => $id,
                                'class_id'      => $targetClassId,
                                'academic_year' => $request->academic_year,
                                'created_at'    => $now,
                                'updated_at'    => $now,
                            ];
                        }
                    }
                }
                
                \App\Models\StudentClassHistory::insert($historyData);
                $message = "Keajaiban Terjadi! {$count} Siswa berhasil diacak merata dan dipindahkan ke seluruh Kelas {$level}.";

            }
            // ==========================================
            // LOGIKA 3: NAIK KELAS / PINDAH 1 KELAS SPESIFIK
            // ==========================================
            else {
                $targetClass = SchoolClass::findOrFail($request->target_action);
                
                if ($request->from_class_id == $targetClass->id) {
                    return back()->with('error', 'Kelas tujuan tidak boleh sama dengan kelas asal!');
                }

                Student::whereIn('id', $studentIds)->update([
                    'class_id' => $targetClass->id,
                    'status'   => 'active' 
                ]);

                $historyData = [];
                $now = now()->toDateTimeString();
                foreach ($studentIds as $id) {
                    $historyData[] = [
                        'student_id'    => $id,
                        'class_id'      => $targetClass->id, 
                        'academic_year' => $request->academic_year,
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ];
                }
                \App\Models\StudentClassHistory::insert($historyData);

                $message = "Berhasil! {$count} Siswa telah dipindahkan ke kelas {$targetClass->name}.";
            }

            DB::commit();
            return redirect()->route('promotions.index', ['from_class_id' => $request->from_class_id])
                             ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}