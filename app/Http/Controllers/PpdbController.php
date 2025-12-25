<?php

namespace App\Http\Controllers;

use App\Models\PpdbRegistrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
     */
    public function store(Request $request)
    {
        // 1. Validasi Input agar data bersih
        $validated = $request->validate([
            // Data Diri
            'full_name' => 'required|string|max:255',
            'nisn' => 'required|numeric|digits:10|unique:ppdb_registrants,nisn',
            'nik' => 'nullable|numeric|digits:16',
            'gender' => 'required|in:L,P',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            
            // Sekolah Asal
            'school_origin' => 'required|string|max:255',
            'npsn_school_origin' => 'nullable|numeric',
            
            // Orang Tua
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'parent_phone' => 'required|numeric',
            'parent_job' => 'nullable|string',
            
            // Jalur & Nilai
            'track' => 'required|in:zonasi,prestasi,afirmasi,pindah_tugas',
            'average_grade' => 'required|numeric|between:0,100',
            
            // File Upload (Wajib Image/PDF & Maksimal 2MB per file)
            'file_photo' => 'nullable|image|max:2048', // JPG/PNG
            'file_kk' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_akta' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_grades' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kip' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'nisn.unique' => 'NISN ini sudah terdaftar sebelumnya.',
            'nisn.digits' => 'NISN harus berjumlah 10 digit angka.',
            'file_photo.max' => 'Ukuran pas foto maksimal 2MB.',
            'required' => 'Kolom :attribute wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            // 2. Handle File Upload ke folder storage
            $paths = [];
            $files = ['file_photo', 'file_kk', 'file_akta', 'file_grades', 'file_kip'];
            
            foreach ($files as $fileKey) {
                if ($request->hasFile($fileKey)) {
                    // Simpan di folder: storage/app/public/ppdb/2025
                    $paths[$fileKey] = $request->file($fileKey)->store('ppdb/' . date('Y'), 'public');
                } else {
                    $paths[$fileKey] = null;
                }
            }

            // 3. Generate Nomor Pendaftaran Otomatis
            // Format: REG-YYYY-XXXX (Contoh: REG-2025-0001)
            $year = date('Y');
            $latest = PpdbRegistrant::where('academic_year', $year)->latest()->first();
            $sequence = 1;
            
            if ($latest) {
                // Ambil 4 digit terakhir nomor registrasi sebelumnya dan tambah 1
                $lastNumber = intval(substr($latest->registration_number, -4));
                $sequence = $lastNumber + 1;
            }
            
            $regNumber = 'REG-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

            // 4. Simpan Data ke Database
            $registrant = PpdbRegistrant::create([
                'registration_number' => $regNumber,
                'academic_year' => $year,
                'status' => 'pending', // Default status menunggu verifikasi
                ...$validated,         // Masukkan semua data input form
                ...$paths,             // Timpa data file dengan path penyimpanan
            ]);

            DB::commit();

            // 5. Redirect ke Halaman Sukses dengan membawa kode pendaftaran
            return redirect()->route('ppdb.success', ['code' => $registrant->registration_number])
                             ->with('success', 'Pendaftaran berhasil dikirim! Silakan simpan bukti pendaftaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Jika error, kembalikan ke form input sebelumnya dengan pesan error
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan halaman cek status/pengumuman PPDB.
     */
    public function index()
    {
        return view('ppdb.check');
    }

    /**
     * Memproses pencarian status siswa.
     */
    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);

        $search = $request->search;

        // Cari berdasarkan No Pendaftaran ATAU NISN
        $registrant = PpdbRegistrant::where('registration_number', $search)
                                    ->orWhere('nisn', $search)
                                    ->first();

        if (!$registrant) {
            return back()->with('error', 'Data tidak ditemukan. Pastikan Nomor Pendaftaran atau NISN benar.');
        }

        return view('ppdb.status', compact('registrant'));
    }
}