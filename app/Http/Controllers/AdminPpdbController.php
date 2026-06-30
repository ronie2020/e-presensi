<?php

namespace App\Http\Controllers;

use App\Models\PpdbRegistrant;
use App\Models\Student;
use App\Models\SchoolClass; // Pastikan model kelas Anda dipanggil
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash; 

class AdminPpdbController extends Controller
{
    public function index(Request $request)
    {
        $query = PpdbRegistrant::query();

        // Filter Tahun Ajaran
        $year = $request->input('year', date('Y'));
        $query->where('academic_year', $year);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        $registrants = $query->latest()->paginate(10);
        
        $stats = [
            'total' => PpdbRegistrant::where('academic_year', $year)->count(),
            'pending' => PpdbRegistrant::where('academic_year', $year)->where('status', 'pending')->count(),
            'verified' => PpdbRegistrant::where('academic_year', $year)->where('status', 'verified')->count(),
            'accepted' => PpdbRegistrant::where('academic_year', $year)->where('status', 'accepted')->count(),
        ];

        $scheduleData = null;
        if (Storage::exists('ppdb_schedule.json')) {
            $scheduleData = json_decode(Storage::get('ppdb_schedule.json'), true);
        }

        return view('admin.ppdb.index', compact('registrants', 'stats', 'year', 'scheduleData'));
    }

    public function setSchedule(Request $request)
    {
        $request->validate([
            'announcement_date' => 'required|date',
        ]);

        $data = [
            'announcement_date' => $request->announcement_date,
            'updated_at' => now()->toDateTimeString(),
            'updated_by' => auth()->user()->name ?? 'Admin'
        ];

        Storage::put('ppdb_schedule.json', json_encode($data));

        return redirect()->back()->with('success', 'Jadwal pengumuman berhasil disimpan.');
    }

   public function show($id)
    {
        $registrant = PpdbRegistrant::findOrFail($id);        
      
        $isPromoted = false;
        if (Schema::hasColumn('students', 'nisn')) {
            $isPromoted = Student::where('nisn', $registrant->nisn)->exists();
        }

        return view('admin.ppdb.show', compact('registrant', 'isPromoted'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,accepted,rejected',
            'admin_note' => 'nullable|string'
        ]);

        $registrant = PpdbRegistrant::findOrFail($id);
        $registrant->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note
        ]);

        return redirect()->back()->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    // =========================================================================
    // FITUR: UPDATE STATUS MASSAL
    // =========================================================================
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:ppdb_registrants,id',
            'bulk_status' => 'required|in:pending,verified,accepted,rejected'
        ]);

        try {
            PpdbRegistrant::whereIn('id', $request->selected_ids)
                ->update(['status' => $request->bulk_status]);

            // Translasi status untuk pesan sukses
            $statusLabels = [
                'pending' => 'PENDING',
                'verified' => 'TERVERIFIKASI',
                'accepted' => 'DITERIMA',
                'rejected' => 'DITOLAK'
            ];
            
            $label = $statusLabels[$request->bulk_status];

            return redirect()->back()->with('success', count($request->selected_ids) . " calon siswa berhasil diubah statusnya menjadi $label.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

   // =========================================================================
    // AUTOMATISASI NISN, PEMBAGIAN KELAS (7A-7F), & PROMOTE
    // =========================================================================
    public function autoDistributeAndPromote(Request $request)
    {
        set_time_limit(500); // Mencegah timeout
        DB::beginTransaction();
        
        try {
            // 1. Ambil pendaftar DITERIMA yang belum ada di tabel Student
            $registrants = PpdbRegistrant::where('status', 'accepted')
                ->whereNotIn('nisn', function($q) {
                    $q->select('nisn')
                    ->from('students')
                    ->whereNotNull('nisn')
                    ->whereNull('deleted_at'); // Tambahan di sini
                })
                ->get();

            if ($registrants->isEmpty()) {
                return redirect()->back()->with('warning', 'Tidak ada siswa DITERIMA yang siap diproses/dibagikan kelas.');
            }

            // 2. Siapkan Kelas 7A sampai 7F
            $classNames = ['7A', '7B', '7C', '7D', '7E', '7F'];
            $classIds = [];
            foreach ($classNames as $cName) {
                $class = \App\Models\SchoolClass::firstOrCreate(['name' => $cName]);
                $classIds[] = $class->id;
            }

            // 3. FASE PEMBERIAN NIS (Berdasarkan Urutan Abjad)
            $registrants = $registrants->sortBy('full_name')->values();

            $currentYearShort = date('y'); 
            $nextYearShort = sprintf('%02d', $currentYearShort + 1); 
            $prefixNis = $currentYearShort . $nextYearShort . '7'; 
            
            $lastStudent = \App\Models\Student::withTrashed()
                ->where('student_id', 'like', $prefixNis . '%')
                ->orderBy('student_id', 'desc')
                ->first();
                
            $sequence = 1;
            if ($lastStudent && strlen($lastStudent->student_id) >= 8) {
                $lastSequence = (int) substr($lastStudent->student_id, -3);
                $sequence = $lastSequence + 1;
            }

            foreach ($registrants as $reg) {
                $reg->generated_nis = $prefixNis . str_pad($sequence, 3, '0', STR_PAD_LEFT); 
                $sequence++;
            }

            // 4. FASE PEMERATAAN KELAS (Round-Robin)
            $males = $registrants->where('gender', 'L')->sortByDesc('average_grade')->values();
            $females = $registrants->where('gender', 'P')->sortByDesc('average_grade')->values();

            // Pembuatan Keranjang
            $classBuckets = [];
            for ($i = 0; $i < count($classIds); $i++) {
                $classBuckets[] = collect();
            }

            $idx = 0;
            foreach ($males as $m) {
                $classBuckets[$idx]->push($m);
                $idx = ($idx + 1) % count($classIds);
            }

            $idx = 0;
            foreach ($females as $f) {
                $classBuckets[$idx]->push($f);
                $idx = ($idx + 1) % count($classIds);
            }

            // 5. FASE PENYIMPANAN KE DATA INDUK
           $successCount = 0;
            foreach ($classBuckets as $i => $bucket) {
                $classId = $classIds[$i];
                
                foreach ($bucket as $studentReg) {
                    $newPhotoPath = null;
                    if ($studentReg->file_photo && Storage::disk('public')->exists($studentReg->file_photo)) {
                        $ext = pathinfo($studentReg->file_photo, PATHINFO_EXTENSION);
                        $newFileName = 'students/' . $studentReg->nisn . '_' . time() . '.' . $ext;
                        Storage::disk('public')->copy($studentReg->file_photo, $newFileName);
                        $newPhotoPath = $newFileName;
                    }

                    // Cek apakah siswa ini sudah pernah ada di database (termasuk yang di-soft delete)
                    $existingStudent = Student::withTrashed()->where('student_id', $studentReg->nisn)->first();

                    if ($existingStudent) {
                        // JIKA ADA: Pulihkan secara resmi terlebih dahulu, lalu perbarui datanya
                        $existingStudent->restore(); 
                        $existingStudent->update([
                            'nisn'             => $studentReg->nisn,                 
                            'nis'              => $studentReg->generated_nis,         
                            'name'             => $studentReg->full_name,
                            'nik'              => $studentReg->nik,
                            'gender'           => $studentReg->gender,
                            'pob'              => $studentReg->birth_place, 
                            'dob'              => $studentReg->birth_date, 
                            'religion'         => $studentReg->religion,
                            'address'          => $studentReg->address,
                            'phone'            => $studentReg->student_phone,
                            'father_name'      => $studentReg->father_name,
                            'mother_name'      => $studentReg->mother_name,
                            'father_job'       => $studentReg->parent_job,
                            'father_income'    => $studentReg->parent_income, 
                            'parent_wa_number' => $studentReg->parent_phone,
                            'parent_phone'     => $studentReg->parent_phone,
                            'school_origin'    => $studentReg->school_origin,
                            'status'           => 'active',
                            'class_id'         => $classId, 
                            'photo_path'       => $newPhotoPath,
                            'general_notes'    => 'Masuk melalui PPDB Terpusat (Generate Ulang). Jalur: ' . ucfirst($studentReg->track),
                            'achievements'     => $studentReg->achievements ?? null,
                        ]);
                    } else {
                        // JIKA BELUM ADA sama sekali: Buat baris data baru secara bersih
                        Student::create([
                            'student_id'       => $studentReg->nisn,           
                            'nisn'             => $studentReg->nisn,                 
                            'nis'              => $studentReg->generated_nis,         
                            'name'             => $studentReg->full_name,
                            'nik'              => $studentReg->nik,
                            'gender'           => $studentReg->gender,
                            'pob'              => $studentReg->birth_place, 
                            'dob'              => $studentReg->birth_date, 
                            'religion'         => $studentReg->religion,
                            'address'          => $studentReg->address,
                            'phone'            => $studentReg->student_phone,
                            'father_name'      => $studentReg->father_name,
                            'mother_name'      => $studentReg->mother_name,
                            'father_job'       => $studentReg->parent_job,
                            'father_income'    => $studentReg->parent_income, 
                            'parent_wa_number' => $studentReg->parent_phone,
                            'parent_phone'     => $studentReg->parent_phone,
                            'school_origin'    => $studentReg->school_origin,
                            'accepted_date'    => now(), 
                            'join_date'        => now(), 
                            'status'           => 'active',
                            'class_id'         => $classId, 
                            'photo_path'       => $newPhotoPath,
                            'general_notes'    => 'Masuk melalui PPDB Terpusat. Jalur: ' . ucfirst($studentReg->track),
                            'achievements'     => $studentReg->achievements ?? null,
                            'password'         => Hash::make($studentReg->nisn),
                        ]);
                    }

                    $successCount++;
                }
            }

            DB::commit();
            return redirect()->route('admin.ppdb.index')->with('success', "Keajaiban Terjadi! $successCount siswa berhasil dibuatkan NIS berurut (A-Z), dibagi rata ke kelas 7A-7F, dan masuk ke Daftar Induk Siswa.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
    
    public function promoteToStudent($id)
    {
        $registrant = PpdbRegistrant::findOrFail($id);

        if ($registrant->status !== 'accepted') {
            return back()->with('error', 'Hanya siswa DITERIMA yang bisa masuk Data Induk.');
        }

        if (Student::where('nisn', $registrant->nisn)->exists()) {
            return back()->with('error', 'Siswa dengan NISN ini sudah ada di Data Induk.');
        }

        DB::beginTransaction();
        try {
            $newPhotoPath = null;
            if ($registrant->file_photo && Storage::disk('public')->exists($registrant->file_photo)) {
                $ext = pathinfo($registrant->file_photo, PATHINFO_EXTENSION);
                $newFileName = 'students/' . $registrant->nisn . '_' . time() . '.' . $ext;
                Storage::disk('public')->copy($registrant->file_photo, $newFileName);
                $newPhotoPath = $newFileName;
            }

            $nisData = Student::generateNextNis();
            $generatedNis = $nisData['prefix'] . str_pad($nisData['sequence'], 3, '0', STR_PAD_LEFT);

            $student = Student::create([
                'student_id' => $generatedNis, 
                'nis' => $generatedNis,        
                'nisn' => $registrant->nisn,   
                'name' => $registrant->full_name,
                'nik' => $registrant->nik,
                'gender' => $registrant->gender,
                'birth_place' => $registrant->birth_place,
                'birth_date' => $registrant->birth_date,
                'religion' => $registrant->religion, 
                'address' => $registrant->address,
                'phone' => $registrant->student_phone,
                'father_name' => $registrant->father_name,
                'mother_name' => $registrant->mother_name,
                'father_job' => $registrant->parent_job,
                'parent_phone' => $registrant->parent_phone,
                'parent_wa_number' => $registrant->parent_phone, 
                'parent_income' => $registrant->parent_income,
                'school_origin' => $registrant->school_origin,
                'join_date' => now(),
                'status' => 'active',
                'class_id' => null, 
                'photo_path' => $newPhotoPath,
                'general_notes' => 'Masuk Jalur ' . ucfirst($registrant->track) . ' (' . $registrant->academic_year . ')',
                'password' => Hash::make($registrant->nisn),
            ]);

            DB::commit();

            return redirect()->route('students.edit', $student->id)
                             ->with('success', 'Siswa berhasil ditambahkan! Silakan atur KELAS siswa ini.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memindahkan data: ' . $e->getMessage());
        }
    }

    public function bulkPromote(Request $request)
    {
        set_time_limit(300);

        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:ppdb_registrants,id', 
        ]);

        $ids = $request->selected_ids;
        $successCount = 0;
        $failCount = 0;
        $failedNames = []; 

        DB::beginTransaction();
        try {
            $registrants = PpdbRegistrant::whereIn('id', $ids)
                ->where('status', 'accepted')
                ->lockForUpdate() 
                ->get();

            $nisData = Student::generateNextNis();
            $yearShort = $nisData['prefix'];
            $sequence = $nisData['sequence'];

            foreach ($registrants as $registrant) {
                if (Student::where('nisn', $registrant->nisn)->exists()) {
                    $failCount++;
                    $failedNames[] = $registrant->full_name . " (NISN Duplikat)";
                    continue;
                }

                $newPhotoPath = null;
                if ($registrant->file_photo && Storage::disk('public')->exists($registrant->file_photo)) {
                    $ext = pathinfo($registrant->file_photo, PATHINFO_EXTENSION);
                    $newFileName = 'students/' . $registrant->nisn . '_' . time() . '.' . $ext;
                    Storage::disk('public')->copy($registrant->file_photo, $newFileName);
                    $newPhotoPath = $newFileName;
                }

                $generatedNis = $yearShort . str_pad($sequence, 3, '0', STR_PAD_LEFT);
                
                Student::create([
                    'student_id' => $generatedNis,
                    'nis' => $generatedNis,
                    'nisn' => $registrant->nisn,
                    'name' => $registrant->full_name,
                    'nik' => $registrant->nik,
                    'gender' => $registrant->gender,
                    'birth_place' => $registrant->birth_place,
                    'birth_date' => $registrant->birth_date,
                    'religion' => $registrant->religion,
                    'address' => $registrant->address,
                    'phone' => $registrant->student_phone,
                    'father_name' => $registrant->father_name,
                    'mother_name' => $registrant->mother_name,
                    'father_job' => $registrant->parent_job,
                    'parent_phone' => $registrant->parent_phone,
                    'parent_wa_number' => $registrant->parent_phone,
                    'parent_income' => $registrant->parent_income,
                    'school_origin' => $registrant->school_origin,
                    'join_date' => now(),
                    'status' => 'active',
                    'class_id' => null,
                    'photo_path' => $newPhotoPath,
                    'general_notes' => 'Masuk Jalur ' . ucfirst($registrant->track) . ' (' . $registrant->academic_year . ')',
                    'password' => Hash::make($registrant->nisn),
                ]);

                $sequence++; 
                $successCount++;
            }

            DB::commit();

            $message = "Berhasil memindahkan $successCount siswa.";
            if ($failCount > 0) {
                $message .= " Gagal: " . implode(', ', $failedNames);              
                return redirect()->back()->with('success', $message)->with('warning', 'Beberapa siswa gagal dipindahkan karena duplikat.');
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

   public function destroy($id)
    {
        $registrant = PpdbRegistrant::findOrFail($id);
        
        $files = ['file_photo', 'file_kk', 'file_akta', 'file_grades', 'file_kip', 'file_achievement'];
        foreach($files as $file) {
            if ($registrant->$file) Storage::disk('public')->delete($registrant->$file);
        }
        
        $registrant->delete();
        return redirect()->route('admin.ppdb.index')->with('success', 'Data pendaftar berhasil dihapus.');
    }

    public function print($id)
    {
        $registrant = PpdbRegistrant::findOrFail($id);
        return view('ppdb.success', compact('registrant')); 
    }   

    public function reports()
    {
        $year = date('Y');
        $genderStats = [
            'L' => PpdbRegistrant::where('academic_year', $year)->where('gender', 'L')->count(),
            'P' => PpdbRegistrant::where('academic_year', $year)->where('gender', 'P')->count(),
        ];
        $trackStats = [
            'zonasi' => PpdbRegistrant::where('academic_year', $year)->where('track', 'zonasi')->count(),
            'prestasi' => PpdbRegistrant::where('academic_year', $year)->where('track', 'prestasi')->count(),
            'afirmasi' => PpdbRegistrant::where('academic_year', $year)->where('track', 'afirmasi')->count(),
            'pindah_tugas' => PpdbRegistrant::where('academic_year', $year)->where('track', 'pindah_tugas')->count(),
        ];
        $totalRegistrants = array_sum($trackStats);
        $totalAccepted = PpdbRegistrant::where('academic_year', $year)->where('status', 'accepted')->count();

        return view('admin.ppdb.reports', compact('genderStats', 'trackStats', 'totalAccepted', 'totalRegistrants'));
    }

    public function exportExcel()
    {
        $fileName = 'data-ppdb-' . date('Y-m-d') . '.csv';
        $registrants = PpdbRegistrant::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('No. Daftar', 'NISN', 'Nama Lengkap', 'JK', 'Asal Sekolah', 'Jalur', 'Status', 'No HP Ortu');

        $callback = function() use($registrants, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($registrants as $row) {
                fputcsv($file, array(
                    $row->registration_number,
                    "'".$row->nisn,
                    $row->full_name,
                    $row->gender,
                    $row->school_origin,
                    $row->track,
                    $row->status,
                    "'".$row->parent_phone,
                ));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function printRecap()
    {
        $year = date('Y');
        $registrants = PpdbRegistrant::where('academic_year', $year)
                        ->orderBy('status', 'asc')
                        ->orderBy('full_name', 'asc')
                        ->get();

        return view('admin.ppdb.print_recap', compact('registrants', 'year'));
    }

    public function printMassLetters()
    {
        $year = date('Y');
        $registrants = PpdbRegistrant::where('academic_year', $year)
                        ->where('status', 'accepted')
                        ->orderBy('full_name', 'asc')
                        ->get();

        if ($registrants->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada siswa yang berstatus DITERIMA untuk dicetak.');
        }

        return view('admin.ppdb.print_mass_letters', compact('registrants'));
    }

    // =========================================================================
    // FITUR BARU: PDF & EXCEL PEMBAGIAN KELAS
    // =========================================================================
    public function printClassDistribution()
    {
        $year = date('Y');
        // Ambil kelas yang memiliki siswa, urutkan nama siswa sesuai abjad
        $classes = SchoolClass::with(['students' => function($q) {
            $q->orderBy('name', 'asc');
        }])->whereHas('students')
        ->where('name', 'like', '7%') // Tambahan di sini
        ->get();

        return view('admin.ppdb.print_class_distribution', compact('classes', 'year'));
    }

    public function exportClassDistributionExcel()
    {
        // Ambil kelas yang memiliki siswa
        $classes = SchoolClass::with(['students' => function($q) {
            $q->orderBy('name', 'asc');
        }])->whereHas('students')
        ->where('name', 'like', '7%')
        ->get();

        $year = date('Y');
        $fileName = 'pembagian-kelas-7-' . date('Y-m-d') . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function() use($classes, $year) {
            $file = fopen('php://output', 'w');

            // Header Surat Utama
            fputcsv($file, ['PENGUMUMAN PEMBAGIAN KELAS 7']);
            fputcsv($file, ['TAHUN PELAJARAN ' . $year . '/' . ($year + 1)]);
            fputcsv($file, []); // Baris kosong pembatas

            foreach ($classes as $class) {
                // Lewati jika kelas kosong
                if ($class->students->count() > 0) {
                    
                    // Header per Kelas
                    fputcsv($file, ['KELAS: ' . $class->name]);
                    fputcsv($file, ['No', 'NIS', 'NISN', 'Nama Lengkap', 'L/P', 'Asal Sekolah']);

                    $no = 1;
                    $males = 0;
                    $females = 0;

                    foreach ($class->students as $student) {
                        fputcsv($file, [
                            $no++,
                            "'" . $student->nis,  // Tanda kutip agar angka tidak berubah jadi format scientific
                            "'" . $student->nisn, 
                            $student->name,
                            $student->gender,
                            $student->school_origin ?? '-'
                        ]);

                        // Hitung jenis kelamin untuk rekap
                        if ($student->gender == 'L') $males++;
                        if ($student->gender == 'P') $females++;
                    }

                    // Rekapitulasi di bawah kelas
                    fputcsv($file, []); // Baris kosong
                    fputcsv($file, ['REKAPITULASI KELAS ' . $class->name]);
                    fputcsv($file, ['Laki-laki', $males]);
                    fputcsv($file, ['Perempuan', $females]);
                    fputcsv($file, ['Total Keseluruhan', $class->students->count()]);
                    
                    // Jarak yang cukup jauh untuk kelas berikutnya
                    fputcsv($file, []);
                    fputcsv($file, []); 
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}