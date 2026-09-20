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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SppdController extends Controller
{
    // =====================================================================
    // INDEX - Daftar SPPD dengan filter status & statistik singkat
    // =====================================================================
    public function index(Request $request)
    {
        $query = Sppd::with(['user', 'followers']);

        // Filter pencarian teks
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_sppd', 'like', "%{$search}%")
                  ->orWhere('tempat_tujuan', 'like', "%{$search}%")
                  ->orWhere('maksud_perjalanan', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($qUser) => $qUser->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sppds = $query->latest()->paginate(10)->withQueryString();

        // Statistik ringkas untuk badge di toolbar
        $stats = [
            'total'     => Sppd::count(),
            'draft'     => Sppd::where('status', 'draft')->count(),
            'submitted' => Sppd::where('status', 'submitted')->count(),
            'approved'  => Sppd::where('status', 'approved')->count(),
            'selesai'   => Sppd::where('status', 'selesai')->count(),
        ];

        return view('sppd.index', compact('sppds', 'stats'));
    }

    // =====================================================================
    // DASHBOARD - Rekap statistik & chart
    // =====================================================================
    public function dashboard()
    {
        $tahun = date('Y');

        // Stat Cards
        $stats = [
            'total_sppd'   => Sppd::count(),
            'bulan_ini'    => Sppd::whereMonth('created_at', date('m'))->whereYear('created_at', $tahun)->count(),
            'total_hari'   => Sppd::sum('lama_hari'),
            'total_biaya'  => Sppd::selectRaw('SUM(COALESCE(biaya_transport,0) + COALESCE(biaya_penginapan,0) + COALESCE(uang_harian,0)) as total')->value('total') ?? 0,
            'belum_selesai'=> Sppd::whereIn('status', ['draft','submitted','approved'])->count(),
        ];

        // Chart 1: SPPD per bulan (12 bulan terakhir)
        $chartBulan = [];
        for ($i = 11; $i >= 0; $i--) {
            $date  = Carbon::now()->subMonths($i);
            $count = Sppd::whereYear('created_at', $date->year)
                         ->whereMonth('created_at', $date->month)
                         ->count();
            $chartBulan[] = [
                'label' => $date->translatedFormat('M Y'),
                'value' => $count,
            ];
        }

        // Chart 2: Distribusi Status
        $chartStatus = collect(Sppd::statusLabel())->map(function($info, $key) {
            return [
                'label' => $info['label'],
                'value' => Sppd::where('status', $key)->count(),
                'color' => $info['color'],
            ];
        })->values();

        // Chart 3: Top 5 pegawai terbanyak dinas
        $topPegawai = Sppd::with('user')
            ->select('user_id', DB::raw('COUNT(*) as total_dinas'), DB::raw('SUM(lama_hari) as total_hari'))
            ->groupBy('user_id')
            ->orderByDesc('total_dinas')
            ->limit(5)
            ->get()
            ->map(fn($s) => [
                'nama'       => $s->user?->name ?? 'Tidak Diketahui',
                'total_dinas'=> $s->total_dinas,
                'total_hari' => $s->total_hari,
            ]);

        // Tabel rekap bulan ini
        $rekapBulanIni = Sppd::with('user')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', $tahun)
            ->latest()
            ->get();

        return view('sppd.dashboard', compact('stats', 'chartBulan', 'chartStatus', 'topPegawai', 'rekapBulanIni'));
    }

    // =====================================================================
    // CREATE & STORE
    // =====================================================================
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
                'id'        => $item->id,
                'nomor'     => $item->nomor_spt,
                'perihal'   => $item->untuk,
                'tujuan'    => $item->tempat_tujuan,
                'tgl_mulai' => $item->tgl_berangkat->format('Y-m-d'),
                'tgl_selesai'=> $item->tgl_kembali->format('Y-m-d'),
                'pegawai'   => $item->users->map(fn($u) => ['id' => $u->id, 'name' => $u->name]),
            ];
        });

        $bulan_romawi   = $this->getRomawi(date('n'));
        $tahun          = date('Y');
        $count          = Sppd::whereYear('created_at', $tahun)->count() + 1;
        $nomor_otomatis = sprintf("090/%03d/SMP.03/Disdik/%s/%s", $count, $bulan_romawi, $tahun);

        return view('sppd.create', compact('users', 'spt_json', 'nomor_otomatis'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pegawai_id'    => 'required|exists:users,id',
            'maksud'        => 'required',
            'tujuan'        => 'required',
            'tgl_berangkat' => 'required|date',
            'tgl_kembali'   => 'required|date|after_or_equal:tgl_berangkat',
            'biaya_transport'  => 'nullable|numeric|min:0',
            'biaya_penginapan' => 'nullable|numeric|min:0',
            'uang_harian'      => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('sppd.create')->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $start     = Carbon::parse($request->tgl_berangkat);
            $end       = Carbon::parse($request->tgl_kembali);
            $lama_hari = $start->diffInDays($end) + 1;

            $spt_id_to_use = $request->spt_id;

            if (empty($spt_id_to_use)) {
                $tahun       = date('Y');
                $bulan_romawi= $this->getRomawi(date('n'));
                $lastLetter  = LetterOutgoing::latest('id')->first();
                $nextAgenda  = $lastLetter ? str_pad(intval($lastLetter->nomor_agenda) + 1, 4, '0', STR_PAD_LEFT) : '0001';
                $last_spt_count  = LetterSpt::whereYear('created_at', $tahun)->count() + 1;
                $nomor_spt_otomatis = sprintf("094/%03d/SMP.03/Disdik/%s/%s", $last_spt_count, $bulan_romawi, $tahun);

                $suratKeluar = LetterOutgoing::create([
                    'nomor_agenda' => $nextAgenda,
                    'nomor_surat'  => $nomor_spt_otomatis,
                    'tujuan_surat' => $request->tujuan,
                    'sifat_surat'  => 'Biasa',
                    'tgl_surat'    => date('Y-m-d'),
                    'perihal'      => 'Surat Perintah Tugas: ' . $request->maksud,
                ]);

                $sptData = [
                    'nomor_spt'     => $nomor_spt_otomatis,
                    'untuk'         => $request->maksud,
                    'tempat_tujuan' => $request->tujuan,
                    'tgl_berangkat' => $request->tgl_berangkat,
                    'tgl_kembali'   => $request->tgl_kembali,
                    'lama_hari'     => $lama_hari,
                    'pejabat_nama'  => 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.',
                    'pejabat_nip'   => '19820928 201101 1 002',
                ];

                if (Schema::hasColumn('letter_spts', 'letter_incoming_id')) $sptData['letter_incoming_id'] = null;
                if (Schema::hasColumn('letter_spts', 'surat_keluar_id'))    $sptData['surat_keluar_id']    = $suratKeluar->id;

                $spt = LetterSpt::create($sptData);
                $spt->users()->attach($request->pegawai_id);
                $spt_id_to_use = $spt->id;
            }

            $sppd = new Sppd();
            $sppd->spt_id            = $spt_id_to_use;
            $sppd->nomor_sppd        = $request->nomor_sppd ?? $this->generateNomorSppd();
            $sppd->status            = 'draft';
            $sppd->user_id           = $request->pegawai_id;
            $sppd->maksud_perjalanan = $request->maksud;
            $sppd->alat_angkut       = $request->transportasi;
            $sppd->tempat_berangkat  = 'SMP Negeri 3 Lakbok';
            $sppd->tempat_tujuan     = $request->tujuan;
            $sppd->tgl_berangkat     = $request->tgl_berangkat;
            $sppd->tgl_kembali       = $request->tgl_kembali;
            $sppd->lama_hari         = $lama_hari;
            $sppd->instansi_pembayar = $request->instansi_biaya ?? 'SMP Negeri 3 Lakbok';
            $sppd->mata_anggaran     = $request->kode_rekening;
            $sppd->biaya_transport   = $request->biaya_transport ?: null;
            $sppd->biaya_penginapan  = $request->biaya_penginapan ?: null;
            $sppd->uang_harian       = $request->uang_harian ?: null;
            $sppd->pejabat_nama      = 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.';
            $sppd->pejabat_nip       = '19820928 201101 1 002';
            $sppd->pejabat_pangkat   = 'Penata, III/d';
            $sppd->pejabat_jabatan   = 'Kepala Sekolah';
            $sppd->save();

            if ($request->has('followers') && Schema::hasTable('sppd_followers')) {
                foreach ($request->followers as $followerData) {
                    if (!empty($followerData['nama'])) {
                        SppdFollower::create([
                            'sppd_id'    => $sppd->id,
                            'nama'       => $followerData['nama'],
                            'nip'        => $followerData['nip'] ?? null,
                            'keterangan' => $followerData['keterangan'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('sppd.index')->with('success', 'SPPD berhasil dibuat dengan status Draft!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('sppd.create')
                ->withErrors(['system_error' => 'Kesalahan Database: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // =====================================================================
    // UPDATE STATUS - Workflow perubahan status SPPD
    // =====================================================================
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'          => 'required|in:submitted,approved,selesai',
            'catatan_kepala'  => 'nullable|string|max:500',
        ]);

        $sppd = Sppd::findOrFail($id);

        // Validasi alur status agar tidak bisa lompat
        $flowMap = [
            'draft'     => 'submitted',
            'submitted' => 'approved',
            'approved'  => 'selesai',
        ];

        if (!isset($flowMap[$sppd->status]) || $flowMap[$sppd->status] !== $request->status) {
            return back()->withErrors(['status' => 'Perubahan status tidak valid untuk kondisi saat ini.']);
        }

        $sppd->update([
            'status'         => $request->status,
            'catatan_kepala' => $request->catatan_kepala ?? $sppd->catatan_kepala,
        ]);

        $labelMap = [
            'submitted' => 'diajukan ke Kepala Sekolah',
            'approved'  => 'disetujui oleh Kepala Sekolah',
            'selesai'   => 'ditandai Selesai',
        ];

        return back()->with('success', "SPPD {$sppd->nomor_sppd} berhasil " . ($labelMap[$request->status] ?? 'diperbarui') . "!");
    }

    // =====================================================================
    // EDIT & UPDATE
    // =====================================================================
    public function edit($id)
    {
        $sppd     = Sppd::with('followers')->findOrFail($id);
        $users    = User::orderBy('name', 'asc')->get();
        $spt_json = collect([]);
        return view('sppd.edit', compact('sppd', 'users', 'spt_json'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pegawai_id'    => 'required|exists:users,id',
            'maksud'        => 'required',
            'tujuan'        => 'required',
            'tgl_berangkat' => 'required|date',
            'tgl_kembali'   => 'required|date|after_or_equal:tgl_berangkat',
            'biaya_transport'  => 'nullable|numeric|min:0',
            'biaya_penginapan' => 'nullable|numeric|min:0',
            'uang_harian'      => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('sppd.edit', $id)->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $start     = Carbon::parse($request->tgl_berangkat);
            $end       = Carbon::parse($request->tgl_kembali);
            $lama_hari = $start->diffInDays($end) + 1;

            $sppd = Sppd::findOrFail($id);
            $sppd->user_id           = $request->pegawai_id;
            $sppd->maksud_perjalanan = $request->maksud;
            $sppd->alat_angkut       = $request->transportasi;
            $sppd->tempat_tujuan     = $request->tujuan;
            $sppd->tgl_berangkat     = $request->tgl_berangkat;
            $sppd->tgl_kembali       = $request->tgl_kembali;
            $sppd->lama_hari         = $lama_hari;
            $sppd->instansi_pembayar = $request->instansi_biaya ?? 'SMP Negeri 3 Lakbok';
            $sppd->mata_anggaran     = $request->kode_rekening;
            $sppd->biaya_transport   = $request->biaya_transport ?: null;
            $sppd->biaya_penginapan  = $request->biaya_penginapan ?: null;
            $sppd->uang_harian       = $request->uang_harian ?: null;
            $sppd->save();

            if (Schema::hasTable('sppd_followers')) {
                $sppd->followers()->delete();
                if ($request->has('followers')) {
                    foreach ($request->followers as $followerData) {
                        if (!empty($followerData['nama'])) {
                            SppdFollower::create([
                                'sppd_id'    => $sppd->id,
                                'nama'       => $followerData['nama'],
                                'nip'        => $followerData['nip'] ?? null,
                                'keterangan' => $followerData['keterangan'] ?? null,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('sppd.index')->with('success', 'Data SPPD berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('sppd.edit', $id)
                ->withErrors(['system_error' => 'Kesalahan Database: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // =====================================================================
    // DESTROY
    // =====================================================================
    public function destroy($id)
    {
        $sppd = Sppd::findOrFail($id);
        if (Schema::hasTable('sppd_followers')) {
            $sppd->followers()->delete();
        }
        $sppd->delete();
        return redirect()->route('sppd.index')->with('success', 'Data SPPD berhasil dihapus!');
    }

    // =====================================================================
    // PRINT SPPD
    // =====================================================================
    public function print($id)
    {
        $sppd = Sppd::with(['user', 'followers', 'spt'])->findOrFail($id);
        return view('sppd.print', compact('sppd'));
    }

    // =====================================================================
    // PRINT SPJ (Surat Pertanggungjawaban Biaya)
    // =====================================================================
    public function printSpj($id)
    {
        $sppd = Sppd::with(['user', 'followers', 'spt'])->findOrFail($id);
        return view('sppd.print_spj', compact('sppd'));
    }

    // =====================================================================
    // HELPERS
    // =====================================================================
    private function generateNomorSppd()
    {
        $bulan_romawi = $this->getRomawi(date('n'));
        $tahun  = date('Y');
        $count  = Sppd::whereYear('created_at', $tahun)->lockForUpdate()->count() + 1;
        return sprintf("090/%03d/SMP.03/Disdik/%s/%s", $count, $bulan_romawi, $tahun);
    }

    private function getRomawi($bulan)
    {
        $map = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'];
        return $map[$bulan];
    }
}
