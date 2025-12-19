<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterSpt;      // Model SPT
use App\Models\LetterIncoming; // Model Surat Masuk
use App\Models\User;           // Model User/Pegawai
use Carbon\Carbon;

class SptController extends Controller
{
    // MENAMPILKAN DAFTAR SPT
    public function index()
    {
        // Ambil data real dari database dengan relasi users & surat masuk
        // pastikan tabel 'letter_spts' sudah ada (hasil migrasi)
        $spts = LetterSpt::with(['users', 'letterIncoming'])->latest()->paginate(10);
        
        return view('letters.spt.index', compact('spts'));
    }

    // HALAMAN FORM BUAT SPT
    public function create(Request $request)
    {
        // 1. AMBIL DATA PEGAWAI REAL (Sama seperti di SPPD)
        $users = User::orderBy('name', 'asc')->get();

        // 2. AMBIL DATA SURAT MASUK (Untuk dasar surat)
        $incoming_letters = LetterIncoming::latest()->get();

        // Opsional: Jika link diklik dari halaman surat masuk tertentu
        $selected_letter_id = $request->get('from_letter');

        // 3. GENERATE NOMOR OTOMATIS
        // Format: 094/NO/SMP.03/Disdik/BLN/THN
        $bulan_romawi = $this->getRomawi(date('n'));
        $tahun = date('Y');
        
        // Cek nomor urut terakhir di tahun ini
        $last_count = LetterSpt::whereYear('created_at', $tahun)->count() + 1;
        
        $nomor_otomatis = sprintf("094/%03d/SMP.03/Disdik/%s/%s", $last_count, $bulan_romawi, $tahun);

        return view('letters.spt.create', compact('users', 'incoming_letters', 'selected_letter_id', 'nomor_otomatis'));
    }

    // SIMPAN DATA KE DATABASE
    public function store(Request $request)
    {
        $request->validate([
            'nomor_spt' => 'required',
            'pegawai_ids' => 'required|array|min:1', // Wajib pilih minimal 1 pegawai
            'untuk' => 'required',
            'tempat' => 'required',
            'tgl_berangkat' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_berangkat',
        ]);

        // Hitung lama hari
        $start = Carbon::parse($request->tgl_berangkat);
        $end = Carbon::parse($request->tgl_kembali);
        $lama_hari = $start->diffInDays($end) + 1;

        // 1. Simpan Data Utama SPT
        $spt = LetterSpt::create([
            'letter_incoming_id' => $request->letter_incoming_id, // Bisa null
            'nomor_spt' => $request->nomor_spt,
            'untuk' => $request->untuk,
            'tempat_tujuan' => $request->tempat,
            'tgl_berangkat' => $request->tgl_berangkat,
            'tgl_kembali' => $request->tgl_kembali,
            'lama_hari' => $lama_hari,
            // Pejabat Default (Bisa dibuat inputan jika perlu dinamis)
            'pejabat_nama' => 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.',
            'pejabat_nip' => '19820928 201101 1 002',
        ]);

        // 2. Simpan Relasi Pegawai (Pivot Table)
        // Fungsi sync/attach ini memerlukan tabel 'letter_spt_user'
        $spt->users()->attach($request->pegawai_ids);

        return redirect()->route('letters.spt.index')
            ->with('success', 'Surat Perintah Tugas berhasil dibuat dan disimpan ke database.');
    }

    // FUNGSI CETAK
    public function print($id)
    {
        $spt = LetterSpt::with(['users', 'letterIncoming'])->findOrFail($id);
        return view('letters.spt.print', compact('spt'));
    }

    // Helper Angka Romawi
    private function getRomawi($bulan)
    {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[$bulan];
    }
}