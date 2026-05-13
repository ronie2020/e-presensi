<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Graduation;
use App\Models\SchoolClass;
use App\Models\AlumniProfile; 
use App\Models\StudentClassHistory;
use App\Models\AcademicYear; // <-- Wajib ditambahkan agar tidak error "Class not found"
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class GraduationController extends Controller
{
    // --- HALAMAN SISWA (Cek Kelulusan & SKL) ---
    public function index()
    {
        // Menggunakan helper active() dari model AcademicYear
        $activeYear = AcademicYear::active(); 
        $tahunAjaran = $activeYear ? $activeYear->name : date('Y') . '/' . (date('Y') + 1);

        $firstGraduation = Graduation::whereNotNull('announcement_date')->orderBy('announcement_date', 'asc')->first();
        $announcementDate = ($firstGraduation && $firstGraduation->announcement_date) ? Carbon::parse($firstGraduation->announcement_date) : Carbon::now()->addYear();
        
        // Jangan lupa compact('tahunAjaran')
        return view('graduation.index', compact('announcementDate', 'tahunAjaran'));
    }

    public function check(Request $request)
    {
        $request->validate(['nisn' => 'required|numeric']);
        $student = Student::where('student_id', $request->nisn)->with(['graduation', 'schoolClass'])->first();

        if (!$student) return back()->with('error', 'NISN tidak ditemukan.');
        if ($student->schoolClass && !str_starts_with($student->schoolClass->name, '9')) return back()->with('error', 'Siswa bukan tingkat akhir (Kelas 9).');
        if (!$student->graduation) return back()->with('error', 'Data kelulusan belum dirilis.');
        if ($student->graduation->announcement_date && now() < $student->graduation->announcement_date) return back()->with('error', 'Pengumuman belum dibuka.');

        // Ambil tahun ajaran untuk ditampilkan di hasil pencarian
        $activeYear = AcademicYear::active(); 
        $tahunAjaran = $activeYear ? $activeYear->name : date('Y') . '/' . (date('Y') + 1);

        $announcementDate = $student->graduation->announcement_date ? Carbon::parse($student->graduation->announcement_date) : Carbon::now()->addYear();
        
        // Jangan lupa compact('tahunAjaran')
        return view('graduation.index', compact('student', 'announcementDate', 'tahunAjaran'));
    }

      public function printSkl($id)
    {
        $student = Student::with('graduation')->findOrFail($id);
        if ($student->graduation->status !== 'LULUS') return back()->with('error', 'SKL hanya untuk siswa LULUS.');
        
        $settings = Storage::disk('local')->exists('graduation_settings.json')
            ? json_decode(Storage::disk('local')->get('graduation_settings.json'), true)
            : [
                'letter_number' => '421.3/     /SMP.03/' . date('Y'),
                'principal_name' => 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.',
                'principal_nip' => '197xxxxxx...'
            ];

        // MENGUBAH DARI DOMPDF MENJADI BROWSER PRINT (Sama seperti SPPD)
        return view('graduation.pdf_skl', compact('student', 'settings'));
    }

    // --- HALAMAN ADMIN (Manajemen) ---
    public function adminIndex(Request $request)
    {
        $classes = SchoolClass::where('name', 'LIKE', '9%')->orderBy('name')->get();
        
        $query = Student::with(['schoolClass', 'graduation'])
            ->whereHas('schoolClass', fn($q) => $q->where('name', 'LIKE', '9%'))
            ->orderBy('name');

        if ($request->filled('class_id')) $query->where('class_id', $request->class_id);
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')->orWhere('student_id', 'like', '%'.$request->search.'%');
            });
        }

        $students = $query->paginate(20)->withQueryString();
        
        $settings = Storage::disk('local')->exists('graduation_settings.json')
            ? json_decode(Storage::disk('local')->get('graduation_settings.json'), true)
            : [
                'letter_number' => '421.3/     /SMP.03/' . date('Y'),
                'principal_name' => 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.',
                'principal_nip' => '197xxxxxx...'
            ];

        return view('admin.graduation.index', compact('students', 'classes', 'settings'));
    }
    
    public function saveSettings(Request $request) 
    {
        $data = $request->validate([
            'letter_number' => 'required|string',
            'principal_name' => 'required|string',
            'principal_nip' => 'required|string',
        ]);

        Storage::disk('local')->put('graduation_settings.json', json_encode($data));
        return back()->with('success', 'Pengaturan dokumen SKL berhasil disimpan.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'status' => 'required|in:LULUS,TIDAK LULUS,DITUNDA',
            'average_score' => 'nullable|numeric',
            'skl_number' => 'nullable|string',
            'announcement_date' => 'nullable'
        ]);

        $announcementDate = null;
        if (!empty($data['announcement_date'])) {
            try {
                $announcementDate = Carbon::parse($data['announcement_date'])->format('Y-m-d H:i:s');
            } catch (\Exception $e) { /* ignore */ }
        }

        Graduation::updateOrCreate(
            ['student_id' => $data['student_id']],
            [
                'status' => $data['status'],
                'average_score' => $data['average_score'],
                'skl_number' => $data['skl_number'],
                'academic_year' => date('Y') . '/' . (date('Y') + 1),
                'announcement_date' => $announcementDate
            ]
        );
        
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Data berhasil disimpan!', 'success' => true]);
        }

        return back()->with('success', 'Data kelulusan siswa berhasil disimpan.');
    }

    public function bulkUpdate(Request $request) 
    {
        $data = $request->input('students');
        if ($data && is_array($data)) {
            DB::transaction(function () use ($data) {
                foreach ($data as $studentId => $fields) {
                    if(!isset($fields['status'])) continue;
                    $date = !empty($fields['announcement_date']) ? Carbon::parse($fields['announcement_date'])->format('Y-m-d H:i:s') : null;
                    Graduation::updateOrCreate(['student_id' => $studentId], [
                        'status' => $fields['status'],
                        'average_score' => $fields['average_score'] ?? 0,
                        'skl_number' => $fields['skl_number'] ?? null,
                        'announcement_date' => $date,
                        'academic_year' => date('Y') . '/' . (date('Y') + 1),
                    ]);
                }
            });
            return back()->with('success', 'Semua data berhasil diperbarui.');
        }
        return back()->with('error', 'Tidak ada data.');
    }

    public function setGlobalDate(Request $request) 
    {
        $request->validate(['global_date' => 'required|date']);
        $date = Carbon::parse($request->global_date)->format('Y-m-d H:i:s');
        
        $query = Student::whereHas('schoolClass', fn($q) => $q->where('name', 'LIKE', '9%'));
        if($request->class_filter) $query->where('class_id', $request->class_filter);
        $students = $query->get();
        
        DB::transaction(function () use ($students, $date) {
            foreach($students as $student) {
                $grad = Graduation::firstOrNew(['student_id' => $student->id]);
                
                $grad->announcement_date = $date;
                $grad->academic_year = date('Y').'/'.(date('Y')+1);
                
                if (!$grad->exists || in_array($grad->status, [null, 'DITUNDA'])) {
                    $grad->status = 'LULUS';
                }
                
                $grad->save();
            }
        });
        
        return back()->with('success', 'Jadwal berhasil diupdate & Seluruh siswa otomatis diset LULUS secara default.');
    }

    // ===> FUNGSI BARU: GENERATE NOMOR SKL MASSAL <===
    public function bulkSetSklNumber(Request $request) 
    {
        $request->validate([
            'skl_format' => 'required|string',
            'start_number' => 'nullable|integer|min:1'
        ]);

        $format = $request->skl_format;
        $counter = $request->start_number ?? 1;

        // Ambil data siswa berdasarkan filter kelas (jika ada), urutkan berdasarkan nama abjad
        $query = Student::whereHas('schoolClass', fn($q) => $q->where('name', 'LIKE', '9%'))->orderBy('name');
        if($request->class_filter) {
            $query->where('class_id', $request->class_filter);
        }
        $students = $query->get();

        // Cari tanggal pengumuman yang sudah ada di database sebagai nilai default (seperti fitur Import)
        $existingDate = Graduation::whereNotNull('announcement_date')->value('announcement_date');
        $defaultDate = $existingDate ? Carbon::parse($existingDate)->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s');

        DB::transaction(function () use ($students, $format, &$counter, $defaultDate) {
            foreach($students as $student) {
                // Jika user menggunakan {urut}, ganti dengan angka urutan (misal: 001, 002)
                $formattedNumber = str_replace('{urut}', str_pad($counter, 3, '0', STR_PAD_LEFT), $format);
                
                // Gunakan firstOrNew untuk mencegah tertimpanya data lama
                $graduation = Graduation::firstOrNew(['student_id' => $student->id]);
                
                $graduation->skl_number = $formattedNumber;
                $graduation->academic_year = date('Y') . '/' . (date('Y') + 1);
                
                // Jika ini adalah data baru diciptakan, isi announcement_date agar tidak error 1364 di database
                if (!$graduation->exists) {
                    $graduation->announcement_date = $defaultDate;
                    $graduation->status = 'LULUS'; // Opsional: Berikan status default agar tidak kosong
                }
                
                $graduation->save();
                
                $counter++; // Naikkan nomor untuk siswa berikutnya
            }
        });

        return back()->with('success', 'Nomor SKL berhasil di-generate secara massal.');
    }

    // --- IMPORT CSV ---
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt|max:2048']);
        $file = $request->file('file');
        $handle = fopen($file->getPathname(), "r");
        fgetcsv($handle); 

        $count = 0;
        $existingDate = Graduation::whereNotNull('announcement_date')->value('announcement_date');
        $defaultDate = $existingDate ? Carbon::parse($existingDate)->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s');

        DB::transaction(function () use ($handle, &$count, $defaultDate) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($data) < 2) continue;
                $nisn = trim($data[0]);
                $status = strtoupper(trim($data[1]));
                $nilai = isset($data[2]) ? floatval(str_replace(',', '.', $data[2])) : 0;
                
                $student = Student::where('student_id', $nisn)->first();
                if ($student) {
                    Graduation::updateOrCreate(
                        ['student_id' => $student->id],
                        [
                            'status' => in_array($status, ['LULUS', 'TIDAK LULUS']) ? $status : 'DITUNDA',
                            'average_score' => $nilai,
                            'academic_year' => date('Y') . '/' . (date('Y') + 1),
                            'announcement_date' => $student->graduation->announcement_date ?? $defaultDate
                        ]
                    );
                    $count++;
                }
            }
        });
        fclose($handle);
        return back()->with('success', "Import selesai. $count data diperbarui.");
    }
    
    public function autoGenerate(Request $request) { 
       return back()->with('success', 'Fitur Auto Generate dipanggil');
    }
    public function downloadTemplate() { /* ... */ }

    // ===> PINDAHKAN SISWA LULUS KE ALUMNI <===
    public function processAlumni(Request $request)
    {
        $students = Student::with('graduation')
            ->whereHas('graduation', function($q) {
                $q->where('status', 'LULUS');
            })
            ->where('status', '!=', 'graduated') 
            ->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'Tidak ada siswa berstatus LULUS yang belum diproses.');
        }

        $count = 0;
        $historyData = [];
        $now = now()->toDateTimeString();

        DB::transaction(function () use ($students, &$count, &$historyData, $now) {
            foreach ($students as $student) {
                $oldClassId = $student->class_id;

                $student->update([
                    'status' => 'graduated',
                    'class_id' => null,
                    'graduated_date' => $student->graduation->announcement_date ?? now(),
                ]);

                if (!AlumniProfile::where('student_id', $student->id)->exists()) {
                    AlumniProfile::create(['student_id' => $student->id]);
                }

                if ($oldClassId) {
                    $historyData[] = [
                        'student_id'    => $student->id,
                        'class_id'      => $oldClassId,
                        'academic_year' => $student->graduation->academic_year ?? (date('Y') . '/' . (date('Y') + 1)),
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ];
                }
                $count++;
            }

            if (!empty($historyData)) {
                StudentClassHistory::insert($historyData);
            }
        });

        return back()->with('success', "Berhasil memindahkan $count siswa ke Database Alumni.");
    }
}