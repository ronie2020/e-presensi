<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sppd;
use App\Models\SppdFollower;
use App\Models\LetterSpt;
use App\Models\LetterOutgoing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Schema; // Tambahan untuk cek kolom database
use Illuminate\Support\Facades\DB; // PERBAIKAN: Menambahkan facade DB untuk transaksi

class SppdController extends Controller
{
    // MENAMPILKAN DATA
    public function index(Request $request)
    {
         $query = Sppd::with(['user', 'followers']); 

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_sppd', 'like', "%{$search}%")
                  ->orWhere('tempat_tujuan', 'like', "%{$search}%")
                  ->orWhere('maksud_perjalanan', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qUser) use ($search) {
                      $qUser->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $sppds = $query->latest()->paginate(10);
        return view('sppd.index', compact('sppds'));
    }

    public function create()
    {
        $users = User::orderBy('name', 'asc')->get();

        if (class_exists('App\Models\LetterSpt')) {
            $spts_raw = LetterSpt::with('users')->latest()->get();
        } else {
            $spts_raw = collect([]);
        }

        $spt_json = $spts_raw->map(function($item) {
            return [
                'id' => $item->id,
                'nomor' => $item->nomor_spt,
                'perihal' => $item->untuk,
                'tujuan' => $item->tempat_tujuan,
                'tgl_mulai' => $item->tgl_berangkat->format('Y-m-d'),
                'tgl_selesai' => $item->tgl_kembali->format('Y-m-d'),
                'pegawai' => $item->users->map(function($u){
                    return ['id' => $u->id, 'name' => $u->name];
                })
            ];
        });

        $bulan_romawi = $this->getRomawi(date('n'));
        $tahun = date('Y');
        $count = Sppd::whereYear('created_at', $tahun)->count() + 1;
        $nomor_otomatis = sprintf("090/%03d/SMP.03/Disdik/%s/%s", $count, $bulan_romawi, $tahun);

        return view('sppd.create', compact('users', 'spt_json', 'nomor_otomatis'));
    }

    public function store(Request $request)
    {
        // 1. VALIDASI EKSPLISIT: Cegah terlempar ke halaman depan
        $validator = Validator::make($request->all(), [
            'pegawai_id' => 'required|exists:users,id',
            'maksud' => 'required',
            'tujuan' => 'required',
            'tgl_berangkat' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_berangkat',
        ]);

        if ($validator->fails()) {
            // Gunakan rute eksplist, bukan back()
            return redirect()->route('sppd.create') 
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction(); // 1. MULAI TRANSAKSI DATABASE

            $start = Carbon::parse($request->tgl_berangkat);
            $end = Carbon::parse($request->tgl_kembali);
            $lama_hari = $start->diffInDays($end) + 1;

            $spt_id_to_use = $request->spt_id;

            // =========================================================================
            // REVERSE MAGIC LOGIC (MANUAL INPUT)
            // =========================================================================
            if (empty($spt_id_to_use)) {
                $tahun = date('Y');
                $bulan_romawi = $this->getRomawi(date('n'));

                $lastLetter = LetterOutgoing::latest('id')->first();
                $nextAgenda = $lastLetter ? str_pad(intval($lastLetter->nomor_agenda) + 1, 4, '0', STR_PAD_LEFT) : '0001';
                
                $last_spt_count = LetterSpt::whereYear('created_at', $tahun)->count() + 1;
                $nomor_spt_otomatis = sprintf("094/%03d/SMP.03/Disdik/%s/%s", $last_spt_count, $bulan_romawi, $tahun);

                // Buat Surat Keluar
                $suratKeluar = LetterOutgoing::create([
                    'nomor_agenda' => $nextAgenda,
                    'nomor_surat'  => $nomor_spt_otomatis, 
                    'tujuan_surat' => $request->tujuan,
                    'sifat_surat'  => 'Biasa',
                    'tgl_surat'    => date('Y-m-d'),
                    'perihal'      => 'Surat Perintah Tugas: ' . $request->maksud,
                ]);

                // Susun data SPT secara aman
                $sptData = [
                    'nomor_spt'          => $nomor_spt_otomatis,
                    'untuk'              => $request->maksud,
                    'tempat_tujuan'      => $request->tujuan,
                    'tgl_berangkat'      => $request->tgl_berangkat,
                    'tgl_kembali'        => $request->tgl_kembali,
                    'lama_hari'          => $lama_hari,
                    'pejabat_nama'       => 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.',
                    'pejabat_nip'        => '19820928 201101 1 002',
                ];

                // Cek agar MySQL tidak crash jika kolom belum dibuat di Database
                if (Schema::hasColumn('letter_spts', 'letter_incoming_id')) {
                    $sptData['letter_incoming_id'] = null;
                }
                if (Schema::hasColumn('letter_spts', 'surat_keluar_id')) {
                    $sptData['surat_keluar_id'] = $suratKeluar->id;
                }

                $spt = LetterSpt::create($sptData);
                $spt->users()->attach($request->pegawai_id);
                
                $spt_id_to_use = $spt->id;
            }
            // =========================================================================

            // 3. SIMPAN SPPD UTAMA
            $sppd = new Sppd();
            $sppd->spt_id = $spt_id_to_use; 
            $sppd->nomor_sppd = $request->nomor_sppd ?? $this->generateNomorSppd(); 
            $sppd->user_id = $request->pegawai_id;
            $sppd->maksud_perjalanan = $request->maksud;
            $sppd->alat_angkut = $request->transportasi;
            $sppd->tempat_berangkat = 'SMP Negeri 3 Lakbok';
            $sppd->tempat_tujuan = $request->tujuan;
            $sppd->tgl_berangkat = $request->tgl_berangkat;
            $sppd->tgl_kembali = $request->tgl_kembali;
            $sppd->lama_hari = $lama_hari;
            $sppd->instansi_pembayar = $request->instansi_biaya ?? 'SMP Negeri 3 Lakbok';
            $sppd->mata_anggaran = $request->kode_rekening;
            
            $sppd->pejabat_nama = 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.';
            $sppd->pejabat_nip = '19820928 201101 1 002';
            $sppd->pejabat_pangkat = 'Penata, III/d';
            $sppd->pejabat_jabatan = 'Kepala Sekolah';

            $sppd->save();

            // 4. SIMPAN PENGIKUT
            if ($request->has('followers')) {
                // Cek aman: Pastikan tabel sppd_followers sudah ada di DB
                if (Schema::hasTable('sppd_followers')) {
                    foreach ($request->followers as $followerData) {
                        if (!empty($followerData['nama'])) {
                            SppdFollower::create([
                                'sppd_id' => $sppd->id,
                                'nama' => $followerData['nama'],
                                'nip' => $followerData['nip'] ?? null,
                                'keterangan' => $followerData['keterangan'] ?? null,
                            ]);
                        }
                    }
                }
            }

            DB::commit(); // 2. SIMPAN SEMUA PERMANEN JIKA TIDAK ADA ERROR

            return redirect()->route('sppd.index')->with('success', 'SPPD berhasil dibuat. Arsip terhubung otomatis!');

        } catch (\Exception $e) {
            DB::rollBack(); // 3. BATALKAN SEMUA INSERT JIKA TERJADI ERROR DI TENGAH PROSES

            return redirect()->route('sppd.create')
                ->withErrors(['system_error' => 'Kesalahan Database: ' . $e->getMessage()])
                ->withInput();
        }
    }

    private function generateNomorSppd()
    {
        $bulan_romawi = $this->getRomawi(date('n'));
        $tahun = date('Y');
        $count = Sppd::whereYear('created_at', $tahun)->count() + 1;
        return sprintf("090/%03d/SMP.03/Disdik/%s/%s", $count, $bulan_romawi, $tahun);
    }

    public function edit($id)
    {
        $sppd = Sppd::with('followers')->findOrFail($id);
        $users = User::orderBy('name', 'asc')->get();
        $spt_json = collect([]); 
        return view('sppd.edit', compact('sppd', 'users', 'spt_json'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pegawai_id' => 'required|exists:users,id',
            'maksud' => 'required',
            'tujuan' => 'required',
            'tgl_berangkat' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_berangkat',
        ]);

        if ($validator->fails()) {
            return redirect()->route('sppd.edit', $id) 
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction(); // 1. MULAI TRANSAKSI UNTUK PROSES UPDATE

            $start = Carbon::parse($request->tgl_berangkat);
            $end = Carbon::parse($request->tgl_kembali);
            $lama_hari = $start->diffInDays($end) + 1;

            $sppd = Sppd::findOrFail($id);
            
            $sppd->user_id = $request->pegawai_id;
            $sppd->maksud_perjalanan = $request->maksud;
            $sppd->alat_angkut = $request->transportasi;
            $sppd->tempat_tujuan = $request->tujuan;
            $sppd->tgl_berangkat = $request->tgl_berangkat;
            $sppd->tgl_kembali = $request->tgl_kembali;
            $sppd->lama_hari = $lama_hari;
            $sppd->instansi_pembayar = $request->instansi_biaya ?? 'SMP Negeri 3 Lakbok';
            $sppd->mata_anggaran = $request->kode_rekening;
            
            $sppd->save();

            if (Schema::hasTable('sppd_followers')) {
                $sppd->followers()->delete(); // Menghapus data lama dengan aman karena db transaction
                if ($request->has('followers')) {
                    foreach ($request->followers as $followerData) {
                        if (!empty($followerData['nama'])) {
                            SppdFollower::create([
                                'sppd_id' => $sppd->id,
                                'nama' => $followerData['nama'],
                                'nip' => $followerData['nip'] ?? null,
                                'keterangan' => $followerData['keterangan'] ?? null,
                            ]);
                        }
                    }
                }
            }

            DB::commit(); // 2. SIMPAN SEMUA PERMANEN JIKA TIDAK ADA ERROR

            return redirect()->route('sppd.index')->with('success', 'Data SPPD berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack(); // 3. BATALKAN SEMUA PERUBAHAN JIKA TERJADI ERROR DI TENGAH PROSES

            return redirect()->route('sppd.edit', $id)
                ->withErrors(['system_error' => 'Kesalahan Database: ' . $e->getMessage()])
                ->withInput();
        }
    }
    
    public function destroy($id)
    {
        $sppd = Sppd::findOrFail($id);
        if (Schema::hasTable('sppd_followers')) {
            $sppd->followers()->delete(); 
        }
        $sppd->delete();

        return redirect()->route('sppd.index')->with('success', 'Data SPPD berhasil dihapus!');
    }

    public function print($id)
    {
        $sppd = Sppd::with(['user', 'followers'])->findOrFail($id);
        return view('sppd.print', compact('sppd'));
    }

    private function getRomawi($bulan)
    {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[$bulan];
    }
}