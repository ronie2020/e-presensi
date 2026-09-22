<?php

namespace App\Http\Controllers;

use App\Models\PpdbRegistrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

// Tambahkan Import untuk Excel Export & Import
use App\Imports\PpdbRegistrantImport;
use App\Exports\PpdbTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

class PpdbController extends Controller
{
    /**
     * Menampilkan formulir pendaftaran PPDB.
     */
    public function create()
    {
        return view('ppdb.register');
    }

    /**
     * Memproses data pendaftaran yang dikirim siswa.
     * FIX BUG 2: lockForUpdate() dipindahkan ke DALAM transaksi agar efektif.
     * FIX BUG 3: File yang terupload dibersihkan jika transaksi gagal (rollback).
     */
    public function store(Request $request)
    {
        // Validasi form standar
        $validated = $request->validate([
            'full_name'          => 'required|string|max:255',
            'nisn'               => 'required|numeric|digits:10|unique:ppdb_registrants,nisn',
            'nik'                => 'nullable|numeric|digits:16', // FIX BUG 4: NIK tetap nullable di server
            'gender'             => 'required|in:L,P',
            'birth_place'        => 'required|string|max:100',
            'birth_date'         => 'required|date',
            'religion'           => 'nullable|string|max:50',
            'address'            => 'required|string',
            'school_origin'      => 'required|string|max:255',
            'npsn_school_origin' => 'nullable|numeric',
            'father_name'        => 'required|string|max:255',
            'mother_name'        => 'required|string|max:255',
            'parent_phone'       => 'required|numeric',
            'parent_job'         => 'nullable|string',
            'parent_income'      => 'nullable|string',
            'student_phone'      => 'nullable|numeric', // FIX C1: tambahkan validasi student_phone
            'track'              => 'required|in:zonasi,prestasi,afirmasi,pindah_tugas',
            'average_grade'      => 'required|numeric|between:0,100',
            'grades_detail'      => 'nullable|string',  // FIX BUG 6: nilai per mapel (JSON string dari JS)
            'file_photo'         => 'nullable|image|max:2048',
            'file_kk'            => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_akta'          => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_grades'        => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kip'           => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_achievement'   => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            // Data prestasi
            'achievement_type'   => 'nullable|in:akademik,non_akademik',
            'achievement_name'   => 'nullable|string|max:255',
            'achievement_level'  => 'nullable|string|max:100',
            'achievement_rank'   => 'nullable|string|max:50',
        ], [
            'nisn.unique'        => 'NISN ini sudah terdaftar sebelumnya.',
            'nisn.digits'        => 'NISN harus berjumlah 10 digit angka.',
            'file_photo.max'     => 'Ukuran pas foto maksimal 2MB.',
            'required'           => 'Kolom :attribute wajib diisi.',
        ]);

        // FIX BUG 3: Upload file DULU sebelum transaksi, simpan paths agar bisa dihapus jika gagal
        $paths = [];
        $uploadedPaths = []; // Lacak file yang berhasil diupload untuk cleanup

        $fileKeys = ['file_photo', 'file_kk', 'file_akta', 'file_grades', 'file_kip', 'file_achievement'];
        foreach ($fileKeys as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $path = $request->file($fileKey)->store('ppdb/' . date('Y'), 'public');
                $paths[$fileKey] = $path;
                $uploadedPaths[] = $path; // Simpan untuk keperluan rollback
            } else {
                $paths[$fileKey] = null;
            }
        }

        // FIX BUG 6: Decode grades_detail JSON string menjadi array
        $gradesDetail = null;
        if (!empty($validated['grades_detail'])) {
            $gradesDetail = json_decode($validated['grades_detail'], true);
        }

        // FIX BUG 2: Semua logika nomor urut sekarang ADA DI DALAM transaksi
        DB::beginTransaction();
        try {
            $year = date('Y');

            // lockForUpdate() harus di dalam beginTransaction() agar efektif mencegah race condition
            $latest = PpdbRegistrant::where('academic_year', $year)
                        ->lockForUpdate()
                        ->latest()
                        ->first();

            $sequence = 1;
            if ($latest) {
                $lastNumber = intval(substr($latest->registration_number, -4));
                $sequence   = $lastNumber + 1;
            }

            $regNumber = 'REG-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

            $registrant = PpdbRegistrant::create([
                'registration_number' => $regNumber,
                'academic_year'       => $year,
                'status'              => 'pending',
                ...$validated,
                ...$paths,
                'grades_detail'       => $gradesDetail, // FIX BUG 6: simpan nilai per mapel
            ]);

            DB::commit();

            return redirect()->route('ppdb.success', ['code' => $registrant->registration_number])
                             ->with('success', 'Pendaftaran berhasil dikirim! Silakan simpan bukti pendaftaran.');

        } catch (\Exception $e) {
            DB::rollBack();

            // FIX BUG 3: Hapus file yang sudah terupload agar tidak orphan di storage
            foreach ($uploadedPaths as $uploadedPath) {
                Storage::disk('public')->delete($uploadedPath);
            }

            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan Halaman Sukses Pendaftaran
     */
    public function success($code)
    {
        $registrant = PpdbRegistrant::where('registration_number', $code)->firstOrFail();
        return view('ppdb.success', compact('registrant'));
    }

    /**
     * Helper untuk mengambil tanggal pengumuman.
     */
    private function getAnnouncementDate()
    {
        $announcementDate = null;
        if (Storage::exists('ppdb_schedule.json')) {
            $data = json_decode(Storage::get('ppdb_schedule.json'), true);
            if (isset($data['announcement_date'])) {
                $announcementDate = Carbon::parse($data['announcement_date']);
            }
        }
        return $announcementDate ?? Carbon::now()->addYears(1);
    }

    /**
     * Menampilkan halaman cek status.
     */
    public function index()
    {
        $announcementDate = $this->getAnnouncementDate();
        return view('ppdb.check', compact('announcementDate'));
    }

    /**
     * Memproses pencarian status siswa.
     */
    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);

        $announcementDate = $this->getAnnouncementDate();

        // 1. Cek Apakah Pengumuman Sudah Dibuka?
        if (Carbon::now()->lessThan($announcementDate)) {
            return view('ppdb.check', [
                'announcementDate' => $announcementDate,
                'customError'      => 'Pengumuman belum dibuka. Harap tunggu waktu hitung mundur selesai.'
            ]);
        }

        $search     = $request->search;
        $registrant = PpdbRegistrant::where('registration_number', $search)
                                    ->orWhere('nisn', $search)
                                    ->first();

        // 2. Jika Data Tidak Ditemukan
        if (!$registrant) {
            return view('ppdb.check', [
                'announcementDate' => $announcementDate,
                'customError'      => 'Data tidak ditemukan. Pastikan Nomor Pendaftaran atau NISN benar.'
            ]);
        }

        // 3. Jika Data Ditemukan
        return view('ppdb.status', compact('registrant', 'announcementDate'));
    }

    /**
     * Cetak Surat Kelulusan.
     */
    public function printLetter($id)
    {
        $registrant = PpdbRegistrant::findOrFail($id);

        if ($registrant->status !== 'accepted') {
            $announcementDate = $this->getAnnouncementDate();
            return view('ppdb.check', [
                'announcementDate' => $announcementDate,
                'customError'      => 'Akses ditolak. Surat kelulusan hanya tersedia bagi peserta yang dinyatakan DITERIMA.'
            ]);
        }

        return view('ppdb.print-letter', compact('registrant'));
    }

    /**
     * Halaman Upload Kolektif (Untuk Guru SD)
     */
    public function collective()
    {
        return view('ppdb.collective');
    }

    /**
     * Proses Upload Excel
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls'
        ], [
            'file_excel.required' => 'Harap pilih file Excel terlebih dahulu.',
            'file_excel.mimes'    => 'Format file harus .xlsx atau .xls'
        ]);

        try {
            $import = new PpdbRegistrantImport();
            Excel::import($import, $request->file('file_excel'));

            return redirect()->route('ppdb.collective.success')
                             ->with('success', 'Data siswa berhasil diimpor!')
                             ->with('imported_data', $import->importedData);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $errorMsg = "Gagal Import: <br>";
             foreach ($failures as $failure) {
                 $errorMsg .= "- Baris " . $failure->row() . ": " . implode(', ', $failure->errors()) . "<br>";
             }
             return redirect()->back()->with('error', $errorMsg);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Sukses Import Kolektif (Rekap Data)
     */
    public function collectiveSuccess()
    {
        // Cegah akses langsung jika tidak ada data dari session
        if (!session('imported_data')) {
            return redirect()->route('ppdb.collective')->with('error', 'Tidak ada data import terbaru untuk ditampilkan.');
        }

        return view('ppdb.collective-success');
    }

    /**
     * Download Template Excel (Auto Generate)
     */
    public function downloadTemplate()
    {
        $fileName = 'template_ppdb_kolektif.xlsx';
        return Excel::download(new PpdbTemplateExport, $fileName);
    }
}