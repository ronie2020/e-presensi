<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LetterIncoming;
use App\Models\LetterSpt;
use App\Models\Sppd;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LetterIncomingController extends Controller
{
    // MENAMPILKAN DATA DARI DATABASE (DENGAN PENCARIAN & FILTER)
    public function index(Request $request)
    {
        $query = LetterIncoming::query();

        // 1. Logika Pencarian Teks
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('asal_surat', 'like', "%{$search}%");
            });
        }

        // 2. Logika Filter Sifat Surat
        if ($request->has('sifat_surat') && $request->sifat_surat != '') {
            $query->where('sifat_surat', $request->sifat_surat);
        }
        
        // Gunakan withQueryString() agar filter/pencarian terbawa saat pagination
        $letters = $query->latest()->paginate(10)->withQueryString();

        return view('letters.incoming.index', compact('letters'));
    }

     public function create()
    {
        // Logika Nomor Agenda Otomatis
        $lastLetter = LetterIncoming::latest('id')->first();
        
        if (!$lastLetter) {
            // Jika belum ada data sama sekali, mulai dari 0001
            $nextAgenda = '0001';
        } else {
            // Ambil angka dari agenda terakhir, jadikan integer, tambah 1
            $lastAgendaNumber = intval($lastLetter->nomor_agenda);
            // Format kembali menjadi 4 digit (contoh: 0002, 0003, dst)
            $nextAgenda = str_pad($lastAgendaNumber + 1, 4, '0', STR_PAD_LEFT);
        }

        // Ambil data user untuk dropdown "Guru Ditugaskan"
        $users = User::orderBy('name', 'asc')->get();

        // Kirim variabel $nextAgenda & $users ke halaman view
        return view('letters.incoming.create', compact('nextAgenda', 'users'));
    }
    
    // MENYIMPAN DATA BARU
    public function store(Request $request)
    {
        $request->validate([
            'nomor_agenda' => 'required|string|unique:letter_incomings,nomor_agenda',
            'nomor_surat'  => 'required|string|max:255',
            'sifat_surat'  => 'required|string',
            'asal_surat'   => 'required|string|max:255',
            'tgl_surat'    => 'required|date',
            'tgl_diterima' => 'required|date',
            'perihal'      => 'required|string',
            'file_surat'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

            // Validasi khusus jika Toggle Penugasan dihidupkan
            'guru_ditugaskan' => 'required_if:is_penugasan,on|array',
            'tgl_berangkat'   => 'required_if:is_penugasan,on|date|nullable',
            'tgl_kembali'     => 'required_if:is_penugasan,on|date|after_or_equal:tgl_berangkat|nullable',
        ]);

        // Kecualikan atribut temporary dari data Surat Masuk
        $data = $request->except(['file_surat', 'is_penugasan', 'guru_ditugaskan', 'tgl_berangkat', 'tgl_kembali']);

        // Handle Upload File jika ada
        if ($request->hasFile('file_surat')) {
            $path = $request->file('file_surat')->store('surat-masuk', 'public');
            $data['file_path'] = $path;
        }

        // Simpan Surat Masuk
        $suratMasuk = LetterIncoming::create($data);

        // =========================================================================
        // LOGIKA MAGIC: Jika Checkbox Penugasan di-Centang
        // =========================================================================
        if ($request->has('is_penugasan') && $request->is_penugasan === 'on') {
            
            $start = Carbon::parse($request->tgl_berangkat);
            $end = Carbon::parse($request->tgl_kembali);
            $lama_hari = $start->diffInDays($end) + 1;

            $bulan_romawi = $this->getRomawi(date('n'));
            $tahun = date('Y');
            
            // 1. Generate Nomor SPT
            $last_spt_count = LetterSpt::whereYear('created_at', $tahun)->count() + 1;
            $nomor_spt_otomatis = sprintf("094/%03d/SMP.03/Disdik/%s/%s", $last_spt_count, $bulan_romawi, $tahun);

            // 2. Buat SPT Tertaut ke Surat Masuk
            $spt = LetterSpt::create([
                'letter_incoming_id' => $suratMasuk->id,
                'nomor_spt'       => $nomor_spt_otomatis,
                'untuk'           => 'Memenuhi Undangan/Panggilan: ' . $request->perihal,
                'tempat_tujuan'   => $request->asal_surat, // Tujuan keberangkatan adalah Instansi Pengirim Surat
                'tgl_berangkat'   => $request->tgl_berangkat,
                'tgl_kembali'     => $request->tgl_kembali,
                'lama_hari'       => $lama_hari,
                'pejabat_nama'    => 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.',
                'pejabat_nip'     => '19820928 201101 1 002',
            ]);

            // 3. Looping guru yang dipilih & Buat SPPD
            if ($request->has('guru_ditugaskan') && is_array($request->guru_ditugaskan)) {
                foreach ($request->guru_ditugaskan as $guru_id) {
                    // Relasi Pivot SPT
                    $spt->users()->attach($guru_id);

                    // Buat SPPD
                    $last_sppd_count = Sppd::whereYear('created_at', $tahun)->count() + 1;
                    $nomor_sppd_otomatis = sprintf("090/%03d/SMP.03/Disdik/%s/%s", $last_sppd_count, $bulan_romawi, $tahun);

                    Sppd::create([
                        'spt_id'            => $spt->id,
                        'nomor_sppd'        => $nomor_sppd_otomatis,
                        'user_id'           => $guru_id,
                        'maksud_perjalanan' => 'Memenuhi Undangan/Panggilan: ' . $request->perihal,
                        'tempat_berangkat'  => 'SMP Negeri 3 Lakbok',
                        'tempat_tujuan'     => $request->asal_surat,
                        'tgl_berangkat'     => $request->tgl_berangkat,
                        'tgl_kembali'       => $request->tgl_kembali,
                        'lama_hari'         => $lama_hari,
                        'instansi_pembayar' => 'SMP Negeri 3 Lakbok',
                        'pejabat_nama'      => 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.',
                        'pejabat_nip'       => '19820928 201101 1 002',
                        'pejabat_pangkat'   => 'Penata, III/c',
                        'pejabat_jabatan'   => 'Kepala Sekolah',
                    ]);
                }
            }

            // Arahkan ke halaman SPPD
            return redirect()->route('sppd.index')
                ->with('success', 'Surat Masuk disimpan. Draft SPT dan SPPD untuk pegawai terpilih telah otomatis dibuat!');
        }

        return redirect()->route('letters.incoming.index')
            ->with('success', 'Surat Masuk berhasil disimpan!');
    }

    // MENAMPILKAN FORM EDIT
    public function edit($id)
    {
        $letter = LetterIncoming::findOrFail($id);
        return view('letters.incoming.edit', compact('letter'));
    }

    // MEMPROSES UPDATE DATA
    public function update(Request $request, $id)
    {
        $letter = LetterIncoming::findOrFail($id);

        $request->validate([
            'nomor_agenda' => 'required|string|unique:letter_incomings,nomor_agenda,' . $id, 
            'sifat_surat'  => 'required|string', // Validasi Ditambahkan
            'nomor_surat'  => 'required|string|max:255',
            'asal_surat'   => 'required|string|max:255',
            'tgl_surat'    => 'required|date',
            'tgl_diterima' => 'required|date',
            'perihal'      => 'required|string',
            'file_surat'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['file_surat', '_token', '_method']);

        // Handle File Upload saat Update
        if ($request->hasFile('file_surat')) {
            // 1. Hapus file lama jika ada
            if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
                Storage::disk('public')->delete($letter->file_path);
            }

            // 2. Upload file baru
            $path = $request->file('file_surat')->store('surat-masuk', 'public');
            $data['file_path'] = $path;
        }

        $letter->update($data);

        return redirect()->route('letters.incoming.index')
            ->with('success', 'Data Surat berhasil diperbarui!');
    }

    // MENGHAPUS DATA
    public function destroy($id)
    {
        $letter = LetterIncoming::findOrFail($id);

        // Hapus file fisik jika ada
        if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
            Storage::disk('public')->delete($letter->file_path);
        }

        $letter->delete();

        return redirect()->route('letters.incoming.index')
            ->with('success', 'Surat berhasil dihapus!');
    }

    // =============================================
    // FUNGSI HELPER UNTUK MENGAMBIL BULAN ROMAWI 
    // =============================================
    private function getRomawi($bulan)
    {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[$bulan];
    }
}