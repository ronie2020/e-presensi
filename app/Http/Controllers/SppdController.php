<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sppd;
use App\Models\SppdFollower;
use App\Models\LetterSpt;
use App\Models\User;
use Carbon\Carbon;

class SppdController extends Controller
{
    // MENAMPILKAN DATA (DENGAN PENCARIAN)
    public function index(Request $request)
    {
        $query = Sppd::with('user');

        // Logika Pencarian
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

        // Ambil Data SPT (Safe Mode)
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

        // Generate Nomor Otomatis
        $bulan_romawi = $this->getRomawi(date('n'));
        $tahun = date('Y');
        $count = Sppd::whereYear('created_at', $tahun)->count() + 1;
        $nomor_otomatis = sprintf("090/%03d/SMP.03/Disdik/%s/%s", $count, $bulan_romawi, $tahun);

        return view('sppd.create', compact('users', 'spt_json', 'nomor_otomatis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:users,id',
            'maksud' => 'required',
            'tujuan' => 'required',
            'tgl_berangkat' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_berangkat',
            'followers.*.nama' => 'required_with:followers',
        ]);

        $start = Carbon::parse($request->tgl_berangkat);
        $end = Carbon::parse($request->tgl_kembali);
        $lama_hari = $start->diffInDays($end) + 1;

        // 1. Simpan SPPD Utama
        $sppd = new Sppd();

        // TAMBAHKAN KODE INI UNTUK MENANGKAP ID SPT
        if ($request->has('spt_id') && !empty($request->spt_id)) {
            $sppd->spt_id = $request->spt_id;
        }
        
        $sppd->nomor_sppd = $request->nomor_sppd;
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
        
        // Pejabat (Bisa dibuat dinamis jika perlu)
        $sppd->pejabat_nama = 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.';
        $sppd->pejabat_nip = '19820928 201101 1 002';
        $sppd->pejabat_pangkat = 'Penata, III/d';
        $sppd->pejabat_jabatan = 'Kepala Sekolah';

        $sppd->save();

        // 2. Simpan Pengikut
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

        return redirect()->route('sppd.index')->with('success', 'SPPD berhasil dibuat!');
    }

    // EDIT DATA
    public function edit($id)
    {
        $sppd = Sppd::with('followers')->findOrFail($id);
        $users = User::orderBy('name', 'asc')->get();
        
        // Data SPT hanya perlu dikirim agar struktur Alpine.js (di view) tidak error, 
        // tapi dalam mode edit kita biasanya fokus edit manual.
        $spt_json = collect([]); 

        return view('sppd.edit', compact('sppd', 'users', 'spt_json'));
    }

    // UPDATE DATA
    public function update(Request $request, $id)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:users,id',
            'maksud' => 'required',
            'tujuan' => 'required',
            'tgl_berangkat' => 'required|date',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_berangkat',
            'followers.*.nama' => 'required_with:followers',
        ]);

        $start = Carbon::parse($request->tgl_berangkat);
        $end = Carbon::parse($request->tgl_kembali);
        $lama_hari = $start->diffInDays($end) + 1;

        $sppd = Sppd::findOrFail($id);
        
        // Update data utama
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

        // Update Pengikut: 
        // Cara paling aman & bersih: Hapus semua pengikut lama, lalu insert yang baru (jika ada)
        $sppd->followers()->delete();

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

        return redirect()->route('sppd.index')->with('success', 'Data SPPD berhasil diperbarui!');
    }
    
    // HAPUS DATA
    public function destroy($id)
    {
        $sppd = Sppd::findOrFail($id);
        // Hapus pengikut otomatis jika sudah di-set cascade di database, 
        // tapi manual delete lebih aman di level aplikasi
        $sppd->followers()->delete(); 
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