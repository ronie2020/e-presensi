<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sppd;
use App\Models\LetterSpt; // Pastikan ini sesuai nama model SPT kamu
use App\Models\User;
use Carbon\Carbon;

class SppdController extends Controller
{
    public function index()
    {
        $sppds = Sppd::with('user')->latest()->paginate(10);
        return view('sppd.index', compact('sppds'));
    }

    public function create()
    {
        // 1. Ambil User untuk Dropdown Manual
        $users = User::orderBy('name', 'asc')->get();

        // 2. Ambil Data SPT untuk Dropdown Otomatis
        // Pastikan model LetterSpt ada. Jika belum ada, gunakan mock array kosong []
        if (class_exists('App\Models\LetterSpt')) {
            $spts_raw = LetterSpt::with('users')->latest()->get();
        } else {
            $spts_raw = collect([]);
        }

        // 3. Format ke JSON untuk AlpineJS
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

        // 4. Nomor Otomatis
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
        ]);

        $start = Carbon::parse($request->tgl_berangkat);
        $end = Carbon::parse($request->tgl_kembali);
        $lama_hari = $start->diffInDays($end) + 1;

        $sppd = new Sppd();
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
        
        $sppd->pejabat_nama = 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.';
        $sppd->pejabat_nip = '19820928 201101 1 002';
        $sppd->pejabat_pangkat = 'Penata, III/c';
        $sppd->pejabat_jabatan = 'Kepala Sekolah';

        $sppd->save();

        return redirect()->route('sppd.print', $sppd->id);
    }

    public function print($id)
    {
        $sppd = Sppd::with('user')->findOrFail($id);
        return view('sppd.print', compact('sppd'));
    }

    private function getRomawi($bulan)
    {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[$bulan];
    }
}