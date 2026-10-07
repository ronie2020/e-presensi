<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\LetterOutgoing;
use App\Models\LetterSpt;
use App\Models\Sppd;
use App\Models\User;
use App\Exports\LetterOutgoingExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LetterOutgoingController extends Controller
{
    public function index(Request $request)
    {
        $query = LetterOutgoing::query()->with('spt');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('tujuan_surat', 'like', "%{$search}%")
                  ->orWhere('nomor_agenda', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sifat_surat')) {
            $query->where('sifat_surat', $request->sifat_surat);
        }

        if ($request->filled('year')) {
            $query->whereYear('tgl_surat', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tgl_surat', $request->month);
        }
        
        $letters = $query->latest('tgl_surat')->latest('id')->paginate(10)->withQueryString();

        // Data statistik cepat
        $currentYear = date('Y');
        $stats = [
            'total_all'      => LetterOutgoing::count(),
            'total_this_year'=> LetterOutgoing::whereYear('tgl_surat', $currentYear)->count(),
            'total_penting'  => LetterOutgoing::whereIn('sifat_surat', ['Penting', 'Segera'])->count(),
            'total_with_spt' => LetterOutgoing::has('spt')->count(),
        ];

        // Daftar tahun arsip yang tersedia di database
        $yearsFromDb = LetterOutgoing::selectRaw('YEAR(tgl_surat) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter()
            ->toArray();
        $availableYears = array_unique(array_merge([intval($currentYear)], $yearsFromDb));
        rsort($availableYears);

        return view('letters.outgoing.index', compact('letters', 'stats', 'availableYears'));
    }

    /**
     * Cetak Buku Agenda Surat Keluar (Print Preview / PDF)
     */
    public function printAgenda(Request $request)
    {
        $query = LetterOutgoing::query()->with(['spt.users']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('tujuan_surat', 'like', "%{$search}%")
                  ->orWhere('nomor_agenda', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sifat_surat')) {
            $query->where('sifat_surat', $request->sifat_surat);
        }

        if ($request->filled('year')) {
            $query->whereYear('tgl_surat', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tgl_surat', $request->month);
        }

        $letters = $query->orderBy('nomor_agenda', 'asc')->get();

        $filterInfo = [
            'year'  => $request->year ?: 'Semua Tahun',
            'month' => $request->month ? Carbon::create()->month($request->month)->translatedFormat('F') : 'Semua Bulan',
            'sifat' => $request->sifat_surat ?: 'Semua Sifat',
        ];

        return view('letters.outgoing.print_agenda', compact('letters', 'filterInfo'));
    }

    /**
     * Export Buku Agenda Surat Keluar ke Excel (.xlsx)
     */
    public function exportExcel(Request $request)
    {
        $year = $request->year;
        $fileName = 'Buku_Agenda_Surat_Keluar_' . ($year ?: 'Semua_Tahun') . '_' . date('Ymd_His') . '.xlsx';

        return Excel::download(
            new LetterOutgoingExport($request->year, $request->month, $request->sifat_surat, $request->search),
            $fileName
        );
    }

    public function create(Request $request)
    {
        $today = $request->query('date', date('Y-m-d'));
        $info = $this->getNextNumberInfo($today, '421.3');
        $nextAgendaKeluar = $info['nomor_agenda'];
        $defaultNomorSurat = $info['nomor_surat'];
        $nextSequence = $info['formatted_sequence'];
        $users = User::orderBy('name', 'asc')->get();

        return view('letters.outgoing.create', compact('nextAgendaKeluar', 'defaultNomorSurat', 'nextSequence', 'users'));
    }

    /**
     * AJAX endpoint: Generate nomor surat otomatis berdasarkan tanggal & kode klasifikasi
     */
    public function generateNumberAjax(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));
        $isPenugasan = filter_var($request->query('is_penugasan', false), FILTER_VALIDATE_BOOLEAN);
        $kode = $isPenugasan ? '094' : ($request->query('kode', '421.3'));

        $info = $this->getNextNumberInfo($date, $kode);
        return response()->json($info);
    }

    /**
     * Quick Update nomor surat via modal atau AJAX di tabel index
     */
    public function quickUpdateNomor(Request $request, $id)
    {
        $request->validate([
            'nomor_surat' => 'required|string|max:255',
        ]);

        $letter = LetterOutgoing::findOrFail($id);
        $letter->update([
            'nomor_surat' => $request->nomor_surat,
        ]);

        if ($letter->spt) {
            $letter->spt->update(['nomor_spt' => $request->nomor_surat]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Nomor surat berhasil diperbarui!',
                'nomor_surat' => $letter->nomor_surat,
            ]);
        }

        return redirect()->back()->with('success', 'Nomor surat berhasil diperbarui!');
    }

    public function store(Request $request)
    {
        $year = $request->tgl_surat ? Carbon::parse($request->tgl_surat)->year : date('Y');

        $request->validate([
            'nomor_agenda'  => [
                'required',
                'string',
                Rule::unique('letter_outgoings', 'nomor_agenda')->where(function ($query) use ($year) {
                    return $query->whereYear('tgl_surat', $year);
                }),
            ],
            'nomor_surat'   => 'required|string|max:255',
            'tujuan_surat'  => 'required|string|max:255',
            'sifat_surat'   => 'required|string',
            'tgl_surat'     => 'required|date',
            'perihal'       => 'required|string',
            'file_surat'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'guru_ditugaskan' => 'required_if:is_penugasan,on|array',
            'tgl_berangkat'   => 'required_if:is_penugasan,on|date|nullable',
            'tgl_kembali'     => 'required_if:is_penugasan,on|date|after_or_equal:tgl_berangkat|nullable',
        ]);

        $dataSurat = $request->only(['nomor_agenda', 'nomor_surat', 'tujuan_surat', 'sifat_surat', 'tgl_surat', 'perihal']);
        
        if ($request->hasFile('file_surat')) {
            $dataSurat['file_path'] = $request->file('file_surat')->store('surat-keluar', 'public');
        }

        $suratKeluar = LetterOutgoing::create($dataSurat);

        if ($request->has('is_penugasan') && $request->is_penugasan === 'on') {
            $this->syncPenugasan($suratKeluar, $request);
            return redirect()->route('sppd.index')
                ->with('success', 'Surat Keluar disimpan. Draft SPT dan SPPD telah otomatis dibuat!');
        }

        return redirect()->route('letters.outgoing.index')
            ->with('success', 'Surat Keluar berhasil disimpan!');
    }

    public function edit($id)
    {
        $letter = LetterOutgoing::with('spt.users')->findOrFail($id);
        $users = User::orderBy('name', 'asc')->get();
        return view('letters.outgoing.edit', compact('letter', 'users'));
    }

    public function update(Request $request, $id)
    {
        $letter = LetterOutgoing::findOrFail($id);
        $year = $request->tgl_surat ? Carbon::parse($request->tgl_surat)->year : date('Y');

        $request->validate([
            'nomor_agenda'  => [
                'required',
                'string',
                Rule::unique('letter_outgoings', 'nomor_agenda')
                    ->ignore($id)
                    ->where(function ($query) use ($year) {
                        return $query->whereYear('tgl_surat', $year);
                    }),
            ],
            'nomor_surat'   => 'required|string|max:255',
            'tujuan_surat'  => 'required|string|max:255',
            'sifat_surat'   => 'required|string',
            'tgl_surat'     => 'required|date',
            'perihal'       => 'required|string',
            'file_surat'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'guru_ditugaskan' => 'required_if:is_penugasan,on|array',
            'tgl_berangkat'   => 'required_if:is_penugasan,on|date|nullable',
            'tgl_kembali'     => 'required_if:is_penugasan,on|date|after_or_equal:tgl_berangkat|nullable',
        ]);

        $data = $request->except(['file_surat', '_token', '_method', 'is_penugasan', 'guru_ditugaskan', 'tgl_berangkat', 'tgl_kembali']);

        if ($request->hasFile('file_surat')) {
            if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
                Storage::disk('public')->delete($letter->file_path);
            }
            $data['file_path'] = $request->file('file_surat')->store('surat-keluar', 'public');
        }

        $letter->update($data);

        if ($request->has('is_penugasan') && $request->is_penugasan === 'on') {
            $this->syncPenugasan($letter, $request);
            return redirect()->route('letters.outgoing.index')
                ->with('success', 'Data Surat dan Penugasan berhasil diperbarui!');
        }

        return redirect()->route('letters.outgoing.index')
            ->with('success', 'Data Surat Keluar berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $letter = LetterOutgoing::findOrFail($id);
        if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
            Storage::disk('public')->delete($letter->file_path);
        }
        $letter->delete();
        return redirect()->route('letters.outgoing.index')->with('success', 'Surat Keluar berhasil dihapus!');
    }

    /**
     * HELPER: Sinkronisasi SPT & SPPD untuk Surat Keluar
     */
    private function syncPenugasan($letter, $request)
    {
        $start = Carbon::parse($request->tgl_berangkat);
        $end = Carbon::parse($request->tgl_kembali);
        $lama_hari = $start->diffInDays($end) + 1;
        $bulan_romawi = $this->getRomawi(date('n'));
        $tahun = date('Y');

        $spt = LetterSpt::where('surat_keluar_id', $letter->id)->first();

        if (!$spt) {
            $last_spt_count = LetterSpt::whereYear('created_at', $tahun)->count() + 1;
            $nomor_spt = sprintf("094/%03d/SMP.03/Disdik/%s/%s", $last_spt_count, $bulan_romawi, $tahun);
            
            $spt = LetterSpt::create([
                'surat_keluar_id' => $letter->id,
                'nomor_spt'       => $nomor_spt,
                'untuk'           => $request->perihal,
                'tempat_tujuan'   => $request->tujuan_surat,
                'tgl_berangkat'   => $request->tgl_berangkat,
                'tgl_kembali'     => $request->tgl_kembali,
                'lama_hari'       => $lama_hari,
                'pejabat_nama'    => 'TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.',
                'pejabat_nip'     => '19820928 201101 1 002',
            ]);
        } else {
            $spt->update([
                'untuk'         => $request->perihal,
                'tempat_tujuan' => $request->tujuan_surat,
                'tgl_berangkat' => $request->tgl_berangkat,
                'tgl_kembali'   => $request->tgl_kembali,
                'lama_hari'     => $lama_hari,
            ]);
            Sppd::where('spt_id', $spt->id)->delete();
            $spt->users()->detach();
        }

        if ($request->has('guru_ditugaskan') && is_array($request->guru_ditugaskan)) {
            foreach ($request->guru_ditugaskan as $guru_id) {
                $spt->users()->attach($guru_id);
                $last_sppd_count = Sppd::whereYear('created_at', $tahun)->count() + 1;
                $nomor_sppd = sprintf("090/%03d/SMP.03/Disdik/%s/%s", $last_sppd_count, $bulan_romawi, $tahun);

                Sppd::create([
                    'spt_id'            => $spt->id,
                    'nomor_sppd'        => $nomor_sppd,
                    'user_id'           => $guru_id,
                    'maksud_perjalanan' => $request->perihal,
                    'tempat_berangkat'  => 'SMP Negeri 3 Lakbok',
                    'tempat_tujuan'     => $request->tujuan_surat,
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
    }

    private function getRomawi($bulan)
    {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[$bulan] ?? 'I';
    }

    /**
     * Menghitung informasi nomor surat dan nomor agenda otomatis berbasis tahun kalender.
     * Nomor urut dimulai dari 01 hingga akhir tahun, dan kembali ke 01 pada tahun berikutnya.
     */
    public function getNextNumberInfo($date = null, $kode = '421.3')
    {
        $dateObj = $date ? Carbon::parse($date) : Carbon::today();
        $year = $dateObj->year;
        $month = $dateObj->month;
        $bulanRomawi = $this->getRomawi($month);

        // Ambil semua surat keluar pada tahun kalender terkait
        $lettersThisYear = LetterOutgoing::whereYear('tgl_surat', $year)->get();
        $countThisYear = $lettersThisYear->count();

        // Cari nomor urut terbesar dalam tahun ini
        $maxSequence = $countThisYear;
        foreach ($lettersThisYear as $letter) {
            // Pola format: .../{no_urut}/... misal 421.3/05/SMP.03/... atau 094/01/...
            if (preg_match('/\/(?:0*(\d{1,4}))\//', $letter->nomor_surat, $matches)) {
                $val = intval($matches[1]);
                if ($val > 0 && $val < 10000 && $val != $year) {
                    if ($val > $maxSequence) {
                        $maxSequence = $val;
                    }
                }
            } elseif (preg_match('/^0*(\d{1,4})(?:\/|$)/', trim($letter->nomor_surat), $matches)) {
                $val = intval($matches[1]);
                if ($val > 0 && $val < 10000 && $val != $year) {
                    if ($val > $maxSequence) {
                        $maxSequence = $val;
                    }
                }
            }
        }

        $nextSequence = $maxSequence + 1;
        $formattedSequence = str_pad($nextSequence, 2, '0', STR_PAD_LEFT);
        $formattedAgenda = str_pad($countThisYear + 1, 4, '0', STR_PAD_LEFT);

        $nomorSurat = "{$kode}/{$formattedSequence}/SMP.03/Disdik/{$bulanRomawi}/{$year}";

        return [
            'sequence'           => $nextSequence,
            'formatted_sequence' => $formattedSequence,
            'nomor_agenda'       => $formattedAgenda,
            'nomor_surat'        => $nomorSurat,
            'kode'               => $kode,
            'bulan_romawi'       => $bulanRomawi,
            'year'               => $year,
        ];
    }
}