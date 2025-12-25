<?php

namespace App\Http\Controllers;

use App\Models\PpdbRegistrant;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AdminPpdbController extends Controller
{
    /**
     * Menampilkan daftar pendaftar dan panel pengaturan jadwal.
     */
    public function index(Request $request)
    {
        $query = PpdbRegistrant::query();

        // Filter Tahun Ajaran
        $year = $request->input('year', date('Y'));
        $query->where('academic_year', $year);

        // Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        $registrants = $query->latest()->paginate(10);
        
        // Statistik
        $stats = [
            'total' => PpdbRegistrant::where('academic_year', $year)->count(),
            'pending' => PpdbRegistrant::where('academic_year', $year)->where('status', 'pending')->count(),
            'verified' => PpdbRegistrant::where('academic_year', $year)->where('status', 'verified')->count(),
            'accepted' => PpdbRegistrant::where('academic_year', $year)->where('status', 'accepted')->count(),
        ];

        // Load Jadwal
        $scheduleData = null;
        if (Storage::exists('ppdb_schedule.json')) {
            $scheduleData = json_decode(Storage::get('ppdb_schedule.json'), true);
        }

        return view('admin.ppdb.index', compact('registrants', 'stats', 'year', 'scheduleData'));
    }

    /**
     * Menyimpan Jadwal Pengumuman
     */
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

    /**
     * Detail Pendaftar
     */
    public function show($id)
    {
        $registrant = PpdbRegistrant::findOrFail($id);
        
        // Cek apakah sudah dipromosikan (berdasarkan NISN)
        // Kita asumsikan tabel students sudah diperbarui dengan kolom nisn
        $isPromoted = false;
        if (Schema::hasColumn('students', 'nisn')) {
            $isPromoted = Student::where('nisn', $registrant->nisn)->exists();
        }

        return view('admin.ppdb.show', compact('registrant', 'isPromoted'));
    }

    /**
     * Update Status Pendaftaran
     */
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

    /**
     * FITUR UTAMA: Promote ke Data Siswa
     */
    public function promoteToStudent($id)
    {
        $registrant = PpdbRegistrant::findOrFail($id);

        if ($registrant->status !== 'accepted') {
            return back()->with('error', 'Hanya siswa DITERIMA yang bisa masuk Data Induk.');
        }

        // Cek Duplikasi NISN
        if (Student::where('nisn', $registrant->nisn)->exists()) {
            return back()->with('error', 'Siswa dengan NISN ini sudah ada di Data Induk.');
        }

        DB::beginTransaction();
        try {
            // 1. Copy Foto
            $newPhotoPath = null;
            if ($registrant->file_photo && Storage::disk('public')->exists($registrant->file_photo)) {
                $ext = pathinfo($registrant->file_photo, PATHINFO_EXTENSION);
                $newFileName = 'students/' . $registrant->nisn . '_' . time() . '.' . $ext;
                Storage::disk('public')->copy($registrant->file_photo, $newFileName);
                $newPhotoPath = $newFileName;
            }

            // 2. Generate NIS Sementara (Format: YY001)
            $yearShort = date('y'); 
            $lastStudent = Student::where('nis', 'like', $yearShort . '%')->orderBy('nis', 'desc')->first();
            $newSequence = $lastStudent ? intval(substr($lastStudent->nis, -3)) + 1 : 1;
            $generatedNis = $yearShort . str_pad($newSequence, 3, '0', STR_PAD_LEFT);

            // 3. Simpan ke Students
            // Kita mapping kolom PPDB ke kolom Students yang baru dibuat
            $student = Student::create([
                'student_id' => $generatedNis, // ID Sistem (bisa sama dengan NIS)
                'nis' => $generatedNis,        // NIS Lokal
                'nisn' => $registrant->nisn,   // NISN Nasional
                'name' => $registrant->full_name,
                'nik' => $registrant->nik,
                'gender' => $registrant->gender,
                'birth_place' => $registrant->birth_place,
                'birth_date' => $registrant->birth_date,
                'religion' => $registrant->religion ?? 'Islam',
                'address' => $registrant->address,
                'phone' => $registrant->student_phone,
                
                // Data Ortu
                'father_name' => $registrant->father_name,
                'mother_name' => $registrant->mother_name,
                'father_job' => $registrant->parent_job,
                'parent_phone' => $registrant->parent_phone,
                'parent_wa_number' => $registrant->parent_phone, // Asumsi WA sama dengan HP
                'parent_income' => $registrant->parent_income,
                
                // Akademik
                'school_origin' => $registrant->school_origin,
                'join_date' => now(),
                'status' => 'active',
                'class_id' => null, // Belum punya kelas
                'photo_path' => $newPhotoPath,
                'general_notes' => 'Masuk Jalur ' . ucfirst($registrant->track) . ' (' . $registrant->academic_year . ')',
            ]);

            DB::commit();

            return redirect()->route('students.edit', $student->id)
                             ->with('success', 'Siswa berhasil ditambahkan! Silakan atur KELAS siswa ini.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memindahkan data: ' . $e->getMessage());
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

    /**
     * Halaman Laporan & Statistik
     */
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

    // --- LOGIKA EXPORT & PRINT MASSAL ---

    /**
     * Export Excel
     */
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
}