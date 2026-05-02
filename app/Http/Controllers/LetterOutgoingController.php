<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterOutgoing;
use App\Models\LetterSpt;
use App\Models\Sppd;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LetterOutgoingController extends Controller
{
    public function index()
    {
        $letters = LetterOutgoing::latest()->paginate(10);
        return view('letters.outgoing.index', compact('letters'));
    }

    public function create()
    {
        // 1. Generate Nomor Agenda Keluar
        $lastLetter = LetterOutgoing::latest('id')->first();
        $nextAgendaKeluar = $lastLetter ? str_pad(intval($lastLetter->nomor_agenda) + 1, 4, '0', STR_PAD_LEFT) : '0001';

        // 2. Ambil data user untuk dropdown "Guru Ditugaskan"
        $users = User::orderBy('name', 'asc')->get();

        // Menggunakan view create_keluar.blade.php yang sudah saya berikan sebelumnya
        return view('letters.outgoing.create', compact('nextAgendaKeluar', 'users'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input Surat Keluar & Dynamic Validation untuk SPT
        $request->validate([
            'nomor_agenda'  => 'required',
            'nomor_surat'   => 'required|string|max:255',
            'tujuan_surat'  => 'required|string|max:255',
            'sifat_surat'   => 'required|string',
            'tgl_surat'     => 'required|date',
            'perihal'       => 'required|string',
            'file_surat'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            
            // Validasi khusus jika Toggle SPT dihidupkan
            'guru_ditugaskan' => 'required_if:is_penugasan,on|array',
            'tgl_berangkat'   => 'required_if:is_penugasan,on|date|nullable',
            'tgl_kembali'     => 'required_if:is_penugasan,on|date|after_or_equal:tgl_berangkat|nullable',
        ]);

        // 2. Simpan Data Surat Keluar
        $dataSurat = $request->only(['nomor_agenda', 'nomor_surat', 'tujuan_surat', 'sifat_surat', 'tgl_surat', 'perihal']);
        
        if ($request->hasFile('file_surat')) {
            $dataSurat['file_path'] = $request->file('file_surat')->store('surat-keluar', 'public');
        }

        $suratKeluar = LetterOutgoing::create($dataSurat);

        // 3. LOGIKA MAGIC: Jika Checkbox Penugasan di-Centang
        if ($request->has('is_penugasan') && $request->is_penugasan === 'on') {
            
            // Hitung lama hari
            $start = Carbon::parse($request->tgl_berangkat);
            $end = Carbon::parse($request->tgl_kembali);
            $lama_hari = $start->diffInDays($end) + 1;

            // Generate Nomor Induk SPT
            $bulan_romawi = $this->getRomawi(date('n'));
            $tahun = date('Y');
            $last_spt_count = LetterSpt::whereYear('created_at', $tahun)->count() + 1;
            $nomor_spt_otomatis = sprintf("094/%03d/SMP.03/Disdik/%s/%s", $last_spt_count, $bulan_romawi, $tahun);

            // Buat 1 SPT yang terhubung ke Surat Keluar ini
            $spt = LetterSpt::create([
                'surat_keluar_id' => $suratKeluar->id,
                'nomor_spt'       => $nomor_spt_otomatis,
                'untuk'           => $request->perihal,       // Mengambil dari perihal surat
                'tempat_tujuan'   => $request->tujuan_surat,  // Mengambil dari tujuan surat
                'tgl_berangkat'   => $request->tgl_berangkat,
                'tgl_kembali'     => $request->tgl_kembali,
                'lama_hari'       => $lama_hari,
                'pejabat_nama'    => 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.',
                'pejabat_nip'     => '19820928 201101 1 002',
            ]);

            // Looping guru yang dipilih
            foreach ($request->guru_ditugaskan as $guru_id) {
                
                // Relasikan Guru ke SPT (Pivot)
                $spt->users()->attach($guru_id);

                // Otomatis buatkan Draft SPPD untuk Guru ini
                // Generate Nomor SPPD Draft
                $last_sppd_count = Sppd::whereYear('created_at', $tahun)->count() + 1;
                $nomor_sppd_otomatis = sprintf("090/%03d/SMP.03/Disdik/%s/%s", $last_sppd_count, $bulan_romawi, $tahun);

                Sppd::create([
                    'spt_id'            => $spt->id,
                    'nomor_sppd'        => $nomor_sppd_otomatis,
                    'user_id'           => $guru_id,
                    'maksud_perjalanan' => $request->perihal,
                    'tempat_berangkat'  => 'SMP Negeri 3 Lakbok',
                    'tempat_tujuan'     => $request->tujuan_surat,
                    'tgl_berangkat'     => $request->tgl_berangkat,
                    'tgl_kembali'       => $request->tgl_kembali,
                    'lama_hari'         => $lama_hari,
                    'instansi_pembayar' => 'SMP Negeri 3 Lakbok',
                    // Atribut lainnya biarkan null/default. Admin bisa edit di menu SPPD nanti.
                    'pejabat_nama'      => 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.',
                    'pejabat_nip'       => '19820928 201101 1 002',
                    'pejabat_pangkat'   => 'Penata, III/c',
                    'pejabat_jabatan'   => 'Kepala Sekolah',
                ]);
            }

            // Arahkan ke halaman SPPD agar admin bisa melengkapi transportasi, biaya, dll
            return redirect()->route('sppd.index')
                ->with('success', 'Surat Keluar berhasil disimpan. Draft SPT dan SPPD untuk pegawai terpilih telah otomatis dibuat!');
        }

        // Jika tidak ada penugasan, cukup kembalikan ke daftar Surat Keluar
        return redirect()->route('letters.outgoing.index')
            ->with('success', 'Surat Keluar berhasil disimpan!');
    }

    private function getRomawi($bulan)
    {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[$bulan];
    }
}